<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'app_')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AppController extends AbstractController
{
    #[Route('/', name: 'pages', methods: ['GET'])]
    public function pages(
        PageRepository $pageRepository,
    ): Response
    {
        $user = $this->getUser();
        $pages = $pageRepository->findBy([
            'user' => $user
        ]);
        return $this->render('app/pages.html.twig', [
            'pages' => $pages
        ]);

    }

}
