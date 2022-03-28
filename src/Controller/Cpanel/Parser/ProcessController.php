<?php


namespace App\Controller\Cpanel\Parser;

use App\Model\Parser\Entity\Process;
use App\Model\Parser\Repository\ProcessRepository;
use App\Model\Parser\UseCase\Cpanel\Process\Destroy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(path="/cpanel/parser/processes", name="cp.parer.process.")
 */
class ProcessController extends AbstractController
{
    private ProcessRepository $processRepository;

    public function __construct(ProcessRepository $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    /**
     * @Route(path="", name="index")
     */
    public function index()
    {
        return $this->render('cpanel/process/index.html.twig', [
            'processes'=>$this->processRepository->findBy([], ['id'=>'DESC'], 50)
        ]);
    }

    /**
     * @Route(path="/destroy/{id<\d+>}", name="destroy", methods={"GET", "POST"})
     * @param Process $process
     * @param Destroy\Handler $handler
     * @return Response
     */
    public function destroy(Process $process, Destroy\Handler $handler): Response
    {
        $cmd = new Destroy\Command($process);

        try {
            $handler->handle($cmd);
            $this->addFlash('success', 'Success, process removed.');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('cp.parer.process.index');
    }
}