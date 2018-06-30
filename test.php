<?php
require_once 'analytics_new.php';

// Use the developers console and download your service account
// credentials in JSON format. Place them in this directory or
// change the key file location if necessary.
$KEY_FILE_LOCATION = __DIR__ . '/ChevalGA-590c412b0562.json';

$analytics = new Analytics($KEY_FILE_LOCATION);

$response = $analytics->getReport('164789088'); //=> Set View ID
//print_r($response);

$analytics->printResults($response);