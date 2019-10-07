<?php

namespace Apility\T4SevenOffice;

class T4SevenOffice {
  
  private static $username = '';
  private static $password = '';
  private static $applicationId = '';
  private static $identityId = '';
  
  
  /**
   * @param $username
   * @param $password
   * @param $applicationId
   */
  public static function setCredentials($username, $password, $applicationId) {
    self::$username = $username;
    self::$password = $password;
    self::$applicationId = $applicationId;
  }
  
  
  /**
   *
   */
  public function connect() {
    // TODO: Connect
  }
  
}
