<?php


namespace App\Controller\Cpanel;

use Symfony\Component\Routing\Annotation\Route;
use App\Model\Parser\Repository\BsScanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private BsScanRepository $bsScanRepository;

    public function __construct(BsScanRepository $bsScanRepository)
    {
        $this->bsScanRepository = $bsScanRepository;
    }

    /**
     * @Route(path="/test")
     */
    public function test(){
        $hash = "0x23049aa62d7d9f3774ae291b7a08ae70802eed64af4e6a099c8b089e0f7fd325";
        //$hash = "0xb32c9a16bd0949ac7eff5a2e9664cff94ba7cb4abb1215424dcef2bda6ead323";

        try {
            $res = $this->bsScanRepository->getTransactionByHash($hash);
        }catch (\DomainException $e){
            dd($e);
        }

        dd($res);
    }
}