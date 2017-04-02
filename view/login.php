<?php

$site->RenderHeader();
$site->StartContent();

/* Flashes */
if (true === $site->HasFlashes()) {
    $site->GetFlash();
}

/* Form */
$site->StartDiv('', [
    'class' => 'form-wrapper',
]);

$site->StartForm('login.php', 'POST', [
    'class' => 'active-form',
    'style' => '
        margin-top: 20px;
    ',
]);

$site->RenderInput('email', [
    'class'       => 'form-input',
    'placeholder' => 'Email', 
    'name'        => 'login_email',
]);
$site->RenderInput('password', [
    'class'       => 'form-input',
    'placeholder' => 'Password', 
    'name'        => 'login_password',
]);

$site->RenderInput('submit', [
    'class'       => 'button',
    'value'       => 'Login',
]);

/* End of form */
$site->EndForm();
$site->EndDiv();

$site->EndContent();
$site->RenderFooter();
