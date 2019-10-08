# 24sevenoffice
A simple and independent webservice connector for the ERP system [24SevenOffice](https://24sevenoffice.com).

## Full documentation
Se the full documentation for all services at [developer.24sevenoffice.com](https://developer.24sevenoffice.com/docs/).

## Installation
```bash
composer require apility/24sevenoffice
```

## Basic usage
Authenticating and getting a list of companies matching a search:
```php
<?php

use Apility\T4SevenOffice\T4SevenOffice;

T4SevenOffice::setCredentials(
  'user@company.com', // username
  'yourpassword123', // password
  '00000000-0000-0000-0000-000000000000', // applicationId
  '00000000-0000-0000-0000-000000000000' // identityId (optional)
);

// By default, this is set to false, since you most likely would 
// like to save the sessionId from 24SevenOffice some other place
T4SevenOffice::setUsePhpSession(true);

try {
  
  T4SevenOffice::authenticateService();
  
  $companyService = T4SevenOffice::companyService();
  
  $companies = $companyService->GetCompanies([
    'searchParams' => ['CompanyName' => 'Test'],
    'returnProperties' => ['Name', 'Id']
  ]);
  
  var_dump($companies);
  
} catch (Exception $e) {
  die('Exception: '.$e->getMessage());
}
```

## Preserve session
To avoid authenticating with the webservice every time (and improve performance), you can save the sessionId from 24SevenOffice and use this as long as it is valid.

You can either use PHP Session for this:
```php
<?php

// By default, this is set to false
T4SevenOffice::setUsePhpSession(true);
```

Or handle the sessionId on your own:
```php
<?php

// Saved from last auth
$sessionId = 'demosw1avyg2h4opyi0demo';

T4SevenOffice::setSessionId($sessionId);

T4SevenOffice::authenticateService();

if ($sessionId !== T4SevenOffice::getSessionId) {
  // Session from last time is no longer valid, and a new one has been created
  // TODO: Save the new sessionId for next time
}
```

## Listing all identities
```php
<?php

T4SevenOffice::setCredentials(
  'user@company.com', // username
  'yourpassword123', // password
  '00000000-0000-0000-0000-000000000000' // applicationId
);

$authService = T4SevenOffice::authenticateService();
  
$identities = $authService->GetIdentities();
  
var_dump($identities);  
  
```
