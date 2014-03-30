<?php

namespace Tipr\ApplicationBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tipr\ApplicationBundle\Form\Type\LogInType;
use Tipr\ApplicationBundle\Form\Type\RegisterType;
use Httpful\Request as Api;
use Tipr\ApplicationBundle\Entity\Donator;
use Tipr\ApplicationBundle\Entity\Recipient;

class AuthenticationController extends BaseController
{
    public function loginDonatorAction(){
        $form = $this->createForm(new LogInType());

        return $this->render('TiprApplicationBundle:Authentication:loginDonator.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function loginDonatorProcessAction(Request $request){
        $form = $this->createForm(new LogInType());

        $form->handleRequest($request);

        if($form->isValid()){
            $data = $form->getData();

            $donator = $this->getDoctrine()
                ->getRepository('TiprApplicationBundle:Donator')
                ->findOneBy(array('username' => $data['username'],'code' => $data['code']));

            $cookie = $this->logIn($donator->getDocumentNumber(),$donator->getBirthday());

            $request->getSession()->set('personId',$donator->getApiId());
            $request->getSession()->set('cookie', $cookie);

            return $this->redirect($this->generateUrl('tipr_application_m_donator_overview'));
        }

        return $this->render('TiprApplicationBundle:Authentication:loginDonator.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function loginRecipientAction(){
        $form = $this->createForm(new LogInType());

        return $this->render('TiprApplicationBundle:Authentication:loginRecipient.html.twig', array(
            'form' => $form->createView(),
            'error' => false
        ));
    }

    public function loginRecipientProcessAction(Request $request){
        $form = $this->createForm(new LogInType());

        $form->handleRequest($request);


        if($form->isValid()){
            $data = $form->getData();

            $recipient = $this->getDoctrine()
                ->getRepository('TiprApplicationBundle:Recipient')
                ->findOneBy(array('username' => $data['username'],'code' => $data['code']));

            $cookie = $this->logIn($recipient->getDocumentNumber(),$recipient->getBirthday());

            $request->getSession()->set('personId',$recipient->getApiId());
            $request->getSession()->set('cookie', $cookie);

            return $this->redirect($this->generateUrl('tipr_application_recipient_overview'));
        }


        return $this->render('TiprApplicationBundle:Authentication:loginRecipient.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function registerDonatorAction()
    {
        $form = $this->createForm(new RegisterType());

        return $this->render('TiprApplicationBundle:Authentication:registerDonator.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function registerRecipientAction()
    {
        $form = $this->createForm(new RegisterType());

        return $this->render('TiprApplicationBundle:Authentication:registerRecipient.html.twig', array(
            'form' => $form->createView(),
            'error' => false
        ));
    }

    public function registerDonatorProcessAction(Request $request)
    {
        $error = false;

        $form = $this->createForm(new RegisterType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $cookie = $this->logIn($data['documentNumber'],$data['birthDate']);
            $client = $this->api_get('/openapi/rest/client', $cookie);

            if ($cookie && isset($client['personId'])) {
                $em = $this->getDoctrine()->getManager();

                $donator = $this->getDoctrine()
                    ->getRepository('TiprApplicationBundle:Donator')
                    ->findOneBy(array('api_id' => $client['personId']));

                if($donator == null){
                    $donator = new Donator();
                    $donator->setName($client['name']);
                    $donator->setSurname($client['firstSurname']);
                    $donator->setUsername($data['username']);
                    $donator->setApiId($client['personId']);
                    $donator->setDocumentNumber($data['documentNumber']);
                    $donator->setBirthday($data['birthDate']);
                    $donator->setCode($data['code']);
                    $em->persist($donator);

                    $em->flush();

                    // log in
                    $request->getSession()->set('personId', $client['personId']);
                    $request->getSession()->set('cookie', $cookie);

                    return $this->redirect($this->generateUrl('tipr_application_m_donator_overview'));
                }
                else{
                    $error = 'Already registered';
                }
            } else {
                $error = 'Incorrect document number or birthday';
            }
        }

        return $this->render('TiprApplicationBundle:Authentication:registerDonator.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }

    public function registerRecipientProcessAction(Request $request)
    {
        $error = false;

        $form = $this->createForm(new RegisterType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $cookie = $this->logIn($data['documentNumber'],$data['birthDate']);
            $client = $this->api_get('/openapi/rest/client', $cookie);

            if ($cookie && isset($client['personId'])) {
                $em = $this->getDoctrine()->getManager();

                $recipient = $this->getDoctrine()
                    ->getRepository('TiprApplicationBundle:Recipient')
                    ->findOneBy(array('api_id' => $client['personId']));

                if($recipient == null){

                    $recipient = new Recipient();
                    $recipient->setName($client['name']);
                    $recipient->setSurname($client['firstSurname']);
                    $recipient->setUsername($data['username']);
                    $recipient->setApiId($client['personId']);
                    $recipient->setDocumentNumber($data['documentNumber']);
                    $recipient->setBirthday($data['birthDate']);
                    $recipient->setCode($data['code']);
                    $em->persist($recipient);

                    $em->flush();

                    // log in
                    $request->getSession()->set('personId', $client['personId']);
                    $request->getSession()->set('cookie', $cookie);

                    return $this->redirect($this->generateUrl('tipr_application_recipient_overview'));
                }
                else{
                    $error = 'Already registered';
                }
            } else {
                $error = 'Incorrect document number or birthday';
            }
        }

        return $this->render('TiprApplicationBundle:Authentication:registerRecipient.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }

    public function logoutRecipientProcessAction(Request $request)
    {
        $request->getSession()->remove('cookie');
        $request->getSession()->remove('personId');
        return $this->redirect($this->generateUrl('tipr_application_loginRecipientProcess'));
    }
} 