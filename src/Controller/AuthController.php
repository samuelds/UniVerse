<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Auth\LoginType;
use App\Form\Auth\SignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth', name: 'auth_')]
class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils
    ): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home_pages');
         }

         $form = $this->createForm(LoginType::class);
         $form->handleRequest($request);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('auth/login.html.twig',[
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
    #[Route('/signup', name: 'signup', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
