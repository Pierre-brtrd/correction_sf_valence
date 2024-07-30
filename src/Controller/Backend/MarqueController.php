<?php

namespace App\Controller\Backend;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/marques', name: 'admin.marques')]
class MarqueController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MarqueRepository $marqueRepository,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('Backend/Marques/index.html.twig', [
            'marques' => $this->marqueRepository->findPaginateOrderByDate(
                $request->query->getInt('limit', 9),
                $request->query->getInt('page', 1),
                $request->query->get('search')
            ),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $marque = new Marque;

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'Marque créée avec succès');

            return $this->redirectToRoute('admin.marques.index');
        }

        return $this->render('Backend/Marques/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(?Marque $marque, Request $request): Response|RedirectResponse
    {
        if (!$marque) {
            $this->addFlash('error', 'Marque non trouvée');

            return $this->redirectToRoute('admin.marques.index');
        }

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'Marque modifiée avec succès');

            return $this->redirectToRoute('admin.marques.index');
        }

        return $this->render('Backend/Marques/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Marque $marque, Request $request): RedirectResponse
    {
        if (!$marque) {
            $this->addFlash('error', 'Marque non trouvée');

            return $this->redirectToRoute('admin.marques.index');
        }

        if ($this->isCsrfTokenValid('delete' . $marque->getId(), $request->request->get('token'))) {
            $this->em->remove($marque);
            $this->em->flush();
        } else {
            $this->addFlash('error', 'Token invalide');
        }

        return $this->redirectToRoute('admin.marques.index');
    }
}
