<?php

namespace Tipr\ApplicationBundle\Controller;

use Httpful\Request as Api;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHttp\Client;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $url = "https://apisandbox.ingdirect.es/openlogin/rest/ticket?apikey=IEjdcD3VfCF63pRDE4DrviMi9zA30GI8";

        $response = Api::post($url, '{
  "loginDocument": {
    "documentType": 0,
    "document": "49644566D"
  },
  "birthday": "01/01/1980"
}', 'application/json')->send();

        $ticket = $response->body->ticket;

        $client = new Client();

        $response = $client->post('https://apisandbox.ingdirect.es/openapi/login/auth/response?apikey=IEjdcD3VfCF63pRDE4DrviMi9zA30GI8', [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'body'    => ['ticket' => $ticket]
        ]);

        var_dump($response);

        return $this->render('TiprApplicationBundle:Default:index.html.twig', array('name' => 'Tipr'));
    }
}
