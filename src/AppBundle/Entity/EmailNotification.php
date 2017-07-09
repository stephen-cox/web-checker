<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="email_notifications")
 */
class EmailNotification {
  
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  
  /**
   * @Assert\NotBlank()
   * @ORM\Column(type="string", length=100)
   */
  private $displayName;
  
  /**
   * @Assert\NotBlank()
   * @ORM\Column(type="string", length=100)
   */
  private $emailAddress;
  
  
  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    
    return $this->id;
  }
  
  /**
   * Set displayName
   *
   * @param string $displayName
   * @return EmailNotification
   */
  public function setDisplayName($displayName) {
    
    $this->displayName = $displayName;
    return $this;
  }
  
  /**
   * Get displayName
   *
   * @return string
   */
  public function getDisplayName() {
    
    return $this->displayName;
  }
  
  /**
   * Set emailAddress
   *
   * @param string $emailAddress
   * @return EmailNotification
   */
  public function setEmailAddress($emailAddress) {
    
    $this->emailAddress = $emailAddress;
    
    return $this;
  }
  
  /**
   * Get emailAddress
   * @return string
   */
  public function getEmailAddress() {
    
    return $this->emailAddress;
  }
}
