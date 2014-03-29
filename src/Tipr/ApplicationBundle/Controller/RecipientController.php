<?php

namespace Tipr\ApplicationBundle\Controller;

class RecipientController extends BaseController
{
    public function indexAction()
    {
        // todo: get reciever
        $id = 1;

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->find($id);

        $recentDonations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getRecentDonationsLimit($recipient->getId(), 13);

        $highestDonations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getHighestDontionsLimit($recipient->getId(), 6);

        $donationsThisDay = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getDonationsThisWeek($recipient->getId());

        $donationsThisWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getDonationsThisWeek($recipient->getId());

        $donationsThisMonth = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getDonationsThisWeek($recipient->getId());

        return $this->render('TiprApplicationBundle:Recipient:index.html.twig', array(
            'recipient' => $recipient,
            'recentDonations' => $recentDonations,
            'highestDonations' => $highestDonations,
            'totalToday' => $donationsThisDay,
            'totalWeek' => $donationsThisWeek,
            'totalMonth' => $donationsThisMonth
        ));
    }
} 