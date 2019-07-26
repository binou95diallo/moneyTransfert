<?php

namespace App\Controller;

use App\Entity\AdminPartenaire;
use App\Entity\Partenaire;
use App\Form\AdminPartenaireType;
use App\Repository\AdminPartenaireRepository;
use App\Repository\PartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/adminPartenaire")
 */
class AdminPartenaireController extends AbstractController
{
    /**
     * @Route("/", name="adminPartenaireIndex", methods={"GET"})
     */
    public function index(AdminPartenaireRepository $adminPartenaireRepository): Response
    {
        return $this->render('admin_partenaire/index.html.twig', [
            'admin_partenaires' => $adminPartenaireRepository->findAll(),
        ]);
    }

   
    /**
     * @Route("/ajout", name="adminPartenaireAjout", methods={"POST","GET"})
     */
    public function ajout(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $adminPartenaire = new AdminPartenaire();
        $form = $this->createForm(AdminPartenaireType::class, $adminPartenaire);
        $form->handleRequest($request);
        $values=json_decode($request->getContent());
        $partenaire=$this->getDoctrine()->getManager()->getRepository(Partenaire::class)->find($values->partenaire);
        $adminPartenaire->setMatricule($values->matricule);
        $adminPartenaire->setUsername($values->username);
        $adminPartenaire->setPassword($values->password);
        $adminPartenaire->setNomComplet($values->nomComplet);
        $adminPartenaire->setAdresse($values->adresse);
        $adminPartenaire->setTelephone($values->telephone);
        $adminPartenaire->setEmail($values->email);
        $adminPartenaire->setPartenaire($partenaire);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($adminPartenaire);
        $entityManager->flush();
    return new Response('users adding', Response::HTTP_CREATED);
}

    /**
     * @Route("/{id}", name="adminPartenaireShow", methods={"GET"})
     */
    public function show(AdminPartenaire $adminPartenaire,AdminPartenaireRepository $adminPartenaireRepo,SerializerInterface $serializer): Response
    {
        $adminPartenaire= $adminPartenaireRepo->find($adminPartenaire->getId());
        $data = $serializer->serialize($adminPartenaire, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adminPartenaireEdit", methods={"GET","POST"})
     */
    
    public function edit(Request $request, AdminPartenaire $adminPartenaire,SerializerInterface $serializer,ValidatorInterface $validator,
                         EntityManagerInterface $entityManager): Response
    {
        $data=[];
        $adminPartenaire = $entityManager->getRepository(AdminPartenaire::class)->find($adminPartenaire->getId());
        $encoders = [new JsonEncoder()];
            $normalizers = [
                (new ObjectNormalizer())
                    ->setIgnoredAttributes([
                        //'updateAt'
                    ])
            ];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($adminPartenaire, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

        $data = json_decode($jsonObject,true);
        foreach ($data as $key => $value){
         
            if($key!="id" && !empty($value)) {
                if($key=="matriculePartenaire"){
                    $adminPartenaire->setMatriculePartenaire("bd018");
                }
                elseif ($key=="nomComplet") {
                    $adminPartenaire->setNomComplet("max");
                }
                elseif ($key=="login") {
                    $adminPartenaire->setLogin("hello");
                }
                elseif ($key=="passWord") {
                    $adminPartenaire->setPassWord("124max");
                }
                elseif ($key=="ninea") {
                    $adminPartenaire->setNinea("abcdf1230");
                }
                elseif ($key=="adresse") {
                    $adminPartenaire->setAdresse("mermoz");
                }
                elseif ($key=="telephone") {
                    $adminPartenaire->setTelephone("77400032458");
                }
                elseif ($key=="email") {
                    $adminPartenaire->setEmail("good@good.fr");
                }
                elseif ($key=="status") {
                    $adminPartenaire->setStatus("débloqué");
                }
               
            }
        }
        $errors = $validator->validate($adminPartenaire);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'L \'utilisateur a bien été mis à jour'
        ];
        return new JsonResponse($data);
 
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
