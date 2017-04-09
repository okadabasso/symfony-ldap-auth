<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GroupsController extends Controller {
	public function __construct(){
		
	}
    /**
     * @Route("/groups", name="groups")
     */
    public function indexAction(Request $request)
    {
    	$service = $this->get("app.group_register_service");
    	$options =$service->getOptions();  
		$groups = $service->ListGroups();
		 return $this->render('AppBundle:Groups:index.html.twig', array(
		 		"options" => $options,
		 		"groups" => $groups
		 ));
    }
}