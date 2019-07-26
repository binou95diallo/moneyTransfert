<?php

namespace App\Controller;

use App\Entity\AdminPartenaire;
use App\Form\AdminPartenaireType;
use App\Repository\AdminPartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/partenaire")
 */
class AdminPartenaireController extends AbstractController
{
    /**
     * @Route("/", name="admin_partenaire_index", methods={"GET"})
     */
    public function index(AdminPartenaireRepository $adminPartenaireRepository): Response
    {
        return $this->render('admin_partenaire/index.html.twig', [
            'admin_partenaires' => $adminPartenaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_partenaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adminPartenaire = new AdminPartenaire();
        $form = $this->createForm(AdminPartenaireType::class, $adminPartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adminPartenaire);
            $entityManager->flush();

            return $this->redirectToRoute('admin_partenaire_index');
        }

        return $this->render('admin_partenaire/new.html.twig', [
            'admin_partenaire' => $adminPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_partenaire_show", methods={"GET"})
     */
    public function show(AdminPartenaire $adminPartenaire): Response
    {
        return $this->render('admin_partenaire/show.html.twig', [
            'admin_partenaire' => $adminPartenaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_partenaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AdminPartenaire $adminPartenaire): Response
    {
        $form = $this->createForm(AdminPartenaireType::class, $adminPartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_partenaire_index');
        }

        return $this->render('admin_partenaire/edit.html.twig', [
            'admin_partenaire' => $adminPartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_partenaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AdminPartenaire $adminPartenaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adminPartenaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adminPartenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_partenaire_index');
    }
}
