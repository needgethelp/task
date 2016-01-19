<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Context;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/init", name="init")
     */
    public function initAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $context = new Context();

        $user = new User();
        $user->setUsername("admin");
        $user->setPassword("admin");
        $user->setActiveContext($context);
        
        $context->setTitle("Global");
        $context->addManager($user);
        $context->setCreatedBy($user);
        
        $folder = new Folder();
        $folder->setPrivate($user);
        
        $entityManager->persist($folder);
        $entityManager->persist($user);
        $entityManager->persist($context);
        
        $entityManager->flush();
        
        
        // replace this example code with whatever you need
        return new Response('Init Performed!');
    }
}
