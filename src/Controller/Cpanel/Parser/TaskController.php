<?php


namespace App\Controller\Cpanel\Parser;


use App\Model\Flusher;
use App\Model\Parser\Entity\Task;
use App\Model\Parser\Repository\ProcessRepository;
use App\Model\Parser\Repository\TaskRepository;
use App\Model\Parser\UseCase\Cpanel\Task\Create;
use App\Model\Parser\UseCase\Cpanel\Task\Destroy;
use App\Model\Parser\UseCase\Cpanel\Task\Edit;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(path="/cpanel/parser/tasks", name="cp.parer.task.")
 */
class TaskController extends AbstractController
{
    private TaskRepository $taskRepository;

    private Flusher $flusher;

    private ProcessRepository $processRepository;

    public function __construct(TaskRepository $taskRepository, ProcessRepository $processRepository, Flusher $flusher)
    {
        $this->taskRepository = $taskRepository;
        $this->flusher = $flusher;
        $this->processRepository = $processRepository;
    }

    /**
     * @Route(path="", name="index")
     */
    public function index()
    {
        return $this->render('cpanel/task/index.html.twig',[
            'tasks'=>$this->taskRepository->findAll()
        ]);
    }

    /**
     * @Route(path="/create", name="create", methods={"GET", "POST"})
     * @param Request $request
     * @param Create\Handler $handler
     * @return RedirectResponse|Response
     */
    public function create(Request $request, Create\Handler $handler)
    {
        $cmd = new Create\Command();

        $form = $this->createForm(Create\Form::class, $cmd);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($cmd);
                $this->addFlash('success', 'Success, task created.');
                return $this->redirectToRoute('cp.parer.task.index');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Error, fill in all the fields.');
        }

        return $this->render('cpanel/task/entity.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/edit/{id<\d+>}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Task $task
     * @param Edit\Handler $handler
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Task $task, Edit\Handler $handler)
    {
        $cmd = new Edit\Command($task);

        $form = $this->createForm(Edit\Form::class, $cmd);

        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($cmd);
                $this->addFlash('success', 'Success, task edited.');
                return $this->redirectToRoute('cp.parer.task.index');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Error, fill in all the fields.');
        }

        return $this->render('cpanel/task/entity.html.twig', [
            'form' => $form->createView(),
            'task'=>$task
        ]);
    }


    /**
     * @Route(path="/destroy/{id<\d+>}", name="destroy", methods={"GET", "POST"})
     * @param Task $task
     * @param Destroy\Handler $handler
     * @return Response
     */
    public function destroy(Task $task, Destroy\Handler $handler): Response
    {
        $cmd = new Destroy\Command($task);

        try {
            $handler->handle($cmd);
            $this->addFlash('success', 'Success, task deleted.');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('cp.parer.task.index');
    }
}