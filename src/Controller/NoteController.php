<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class NoteController extends AbstractFOSRestController
{
    private $noteRepository;
    private $entityManager;

    public function __construct(NoteRepository $noteRepository, EntityManagerInterface $entityManager)
    {
        $this->noteRepository = $noteRepository;
        $this->entityManager = $entityManager;
    }

    public function getNoteAction(Note $note)
    {
        return $this->view($note, 200);
    }

    public function deleteNoteAction($id)
    {
        $note = $this->noteRepository->find($id);

        if($note) {
            $this->entityManager->remove($note);
            $this->entityManager->flush();

            return $this->view(['message' => 'deleted'], 204);
        }

        return $this->view(['message'=>'error'], 500);
    }
}
