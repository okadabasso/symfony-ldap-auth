<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Forms\GroupEditForm;

class GroupsController extends Controller {
    public function __construct(){
    }
    /**
     * @Route("/groups", name="groups")
     */
    public function indexAction(Request $request)
    {
        $service = $this->get("app.group_register_service");
        $groups = $service->ListGroups();
         return $this->render('AppBundle:Groups:index.html.twig', array(
             "groups" => $groups
         ));
    }
    
    /**
     * @Route("/groups/edit/{cn}", name="groups_edit")
     * @Method({"GET"})
     */
    public function editAction(Request $request)
    {
        $cn = $request->get("cn");
        $form = $this->createForm(GroupEditForm::class);
        
        $service = $this->get("app.group_register_service");
        $entry = $service->getGroup($cn);
        if($entry){
            $form->setData([
                "cn" => $entry->getAttribute("cn")[0],
                "ou" => $entry->getAttribute("ou")[0]
            ]);
        }
        return $this->render('AppBundle:Groups:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/groups/update", name="groups_update")
     * @Method({"POST"})
     */
    public function updateAction(Request $request)
    {
        $form = $this->createForm(GroupEditForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $service = $this->get("app.group_register_service");
            $service->update($data);
        }
        return $this->render('AppBundle:Groups:edit.html.twig', array(
            'message' => "update complete",
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/groups/edit-success", name="groups_edit-success")
     */
    public function editSuccessAction(Request $request)
    {
    	$service = $this->get("app.group_register_service");
    	var_dump($request); 
    	return $this->render('AppBundle:Groups:edit_success.html.twig', array(
    			'message' => "save complete",
    	));
    }
    
}