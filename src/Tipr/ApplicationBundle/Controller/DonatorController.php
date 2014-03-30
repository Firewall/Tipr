<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tipr\ApplicationBundle\Form\Type\DonateType;
use Symfony\Component\HttpFoundation\Request;
use Tipr\ApplicationBundle\Entity\Donation;

class DonatorController extends BaseController
{

    public function indexAction(Request $request)
    {
        if (!$this->check_login($request->getSession())) {
            return $this->redirect($this->generateUrl('tipr_application_logoutDonatorProcess'));
        }
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

        $totalDay = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getTotalThisDay($donator->getId());

        $totalWeek = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->getTotalThisWeek($donator->getId());


        $totalMonth = $this->getDoctrine()
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

    public function donateAction(Request $request, $username)
    {
        if (!$this->check_login($request->getSession())) {
            return $this->redirect($this->generateUrl('tipr_application_logoutDonatorProcess'));
        }

        $form = $this->createForm(new DonateType());

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('username' => $username));

        if (!$recipient) {

            return $this->render('TiprApplicationBundle:Donator:error.html.twig', array());
        }

        $totalToday = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getTotalThisDay($recipient);

        $totalDonated = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->getTotalThisMonth($recipient);

        $last7Days['Mar 28'] = 115.00;
        $last7Days['Mar 27'] = 95.00;
        $last7Days['Mar 26'] = 83.00;
        $last7Days['Mar 25'] = 75.00;
        $last7Days['Mar 24'] = 62.00;
        $last7Days['Mar 23'] = 58.00;
        $last7Days['Mar 22'] = 44.00;
        $last7Days['Mar 21'] = 34.00;

        return $this->render('TiprApplicationBundle:Donator:donate.html.twig', array(
            'recipient' => $recipient,
            'username' => $username,
            'totalToday' => $totalToday,
            'totalDonated' => $totalDonated,
            'last7Days' => $last7Days,
            'form' => $form->createView()
        ));
    }

    public function donateProcessAction(Request $request, $username)
    {
        if (!$this->check_login($request->getSession())) {
            return $this->redirect($this->generateUrl('tipr_application_logoutDonatorProcess'));
        }

        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('username' => $username));

        if (!$recipient) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new DonateType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // check data again
            $donator = $this->getDoctrine()
                ->getRepository('TiprApplicationBundle:Donator')
                ->findOneBy(array('username' => $data['username'], 'code' => $data['code']));

            if ($donator == null) {
                var_dump('null');
                throw new \Exception();
                $error = '';
            } else {
//                $cookie = $request->getSession()->get('cookie');
//
//                if($cookie){
//                    // todo: renew
//                    $this->api_renew($cookie);
//                }

                // log in if not logged in
                $cookie = $this->logIn($donator->getDocumentNumber(), $donator->getBirthDay());
                $request->getSession()->set('cookie', $cookie);
                $request->getSession()->set('personId', $donator->getApiId());

                //todo: get from donator
                $iban = 'ES25 1465 0100 92 1708339378';

                $products = $this->api_get('/openapi/rest/products', $cookie);

                $pProduct = null;
                foreach ($products as $product) {
                    if ($product['iban'] = $iban) {
                        $pProduct = $product['iban'];
                        break;
                    }
                }

                $donation = new Donation();
                $donation->setAmount($data['amount']);
                $donation->setRecipient($recipient);
                $donation->setDonator($donator);
                $donation->setDate(new \DateTime());

                $em = $this->getDoctrine()->getManager();
                $em->persist($donation);
                $em->flush();

                // make transfer
                //$this->make_transfer($pProduct);

                return $this->redirect($this->generateUrl('tipr_application_m_donator_thanks', array('username' => $username)));
            }
        }

        return $this->render('TiprApplicationBundle:Donator:donate.html.twig', array(
            'recipient' => $recipient,
            'username' => $username,
            'form' => $form->createView()
        ));
    }

    public function thanksAction(Request $request, $username)
    {
        $recipient = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Recipient')
            ->findOneBy(array('username' => $username));

        $donator = $this->getDoctrine()
            ->getRepository('TiprApplicationBundle:Donator')
            ->findOneBy(array('api_id' => $request->getSession()->get('personId')));

        if ($donator == null) {
            throw new \Exception();
        }

        $badge1 = false;
        $badge2 = false;
        $badge3 = false;

        if(sizeof($donator->getDonnations()) == 1){
            $badge1 = true;
        }elseif(sizeof($donator->getDonnations()) == 5){
            $badge2 = true;
        }elseif(sizeof($donator->getDonnations()) == 10){
            $badge3 = true;
        }

        $cookie = $this->logIn($donator->getDocumentNumber(), $donator->getBirthDay());
        $request->getSession()->set('cookie', $cookie);

        return $this->render('TiprApplicationBundle:Donator:thanks.html.twig', array(
            'recipient' => $recipient,
            'donator' => $donator,
            'badge1' => $badge1,
            'badge2' => $badge2,
            'badge3' => $badge3
        ));
    }
}