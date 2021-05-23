<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Dashboard;

class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        if ( $this->isGranted('ROLE_NULL')){

            return $this->redirectToRoute("app_login");


        }else if ( $this->isGranted('ROLE_USER')){

            return $this->render('dashboard/index.html.twig', [
                'controller_name' => 'DashboardController',
            ]);

        }else {
            /*  return   $this->render('page_404.html.twig', [
        'controller_name' => 'DashboardController',
    ]);*/
            return $this->redirectToRoute("app_login");
        }
    }







    /**
     * @Route("/dashboard/listTables", name="listTables", methods={"GET"})
     */
    public function listTables()
    {

        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $entityManager = $this->getDoctrine()->getManager();

        $lst = $repository->listTables($entityManager);
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

        dump($response);

        $response->headers->set('Content-Type', 'application/json');


        return $response;

    }


    /**
     * @Route("/dashboard/getColumns", name="getColumns", methods={"GET"})
     */
    public function columnsApi(Request $request)
    {


        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $entityManager = $this->getDoctrine()->getManager();

        $lst = $repository->listColumns($request->get('tab'));

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


    /**
     * @Route("/dashboard/chart", name="chartApi", methods={"GET"})
     */
    public function chartApi(Request $request)
    {
        if ($request->get('chart') == "pieChart") {


            $repository = $this->getDoctrine()->getRepository(Dashboard::class);


            $lst = $repository->pieChartQuery($request->get('tab'), $request->get('col1'));
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

        } else if ($request->get('chart') == "lineChart") {
            $repository = $this->getDoctrine()->getRepository(Dashboard::class);

            $lst = $repository->barChartQuery($request->get('tab'), $request->get('col1'), $request->get('col2'));
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

        } else if ($request->get('chart') == "barChart") {
            $repository = $this->getDoctrine()->getRepository(Dashboard::class);

            $lst = $repository->barChartQuery($request->get('tab'), $request->get('col1'), $request->get('col2'));
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

        } else if ($request->get('chart') == "barChart2") {


            $repository = $this->getDoctrine()->getRepository(Dashboard::class);

            $lst = $repository->barChartQuery2($request->get('tab'), $request->get('col1'), $request->get('col2'), $request->get('query'));
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

    /**
     * @Route("/dashboard/getUniques", name="getUniques", methods={"GET"})
     */
    public function getUniques(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Dashboard::class);

        $lst = $repository->getUniques($request->get('tab'), $request->get('col1'));
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

    /*
    /**
     * @Route("/dashboard/barChart", name="barChart", methods={"GET"})
     */
   /* public function barChartApi(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Dashboard::class);

        $lst = $repository->barChartQuery($request->get('tab') , $request->get('col1') , $request->get('col2') );
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

    /**
     * @Route("/dashboard/lineChart", name="lineChart", methods={"GET"})
     */
  /*  public function lineChartApi(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);

        $lst = $repository->barChartQuery($request->get('tab') , $request->get('col1') , $request->get('col2') );
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







    /**
     * @Route("/dashboard/barChart2", name="barChart2", methods={"GET"})
     */
   /* public function barChart2Api(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Dashboard::class);

        $lst = $repository->barChartQuery2($request->get('tab') , $request->get('col1') , $request->get('col2'),$request->get('query') );
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

*/





