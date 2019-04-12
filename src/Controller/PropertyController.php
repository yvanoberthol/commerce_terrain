<?php
/**
 * Created by PhpStorm.
 * User: yvano berthol
 * Date: 2/22/2019
 * Time: 1:35 PM
 */

namespace App\Controller;



use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class PropertyController extends AbstractController
{
    private $repository;
    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/biens",name="properties.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator,Request $request):Response{
        $search = new PropertySearch();
        $formSearch = $this->createForm(PropertySearchType::class,$search);

        $formSearch->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/index.html.twig',[
            'current_menu' => 'properties',
            'properties'=>$properties,
            'formSearch'=>$formSearch->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}",name="properties.show",requirements={"slug":"[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    public function show(Property $property,string $slug):Response{

        if ($property->getSlug() !== $slug){
            return $this->redirectToRoute('properties.show',[
                'id' => $property->getId(),
                'slug' =>$property->getSlug()
            ],301);
        }
        return $this->render('property/show.html.twig',[
            'current_menu' => 'properties','property'=>$property
        ]);

    }
}