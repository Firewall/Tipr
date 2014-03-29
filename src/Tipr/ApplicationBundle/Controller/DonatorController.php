<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DonatorController extends Controller{

    public function indexAction()
    {
        // todo: find donator id in sessions
        $id = 0;

        $donator = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->find($id);


        // todo: get donations of donator
        $donations = $donator->getDonations();

        return $this->render('TiprApplicationBundle:Donator:index.html.twig',array(
            'donator' => $donator,
            'donations' => $donations
        ));
    }
} 