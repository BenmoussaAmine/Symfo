<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Entity\Dataset;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class DatasetController extends AbstractController
{
    /**
     * @Route("/dataset", name="dataset")
     */
    public function index(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Dataset::class);

        $entityManager = $this->getDoctrine()->getManager();
        $lst = $repository->findAll($entityManager);

        return $this->render('dataset/index.html.twig', [
            'datasets' => $lst, 'key' => ""
        ]);

    }

    /**
     * @Route("/datasetFiltred", name="datasetFiltred")
     */
    public function indexFiltred(Request $request): Response
    {

        $key = $request->get('key');

        $repository = $this->getDoctrine()->getRepository(Dataset::class);

        $entityManager = $this->getDoctrine()->getManager();
        $lst = $repository->filterByKey($key);

        return $this->render('dataset/index.html.twig', [
            'datasets' => $lst, 'key' => $key
        ]);

    }


    /**
     * @Route("/dataset/create", name="createDataset")
     */
    public function create(): Response
    {



        $repository = $this->getDoctrine()->getRepository(Dataset::class);

        $entityManager = $this->getDoctrine()->getManager();
        $lst = $repository->findAll($entityManager);
        return $this->render('dataset/create.html.twig', [
            'users' => $lst,
        ]);
    }

    /**
     * @Route("/dataset/add", name="addDataset")
     */
    public function add(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Dataset::class);

        $entityManager = $this->getDoctrine()->getManager();
        $lst = $repository->findAll($entityManager);
        return $this->render('dataset/add.html.twig', [
            'users' => $lst,
        ]);
    }

    // API  :   http://127.0.0.1:8000/api/dataset/listDatasets

    /**
     * @Route("/api/dataset/listDatasets", name="listDatasets")
     */
    public function listDatasets(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Dataset::class);
        $entityManager = $this->getDoctrine()->getManager();

        $lst = $repository->listDatasets($request->get('nom'));
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



    // API  :   http://127.0.0.1:8000/api/dataset/create?nom=test

    /**
     * @Route("/api/dataset/create", name="apiCreateDataset")
     */
    public function createDataset(Request $request)
    {

        $tabs = $request->get('tabs') ;
        $nom = $request->get('nom') ;


        $repository = $this->getDoctrine()->getRepository(Dataset::class);
        $entityManager = $this->getDoctrine()->getManager();

        $lst = $repository->createDataset($nom);
        $lst = $repository->addToDataset( $nom , $tabs);
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

    // API  :   http://127.0.0.1:8000/api/dataset/add?dataset=az&table=testing

    /**
     * @Route("/api/dataset/add", name="apiAddTableDataset")
     */
    public function addTableDataset(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Dataset::class);
        $entityManager = $this->getDoctrine()->getManager();

        $dataset = $request->get('dataset') ;
        $table = $request->get('table');
        $lst = $repository->findByName( $dataset , $table);
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
        $parametersAsArray = [];
        if ($content = $response->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $tables = $parametersAsArray[0]['tables'];
        $tablesArray = json_decode($tables, true);


        if (empty($tablesArray) ) {
            $tablesArray = array();
        }
        array_push($tablesArray, $table);


        $id = $parametersAsArray[0]['id'];

        $lst = $repository->addToDataset( $dataset , json_encode($tablesArray));



        die();

        return $response;
    }

    /**
     * @Route("/dataset/config", name="configDataset")
     */
    public function configDataset(Request $request)
    {
        $repository1 = $this->getDoctrine()->getRepository(Dataset::class);
        $lst = $repository1->getTables( $request->get("dataset"));
        $repository2 = $this->getDoctrine()->getRepository(Dashboard::class);



        $array = [];
        $lstTablesArray =  json_decode(json_decode(json_encode($lst))[0]->tables);
        foreach ($lstTablesArray as $value)
        { $cols = $repository2->listColumns(    $value);
            $colsArray = [];
            foreach ($cols as $col){
                array_push($colsArray, $col["COLUMN_NAME"]);
            }
            $tab = array("Tab" => $value , "cols" => $colsArray);

            array_push($array, $tab);
        }



        return $this->render('dataset/config.html.twig', [
            'tabs' => $array,
        ]);

    }

    /**
     * @Route("/api/dataset/config", name="apiConfigDataset")
     */
    public function apiConfigDataset(Request $request): Response
    {
        $tab1 = $request->get('tab1');
        $tab1Cols = $request->get('tab1Cols');
        $tab1JoinCol = $request->get('tab1JoinCol');

        $tab2 = $request->get('tab2');
        $tab2Cols = $request->get('tab2Cols');
        $tab2JoinCol = $request->get('tab2JoinCol');


        dump("tab1   " . $tab1);
        dump("tab1Cols   " . $tab1Cols);
        dump("tab1JoinCol   " . $tab1JoinCol);

        dump("tab2   " . $tab2);
        dump("tab2Cols   " . $tab2Cols);
        dump("tab2JoinCol   " . $tab2JoinCol);


        die();
    }


}
