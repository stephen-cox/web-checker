<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="website")
 */
class Website {
  
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
   * @ORM\Column(type="string", length=250)
   */
  private $url;
  
  /**
   * @Assert\NotBlank()
   * @ORM\Column(type="text")
   */
  private $checkString;
  
  /**
   * @ORM\Column(type="smallint", nullable=TRUE)
   */
  private $status;

  /**
   * Set id
   *
   * @param integer $id
   * @return Website
   */
  public function setId($id) {

    $this->id = $id;
    return $this;
  }

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
   * @return Website
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
   * Set url
   *
   * @param string $url
   * @return Website
   */
  public function setUrl($url) {

    $this->url = $url;
    return $this;
  }
  
  /**
   * Get url
   *
   * @return string
   */
  public function getUrl() {

    return $this->url;
  }
  
  /**
   * Set checkString
   *
   * @param string $checkString
   * @return Website
   */
  public function setCheckString($checkString) {

    $this->checkString = $checkString;
    return $this;
  }
  
  /**
   * Get checkString
   *
   * @return string
   */
  public function getCheckString() {

    return $this->checkString;
  }
  
  /**
   * Set status
   *
   * @param integer $status

   * @return Website
   */
  public function setStatus($status) {

    $this->status = $status;
    return $this;
  }
  
  /**
   * Get status
   *
   * @return integer
   */
  public function getStatus() {

    return $this->status;
  }
}
