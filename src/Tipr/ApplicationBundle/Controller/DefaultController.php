<?php

namespace Tipr\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TiprApplicationBundle:Default:index.html.twig', array('name' => $name));
    }
}
