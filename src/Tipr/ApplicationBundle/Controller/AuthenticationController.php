<?php
/**
 * Created by PhpStorm.
 * User: antho_000
 * Date: 29/03/14
 * Time: 12:52
 */

namespace Tipr\ApplicationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tipr\ApplicationBundle\Form\Type\LogInType;

class AuthenticationController extends Controller
{
    public function logInAction()
    {
        $form = $this->createForm(new LogInType());

        return $this->render('TiprApplicationBundle:Authentication:login.html.twig',array(
            'form' => $form->createView()
        ));
    }

    public function logInProcessAction(Request $request)
    {
        $form = $this->createForm(new LogInType());

        $form->handleRequest($request);

        if($form->isValid()){
            $data = $form->getData();
        }

        return $this->render('TiprApplicationBundle:Authentication:login.html.twig',array(
            'form' => $form->createView()
        ));
    }
} 