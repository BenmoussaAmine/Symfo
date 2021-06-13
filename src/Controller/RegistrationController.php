<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Dataset;
use App\Entity\Gouvernorat;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
     /*   $user = new User();
        $adresse = new Adresse();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $adresse->setCodePostal($form->get('codePostal')->getData());
            $rolesArr = array('ROLE_USER');
            $user->setRoles($rolesArr);
            $user->setEtat("inactive");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email



            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }
*/
        $repository = $this->getDoctrine()->getRepository(Gouvernorat::class);
        $gouvers = $repository->findAll();

        return $this->render('registration/register.html.twig', [
            'gouvers' => $gouvers,
        ]);
    }


    /**
     * @Route("/api/register/villes", name="apiGetVilles")
     */
    public function apiGetVilles(Request $request)
    {

        $gouv = $request->get('gouv') ;


        $repository = $this->getDoctrine()->getRepository(Ville::class);
        $lst = $repository->findBy(['idGouvernorat' => $gouv]);


        json_encode($lst);

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($lst, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response($jsonContent);

        dump($response);

        $response->headers->set('Content-Type', 'application/json');

        return $response;




    }

    /**
     * @Route("/api/register", name="apiRegister")
     */
    public function apiRegister(Request $request , UserPasswordEncoderInterface $passwordEncoder)
    {

        $nom = $request->get('nom') ;
        $prenom= $request->get('prenom') ;
        $email = $request->get('email') ;
        $password = $request->get('password') ;
        $adresseLib = $request->get('adresse') ;
        $codePostal = $request->get('codePostal') ;
        $idGouv = $request->get('gouv') ;
        $idVille = $request->get('ville') ;




//        dump("nomm : " . $nom);
//        dump("prenom : " . $prenom);
//        dump("email : " . $email);
//        dump("password : " . $password);
//        dump("adresse : " . $adresse);
//        dump("codePostal  : " . $codePostal);
//        dump("gouvernorat : " . $idGouv);
//        dump("ville : " . $idVille);
//        die();



        $repositoryGouv = $this->getDoctrine()->getRepository(Gouvernorat::class);
        $gouv = $repositoryGouv->findOneBy(['id' => $idGouv]);

        $repositoryVille = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $repositoryVille->findOneBy(['id' => $idVille]);



        $user = new User();
        $adresse = new Adresse();
        $adresse->setCodePostal($codePostal);
        $adresse->setIdGouvernorat($gouv);
        $adresse->setIdVille($ville);
        $adresse->setLibelle($adresseLib);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($adresse);
        $entityManager->flush();

        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $rolesArr = array('ROLE_USER');
        $user->setRoles($rolesArr);
        $user->setIdAdresse($adresse);
        $user->setEtat("non actif");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();





        json_encode($entityManager->flush());

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($entityManager->flush(), 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response($jsonContent);

        dump($response);

        $response->headers->set('Content-Type', 'application/json');

        return $response;




    }

}
