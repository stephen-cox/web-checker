<?php

namespace AppBundle\Checker;


class CurlChecker implements CheckerInterface {
  
  /**
   * URL to check
   *
   * @var string
   */
  private $url;
  
  /**
   * String to check is in request
   *
   * @var string
   */
  private $checkString;
  
  /**
   * Response from requesting URL
   *
   * @var null|string
   */
  private $response = NULL;
  
  /**
   * Info array from request
   *
   * @var null|array
   */
  private $info = NULL;
  
  /**
   * Error code returned by curl plus -1, check string not found
   *
   * @var null|int
   */
  private $error = NULL;
  
  /**
   * Check string found
   *
   * @var NULL|bool
   */
  private $checkFound = NULL;
  
  /**
   * CheckWebsite constructor
   *
   * @param string $url
   */
  function __construct($url, $checkString) {
    
    $this->url = $url;
    $this->checkString = $checkString;
  }
  
  /**
   * Check website, returns error code
   *
   * @return int
   */
  public function check() {
  
    // Initialise curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_USERAGENT, "Agile Collective time reporting");
  
    // Get URL and info
    $this->response = curl_exec($curl);
    $this->info = curl_getinfo($curl);
    $this->error = curl_errno($curl);
    curl_close($curl);
  
    // If error, return curl error code
    if ($this->error) {
      return $this->error;
    }
  
    // Check for check string
    if (strpos($this->response, $this->checkString) !== FALSE) {
      $this->checkFound = TRUE;
      $this->error = 0;
    }
    else {
      $this->checkFound = FALSE;
      $this->error = -1;
    }
    
    return $this->error;
  }
  
  /**
   * Get error message. Returns a human readable string for the error code.
   */
  public function getErrorMessage() {
  
    if (is_null($this->error)) {
      return 'Checker hasn\'t been run yet';
    }
    elseif ($this->error == 0) {
      return 'No errors';
    }
    elseif ( ($this->error == -1)) {
      return 'Check string not found';
    }
    else {
      return curl_strerror($this->error);
    }
  }
  
  /**
   * Get URL
   *
   * @return string
   */
  public function getUrl() {
    
    return $this->url;
  }
  
  /**
   * Set URL
   *
   * @param string $url
   */
  public function setUrl($url) {
    
    $this->url = $url;
  }
  
  /**
   * Get Check String
   *
   * @return string
   */
  public function getCheckString() {
    
    return $this->checkString;
  }
  
  /**
   * Set Check String
   *
   * @param string $string
   */
  public function setCheckString($string) {
    
    $this->checkString = $string;
  }
  
  /**
   * Get Response
   *
   * @return null|string
   */
  public function getResponse() {
    
    return $this->response;
  }
  
  /**
   * Get Info
   *
   * @return null|array
   */
  public function getInfo() {
    
    return $this->info;
  }
  
  /**
   * Get Error
   *
   * @return null|int
   */
  public function getError() {
    
    return $this->error;
  }
  
  /**
   * Get Check Found
   *
   * @return null|bool
   */
  public function getCheckFound() {
    
    return $this->checkFound;
  }
}