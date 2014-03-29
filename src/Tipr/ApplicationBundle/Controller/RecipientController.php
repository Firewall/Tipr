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

        // todo: get donations of donator
        $donations = $recipient->getDonations();

        return $this->render('TiprApplicationBundle:Donator:index.html.twig',array(
            'recipient' => $recipient,
            'donations' => $donations
        ));
    }
} 