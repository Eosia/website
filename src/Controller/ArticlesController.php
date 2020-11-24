<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="articles_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/article/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="articles_show", methods={"GET"})
     */
    public function show(Articles $article): Response
    {
        return $this->render('admin/articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }
}
