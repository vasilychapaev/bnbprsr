<?php


namespace App\Model\Parser\Repository;


use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BsScanRepository
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'https://bscscan.com'
        ]);
    }

    public function getTransactionByHash(string $hash)
    {
        return $this->normalizedTransactionData(
            $this->fetchTransaction($hash)
        );
    }

    public function getIdsByContract(string $contract, ?string $lastTransactionHash = null)
    {
        $ids = [];
        $i = 1;
        $flag = true;

        while ($flag) {
            $content = $this->fetchTransactions($contract, $i);
            $transactionIds = $this->normalizedTransactionsHashes($content);
            $flag = !!$transactionIds;

            if (in_array($lastTransactionHash, $transactionIds)) {
                $flag = false;
                $key = array_search($lastTransactionHash, $transactionIds);
                $transactionIds = array_slice($transactionIds, 0, $key);
                $ids = array_merge($ids, $transactionIds);
            }

            if ($flag) $ids = array_merge($ids, $transactionIds);
            $i++;
        }

        echo "All transactions count - " . count($ids) . PHP_EOL;

        return $ids;
    }

    private function fetchTransactions(string $contract, int $page, $limit = 100): string
    {
        usleep(1000000);
        echo 'Download page - ' . $page . PHP_EOL;
        try {
            return $this->client
                ->request('GET', "/txs?a={$contract}&ps={$limit}&p={$page}")
                ->getContent();
        } catch (TransportExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface|ClientExceptionInterface $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        return '';
    }

    private function fetchTransaction(string $hash)
    {
        //usleep(1000000);
        echo 'Download page transaction - ' . $hash . PHP_EOL;
        try {
            return $this->client
                ->request('GET', "/tx/{$hash}")
                ->getContent();
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    private function normalizedTransactionsHashes(string $pageContent): ?array
    {
        $crawler = new Crawler($pageContent);

        return $crawler
            ->filterXPath('//tbody/tr/td[2]')
            ->each(function (Crawler $node, $i) {
                return $node->text();
            });
    }

    private function normalizedTransactionData(string $pageContent): object
    {

        $crawler = new Crawler($pageContent);

        //hash
        $data['hash'] = $crawler->filterXPath('//*[@id="spanTxHash"]')->text('n/a');

        //status
        $data['status'] = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_maintable"]/div[2]/div[2]/span')->text('n/a');

        //block
        $data['block'] = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_maintable"]/div[3]/div[2]/a')->text(0);

        //timestamp
        $dateTime = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_divTimeStamp"]/div/div[2]')->text(0);
        $data['dateTime'] = $this->prepareTimestamp($dateTime);

        //fromWallet
        $data['fromWallet'] = $crawler->filterXPath('//*[@id="spanFromAdd"]')->text('n/a');

        //toWallet1
        $data['toWallet1'] = $crawler->filterXPath('//*[@id="contractCopy"]')->text('n/a');
        $data['toWallet2'] = null;
        $data['toWallet3'] = null;
        $data['toWallet4'] = null;

        //value
        $value = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_spanValue"]')->text('0.0 BNB ($0.0)');
        $data['value1'] = $this->prepareValue($value);
        $data['value2'] = null;
        $data['value3'] = null;
        $data['value4'] = null;
        $data['currency'] = $this->prepareCurrency($value);
        $data['valueUSD'] = $this->prepareUDSValue($value);


        //Internal tab
        $internalTab = $crawler->filterXPath('//*[@id="internal-tab"]')->count();

        $data['toWalletJson'] = [];

        if ($internalTab) {

            $data['toWalletJson'] = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_divinternaltable"]/table/tbody/tr')
                ->each(function (Crawler $crawler) {
                    $value = $crawler->filterXPath('//td[5]')->text(0);
                    $value = (float) str_replace(' BNB', '', $value);
                    return [
                        'from' => $crawler->filterXPath('//td[2]/span/a')->text('n/a'),
                        'to' => $crawler->filterXPath('//td[4]/span/a')->text('n/a'),
                        'value' => $value,
                    ];
                });

            $firstThree = array_slice($data['toWalletJson'], 0, 2);

            foreach ($firstThree as $key => $val) {
                $index = $key + 2;
                $indexToWallet = "toWallet{$index}";
                $indexValue = "value{$index}";
                $data[$indexToWallet] = $val['to'];
                $data[$indexValue] = $val['value'];
            }
        }

        //fee
        $feeValue = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_spanTxFee"]')->text('0.0 BNB ($0.0)');
        $data['fee'] = $this->prepareFeeValue($feeValue);
        $data['feeUSD'] = $this->prepareFeeUSDValue($feeValue);

        //Method
        $method = $crawler->filterXPath('//*[@id="inputdata"]')->text('');
        $data['method'] = $this->prepareMethod($method);
        $data['raw'] = '';

        return (object)$data;
    }

    private function prepareTimestamp(string $value): \DateTime
    {
        try {
            return new \DateTime(str_replace('+UTC', 'UTC', $value));
        } catch (\Exception $e) {
            return new \DateTime();
        }
    }

    private function prepareValue(string $value): float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return (float)$value;
    }

    private function prepareUDSValue(string $value): float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        $valueUDS = preg_replace('/\(\)\$/', '', $valueUDS);
        return (float)$valueUDS;
    }

    private function prepareCurrency(string $value): string
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return $currency;
    }

    private function prepareFeeValue(string $value): float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return (float)$value;
    }

    private function prepareFeeUSDValue(string $value): float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return (float)str_replace(['(', ')', '$'], '', $valueUDS);
    }

    private function prepareMethod(string $string): string
    {
        $method = preg_replace(["/^Function: /", "/\(.+\).+$/", "/\(\).+$/"], '', $string);
        return strlen($method) > 20 ? '-' : $method;
    }
}