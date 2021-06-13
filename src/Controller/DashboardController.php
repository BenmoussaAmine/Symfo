<?php

namespace App\Controller;

use App\Entity\Chart;
use App\Entity\Dataset;
use App\Entity\DatasetTables;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Dashboard;

class DashboardController extends AbstractController
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
     * @Route("/dashboard/addChart", name="addChartDashboard")
     */
    public function addChart(): Response
    {
        if ( $this->isGranted('ROLE_NULL')){

            return $this->redirectToRoute("app_login");


        }else if ( $this->isGranted('ROLE_USER')){

            return $this->render('dashboard/addChart.html.twig', [
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
     * @Route("/dashboard/datasetChart", name="chartDatasetApi", methods={"GET"})
     */
    public function chartDatasetApi(Request $request)
    {

        // 127.0.0.1:8000/dashboard/datasetChart?chart=pirChart&dataset=test2&dimension=nom&mesure=pays
        if ($request->get('chart') == "pieChart") {

            $dataset = $request->get('dataset');
            $dimension = $request->get('dimension');


            $repository = $this->getDoctrine()->getRepository(Dashboard::class);


            $lst1 = $repository->getTable($dataset, $dimension);
            $tabDim = $lst1[0]["table_name"];


            dump($dataset);
            dump($dimension);
            dump($tabDim);
            /*  dump($mesure);
              dump($tabMes);*/

            $lst = $repository->pieChartQuery($tabDim, $dimension);


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
            // 127.0.0.1:8000/dashboard/datasetChart?chart=lineChart&dataset=test2&dimension=produit&mesure=pays
        } else if ($request->get('chart') == "lineChart") {
            $repository = $this->getDoctrine()->getRepository(Dashboard::class);

            $dataset = $request->get('dataset');
            $dimension = $request->get('dimension');
            $mesure = $request->get('mesure');
            $tabDim = $repository->getTable($dataset, $dimension)[0]["table_name"];
            $tabMes = $repository->getTable($dataset, $mesure)[0]["table_name"];
            $joinDim = $repository->getJoinKey($dataset, $dimension)[0]["cle_jointure"];
            $joinMes = $repository->getJoinKey($dataset, $mesure)[0]["cle_jointure"];
            /*   dump("dataset " . $dataset);
               dump("dimension " . $dimension);
               dump("mesure " . $mesure);
               dump("tabDim " . $tabDim);
               dump("tabMes " . $tabMes);
               dump("joinDim " . $joinDim);
               dump("joinMes " . $joinMes);
               die(); */
            $lst = $repository->lineChartDataset($dimension, $mesure, $tabDim, $tabMes, $joinDim, $joinMes);
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
// http://127.0.0.1:8000/dashboard/datasetChart?chart=barChart&dataset=test2&dimension=nom&mesure=pays
//                  &tabDim=produit&joinDim=id&tabMes=produit&joinMes=id
        } else if ($request->get('chart') == "barChart") {
            $repository = $this->getDoctrine()->getRepository(Dashboard::class);

            $dataset = $request->get('dataset');
            $dimension = $request->get('dimension');
            $mesure = $request->get('mesure');
            $tabDim = $repository->getTable($dataset, $dimension)[0]["table_name"];
            $tabMes = $repository->getTable($dataset, $mesure)[0]["table_name"];
            $joinDim = $repository->getJoinKey($dataset, $dimension)[0]["cle_jointure"];
            $joinMes = $repository->getJoinKey($dataset, $mesure)[0]["cle_jointure"];
            /*   dump("dataset " . $dataset);
               dump("dimension " . $dimension);
               dump("mesure " . $mesure);
               dump("tabDim " . $tabDim);
               dump("tabMes " . $tabMes);
               dump("joinDim " . $joinDim);
               dump("joinMes " . $joinMes);
               die(); */
            $lst = $repository->barChartDataset($dimension, $mesure, $tabDim, $tabMes, $joinDim, $joinMes);
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

//       /dashboard/datasetChart?chart=barChar2t&dataset=testBarChart1&dimension=age&mesure=genreCl
        } else if ($request->get('chart') == "barChart2") {
            $repository = $this->getDoctrine()->getRepository(Dashboard::class);

            $dataset = $request->get('dataset');
            $dimension = $request->get('dimension');
            $mesure = $request->get('mesure');
            $tabDim = $repository->getTable($dataset, $dimension)[0]["table_name"];
            $tabMes = $repository->getTable($dataset, $mesure)[0]["table_name"];
            $joinDim = $repository->getJoinKey($dataset, $dimension)[0]["cle_jointure"];
            $joinMes = $repository->getJoinKey($dataset, $mesure)[0]["cle_jointure"];

            $query = $request->get('query');


            $lst = $repository->barChartDataset2($dimension, $mesure, $tabDim, $tabMes, $joinDim, $joinMes , $query);
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

        } else {
            $response = new Response("erreur");
            return $response;
        }


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

    /**
     * @Route("/dashboard/getUniquesDataset", name="getUniquesDataset", methods={"GET"})
     */
    public function getUniquesDataset(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Dashboard::class);


        $mesure = $request->get('mesure');
        $dataset = $request->get('dataset');


        $repository = $this->getDoctrine()->getRepository(Dashboard::class);


        $tab = $repository->getTable($dataset, $mesure)[0]["table_name"];


        $lst = $repository->getUniques($tab,$mesure );
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
     * @Route("/api/dashboard/getDataset", name="getDataset", methods={"GET"})
     */
    public function getDataset()
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $lst = $repository->getDataset();
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
     * @Route("/api/dashboard/getDimensions", name="getDimensions", methods={"GET"})
     */
    public function getDimensions(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $lst = $repository->getDimensions($request->get('dataset'));
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
     * @Route("/api/dashboard/getMesures", name="getMesures", methods={"GET"})
     */
    public function getMesures(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $lst = $repository->getMesures($request->get('dataset'));
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


// http://127.0.0.1:8000/api/dashboard/addChart?typeChart=barChart&dataset=datasetTest&dimension=age&mesure=type

    /**
     * @Route("/api/dashboard/addChart", name="addChartApi", methods={"POST"})
     */
    public function addChartApi(Request $request)
    {
        $user = $this->security->getUser();
        $repository = $this->getDoctrine()->getRepository(Dataset::class);
        $dataset = $repository->findOneBy(['nom' => $request->get('dataset')]);


        $mesure = $request->get('mesure');
        $dimension = $request->get('dimension');
        $type = $request->get('typeChart');



        $chart = new Chart();
        $chart->setDimension($dimension);

        if ( $mesure != ""){
            $chart->setMesure($mesure);
        }
        $chart->setDataset($dataset);
        $chart->setType($type);
        $chart->setUser($user);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($chart);
        $entityManager->flush();


        json_encode("done");
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize("done", 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @Route("/api/dashboard/getUserCharts", name="getUserCharts", methods={"GET"})
     */
    public function getUserCharts(Request $request)
    {
        $user = $this->security->getUser();

        dump($user);

        $repository = $this->getDoctrine()->getRepository(Chart::class);
        $charts = $repository->findBy(['user' => $user]);


           json_encode($charts);
           $encoders = [new JsonEncoder()];
           $normalizers = [new ObjectNormalizer()];
           $serializer = new Serializer($normalizers, $encoders);
           $jsonContent = $serializer->serialize($charts, 'json', [
               'circular_reference_handler' => function ($object) {
                   return $object->getId();
               }
           ]);
           $response = new Response($jsonContent);
           $response->headers->set('Content-Type', 'application/json');
           return $response;
    }

    /**
     * @Route("/api/dashboard/deleteChart", name="deleteChart")
     */
    public function deleteChart(Request $request)
    {
        $user = $this->security->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        $repository = $this->getDoctrine()->getRepository(Chart::class);

        $chart = $repository->find($request->get('id'));

        $entityManager->remove($chart);
        $entityManager->flush();


        json_encode($chart);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($chart, 'json', [
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





