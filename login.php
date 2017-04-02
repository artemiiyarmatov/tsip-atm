<?php

$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}bootstrap.php");

/* Controller part */

// Go to index page in case of user already logined
if (isset($_SESSION["Identity"])) {
    $site->setFlash('warning', 'You\'re already logined in site!');
    header('Location: index.php');
}

// Get parameters which are posted from form
if (isset($_POST['login_email']) && isset($_POST['login_password'])) {
    $identity = new \core\Identity();
    $identity->db = $db;
    if ($identity->Login()) {
        $_SESSION['Identity'] = $identity;
        $site->SetFlash('success', 'Login successful.');
        header('Location: index.php');
    } else {
        $site->SetFlash('warning', 'Invalid user email or password');
    }
}

include(VIEW_PATH . "login.php");
