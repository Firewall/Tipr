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

        $donations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsLimit($donator->getId(), 5);

        $donationsThisWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsThisWeek($donator->getId());

        var_dump($donationsThisWeek);

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