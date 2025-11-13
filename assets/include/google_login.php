<?php
require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

session_start();

$client = new Google_Client();
$client->setClientId('353718323833-n0rb5ae3mcasvj2leuv2vslrpu0vdglg.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/TheProperty/assets/include/google_callback.php');
$client->addScope(['email','profile']);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

header('Location: ' . $client->createAuthUrl());
exit;
?>