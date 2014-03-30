<?php

namespace Tipr\ApplicationBundle\Controller;

use Httpful\Request as Api;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHttp\Client;

class BaseController extends Controller
{
    public $api_base_url = 'https://apisandbox.ingdirect.es';
    public $api_key = '?apikey=IEjdcD3VfCF63pRDE4DrviMi9zA30GI8';

    public function api_get($resource, $cookie)
    {
        $url = $this->api_base_url . $resource . $this->api_key;
        $client = new Client();
        $response = $client->get($url, [
            'headers' => ['Cookie' => $cookie]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function api_renew($cookie)
    {
        $url = $this->api_base_url . '/openapi/rest/session' . $this->api_key;
        $client = new Client();
        $response = $client->put($url, [
            'headers' => ['Cookie' => $cookie]
        ]);
    }

    public function check_login($session)
    {
        if ($session->get('personId') == null || $session->get('cookie') == null) {
           return false;
        } else {
            return true;
        }

    }

    public function make_transfer($product)
    {
        $url = $this->api_base_url . '/openapi/rest/transfers/' . '6ea7e24c-2993-34af-bc87-89bec6edfc36' . $this->api_key;
        $client = new Client();

        $body = array();

        $client->setDefaultOption('headers', array('Content-Length' => '0'));
        $response = $client->put($url, array(), '
        "from": {
            "productNumber": "ES84 1465 0100 96 2025957054”
        },
        "to": {
            "productNumber": "ES25 1465 0100 92 1708339378”,
            "titular": "PEPE PEREZ PEREZ"
        },
        "amount": 100
        "currency": "EURO",
        "operationDate": "29/03/2014",
        "concept": "tipr"
        }');

        var_dump($response);
    }

    public function logIn($documentNumber, $birthday)
    {
        $url = $this->api_base_url . "/openlogin/rest/ticket" . $this->api_key;

        $json['loginDocument'] = ['documentType' => 0, 'document' => $documentNumber];
        $json['birthday'] = $birthday;

        $response = Api::post($url, json_encode($json), 'application/json')->send();

        $ticket = $response->body->ticket;

        $client = new Client();

        $response = $client->post($this->api_base_url . '/openapi/login/auth/response' . $this->api_key, [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'body' => ['ticket' => $ticket]
        ]);

        // get cookie
        return $response->getHeader('set-cookie');
    }
}
