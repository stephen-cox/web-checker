<?php

namespace AppBundle\Checker;


interface CheckerInterface {
  
  
  /**
   * Check website, returns error code
   *
   * @return int
   */
  public function check();
  
  /**
   * Get error message. Returns a human readable string for the error code.
   */
  public function getErrorMessage();
  
  /**
   * Get URL
   *
   * @return string
   */
  public function getUrl();
  
  /**
   * Set URL
   *
   * @param string $url
   */
  public function setUrl($url);
  
  /**
   * Get Check String
   *
   * @return string
   */
  public function getCheckString();
  
  /**
   * Set Check String
   *
   * @param string $string
   */
  public function setCheckString($string);
  
  /**
   * Get Response
   *
   * @return null|string
   */
  public function getResponse();
  
  /**
   * Get Error
   *
   * @return null|int
   */
  public function getError();
  
  /**
   * Get Check Found
   *
   * @return null|bool
   */
  public function getCheckFound();
  
}