<?php

namespace App\Controller;

use App\Entity\UserPartenaire;
use App\Form\UserPartenaireType;
use App\Repository\UserPartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/partenaire")
 */
class UserPartenaireController extends AbstractController
{
    /**
     * @Route("/", name="user_partenaire_index", methods={"GET"})
     */
    public function index(UserPartenaireRepository $userPartenaireRepository): Response
    {
        return $this->render('user_partenaire/index.html.twig', [
            'user_partenaires' => $userPartenaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_partenaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userPartenaire = new UserPartenaire();
        $form = $this->createForm(UserPartenaireType::class, $userPartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userPartenaire);
            $entityManager->flush();

            return $this->redirectToRoute('user_partenaire_index');
        }

        return $this->render('user_partenaire/new.html.twig', [
            'user_partenaire' => $userPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_partenaire_show", methods={"GET"})
     */
    public function show(UserPartenaire $userPartenaire): Response
    {
        return $this->render('user_partenaire/show.html.twig', [
            'user_partenaire' => $userPartenaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_partenaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPartenaire $userPartenaire): Response
    {
        $form = $this->createForm(UserPartenaireType::class, $userPartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_partenaire_index');
        }

        return $this->render('user_partenaire/edit.html.twig', [
            'user_partenaire' => $userPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_partenaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserPartenaire $userPartenaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userPartenaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userPartenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_partenaire_index');
    }
}
