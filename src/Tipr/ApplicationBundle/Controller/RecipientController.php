<?php

namespace Tipr\ApplicationBundle\Controller;

class RecipientController extends BaseController
{
    public function indexAction()
    {
        // todo: get reciever
        $id = 0;

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->find($id);

        $donations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsLimit($recipient->getId(), 13);

        $donationsThisWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getDonationsThisWeek($recipient->getId());
        $donations = $recipient->getDonations();

        return $this->render('TiprApplicationBundle:Donator:index.html.twig',array(
            'recipient' => $recipient,
            'donations' => $donations
        ));
    }
} 