<?php

namespace Apility\T4SevenOffice;

class T4SevenOffice {
  
  private static $apiUrl = 'https://api.24sevenoffice.com/';
  private static $webservicesUrl = 'https://webservices.24sevenoffice.com/';
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
    
    if ($value && session_status() == PHP_SESSION_NONE) {
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
    return $_SESSION['T4SevenOffice_sessionId'] ?? self::$sessionId;
  }
  
  
  /**
   * Logs in using the authenticateService, validates session and return authenticateService if valid login
   * @return \SoapClient
   * @throws \Exception
   */
  public static function authenticateService() {
    
    $auth = self::newSoapClient(self::$apiUrl.'authenticate/v001/authenticate.asmx?wsdl');
    
    if ($auth->HasSession()->HasSessionResult) {
      return $auth;
    }
    
    $login = $auth->Login([
      'credential' => [
        'Username' => self::$username,
        'Password' => self::$password,
        'ApplicationId' => self::$applicationId,
        'IdentityId' => self::$identityId ?? '00000000-0000-0000-0000-000000000000'
      ]
    ]);
    
    self::$sessionId = $login->LoginResult;
    
    if (self::$sessionId) {
      if (self::$usePhpSession) {
        $_SESSION['T4SevenOffice_sessionId'] = self::$sessionId;
      }
      
      $auth->__setCookie('ASP.NET_SessionId', self::$sessionId);
      if ($auth->HasSession()->HasSessionResult) {
        return $auth;
      }
    }
    
    throw new \Exception('Could not authenticate with 24SevenOffice');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function accountService() {
    return self::newSoapClient(self::$webservicesUrl.'economy/accountV002/Accountservice.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function attachmentService() {
    return self::newSoapClient(self::$webservicesUrl.'Economy/Accounting/Accounting_V001/AttachmentService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function budgetService() {
    return self::newSoapClient(self::$apiUrl.'Economy/Budget/V001/BudgetService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function clientService() {
    return self::newSoapClient(self::$apiUrl.'Client/V001/ClientService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function companyService() {
    return self::newSoapClient(self::$apiUrl.'CRM/Company/V001/CompanyService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function fileService() {
    return self::newSoapClient(self::$webservicesUrl.'file/V001/FileService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function fileInfoService() {
    return self::newSoapClient(self::$webservicesUrl.'file/V001/FileInfoService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function invitationService() {
    return self::newSoapClient(self::$webservicesUrl.'Invitation/Invitation_V001/InvitationService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function invoiceService() {
    return self::newSoapClient(self::$apiUrl.'Economy/InvoiceOrder/V001/InvoiceService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function paymentService() {
    return self::newSoapClient(self::$apiUrl.'Economy/InvoiceOrder/V001/PaymentService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function personService() {
    return self::newSoapClient(self::$webservicesUrl.'CRM/Contact/PersonService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function productService() {
    return self::newSoapClient(self::$apiUrl.'Logistics/Product/V001/ProductService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function projectService() {
    return self::newSoapClient(self::$webservicesUrl.'Project/V001/ProjectService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function salesOppService() {
    return self::newSoapClient(self::$webservicesUrl.'SalesOpp/V001/SalesOppService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function templateService() {
    return self::newSoapClient(self::$apiUrl.'CRM/Template/V001/TemplateService.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function timeService() {
    return self::newSoapClient(self::$webservicesUrl.'timesheet/v001/timeservice.asmx?wsdl');
  }
  
  
  /**
   * @return \SoapClient
   */
  public static function transactionService() {
    return self::newSoapClient(self::$apiUrl.'Economy/Accounting/V001/TransactionService.asmx?wsdl');
  }
  
  
  /**
   * Creates a new SoapClient and set ASP.NET_SessionId cookie if exist
   * @param string $url
   * @return \SoapClient
   */
  public static function newSoapClient($url) {
    
    $client = new \SoapClient($url, self::$options);
    
    if (!empty(self::$sessionId)) {
      $client->__setCookie('ASP.NET_SessionId', self::$sessionId);
      
    } elseif (!empty($_SESSION['T4SevenOffice_sessionId'])) {
      $client->__setCookie('ASP.NET_SessionId', $_SESSION['T4SevenOffice_sessionId']);
    }
    
    return $client;
  }
  
}
