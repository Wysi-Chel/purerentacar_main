<?php
session_start();

// If already logged in, redirect to admin dashboard.
if(isset($_SESSION['user'])){
    header("Location: admin-dashboard.php");
    exit;
}

require_once 'vendor/autoload.php'; // Composer autoload for Google API Client

// Check if a Google action is requested
if(isset($_GET['action']) && $_GET['action'] == 'google'){
    $client = new Google_Client();
    $client->setClientId('512750139884-b2j0vkka61rqcba3gsjku83ulqnqkmmh.apps.googleusercontent.com');
    $client->setClientSecret('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');
    // Ensure this redirect URI matches your configuration in Google Cloud Console
    $client->setRedirectUri('http://localhost/login.php?action=google_callback');
    $client->addScope("email");
    $client->addScope("profile");
    
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
}

// Process the Google callback
if(isset($_GET['action']) && $_GET['action'] == 'google_callback'){
    $client = new Google_Client();
    $client->setClientId('512750139884-b2j0vkka61rqcba3gsjku83ulqnqkmmh.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost/login.php?action=google_callback');
    
    if(isset($_GET['code'])){
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if(!isset($token["error"])){
            $client->setAccessToken($token['access_token']);
            $oauth = new Google_Service_Oauth2($client);
            $profile = $oauth->userinfo->get();
            
            $_SESSION['user'] = [
                'email'   => $profile->email,
                'name'    => $profile->name,
                'picture' => $profile->picture
            ];
            header("Location: admin-dashboard.php");
            exit;
        } else {
            $error = "Error during Google login.";
        }
    }
}

// Process email login (if form submitted)
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    // For demonstration, use hardcoded credentials.
    if($email === 'admin@example.com' && $password === 'password'){
        $_SESSION['user'] = [
            'email' => $email,
            'name'  => 'Admin'
        ];
        header("Location: admin-dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <link rel="icon" href="images/rel-icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Pure Rental Group Webpage" name="description">
    <meta content="" name="keywords">
    <meta content="" name="author">
    <!-- CSS Files ================================================== -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="css/mdb.min.css" rel="stylesheet" type="text/css" id="mdb">
    <link href="css/plugins.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/coloring.css" rel="stylesheet" type="text/css">
    <!-- color scheme -->
    <link id="colors" href="css/colors/scheme-07.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="wrapper">
        <!-- Page Preloader -->
        <div id="de-preloader"></div>
        <?php include 'header.php';?>
        <!-- Content begin -->
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>
            <section id="section-hero" aria-label="section" class="jarallax">
                <img src="images/background/2.jpg" class="jarallax-img" alt="">
                <div class="v-center">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-4 offset-lg-4">
                                <div class="padding40 rounded-3 shadow-soft" data-bgcolor="#ffffff">
                                    <h4>Login</h4>
                                    <?php if($error != ''): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php endif; ?>
                                    <div class="spacer-10"></div>
                                    <!-- Email login form -->
                                    <form id="form_login" class="form-border" method="post" action="">
                                        <div class="field-set">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" required />
                                        </div>
                                        <div class="field-set">
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                                        </div>
                                        <div id="submit">
                                            <input type="submit" id="send_message" value="Sign In" class="btn-main btn-fullwidth rounded-3" />
                                        </div>
                                    </form>
                                    <div class="title-line">Or&nbsp;sign&nbsp;up&nbsp;with</div>
                                    <div class="row g-2">
                                        <div class="col-lg-6">
                                            <!-- Google sign-in now points to this file with a Google action -->
                                            <a class="btn-sc btn-fullwidth mb10" href="login.php?action=google">
                                                <img src="images/svg/google_icon.svg" alt="">Google
                                            </a>
                                        </div>
                                        <div class="col-lg-6">
                                            <a class="btn-sc btn-fullwidth mb10" href="facebook_login.php">
                                                <img src="images/svg/facebook_icon.svg" alt="">Facebook
                                            </a>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <p class="text-center">Don't have an account? <a href="register.php" class="btn-link">Sign Up</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Content close -->
    </div>

    <!-- Javascript Files ================================================== -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
</body>
</html>
