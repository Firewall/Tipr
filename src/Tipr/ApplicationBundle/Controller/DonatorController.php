<?php

namespace Tipr\ApplicationBundle\Controller;

class DonatorController extends BaseController{

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