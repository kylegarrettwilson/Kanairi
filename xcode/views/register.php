
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lander Mobile App Onepage Template</title>
    <meta name="author" content="">
    <meta name="description" content="">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


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
    <form class="form-signin" method="post" action="register.php" name="registerform">



        <?php
        // show potential errors / feedback (from registration object)
        if (isset($registration)) {
            if ($registration->errors) {
                foreach ($registration->errors as $error) {
                    echo $error;
                }
            }
            if ($registration->messages) {
                foreach ($registration->messages as $message) {
                    echo $message;
                }
            }
        }

        ?>

        <img src="../images/logo-mini.png" class="center-block" style="width: 100px;" >


        <h2 class="form-signin-heading" style="text-align: center">Create Account</h2>


        <input class="form-control login_input" style="margin-bottom: 10px;" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Username" required />

        <input class="form-control login_input" style="margin-bottom: 10px;" type="email" name="user_email" placeholder="Email" required />

        <input class="form-control login_input" style="margin-bottom: 10px;" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" placeholder="Password"/>

        <input class="form-control login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" placeholder="Repeat Password"/>

        <input class="form-control login_input" style="display: none;" type="text" name="user_code" placeholder="Username" value="<?php $random = substr(md5(microtime()), rand(0,26), 6); echo $random; ?>"/>

        <input type="checkbox" name="checkbox" value="Yes" title="" required /> I have read and agree to the <a style="color: blue" href="../terms.php">Terms of Use and Privacy Policy</a><br>
        
        <input class="btn btn-lg btn-primary btn-block" style="-webkit-appearance: none; margin-top: 25px;" type="submit"  name="register" value="Register" onclick="if(!this.form.checkbox.checked){alert('You must agree to the terms first.');return false}" /><br>

        <input type="button" style="-webkit-appearance: none;" class="btn btn-info" value="Return to login" onclick="location.href = 'index.php';">


    </form>
</div>





</body>
</html>





