<?php

namespace App\Controller;

use App\Entity\AdminSysteme;
use App\Form\AdminSystemeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdminSystemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/adminSysteme")
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
     * @Route("/ajout", name="adminSystemeNew", methods={"POST"})
     */
    public function ajoutadminS(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $values = json_decode($request->getContent());
        if(isset($values->matricule,$values->username,$values->nomComplet,$values->username,$values->adresse,$values->telephone,$values->password,$values->email)) {
            $adminSysteme = new AdminSysteme();

            $adminSysteme->setMatricule($values->matricule);
            $adminSysteme->setUsername($values->username);
            $adminSysteme->setNomComplet($values->nomComplet);
            $adminSysteme->setAdresse($values->adresse);
            $adminSysteme->setTelephone($values->telephone);
            $adminSysteme->setEmail($values->email);
            $adminSysteme->setPassword($values->password);
            $errors = $validator->validate($adminSysteme);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($adminSysteme);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
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
