<?php

namespace Tipr\ApplicationBundle\Controller;

use Httpful\Request as Api;
use GuzzleHttp\Client;

class DefaultController extends BaseController
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

        $cookie = $response->getHeader('set-cookie');

        var_dump($cookie);

        $response = $client->get('https://apisandbox.ingdirect.es/openapi/rest/client?apikey=IEjdcD3VfCF63pRDE4DrviMi9zA30GI8', [
            'headers' => ['Cookie' => $cookie]
        ]);

        $json = json_decode($response->getBody());

        var_dump($json);

        return $this->render('TiprApplicationBundle:Default:index.html.twig', array('name' => 'Tipr'));
    }
}
