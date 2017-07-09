<?php

namespace AppBundle\Event;

use AppBundle\Checker\CheckerInterface;
use AppBundle\Entity\Website;
use Symfony\Component\EventDispatcher\Event;


class CheckEvent extends Event {
  
  /**
   * Event name
   */
  const NAME = 'website.check';
  
  /**
   * @var \AppBundle\Entity\Website
   */
  protected $website;
  
  /**
   * @var \AppBundle\Checker\CheckerInterface
   */
  protected $checker;
  
  /**
   * CheckEvent constructor
   *
   * @param \AppBundle\Entity\Website $website
   * @param \AppBundle\Checker\CheckerInterface $checker
   */
  public function __construct(Website $website, CheckerInterface $checker) {
  
    $this->website = $website;
    $this->checker = $checker;
  }
  
  /**
   * Get website
   *
   * @return \AppBundle\Entity\Website
   */
  public function getWebsite() {
    
    return $this->website;
  }
  
  /**
   * Get checker
   *
   * @return \AppBundle\Checker\CheckerInterface
   */
  public function getChecker() {
    
    return $this->checker;
  }
}