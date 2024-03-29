<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api")
 */
class PartenaireController extends AbstractController
{
    /**
     * @Route("/", name="partenaire_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="PartenaireAjout", methods={"POST","GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function ajout(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $Partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $Partenaire);
        $form->handleRequest($request);
        $values = json_decode($request->getContent());
        if(isset($values->username,$values->password)) {
            $partenaire = new Partenaire();
            $partenaire->setNinea($values->ninea);
        }
            $Partenaire=$serializer->deserialize($request->getContent(), Partenaire::class, 'json');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Partenaire);
            $entityManager->flush();
    return new Response('users adding', Response::HTTP_CREATED);
}

    /**
     * @Route("/partenaire/{id}", name="PartenaireShow", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Partenaire $partenaire,PartenaireRepository $partenaireRepo,SerializerInterface $serializer): Response
    {
        $partenaire= $partenaireRepo->find($partenaire->getId());
        $data = $serializer->serialize($partenaire, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adminPartenaireEdit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMINPARTENAIRE")
     */
    
    public function edit(Request $request, Partenaire $partenaire,SerializerInterface $serializer,ValidatorInterface $validator,
                         EntityManagerInterface $entityManager): Response
    {
        $data=[];
        $partenaire = $entityManager->getRepository(Partenaire::class)->find($partenaire->getId());
        $encoders = [new JsonEncoder()];
            $normalizers = [
                (new ObjectNormalizer())
                    ->setIgnoredAttributes([
                        //'updateAt'
                    ])
            ];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($partenaire, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

        $data = json_decode($jsonObject,true);
        foreach ($data as $key => $value){
         
            if($key!="id" && !empty($value)) {
                if($key=="matriculePartenaire"){
                    $partenaire->setMatriculePartenaire("bd018");
                }
                elseif ($key=="nomComplet") {
                    $partenaire->setNomComplet("max");
                }
                elseif ($key=="login") {
                    $partenaire->setLogin("hello");
                }
                elseif ($key=="passWord") {
                    $partenaire->setPassWord("124max");
                }
                elseif ($key=="ninea") {
                    $partenaire->setNinea("abcdf1230");
                }
                elseif ($key=="adresse") {
                    $partenaire->setAdresse("mermoz");
                }
                elseif ($key=="telephone") {
                    $partenaire->setTelephone("77400032458");
                }
                elseif ($key=="email") {
                    $partenaire->setEmail("good@good.fr");
                }
                elseif ($key=="status") {
                    $partenaire->setStatus("débloqué");
                }
               
            }
        }
        $errors = $validator->validate($partenaire);
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
     * @Route("/{id}", name="partenaireDelete", methods={"DELETE"})
     */
    public function delete(Request $request, Partenaire $partenaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($partenaire);
            $entityManager->flush();
        }

        return new Response('users deleted', Response::HTTP_CREATED);
    }
}
