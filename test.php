<?php
require_once 'analytics.php';

// Use the developers console and download your service account
// credentials in JSON format. Place them in this directory or
// change the key file location if necessary.

$KEY_FILE_LOCATION = __DIR__ . '/XXXX.json';
$analytics = new Analytics($KEY_FILE_LOCATION);

$response = $analytics->getReport('XXX'); //=> Set View ID
//print_r($response);

$analytics->printResults($response);