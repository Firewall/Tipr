<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DonatorController extends BaseController
{
    public function indexAction()
    {
        // todo: find donator id in sessions
        $id = 0;

        $donator = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->find($id);

        // todo: get donations of donator
        $donations = $donator->getDonations();

        return $this->render('TiprApplicationBundle:Donator:index.html.twig', array(
            'donator' => $donator,
            'donations' => $donations
        ));
    }

    public function donateAction($displayname)
    {
        $donator = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->findOneBy(array('displayname' => $displayname));

        if(!$donator){
            throw new NotFoundHttpException();
        }

        return $this->render('TiprApplicationBundle:Donator:donate.html.twig', array(
            'donator' => $donator,
        ));
    }

    public function donateProcessAction()
    {

    }
} 