<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRespository ;
use App\Entity\User;
use App\Entity\Dashboard;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }



    /**
     * @Route("/userRedirect", name="userRedirect")
     */
    public function userRedirect(): Response
    {
        $user = $this->security->getUser();
       if ($user->getEtat() == "non actif"){
           return $this->redirectToRoute("app_login");
       } else {
           if ($this->isGranted('ROLE_ADMIN')){
               /*  return   $this->render('page_404.html.twig', [
           'controller_name' => 'DashboardController',
       ]);*/
               return $this->redirectToRoute("utilisateurs");
           }else if ($this->isGranted('ROLE_USER')){
               /*  return   $this->render('page_404.html.twig', [
                       'controller_name' => 'DashboardController',
                   ]);*/
               return $this->redirectToRoute("dashboard");
           }

       }


//        if ($this->isGranted('ROLE_ADMIN')){
//            /*  return   $this->render('page_404.html.twig', [
//        'controller_name' => 'DashboardController',
//    ]);*/
//            return $this->redirectToRoute("utilisateurs");
//        }else if ($this->isGranted('ROLE_USER')){
//            /*  return   $this->render('page_404.html.twig', [
//                    'controller_name' => 'DashboardController',
//                ]);*/
//            return $this->redirectToRoute("dashboard");
//        }else {
//            /*  return   $this->render('page_404.html.twig', [
//        'controller_name' => 'DashboardController',
//    ]);*/
//            return $this->redirectToRoute("logout");
//        }

    }


    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function usersList()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);


        $entityManager = $this->getDoctrine()->getManager();
        $lst = $repository->findAll($entityManager);

        return $this->render('admin/users.html.twig', [
            'users' => $lst,
        ]);
    }


    // API  :   http://127.0.0.1:8000/utilisateurs/active?id=10
    /**
     * @Route("/utilisateurs/active", name="userActive")
     */
    public function userActive(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $response = new Response();

        if ($repository->active( $request->get('id') )){
            $response->setContent(json_encode([
                'resultat' => "OK",
            ]));

        }else {
            $response->setContent(json_encode([
                'resultat' => "ERREUR",
            ]));
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    // API  :   http://127.0.0.1:8000/utilisateurs/desactive?id=10
    /**
     * @Route("/utilisateurs/desactive", name="userDesactive")
     */
    public function userDesactive(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $response = new Response();

        if ($repository->desactive( $request->get('id') )){
            $response->setContent(json_encode([
                'resultat' => "OK",
            ]));

        }else {
            $response->setContent(json_encode([
                'resultat' => "ERREUR",
            ]));
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    // API  :   http://127.0.0.1:8000/utilisateurs/promote?id=10
    /**
     * @Route("/utilisateurs/promote", name="userPromote")
     */
    public function userPromote(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $response = new Response();

        if ($repository->promote( $request->get('id') )){
            $response->setContent(json_encode([
                'resultat' => "OK",
            ]));

        }else {
            $response->setContent(json_encode([
                'resultat' => "ERREUR",
            ]));
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    // API  :   http://127.0.0.1:8000/utilisateurs/demote?id=10
    /**
     * @Route("/utilisateurs/demote", name="userDemote")
     */
    public function userDemote(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $response = new Response();

        if ($repository->demote( $request->get('id') )){
            $response->setContent(json_encode([
                'resultat' => "OK",
            ]));

        }else {
            $response->setContent(json_encode([
                'resultat' => "ERREUR",
            ]));
        }
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }




     // API  :   http://127.0.0.1:8000/utilisateurs/changePw?id=10&pw=000000
    /**
     * @Route("/utilisateurs/changePw", name="userChangePw")
     */
    public function userChangePw(Request $request , UserPasswordEncoderInterface $encoder)
    {$repository = $this->getDoctrine()->getRepository(User::class);

        $id = $request->get('id') ;
        $pw = $request->get('pw')  ;

        $user = new User();
        $plainPassword = $pw;
        $encoded = $encoder->encodePassword($user, $plainPassword);

        $response = new Response();

        if ($repository->change( $id , $encoded)){
            $response->setContent(json_encode([
                'resultat' => "OK",
            ]));

        }else {
            $response->setContent(json_encode([
                'resultat' => "ERREUR",
            ]));
        }
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }



    // API  :   http://127.0.0.1:8000/utilisateurs/delete?id=10
    /**
     * @Route("/utilisateurs/delete", name="userDelete")
     */
    public function userDelete(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $response = new Response();

        if ($repository->delete( $request->get('id') )){
            $response->setContent(json_encode([
                'resultat' => "OK",
            ]));

        }else {
            $response->setContent(json_encode([
                'resultat' => "ERREUR",
            ]));
        }
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }


    // API  :   http://127.0.0.1:8000/utilisateurs/getCountGov
    /**
     * @Route("/utilisateurs/getCountGov", name="getCountGov")
     */
    public function getCountGov(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(User::class);
        $entityManager = $this->getDoctrine()->getManager();

        $lst = $repository->getCountGov($entityManager);
        // $lst = $repository->findAll();
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

        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }
}
