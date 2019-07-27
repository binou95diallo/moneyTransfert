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
     * @Route("/{id}/edit", name="bankAccountEdit", methods={"GET","POST"})
     */
    public function edit(Request $request, BankAccount $bankAccount): Response
    {
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bank_account_index');
        }

        return $this->render('bank_account/edit.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bank_account_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BankAccount $bankAccount): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bankAccount->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bankAccount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bank_account_index');
    }
}
