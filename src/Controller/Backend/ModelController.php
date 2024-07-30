<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/models', name: 'admin.models')]
class ModelController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ModelRepository $modelRepository,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/Models/index.html.twig', [
            'models' => $this->modelRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $model = new Model();

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success', 'Le modèle a été créé avec succès.');

            return $this->redirectToRoute('admin.models.index');
        }

        return $this->render('Backend/Models/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(?Model $model, Request $request): Response
    {
        if (!$model) {
            $this->addFlash('danger', 'Le modèle n\'existe pas.');

            return $this->redirectToRoute('admin.models.index');
        }

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Le modèle a été modifié avec succès.');

            return $this->redirectToRoute('admin.models.index');
        }

        return $this->render('Backend/Models/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Model $model, Request $request): Response
    {
        if (!$model) {
            $this->addFlash('danger', 'Le modèle n\'existe pas.');

            return $this->redirectToRoute('admin.models.index');
        }

        if ($this->isCsrfTokenValid('delete' . $model->getId(), $request->request->get('token'))) {
            $this->em->remove($model);
            $this->em->flush();

            $this->addFlash('success', 'Le modèle a été supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Le jeton CSRF est invalide.');
        }

        return $this->redirectToRoute('admin.models.index');
    }
}
