<?php
require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

session_start();

$client = new Google_Client();
$client->setClientId('353718323833-n0rb5ae3mcasvj2leuv2vslrpu0vdglg.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/TheProperty/assets/include/google_callback.php');
$client->addScope(['email', 'profile']);


$service = new Google_Service_Oauth2($client);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token);
        $googleUser = $service->userinfo->get();

        // Save user info into session 
        $_SESSION['user'] = [
            'id' => $googleUser->id,
            'email' => $googleUser->email,
            'name' => $googleUser->name
        ];
        header('Location: ../../Profile.php');
        exit;
    } else {
        echo "Error fetching token: " . htmlspecialchars($token['error']);
    }
} else {
    echo "No authorization code received from Google.";
}
?>
