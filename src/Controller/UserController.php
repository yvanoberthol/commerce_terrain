<?php
/**
 * Created by PhpStorm.
 * User: yvano berthol
 * Date: 2/26/2019
 * Time: 11:54 AM
 */

namespace App\Controller;


use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    //private $repository;
    /**
     * @var ObjectManager
     */
    //private $em;

    /*public function __construct(UserRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }*/


    /**
     * @Route("/login", name="login",methods={"GET","POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig',
            [
                'last_username'=>$lastUsername,
                'error'=>$error
            ]);
    }

}