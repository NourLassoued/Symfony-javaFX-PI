<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\OffresType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Service;
#[Route('/offres')]
class OffresController extends AbstractController
{
    #[Route('/', name: 'app_offres_index', methods: ['GET'])]
    public function index(OffresRepository $offresRepository): Response
    {
        return $this->render('offres/index.html.twig', [
            'offres' => $offresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_offres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offres();
        $form = $this->createForm(OffresType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();
            dump($form->getData());
            return $this->redirectToRoute('app_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offres/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffres}', name: 'app_offres_show', methods: ['GET'])]
    public function show(Offres $offre): Response
    {
        return $this->render('offres/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{idOffres}/edit', name: 'app_offres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffresType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offres/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffres}', name: 'app_offres_delete', methods: ['POST'])]
    public function delete(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffres(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offres_index', [], Response::HTTP_SEE_OTHER);
    }
}
