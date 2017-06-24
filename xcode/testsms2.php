<?php



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



    $coordinates = $x;



    //$coordinates = "http://www.google.com/maps/place/" . $gps;


        $people = array(
            "+15417887601" => "Curious George",

        );



    // Step 5: Loop over all our friends. $number is a phone number above, and
    // $name is the name next to it
    foreach ($people as $number => $name) {

        $sms = $client->account->messages->create(

        // the number we are sending to - Any phone number
            $number,

            array(
                // Step 6: Change the 'From' number below to be a valid Twilio number
                // that you've purchased
                'from' => "+15416688086",

                // the sms body
                'body' => "Hey $name, Monkey Party at 6PM. GPS coordinates are $coordinates. Please take appropriate actions."
            )
        );

        // Display a confirmation message on the screen
        echo "Sent message to $name";


}

