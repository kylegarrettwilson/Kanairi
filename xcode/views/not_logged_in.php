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
    <form class="form-signin" method="post" action="index.php" name="loginform">



        <?php
        // show potential errors / feedback (from login object)
        if (isset($login)) {
            if ($login->errors) {
                foreach ($login->errors as $error) {
                    echo $error;
                }
            }
            if ($login->messages) {
                foreach ($login->messages as $message) {
                    echo $message;
                }
            }
        }
        ?>

        
        
        <img src="../images/logo-mini.png" class="center-block" style="width: 100px;" >



        <h3 class="form-signin-heading" style="text-align: center">Login</h3>


        <input type="text" style="margin-bottom: 10px;" class="form-control login_input" name="user_name" placeholder="Username or Email" required=""/>
        <input type="password" class="form-control login_input" name="user_password" placeholder="Password" required=""/>


        <input style="-webkit-appearance: none;" class="btn btn-lg btn-primary btn-block" type="submit"  name="login" value="Log in" /> <br>

        <input type="button" style="-webkit-appearance: none;" class="btn btn-info" value="Register new account" onclick="location.href = 'register.php';">


    </form>
</div>






























































</body>
</html>










