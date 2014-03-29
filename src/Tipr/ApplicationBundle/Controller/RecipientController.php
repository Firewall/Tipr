<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class RecipientController extends BaseController
{
    public function indexAction(Request $request)
    {
        // get recipient
        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('api_key' => $request->getSession()->get('personId')));

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

    public function settingsAction(Request $request)
    {
        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('apiId' => $request->getSession()->get('personId')));

        return $this->render('TiprApplicationBundle:Recipient:settings.html.twig', array(
            'recipient' => $recipient,
        ));
    }

    public function settingsProcessAction(Request $request)
    {
        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('apiId' => $request->getSession()->get('personId')));

        return $this->render('TiprApplicationBundle:Recipient:settings.html.twig', array(
            'recipient' => $recipient,
        ));
    }
} 