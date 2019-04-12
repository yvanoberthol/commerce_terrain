<?php
/**
 * Created by PhpStorm.
 * User: yvano berthol
 * Date: 2/25/2019
 * Time: 10:36 AM
 */

namespace App\Controller\admin;



use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AdminPropertyController extends AbstractController
{


    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repository,ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/biens",name="admin.property.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator,Request $request):Response{

        $properties = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        $count = count($this->repository->findAll());
        return $this->render('admin/property/index.html.twig',['properties' => $properties,'count'=>$count]);
    }

    /**
     * @Route("/admin/property/edit/{id}",name="admin.property.edit",methods={"GET","POST"})
     * @param Property $property
     * @param Request $request
     * @param CacheManager $cacheManager
     * @param UploaderHelper $uploaderHelper
     * @return Response
     */
    public function editForm(Property $property,Request $request){

        $form =  $this->createForm(PropertyType::class,$property);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->em->flush();
                $this->addFlash('succes','Le bien a été modifié avec succès');
                return $this->redirectToRoute('admin.property.index');
            }
        }


        if ($property === null){
            return $this->redirectToRoute('admin.property.index',[],301);
        }


        return $this->render('admin/property/edit.html.twig',
        [
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/admin/property/create",name="admin.property.create",methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function newProperty(Request $request){
        $property = new Property();

        $form =  $this->createForm(PropertyType::class,$property);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->em->persist($property);
                $this->em->flush();
                $this->addFlash('succes','Bien créé avec succès');
                return $this->redirectToRoute('admin.property.index');
            }
        }

        return $this->render('admin/property/new.html.twig',
            [
                'property'=>$property,
                'form'=>$form->createView()
            ]);
    }

    /**
     * @Route("/admin/property/delete/{id}",name="admin.property.delete",methods={"DELETE"})
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function delete(Property $property,Request $request){
        if ($this->isCsrfTokenValid('delete'.$property->getId(),$request->get('_token'))){
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('succes','Le bien '.$property->getTitle().' a été supprimé avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        $this->addFlash('error','Désolé, tu ne peux pas supprimer ce bien');
        return $this->redirectToRoute('admin.property.index');

    }


}