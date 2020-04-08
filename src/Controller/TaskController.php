<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class TaskController extends AbstractFOSRestController
{
    private $taskRepository;
    private $entityManager;
    
    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function getTaskAction(Task $task)
    {
        return $this->view($task, 200);
    }

    public function getTaskNotesAction(int $id)
    {
        $task = $this->taskRepository->find($id);

        if($task) {
            return $this->view($task->getNotes(), 200);
        }
        return $this->view(['message' => 'Something went wrong'], 500);
    }

    public function deleteTaskAction(int $id) 
    {
        $task = $this->taskRepository->find($id);

        if($task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();

            return $this->view(['message' => 'deleted'], 204);
        }

        return $this->view(['message' => 'Something went wrong'], 500);
    }

    public function statusTaskAction(Task $task) 
    {
        if($task) {
            $task->setIsComplete(!$task->getIsComplete());

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->view($task->getIsComplete(), 200);
        }

        return $this->view(['message' => 'Something went wrong'], 500);
    }

    /**
     * @Rest\RequestParam(name="note", description="note in a task", nullable=false)
     */
    public function postTaskNoteAction(int $id, ParamFetcher $paramFetcher)
    {
        $task = $this->taskRepository->find($id);

        if($task) {
            $note = new Note();
            
            $titleOfNote = $paramFetcher->get('note');
            $note->setNote($titleOfNote);
            $note->setTask($task);

            $task->addNote($note);

            $this->entityManager->persist($note);
            $this->entityManager->flush();

            return $this->view($note, 200);
        }

        return $this->view(['message' => 'Something went wrong'], 500);
    }
}
