<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Website;
use AppBundle\Form\WebsiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {
  
  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request) {
    // replace this example code with whatever you need
    return $this->render('default/index.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
    ]);
  }
  
  /**
   * @Route("/edit", name="edit")
   */
  public function editAction(Request $request) {
  
    //$website = new Website();
    $form = $this->createForm(WebsiteType::class);
    $form->handleRequest($request);
  
    if ($form->isSubmitted() && $form->isValid()) {
      $website = $form->getData();
      $em = $this->getDoctrine()->getManager();
      $em->persist($website);
      $em->flush();
    }
    
    return $this->render('default/new_website.html.twig', array(
      'form' => $form->createView(),
    ));
  }
  
  
}
