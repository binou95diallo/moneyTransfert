<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Partenaire;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\PartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
     /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if(isset($values->username,$values->password)) {
            $user = new User();
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setProfil($values->profil);
            $user->setStatus($values->status);
            $user->setNomComplet($values->nomComplet);
            $user->setAdresse($values->adresse);
            $user->setTelephone($values->telephone);
            $user->setEmail($values->email);
            $partenaire=$this->getDoctrine()->getManager()->getRepository(Partenaire::class)->find($values->partenaire);
            $user->setPartenaire($partenaire);
            $profil=$values->profil;
            $roles=[];
            if($profil=="admin"){
                $roles=["ROLE_ADMIN"];
            }
            elseif ($profil=="user") {
                $roles=["ROLE_USER"];
            }
            elseif ($profil=="partenaire" || $profil=="adminPartenaire") {
                $roles=["ROLE_ADMINPARTENAIRE"];
            }
            $user->setRoles($roles);
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($user);
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
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }

     /**
     * @Rest\Get("/users", name="usersList")
     */
    public function listAction(SerializerInterface $serializer):Response
    {
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $encoders = [new JsonEncoder()];
            $normalizers = [
                (new ObjectNormalizer())
                    ->setIgnoredAttributes([
                        //'updateAt'
                    ])
            ];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($users, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
        return new Response($jsonObject, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

     /**
     * @Route("/users/{id}/edit", name="usersEdit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    
    public function edit(Request $request, User $user,SerializerInterface $serializer,ValidatorInterface $validator,
                         EntityManagerInterface $entityManager): Response
    {
        $data=[];
        $user = $entityManager->getRepository(User::class)->find($user->getId());
        $encoders = [new JsonEncoder()];
            $normalizers = [
                (new ObjectNormalizer())
                    ->setIgnoredAttributes([
                        //'updateAt'
                    ])
            ];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($user, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

        $data = json_decode($jsonObject,true);
        foreach ($data as $key => $value){
         
            if($key!="id" && !empty($value)) {

                if ($key=="username") {
                    $user->setUsername("yacine");
                }
                elseif ($key=="passWord") {
                    $user->setPassWord("124");
                }
                elseif ($key=="profil") {
                    $user->setProfil("user");
                }
                elseif ($key=="status") {
                    $user->setStatus("bloqué");
                }
                elseif ($key=="nomComplet") {
                    $user->setNomComplet("diallo");
                }
                elseif ($key=="adresse") {
                    $user->setAdresse("fann hock");
                }
                elseif ($key=="telephone") {
                    $user->setTelephone("7746200018200");
                }
               
            }
        }
        $errors = $validator->validate($user);
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
     * @Route("/users/bloquer", name="userBlock", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function userBloquer(Request $request, UserRepository $userRepo,EntityManagerInterface $entityManager): Response
    {
        $values = json_decode($request->getContent());
        $user=$userRepo->findOneByUsername($values->username);
        $user->setStatus("bloquer");
        $user->setRoles(["ROLE_USERLOCK"]);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'utilisateur bloqué'
        ];
        return new JsonResponse($data);
    }
}