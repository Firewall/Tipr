<?php

namespace Tipr\ApplicationBundle\Controller;

class DonatorController extends BaseController
{

    public function indexAction()
    {
        // todo: find donator id in sessions
        $id = 1;

        $donator = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->find($id);

        // todo: get donations of donator
        $donations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsLimit($donator->getId(), 13);

        $donationsThisWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsThisWeek($donator->getId());


        return $this->render('TiprApplicationBundle:Donator:index.html.twig', array(
            'donator' => $donator,
            'donations' => $donations,
            'totalToday' => 5.00,
            'totalWeek' => 12.00,
            'totalMonth' => 150.00,
            'donationsThisWeek' => $donationsThisWeek
        ));
    }
} 