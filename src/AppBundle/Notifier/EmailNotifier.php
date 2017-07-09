<?php

namespace AppBundle\Notifier;

use AppBundle\Event\CheckEvent;
use AppBundle\Checker\CheckerInterface;
use AppBundle\Entity\Website;
use Symfony\Component\DependencyInjection\ContainerInterface;


class EmailNotifier {
  
  /**
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  private $container;
  
  /**
   * EmailNotifier constructor
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   */
  public function __construct(ContainerInterface $container) {
    
    $this->container = $container;
  }
  
  /**
   * Send notifications for website check
   *
   * @param \AppBundle\Entity\Website $website
   * @param \AppBundle\Checker\CheckerInterface $checker
   */
  public function notify(Website $website, CheckerInterface $checker) {
  
    // Only send notifications for errors
    if ($checker->getError()) {
      
      // Get list of email addresses to notify
      $doctrine = $this->container->get('doctrine');
      $em = $doctrine->getEntityManager();
      $repository = $em->getRepository('AppBundle:EmailNotification');
      $notifiers = $repository->findAll();
      
      // Send email alert
      $recipients = [];
      foreach ($notifiers as $notify) {
        $recipients[$notify->getEmailAddress()] = $notify->getDisplayName();
      }
      $subject = 'Website Alert - ' . $website->getDisplayName() . ' - ' . $checker->getErrorMessage();
      $body = "Error fetching ". $website->getUrl() . "
" . $checker->getErrorMessage() ."

" . var_export($checker->getInfo(), TRUE);
      if ($recipients) {
        $this->sendEmail($recipients, $subject, $body, $checker->getResponse());
      }
    }
  }
  
  /**
   * Send an email alert
   *
   * @param array $recipients
   * @param string $subject
   * @param string $body
   * @param string $attachment
   */
  protected function sendEmail($recipients, $subject, $body, $attachment = NULL) {
    
    $message = (new \Swift_Message($subject))
      ->setFrom(['servers@agile.coop' => 'Server Alerts'])
      ->setTo($recipients)
      ->setContentType('text/plain')
      ->setBody($body);
    
    if ($attachment) {
      $file = (new \Swift_Attachment())
        ->setFilename('response.txt')
        ->setContentType('text/plain')
        ->setBody($attachment);
      $message->attach($file);
    }
    
    $this->container->get('mailer')->send($message);
  }
  
  /**
   * Respond to the website.check event
   *
   * @param \AppBundle\Event\CheckEvent $event
   */
  public function onWebsiteCheck(CheckEvent $event) {
    
    $this->notify($event->getWebsite(), $event->getChecker());
  }
  
  /**
   * Get container
   */
  public function getContainer() {
    
    return $this->container;
  }
  
  /**
   * Set container
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   */
  public function setContainer($container) {
    
    $this->container = $container;
  }
  
}
