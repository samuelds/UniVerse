<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\Page\EditType;
use App\Form\Page\NewType;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/page', name: 'page_')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class PageController extends AbstractController
{

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $page = new Page();
        $form = $this->createForm(NewType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setUser($this->getUser());
            $em->persist($page);
            $em->flush();
            return $this->redirectToRoute('page_show', [
                'id' => $page->getId()
            ]);
        }

        return $this->render('page/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        PageRepository $pageRepository,
        int $id
    ): Response
    {
        $page = $pageRepository->find($id);
        if (!$page) {
            throw $this->createNotFoundException('The page does not exist');
        }
        return $this->render('page/show.html.twig', [
            'page' => $page
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        PageRepository $pageRepository,
        int $id
    ): Response
    {
        $page = $pageRepository->find($id);
        if (!$page) {
            throw $this->createNotFoundException('The page does not exist');
        }
        $form = $this->createForm(EditType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setTitle($form->get('title')->getData());
            $em->flush();
            return $this->redirectToRoute('page_show', [
                'id' => $page->getId()
            ]);
        }

        return $this->render('page/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
