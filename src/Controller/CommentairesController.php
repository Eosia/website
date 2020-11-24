<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class CommentairesController extends AbstractController
{
    /**
     * @Route("/admin/commentaires", name="commentaires_index", methods={"GET"})
     */
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('admin/commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/commentaires/new", name="commentaires_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render('admin/commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/commentaires/{id}", name="commentaires_show", methods={"GET"})
     */
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('admin/commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/admin/commentaires/{id}/edit", name="commentaires_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commentaires $commentaire): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render('admin/commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/commentaires/{id}", name="commentaires_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commentaires $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commentaires_index');
    }
}
