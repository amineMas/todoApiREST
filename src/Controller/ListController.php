<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;


class ListController extends AbstractFOSRestController
{
    // -----------------------Manual Routing ------------------------
    // /**
    //  * @Route("/list", name="list")
    //  */
    // public function index()
    // {
    //     return [
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/ListController.php',
    //     ];
    // }

    // /**
    //  * @Rest\Get("/update", name="update")
    //  */
    // public function update()
    // {
    //     return ['message' => 'update'];
    // }
}
