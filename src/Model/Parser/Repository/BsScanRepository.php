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

    public function getTransactionByHash(string $hash){
        $pageContent = $this->fetchTransaction($hash);
        return $this->normalizedTransactionData($pageContent);
    }

    public function getIdsByContract(string $contract, ?string $lastTransactionHash = null ){
        $ids = [];
        $i = 1;
        $flag = true;

        while ($flag) {
            $content = $this->fetchTransactions($contract, $i);
            $transactionIds = $this->normalizedTransactionsHashes($content);
            $flag = !!$transactionIds;

            if (in_array($lastTransactionHash , $transactionIds)){
                $flag = false;
                $key = array_search($lastTransactionHash, $transactionIds);
                $transactionIds = array_slice($transactionIds, 0, $key);
            }

            if ($flag) $ids = array_merge($ids, $transactionIds);
            $i++;
        }

        echo "All transactions count - ".count($ids).PHP_EOL;

        return $ids;
    }

    private function fetchTransactions(string $contract, int $page, $limit = 100): string
    {
        usleep(3200000/4);
        echo 'Download page - '. $page.PHP_EOL;
        try {
            return $this->client
                ->request('GET', "/txs?a={$contract}&ps={$limit}&p={$page}")
                ->getContent();
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }

        return '';
    }

    private function fetchTransaction(string $hash){
        usleep(3200000/4);
        echo 'Download page transaction - '. $hash.PHP_EOL;
        try {
            return $this->client
                ->request('GET', "/tx/{$hash}")
                ->getContent();
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }

        return '';
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

        //from
        $data['from'] = $crawler->filterXPath('//*[@id="spanFromAdd"]')->text('n/a');

        //to
        $data['to'] = $crawler->filterXPath('//*[@id="contractCopy"]')->text('n/a');

        //value
        $value = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_spanValue"]')->text('0.0 BNB ($0.0)');
        $data['value'] = $this->prepareValue($value);
        $data['currency'] = $this->prepareCurrency($value);
        $data['valueUSD'] = $this->prepareUDSValue($value);


        //fee
        $feeValue = $crawler->filterXPath('//*[@id="ContentPlaceHolder1_spanTxFee"]')->text('0.0 BNB ($0.0)');
        $data['fee'] = $this->prepareFeeValue($feeValue);
        $data['feeUSD'] = $this->prepareFeeUSDValue($feeValue);

        //Method
        $method = $crawler->filterXPath('//*[@id="inputdata"]')->text('');
        $data['method'] = $this->prepareMethod($method);
        $data['raw'] = '';

        return (object) $data;
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
        return (float) $value;
    }

    private function prepareUDSValue(string $value): float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        $valueUDS = preg_replace('/\(\)\$/', '', $valueUDS);
        return (float) $valueUDS;
    }

    private function prepareCurrency(string $value): string
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return $currency;
    }

    private function prepareFeeValue(string $value):float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return (float) $value;
    }

    private function prepareFeeUSDValue(string $value):float
    {
        list($value, $currency, $valueUDS) = explode(' ', $value);
        return (float)str_replace(['(', ')', '$'], '', $valueUDS);
    }

    private function prepareMethod(string $string):string
    {
        $method = preg_replace( ["/^Function: /", "/\(.+\).+$/", "/\(\).+$/"],'', $string);
        return strlen($method) > 20 ? '-' : $method;
    }
}