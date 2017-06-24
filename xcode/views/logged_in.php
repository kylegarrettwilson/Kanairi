<?php


error_reporting(0);



// establishing a pdo connection to database

$user = 'kylewilson';
$password = 'kw121889';
$mysql = 'mysql:host=localhost;dbname=login;port=80';
$dbh = new PDO($mysql, $user, $password);



// first get the client by id with this variable to use later

$name = $_SESSION['user_name'];




$stmt = $dbh->prepare("SELECT user_code FROM users WHERE user_name = '$name'");

$stmt->execute();

$user_code = $stmt->fetchAll(PDO::FETCH_ASSOC);







// select all from database where the id equals the id that we are targeting (using the $solarid variable above)


$stmt = $dbh->prepare("SELECT * FROM users WHERE user_name = :user_name");
$stmt->bindParam(':user_name', $name);
$stmt->execute();
$result = $stmt->fetchAll();


// this is where the magic happens, there is a isset to make sure the fields are entered correctly and then
// using post and update to target the invenstore table and then setting the new post information to change the
// respected field on the database table, using $solarid to make sure we only target THAT ONE CLIENT, NOT ALL OF THEM

if (isset($_POST['submit'])){

    $clientname = $_POST['clientname'];
    $fullname1 = $_POST['fullname1'];
    $fullphone1 = $_POST['fullphone1'];
    $fullname2 = $_POST['fullname2'];
    $fullphone2 = $_POST['fullphone2'];
    $fullname3 = $_POST['fullname3'];
    $fullphone3 = $_POST['fullphone3'];



    $stmt = $dbh->prepare("UPDATE users SET clientname='" . $clientname . "', fullname1='" . $fullname1 . "', fullphone1='" . $fullphone1 . "', fullname2='" . $fullname2 . "', fullphone2='" . $fullphone2 . "', fullname3='" . $fullname3 . "', fullphone3='" . $fullphone3 . "' WHERE user_name = '$name'");
    $stmt->execute();







    // send us back to the main app window

    header('Location: update.php');
    die();

}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lander Mobile App Onepage Template</title>
    <meta name="author" content="">
    <meta name="description" content="">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<meta http-equiv="refresh" content="1200" > -->


    <!-- Site favicon -->
    <link rel="shortcut icon" sizes="16x16" href="../images/favicon.png" />

    <!-- Css files -->
    <link rel="stylesheet" type="text/css"  href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome.css">
    <link href="../css/owl.carousel.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css"  href="../css/style-red.css">
    <link rel="stylesheet" type="text/css"  href="../css/responsive-style.css">
    <link rel="stylesheet" type="text/css"  href="../css/animate.css">
    <link rel="stylesheet" type="text/css" href="../css/portfolio-style.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <!-- end Css files -->


    <!-- Google fonts -->
    <link href='https://fonts.googleapis.com/css?family=Dosis:400,300,500,700,800,600' rel='stylesheet' type='text/css'>
</head>
<body>


















<div class="wrapper" id="wrapper">


    <form class="form-signin" action="" id="add" method="POST">

        <img src="../images/logo-mini.png" class="center-block" style="width: 100px;" >


        <h2 style="text-align: center">Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>


        <input style="-webkit-appearance: none;" type="button" class="btn btn-info center-block" value="Logout" onclick="location.href = '../index.php?logout';">


        <h3 style="text-align: center; padding-bottom: 15px; padding-top: 60px;">Register Sensor</h3>



        <h4>Sensor Owner: </h4><input class="form-control" type="text" name="clientname" value=<?php echo '"'.$result[0]['clientname'].'"';?>/><br>



        <h4>Emergency Contact: </h4><input class="form-control" type="text" name="fullname1" value=<?php echo '"'.$result[0]['fullname1'].'"';?>/>
        <h4>Phone Number: </h4><input class="form-control" id="phone" type="text" name="fullphone1" value=<?php echo '"'.$result[0]['fullphone1'].'"';?>/><br>




        <h4>Emergency Contact: </h4><input class="form-control" type="text" name="fullname2" value=<?php echo '"'.$result[0]['fullname2'].'"';?>/>
        <h4>Phone Number: </h4><input class="form-control" id="phone2" type="text" name="fullphone2" value=<?php echo '"'.$result[0]['fullphone2'].'"';?>/><br>



        <h4>Emergency Contact: </h4><input class="form-control" type="text" name="fullname3" value=<?php echo '"'.$result[0]['fullname3'].'"';?>/>
        <h4>Phone Number: </h4><input class="form-control" id="phone3" type="text" name="fullphone3" value=<?php echo '"'.$result[0]['fullphone3'].'"';?>/><br>



        <h4>App Credential Number: </h4><h4 style="color: red;">

            <?php


                foreach($user_code as $rw){

                    echo '<tr ><td>' . $rw['user_code'] . '</td></tr>';
                }


            ?>

        </h4><br>



        <input style="-webkit-appearance: none;" class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Save">


    </form>
</div>


































































































<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery.maskedinput.js" type="text/javascript"></script>



<script>
    jQuery(function($){
        $("#date").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
        $("#phone").mask("+1 (999) 999-9999");
        $("#phone2").mask("+1 (999) 999-9999");
        $("#phone3").mask("+1 (999) 999-9999");
        $("#tin").mask("99-9999999");
        $("#ssn").mask("999-99-9999");
    });
</script>









</body>
</html>










