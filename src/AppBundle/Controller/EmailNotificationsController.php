<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class EmailNotificationsController extends Controller {
  
  /**
   * @Route("/notifications/email", name="email")
   */
  public function emailNotificationsAction(Request $request) {
    
    return $this->render('default/index.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
    ]);
  }
  
  /**
   * @Route("/notifications/email/new", name="new-email")
   */
  public function newEmailNotificationsAction(Request $request) {
    
    return $this->render('default/index.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
    ]);
  }
  
  /**
   * @Route("/notifications/email/edit/{id}", name="edit-email", requirements={"page": "\d+"})
   */
  public function editEmailNotificationsAction(Request $request) {
    
    return $this->render('default/index.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
    ]);
  }
  
  /**
   * @Route("/notifications/email/delete/{id}", name="delete-email", requirements={"page": "\d+"})
   */
  public function deleteEmailNotificationsAction(Request $request) {
    
    return $this->render('default/index.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
    ]);
  }
  
}