<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractFOSRestController
{
    private $userRepository;
    private $passwordEncoder;
    private $entityManager;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if(!is_null($user)) {
            return $this->view(['message' => 'user already exists'], 409);
        }

        $user = new User();

        $user->setEmail($email);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->view($user, 201)->setContext((new Context())->setGroups(['public']));
    }
}
