<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tipr\ApplicationBundle\Form\Type\DonateType;
use Symfony\Component\HttpFoundation\Request;

class DonatorController extends BaseController
{

    public function indexAction()
    {
        // todo: find donator id in sessions
        $id = 1;

        $donator = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->find($id);

        $donations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsLimit($donator->getId(), 5);

        $donationsThisWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsThisWeek($donator->getId());

        $totalDay = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getTotalThisDay($donator->getId());

        $totalWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getTotalThisWeek($donator->getId());


        $totalMonth  = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getTotalThisMonth($donator->getId());


        return $this->render('TiprApplicationBundle:Donator:index.html.twig', array(
            'donator' => $donator,
            'donations' => $donations,
            'totalToday' => $totalDay,
            'totalWeek' => $totalWeek,
            'totalMonth' => $totalMonth,
            'donationsThisWeek' => $donationsThisWeek
        ));
    }

    public function donateAction($username)
    {
        $form = $this->createForm(new DonateType());

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('username' => $username));

        if(!$recipient){
            throw new NotFoundHttpException();
        }

        return $this->render('TiprApplicationBundle:Donator:donate.html.twig', array(
            'recipient' => $recipient,
            'username' => $username,
            'form' => $form->createView()
        ));
    }

    public function donateProcessAction(Request $request,$username)
    {
        var_dump('test');

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('username' => $username));

        if(!$recipient){
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new DonateType());
        $form->handleRequest($request);

        if($form->isValid()){
            $data = $form->getData();

            // check data again
            $donator = $this->getDoctrine()
                ->getRepository('TiprApplicationBundle:Donator')
                ->findOneBy(array('username' => $data['username'],'code' => $data['code']));

            if($donator == null){
                $error = '';
            }else{
                // log in if not logged in
                $cookie = $request->getSession()->get('cookie') ?: $this->logIn($donator->getDocumentNumber(),$donator->getBirthDay());
                $request->getSession()->set('cookie',$cookie);
                $request->getSession()->set('personId',$donator->getApiId());

                var_dump($this->api_get('/openapi/rest/products',$cookie));
            }
        }

        return $this->render('TiprApplicationBundle:Donator:donate.html.twig', array(
            'recipient' => $recipient,
            'username' => $username,
            'form' => $form->createView()
        ));
    }
} 