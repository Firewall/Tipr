<?php

namespace Tipr\ApplicationBundle\Controller;

use Httpful\Request as Api;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHttp\Client;

class BaseController extends Controller
{
    public  $api_base_url = 'https://apisandbox.ingdirect.es';
    public  $api_key = '?apikey=IEjdcD3VfCF63pRDE4DrviMi9zA30GI8';

    public function api_get($resource, $cookie)
    {
        $url = $this->api_base_url . $resource . $this->api_key;
        $client = new Client();
        $response = $client->get($url, [
            'headers' => ['Cookie' => $cookie]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function check_login($session){
        if (!$session->get('cookie')) {
            //redirect to login
            return false;
        }
        return true;
    }

    public function logIn($documentNumber,$birthday){
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
