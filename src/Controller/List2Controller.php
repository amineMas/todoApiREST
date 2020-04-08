<?php

namespace App\Controller;

use App\Entity\Preferene;
use App\Entity\Task;
use App\Entity\TaskList;
use Swagger\Annotations as SWG;
use App\Repository\TaskRepository;
use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class List2Controller extends AbstractFOSRestController
{
    private $taskListRepository;
    private $entityManager;
    private $taskRepository;

    public function __construct(TaskListRepository $taskListRepository, TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskListRepository = $taskListRepository;
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the lists"
     * )
     */
    public function getListsAction()
    {
        $data = $this->taskListRepository->findAll();
        return $this->view($data,200);
    
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns one list"
     * )
     */
    public function getListAction(TaskList $list)
    {
        if($list) {
            return $this->view($list, 200);
        }
        
        return $this->view(['message' => 'error'], 500);

    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the list", nullable=false)
     * 
     *  @SWG\Response(
     *      response=200,
     *      description="create a new list of tasks with a title"
     * )
     * @SWG\Parameter(
     *      name="title",
     *      in="formData",
     *      type="string",
     *      description="The field used to choose a title for the list"
     * )
     */
    public function postListsAction(ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get('title');
        if($title) {
            $taskList = new TaskList();

            $preferences = new Preferene();

            $preferences->setList($taskList);

            $taskList->setPreferences($preferences);
            $taskList->setTitle($title);


            $this->entityManager->persist($taskList);
            $this->entityManager->flush();

            return $this->view($taskList, 200);
        }
        
        return $this->view(['message' => 'errror'], 400);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the new task", nullable=false)
     */
    public function postListTaskAction(ParamFetcher $paramFetcher, $id) 
    {
        $list = $this->taskListRepository->find($id);

        if($list) {
            $title = $paramFetcher->get('title');

            $task = new Task();
            $task->setTitle($title);
            $task->setList($list);

            $list->addTask($task);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->view($task, 200);
        }

        return $this->view(['message' => 'Something went wrong'], 500);
    }

    public function getListsTasksAction(TaskList $list)
    {
        return $this->view($list->getTasks(), 200);
    }

    /**
     * @Rest\FileParam(name="image", description="The background of the file",nullable=false, image=true)
     */
    public function backgroundListsAction(Request $request, $id, paramFetcher $paramFetcher)
    {
        $list = $this->taskListRepository->find($id);
        // remove old file if any
        $currentBackground = $list->getBackground();
        if(!is_null($currentBackground)) {
            $filesystem = new Filesystem();
            $filesystem->remove(
                $this->getUploadsDir() . $currentBackground
            ); // remove the file if it exists
        }

        $file = $paramFetcher->get('image');

        if($file) {
            $fileName = md5(uniqid()) . '.' . $file->guessClientExtension();

            $file->move(
                $this->getUploadsDir(),
                $fileName
            );

            $list->setBackground($fileName);
            $list->setBackgroundPath('/uploads/' . $fileName);

            $this->entityManager->persist($list);
            $this->entityManager->flush();

            $data = $request->getUriForPath(
                $list->getBackgroundPath()
            );

            return $this->view($data, 200);
        }

        return $this->view(['message' => 'something went wrong'], 402);
    }

    /**
     * @Rest\RequestParam(name="title", description="title of the list", nullable=false)
     */
    public function patchListTitleAction(TaskList $list, ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get('title');

        if(trim($title) !== '') {
            if($list) {
                $list->setTitle($title);

                $this->entityManager->flush();

                return $this->view(true, 200);
            }
            $errors = [
                'title' => 'this value cannot be empty'
            ];
        }

        return $this->view($errors, 204);
    }

    public function deleteListAction(TaskList $list)
    {
        $this->entityManager->remove($list);
        $this->entityManager->flush();

        return $this->view(null, 204);
    }

    private function getUploadsDir()
    {
        return $this->getParameter('uploads_dir');
    }

}
