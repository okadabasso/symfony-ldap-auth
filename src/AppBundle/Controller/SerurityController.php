<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 *
 * @author mic
 *        
 */
class SerurityController extends Controller {
	
	public function __construct(){
		
	}
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
		 $authenticationUtils = $this->get('security.authentication_utils');

		 $error = $authenticationUtils->getLastAuthenticationError();
		 $lastUsername = $authenticationUtils->getLastUsername();
		
		 return $this->render('AppBundle:Security:login.html.twig', array(
		        'last_username' => $lastUsername,
		        'error'         => $error,
		 ));
    }
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction(Request $request)
    {
		 $authenticationUtils = $this->get('security.authentication_utils');

		 $error = $authenticationUtils->getLastAuthenticationError();
		 $lastUsername = $authenticationUtils->getLastUsername();
		
		 return $this->render('AppBundle:Security:login.html.twig', array(
		        'last_username' => $lastUsername,
		        'error'         => $error,
		 ));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
    }
}