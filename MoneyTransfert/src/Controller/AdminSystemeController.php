<?php

namespace App\Controller;

use App\Entity\AdminSysteme;
use App\Form\AdminSystemeType;
use App\Repository\AdminSystemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/systeme")
 */
class AdminSystemeController extends AbstractController
{
    /**
     * @Route("/", name="admin_systeme_index", methods={"GET"})
     */
    public function index(AdminSystemeRepository $adminSystemeRepository): Response
    {
        return $this->render('admin_systeme/index.html.twig', [
            'admin_systemes' => $adminSystemeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_systeme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adminSysteme = new AdminSysteme();
        $form = $this->createForm(AdminSystemeType::class, $adminSysteme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adminSysteme);
            $entityManager->flush();

            return $this->redirectToRoute('admin_systeme_index');
        }

        return $this->render('admin_systeme/new.html.twig', [
            'admin_systeme' => $adminSysteme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_systeme_show", methods={"GET"})
     */
    public function show(AdminSysteme $adminSysteme): Response
    {
        return $this->render('admin_systeme/show.html.twig', [
            'admin_systeme' => $adminSysteme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_systeme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AdminSysteme $adminSysteme): Response
    {
        $form = $this->createForm(AdminSystemeType::class, $adminSysteme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_systeme_index');
        }

        return $this->render('admin_systeme/edit.html.twig', [
            'admin_systeme' => $adminSysteme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_systeme_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AdminSysteme $adminSysteme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adminSysteme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adminSysteme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_systeme_index');
    }
}
