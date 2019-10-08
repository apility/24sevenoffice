<?php

namespace Apility\T4SevenOffice;

class T4SevenOffice {
  
  private static $baseUrl = 'https://api.24sevenoffice.com/';
  private static $options = ['trace' => true];
  private static $usePhpSession = false;
  
  private static $username;
  private static $password;
  private static $applicationId;
  private static $identityId;
  
  private static $sessionId;
  
  
  /**
   * @param string $username
   * @param string $password
   * @param string $applicationId
   * @param string $identityId (optional)
   */
  public static function setCredentials($username, $password, $applicationId, $identityId = null) {
    
    self::$username = $username;
    self::$password = $password;
    self::$applicationId = $applicationId;
    self::$identityId = $identityId;
  }
  
  
  /**
   * @param bool $value
   */
  public static function setUsePhpSession($value) {
    
    self::$usePhpSession = $value;
    
    if ($value) {
      session_start();
    }
  }
  
  
  /**
   * @param string $sessionId
   */
  public static function setSessionId($sessionId) {
    self::$sessionId = $sessionId;
  }
  
  
  /**
   * @return string $sessionId
   */
  public static function getSessionId() {
    return self::$sessionId;
  }
  
  
  /**
   * Logs in using the authenticateService, validates session and return authenticateService if valid login
   * @return \SoapClient
   */
  public static function authenticateService() {
    
    $auth = self::newSoapClient('authenticate/v001/authenticate.asmx?wsdl');
    
    if ($auth->HasSession()->HasSessionResult) {
      return $auth;
    }
    
    $login = $auth->Login([
      'credential' => [
        'Username' => self::$username,
        'Password' => md5(mb_convert_encoding(self::$password, 'utf-16le', 'utf-8')),
        'ApplicationId' => self::$applicationId,
        'IdentityId' => self::$identityId ?? '00000000-0000-0000-0000-000000000000'
      ]
    ]);
    
    self::$sessionId = $login->LoginResult;
    
    if (self::$usePhpSession) {
      $_SESSION['T4SevenOffice_sessionId'] = self::$sessionId;
    }
    
    $auth->__setCookie('ASP.NET_SessionId', self::$sessionId);
    if ($auth->HasSession()->HasSessionResult) {
      return $auth;
    }
    
    return false;
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function productService() {
    return self::newSoapClient('Logistics/Product/V001/ProductService.asmx?wsdl');
  }
  
  
  /**
   * Creates a new SoapClient and set ASP.NET_SessionId cookie if exist
   * @param string $url
   * @return \SoapClient
   */
  public static function newSoapClient($url) {
    
    $client = new \SoapClient(self::$baseUrl.$url, self::$options);
    
    if (!empty(self::$sessionId)) {
      $client->__setCookie('ASP.NET_SessionId', self::$sessionId);
      
    } elseif (!empty($_SESSION['T4SevenOffice_sessionId'])) {
      $client->__setCookie('ASP.NET_SessionId', $_SESSION['T4SevenOffice_sessionId']);
    }
    
    return $client;
  }
  
}
