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
        $hash = "0x75518f01be52dd6a3e46093a9893ff329ef3b9c5d34bebc872850ded7b9b78cb";

        try {
            $res = $this->bsScanRepository->getTransactionByHash($hash);
        }catch (\DomainException $e){
            dd($e);
        }

        dd($res);
    }
}