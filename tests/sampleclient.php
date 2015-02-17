<?php

// load lib
require 'src/KeyCDN.php';

// create object and pass login credentials
$keycdn = new KeyCDN('your_api_key');

// GET object zones
$result = $keycdn->get('zones.json');

// create zone
$result = $keycdn->post('zones.json', array(
    'name' => 'testzone',
));

// edit zone (the 1 specifies the zoneid, to be edited)
$result = $keycdn->put('zones/1.json', array(
    'name' => 'newzonename',
));

// delete zone (the 1 specifies the zoneid, to be deleted)
$result = $keycdn->delete('zones/1.json');

?>
