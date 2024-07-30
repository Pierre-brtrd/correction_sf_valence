<?php

namespace App\Controller\Backend;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/gender', name: 'admin.genders')]
class GenderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(GenderRepository $genderRepo): Response
    {
        return $this->render('Backend/Gender/index.html.twig', [
            'genders' => $genderRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $gender = new Gender();

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a été créé avec succès.');

            return $this->redirectToRoute('admin.genders.index');
        }

        return $this->render('Backend/Gender/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function update(?Gender $gender, Request $request): Response|RedirectResponse
    {
        if (!$gender) {
            $this->addFlash('danger', 'Le genre n\'existe pas.');

            return $this->redirectToRoute('admin.genders.index');
        }

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a été modifié avec succès.');

            return $this->redirectToRoute('admin.genders.index');
        }

        return $this->render('Backend/Gender/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Gender $gender, Request $request): RedirectResponse
    {
        if (!$gender) {
            $this->addFlash('danger', 'Le genre n\'existe pas.');

            return $this->redirectToRoute('admin.genders.index');
        }

        if ($this->isCsrfTokenValid('delete' . $gender->getId(), $request->request->get('token'))) {
            $this->em->remove($gender);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a été supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Le jeton CSRF est invalide.');
        }

        return $this->redirectToRoute('admin.genders.index');
    }
}
