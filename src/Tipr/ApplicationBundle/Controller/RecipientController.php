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
            ->findOneBy(array('api_id' => $request->getSession()->get('personId')));

        $donationsWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getDonationsThisWeekPerDay($recipient);

        $recentDonations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getRecentDonationsLimit($recipient->getId(), 13);

        $highestDonations = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getHighestDontionsLimit($recipient->getId(), 6);

        $donationsThisDay = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getTotalThisDay($recipient->getId());

        $donationsThisWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getTotalThisWeek($recipient->getId());

        $donationsThisMonth = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getTotalThisMonth($recipient->getId());

        $url = "http://tipr.be/m/donate/" . $recipient->getUsername();
//        $logo_file = "/home/matthew/code/Tipr/src/Tipr/ApplicationBundle/Controller/overlay.png";
        $qrUrl = "http://chart.apis.google.com/chart?cht=qr&chs=500x500&chld=L|0&chl=$url";
//        $targetfile = 'qrs/' . $recipient->getUsername() . ".png";
//        $photo = imagecreatefrompng($image_file);
//        $fotoW = imagesx($photo);
//        $fotoH = imagesy($photo);
//        $logoImage = imagecreatefrompng($logo_file);
//        $logoW = imagesx($logoImage);
//        $logoH = imagesy($logoImage);
//        $photoFrame = imagecreatetruecolor($fotoW, $fotoH);
//        $dest_x = $fotoW - $logoW;
//        $dest_y = $fotoH - $logoH;
//        imagecopyresampled($photoFrame, $photo, 0, 0, 0, 0, $fotoW, $fotoH, $fotoW, $fotoH);
//        imagecopy($photoFrame, $logoImage, $dest_x, $dest_y, 0, 0, $logoW, $logoH);
//        imagepng($photoFrame, $targetfile);
//       $qrUrl = $targetfile;

        return $this->render('TiprApplicationBundle:Recipient:index.html.twig', array(
            'recipient' => $recipient,
            'recentDonations' => $recentDonations,
            'donationsThisWeek' => $donationsWeek,
            'highestDonations' => $highestDonations,
            'totalToday' => $donationsThisDay,
            'totalWeek' => $donationsThisWeek,
            'totalMonth' => $donationsThisMonth,
            'qrUrl' => $qrUrl
        ));
    }

    public function settingsAction(Request $request)
    {
        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('apiId' => $request->getSession()->get('personId')));

        if (!$recipient) {

        }

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