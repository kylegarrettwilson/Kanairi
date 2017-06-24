<?php


$user = 'kylewilson';
$password = 'kw121889';
$mysql = 'mysql:host=localhost;dbname=login;port=80';
$dbh = new PDO($mysql, $user, $password);



$name = $_SESSION['user_name'];



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








if (isset($_POST['data']))
    $x = $_POST['data'];
else
    echo "no post data here";







$stmt = $dbh->prepare("SELECT user_code FROM users WHERE user_name = '$name'");

$stmt->execute();

$user_code = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach($user_code as $rw){

   // echo '<tr ><td>' . $rw['user_code'] . '</td></tr>';





    $now = $rw['user_code'];

    $stmt = $dbh->prepare("SELECT * FROM users WHERE user_code='$now'");

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



}







$coordinates = preg_replace("/[^0-9,-.]/", "", $x);

$google = "http://www.google.com/maps/place/" . $coordinates;




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
            'body' => "Hey $name, $metawear's Kanairi sensor has been triggered. Their GPS coordinates are $coordinates. Click this link to see where $metawear's Kanairi is on a map, $google. Please check in with them to see if they need assistance. If you feel that emergency services may be needed, use the map provided to find the nearest police department to their location."
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
            'body' => "Hey $name, $metawear's Sensor has been triggered. Their GPS coordinates are $coordinates. Click this link to see where $metawear's Kanairi is on a map, $google. Please check in with them to see if they need assistance. If you feel that emergency services may be needed, use the map provided to find the nearest police department to their location."
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
            'body' => "Hey $name, $metawear's Sensor has been triggered. Their GPS coordinates are $coordinates. Click this link to see where $metawear's Kanairi is on a map, $google. Please check in with them to see if they need assistance. If you feel that emergency services may be needed, use the map provided to find the nearest police department to their location."
        )
    );

    // Display a confirmation message on the screen
    echo "Sent message to $name </br></br> <a href='index.php'>Back To Dashboard</a> ";
}
