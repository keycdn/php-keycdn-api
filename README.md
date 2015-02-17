KeyCDN REST API Library
=======================

PHP Library for the KeyCDN API

[KeyCDN](https://www.keycdn.com) is a Content Delivery Network to accelerate your web assets.

Please contact us if you got any questions or if you need more functionality: [KeyCDN Support](https://www.keycdn.com/contacts)

## Requirements
- PHP 5.3 or above
- PHP Curl Extension

## Usage
```php
<?php

require 'path_to_repo/src/KeyCDN.php';

// create the REST object
$keycdn_api = new KeyCDN('your_api_key');

// get zone information
$keycdn_api->get('zones.json');


// change zone name and check if successfull
$result = $keycdn_api->post('zones/123.json', array(
    'name' => 'newzonename',
));


// convert json-answer into an array
$answer = json_decode($result, true);

if ($answer['status'] == 'success') {
    echo 'Zonename successfully changed...';
}


...

?>
```

## Methods

Each of the supported HTTP methods (GET, PUT, POST, DELETE) is produced by an own function in the KeyCDN lib. E.g. POST becomes ```$keycdn_api->post(...);```.
