<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Entity\Dataset;
use App\Entity\DatasetTables;
use App\Entity\DatasetTablesFields;
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
        for ($i = 0; $i < count(json_decode($tabs)) ; $i++){

            $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Dataset::class);
            $ds = $repository->findOneBy(['nom' => $nom]);

           dump(json_decode($tabs)[$i]);
           $dsTab = new DatasetTables();
           $dsTab->setTableName(json_decode($tabs)[$i]);
           $dsTab->setIdDataset($ds);
            $entityManager->persist($dsTab);
            $entityManager->flush();


            // $queryTab1 = $queryTab1 . $tab1 . "." . json_decode($tab1Cols)[$i] . "," ;
        }

        json_encode($dsTab);

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
        $lst = $repository->findByName( $dataset ); /////////////////////////2nd par : table
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
        $dataset = $repository1->findOneBy(['id' => $request->get("dataset")]);

        $repository2 = $this->getDoctrine()->getRepository(Dashboard::class);



        $array = [];
        foreach ($dataset->getDatasetTables() as $tab)

        { $cols = $repository2->listColumns(   $tab->getTableName());
            $colsArray = [];
            foreach ($cols as $col){
                array_push($colsArray, $col["COLUMN_NAME"]);
            }
            $tab = array("Tab" => $tab->getTableName() , "cols" => $colsArray);

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
        $dataset = $request->get('dataset');


        $tab1 = $request->get('tab1');
        $tab1Cols = $request->get('tab1Cols');
        $tab1Agreg =  $request->get('tab1Agreg');
        $tab1JoinCol = $request->get('tab1JoinCol');

        $tab2 = $request->get('tab2');
        $tab2Cols = $request->get('tab2Cols');
        $tab2Agreg =  $request->get('tab2Agreg');
        $tab2JoinCol = $request->get('tab2JoinCol');


        $entityManager = $this->getDoctrine()->getManager();
        $repository0 = $this->getDoctrine()->getRepository(DatasetTables::class);
        $dataset = $repository0->findOneBy(['id' => $dataset]);
        $repository = $this->getDoctrine()->getRepository(DatasetTables::class);
        $tab1 = $repository->findOneBy(['table_name' => $tab1,
                                    'id_dataset' => $dataset]);

        $tab1->setCleJointure($tab1JoinCol);
        $entityManager->persist($tab1);
        $entityManager->flush();

        $repository = $this->getDoctrine()->getRepository(DatasetTables::class);
        $tab2 = $repository->findOneBy(['table_name' => $tab2,
                             'id_dataset' => $dataset]);
        $tab2->setCleJointure($tab2JoinCol);
        $entityManager->persist($tab2);
        $entityManager->flush();


        for ($i = 0; $i < count(json_decode($tab1Cols)); $i++){
            $field = new DatasetTablesFields();
            $field->setIdDatasetTable($tab1);
            $field->setLabel(json_decode($tab1Cols)[$i]);
            $field->setAgregation(json_decode($tab1Agreg)[$i]);
            $entityManager->persist($field);
            $entityManager->flush();
        }

        for ($i = 0; $i < count(json_decode($tab2Cols)); $i++){
            $field = new DatasetTablesFields();
            $field->setIdDatasetTable($tab2);
            $field->setLabel(json_decode($tab2Cols)[$i]);
            $field->setAgregation(json_decode($tab2Agreg)[$i]);
            $entityManager->persist($field);
            $entityManager->flush();
        }

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


}
