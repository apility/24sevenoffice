<?php

require_once(__DIR__.'/vendor/autoload.php');

use Apility\T4SevenOffice\T4SevenOffice;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


/* ----------------------------------------------------- */


T4SevenOffice::setCredentials(
  $_ENV['USERNAME'],
  $_ENV['PASSWORD'],
  $_ENV['APPLICATION_ID'],
  $_ENV['IDENTITY_ID']
);

// By default, this is set to false
T4SevenOffice::setUsePhpSession(true);

try {
  
  if (!$authService = T4SevenOffice::authenticateService()) {
    die('Could not authenticate with 24SevenOffice');
  }
  
  var_dump(T4SevenOffice::getSessionId());
  
  $identities = $authService->GetIdentities();
  
  var_dump($identities);
  
  /*
  $companyService = T4SevenOffice::companyService();
  
  $companies = $companyService->GetCompanies([
    'searchParams' => ['CompanyName' => 'Test'],
    'returnProperties' => ['Name', 'Id']
  ]);
  
  var_dump($companies);
  */
  
} catch (Exception $e) {
  die('24SevenOffice Exception: '.$e->getMessage());
}
