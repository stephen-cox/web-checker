<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Website;
use AppBundle\Form\WebsiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class WebsitesController extends Controller {
  
  /**
   * @Route("/websites", name="websites")
   */
  public function websitesAction() {
    
    // Get list of websites to check
    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository('AppBundle:Website');
    $websites = $repository->findAll();

        return $this->render('websites/list.page.twig', [
      'websites' => $websites,
    ]);
  }
  
  /**
   * @Route("/websites/new", name="new-website")
   */
  public function newWebsiteAction(Request $request) {

    $form = $this->createForm(WebsiteType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $website = $form->getData();
      $em = $this->getDoctrine()->getManager();
      $em->persist($website);
      $em->flush();
      return $this->redirectToRoute('websites');
    }

    return $this->render('websites/new.page.twig', array(
      'form' => $form->createView(),
    ));
  }
  
  /**
   * @Route("/websites/edit/{id}", name="edit-website", requirements={"page": "\d+"})
   */
  public function editWebsiteAction(Request $request, $id) {

    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository('AppBundle:Website');
    $website = $repository->findOneById($id);
    $form = $this->createForm(WebsiteType::class, $website);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      return $this->redirectToRoute('websites');
    }

    return $this->render('websites/edit.page.twig', array(
      'website' => $website,
      'form' => $form->createView(),
    ));
  }
  
  /**
   * @Route("/websites/delete/{id}", name="delete-website", requirements={"page": "\d+"})
   */
  public function deleteWebsiteAction(Request $request, $id) {

    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository('AppBundle:Website');
    $website = $repository->findOneById($id);

    $form = $this->createFormBuilder($website)
        ->add('id', HiddenType::class)
        ->add('Delete', SubmitType::class)
        ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->remove($website);
      $em->flush();
      return $this->redirectToRoute('websites');
    }

    return $this->render('websites/delete.page.twig', array(
      'website' => $website,
      'form' => $form->createView(),
    ));
  }
  
}