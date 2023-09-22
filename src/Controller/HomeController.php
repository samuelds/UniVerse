<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'home_')]
#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'pages', methods: ['GET'])]
    public function pages(
        PageRepository $pageRepository,
    ): Response
    {
        $pages = $pageRepository->findBy([
            'user' => $this->getUser()
        ]);
        return $this->render('home/pages.html.twig', [
            'pages' => $pages
        ]);
    }

}
