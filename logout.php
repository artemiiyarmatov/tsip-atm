<?php

$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}bootstrap.php");

if ($identity->Logout()){
    $site->SetFlash('success', 'You successfully have been logout from site.');
    header('Location: index.php');
}
