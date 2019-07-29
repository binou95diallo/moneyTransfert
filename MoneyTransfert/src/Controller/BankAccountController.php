<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Partenaire;
use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/api")
 */
class BankAccountController extends AbstractController
{
    /**
     * @Route("/", name="bankAccountIndex", methods={"GET"})
     */
    public function index(BankAccountRepository $bankAccountRepository): Response
    {
        return $this->render('bank_account/index.html.twig', [
            'bank_accounts' => $bankAccountRepository->findAll(),
        ]);
    }

    /**
     * @Route("/bankAccount/ajout", name="bankAccountAjout", methods={"POST","GET"})
     * isGranted("ROLES_ADMIN")
     */
    public function ajout(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        
        $bankAccount = new BankAccount();
        $values=json_decode($request->getContent());
        $partenaire=$this->getDoctrine()->getManager()->getRepository(Partenaire::class)->find($values->partenaire);
        $bankAccount->setNumeroCompte($values->numeroCompte);
        $bankAccount->setSolde($values->solde);
        //$bankAccount->setPartenaire($bankAccount->getId());
        $bankAccount->setPartenaire($partenaire);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bankAccount);
        $entityManager->flush();
    return new Response('users adding', Response::HTTP_CREATED);
}

    /**
     * @Route("/bankAccount/{id}", name="bankAccountShow", methods={"GET"})
     */
    public function show(BankAccount $bankAcc,BankAccountRepository $bankARepo,SerializerInterface $serializer): Response
    {
        $bankAcc= $bankARepo->find($bankAcc->getId());
        $data = $serializer->serialize($bankAcc, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/bankAccount/{id}/edit", name="BankAEdit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    
    public function edit(Request $request, BankAccount $bankA,SerializerInterface $serializer,ValidatorInterface $validator,
                         EntityManagerInterface $entityManager): Response
    {
        $data=[];
        $bankA = $entityManager->getRepository(BankAccount::class)->find($bankA->getId());
        $encoders = [new JsonEncoder()];
            $normalizers = [
                (new ObjectNormalizer())
                    ->setIgnoredAttributes([
                        //'updateAt'
                    ])
            ];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($bankA, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

        $data = json_decode($jsonObject,true);
        foreach ($data as $key => $value){
         
            if($key!="id" && !empty($value)) {

                $bankA->setSolde(2000000);
               
            }
        }
        $errors = $validator->validate($bankA);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le solde a bien été mis à jour'
        ];
        return new JsonResponse($data);
 
    }

     /**
     * @Rest\Get("/bankAccount", name="compteList")
     */
    public function listAction(SerializerInterface $serializer):Response
    {
        $compte = $this->getDoctrine()->getRepository('App:BankAccount')->findAll();
        $encoders = [new JsonEncoder()];
            $normalizers = [
                (new ObjectNormalizer())
                    ->setIgnoredAttributes([
                        //'updateAt'
                    ])
            ];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($compte, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
        return new Response($jsonObject, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
