<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\BlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\PageRepository;
use App\Entity\Block;
use App\Form\Block\{
    BlockAddType,
    BlockEditType
};

#[Route('/page/{pageId}/block', name: 'block_')]
#[IsGranted('ROLE_USER')]
class
BlockController extends AbstractController
{
    private PageRepository $pageRepository;

    function __construct(
        PageRepository $pageRepository
    )
    {
        $this->pageRepository = $pageRepository;
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        int $pageId
    ): Response
    {
        $page = $this->pageRepository->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Page does not exist');
        }

        $block = new Block();
        $block->setPage($page);
        $form = $this->createForm(BlockAddType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($block);
            $em->flush();
            return $this->redirectToRoute('page_show', [
                'id' => $pageId
            ]);
        }

        return $this->render('block/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        PageRepository $pageRepository,
        int $pageId,
        int $id
    ): Response
    {
        $page = $this->pageRepository->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Page does not exist');
        }

        $block = $pageRepository->find($id);
        if (!$block) {
            throw $this->createNotFoundException('The block does not exist');
        }
        return $this->render('block/show.html.twig', [
            'page' => $pageId,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        BlockRepository $blockRepository,
        int $pageId,
        int $id
    ): Response
    {
        $page = $this->pageRepository->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('Page does not exist');
        }

        $block = $blockRepository->find($id);
        $form = $this->createForm(BlockEditType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($block);
            $em->flush();
            return $this->redirectToRoute('page_show', [
                'id' => $pageId
            ]);
        }

        return $this->render('page/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
