<?php
/**
 * Created by PhpStorm.
 * User: antho_000
 * Date: 29/03/14
 * Time: 12:52
 */

namespace Tipr\ApplicationBundle\Controller;


use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tipr\ApplicationBundle\Form\Type\LogInType;
use Httpful\Request as Api;

class AuthenticationController extends BaseController
{
    public function logInAction()
    {
        $form = $this->createForm(new LogInType());

        return $this->render('TiprApplicationBundle:Authentication:login.html.twig',array(
            'form' => $form->createView()
        ));
    }

    public function logInProcessAction(Request $request)
    {
        $error = false;

        $form = $this->createForm(new LogInType());

        $form->handleRequest($request);

        if($form->isValid()){
            $data = $form->getData();

            $url = $this->api_base_url . "/openlogin/rest/ticket" . $this->api_key;

            $json['loginDocument'] = ['documentType' => 0, 'document' => $data['documentNumber']];
            $json['birthday'] =  $data['birthDate'];

            $response = Api::post($url,json_encode($json), 'application/json')->send();

            $ticket = $response->body->ticket;

            $client = new Client();

            $response = $client->post($this->api_base_url . '/openapi/login/auth/response' . $this->api_key, [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'body'    => ['ticket' => $ticket]
            ]);

            // get cookie
            $cookie = $response->getHeader('set-cookie');

            $client = $this->api_get('/openapi/rest/client',$cookie);

            if($cookie && $client->personId){
                $request->getSession()->set('cookie',$cookie);
                $request->getSession()->set('personId',$client->personId);
            }else{
                $error = 'Incorrect document number or birthday';
            }
        }

        return $this->render('TiprApplicationBundle:Authentication:login.html.twig',array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }
} 