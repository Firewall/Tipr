<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tipr\ApplicationBundle\Form\Type\RecipientType;

class RecipientController extends BaseController
{
    public function indexAction(Request $request)
    {
        if(!$this->check_login($request->getSession())){
            return $this->redirect($this->generateUrl('tipr_application_logoutRecipientProcess'));
        }
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

        $url = "http://dev.tipr.be/m/donate/" . $recipient->getUsername();
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
        if(!$this->check_login($request->getSession())){
            return $this->redirect($this->generateUrl('tipr_application_logoutRecipientProcess'));
        }

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('api_id' => $request->getSession()->get('personId')));

        if(!$recipient){
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new RecipientType(),$recipient);

        return $this->render('TiprApplicationBundle:Recipient:settings.html.twig', array(
            'recipient' => $recipient,
            'form' => $form->createView()
        ));
    }

    public function settingsProcessAction(Request $request)
    {
        if(!$this->check_login($request->getSession())){
            return $this->redirect($this->generateUrl('tipr_application_logoutRecipientProcess'));
        }
        
        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('api_id' => $request->getSession()->get('personId')));

        if(!$recipient){
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new RecipientType(),$recipient);
        $form->handleRequest($request);

        if($form->isValid()){
            $data = $form->getData();

            $recipient->setUsername($data->getUsername());
            $recipient->setEmailaddress($data->getEmailaddress());
            $recipient->setPlace($data->getPlace());
            $recipient->setActivity($data->getActivity());
            $recipient->setAbout($data->getAbout());
            $recipient->setStandardamount($data->getStandardAmount());
            $recipient->setGoal($data->getGoal());
            $recipient->setFacebook($data->getFacebook());
            $recipient->setTwitter($data->getTwitter());
            $recipient->setYoutube($data->getYoutube());

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('TiprApplicationBundle:Recipient:settings.html.twig', array(
            'recipient' => $recipient,
            'form' => $form->createView()
        ));
    }
} 