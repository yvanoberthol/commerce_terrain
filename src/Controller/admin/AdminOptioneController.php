<?php

namespace App\Controller\admin;

use App\Entity\Optione;
use App\Form\OptioneType;
use App\Repository\OptioneRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/optione")
 */
class AdminOptioneController extends AbstractController
{
    /**
     * @var OptioneRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(OptioneRepository $repository,ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/", name="admin.optione.index", methods={"GET"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $optiones = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        $count = count($this->repository->findAll());
        return $this->render('admin/optione/index.html.twig',['optiones' => $optiones,'count'=>$count]);
    }

    /**
     * @Route("/new", name="admin.optione.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $optione = new Optione();
        $form = $this->createForm(OptioneType::class, $optione);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->em->persist($optione);
                $this->em->flush();

                return $this->redirectToRoute('admin.optione.index');
            }
        }

        return $this->render('admin/optione/new.html.twig', [
            'optione' => $optione,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="admin.optione.show", methods={"GET"})
     * @param Optione $optione
     * @return Response
     */
    public function show(Optione $optione): Response
    {
        return $this->render('admin/optione/show.html.twig', [
            'optione' => $optione,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.optione.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Optione $optione
     * @return Response
     */
    public function edit(Request $request, Optione $optione): Response
    {
        $form = $this->createForm(OptioneType::class, $optione);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->flush();

                return $this->redirectToRoute('admin.optione.index', [
                    'id' => $optione->getId(),
                ]);
            }
        }

        return $this->render('admin/optione/edit.html.twig', [
            'optione' => $optione,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="admin.optione.delete", methods={"DELETE"})
     * @param Request $request
     * @param Optione $optione
     * @return Response
     */
    public function delete(Request $request, Optione $optione): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optione->getId(), $request->request->get('_token'))) {
            $this->em->remove($optione);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin.optione.index');
    }
}
