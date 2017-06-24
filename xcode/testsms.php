<?php


$user = 'kylewilson';
$password = 'kw121889';
$mysql = 'mysql:host=localhost;dbname=login;port=80';
$dbh = new PDO($mysql, $user, $password);







/* Send an SMS using Twilio. You can run this file 3 different ways:
 *
 * 1. Save it as sendnotifications.php and at the command line, run
 *         php sendnotifications.php
 *
 * 2. Upload it to a web host and load mywebhost.com/sendnotifications.php
 *    in a web browser.
 *
 * 3. Download a local server like WAMP, MAMP or XAMPP. Point the web root
 *    directory to the folder containing this file, and load
 *    localhost:8888/sendnotifications.php in a web browser.
 */

// Step 1: Get the Twilio-PHP library from twilio.com/docs/libraries/php,
// following the instructions to install it with Composer.
require_once "Twilio/autoload.php";
use Twilio\Rest\Client;

// Step 2: set our AccountSid and AuthToken from https://twilio.com/console
$AccountSid = "AC5348198fb10c079625c5c2b4096cbabd";
$AuthToken = "7a9e2491180a1a1d862ab6e7618f11dd";

// Step 3: instantiate a new Twilio Rest Client
$client = new Client($AccountSid, $AuthToken);















$user_code = "93174D";

$gps = "48.7519,-122.4787";

$coordinates = "http://www.google.com/maps/place/" . $gps;



















$stmt = $dbh->prepare("SELECT * FROM users WHERE user_code='$user_code'");

$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $ww){


    //  echo '<tr ><td>' . $ww['fullname1'] . '</td></tr>';


    $people1 = array(
        $ww['fullphone1'] => $ww['fullname1']

    );

    $people2 = array(
        $ww['fullphone2'] => $ww['fullname2']

    );

    $people3 = array(
        $ww['fullphone3'] => $ww['fullname3']

    );

    $metawear = $ww['clientname'];



}

































//  1
foreach ($people1 as $number => $name) {




    // this uses the API to send the sms
    $sms = $client->messages->create(

    // the number we are sending to - Any phone number
        $number,

        array(
            // Step 6: Change the 'From' number below to be a valid Twilio number
            // that you've purchased
            'from' => "+15416688086",

            // the sms body
            'body' => "THIS IS A TEST! Hey $name, $metawear's Sensor has been triggered. Their location is $coordinates. GPS coordinates are $gps. Please take appropriate actions. "
        )
    );

    // Display a confirmation message on the screen
    echo "Sent message to $name </br>";
}





//  2
foreach ($people2 as $number => $name) {



    // this uses the API to send the sms
    $sms = $client->messages->create(

    // the number we are sending to - Any phone number
        $number,

        array(
            // Step 6: Change the 'From' number below to be a valid Twilio number
            // that you've purchased
            'from' => "+15416688086",

            // the sms body
            'body' => "THIS IS A TEST! Hey $name, $metawear's Sensor has been triggered. Their location is $coordinates. GPS coordinates are $gps. Please take appropriate actions. "
        )
    );

    // Display a confirmation message on the screen
    echo "Sent message to $name </br>";
}




//  3
foreach ($people3 as $number => $name) {



    // this uses the API to send the sms
    $sms = $client->messages->create(

    // the number we are sending to - Any phone number
        $number,

        array(
            // Step 6: Change the 'From' number below to be a valid Twilio number
            // that you've purchased
            'from' => "+15416688086",

            // the sms body
            'body' => "THIS IS A TEST! Hey $name, $metawear's Sensor has been triggered. Their location is $coordinates. GPS coordinates are $gps. Please take appropriate actions. "
        )
    );

    // Display a confirmation message on the screen
    echo "Sent message to $name </br>";
}
