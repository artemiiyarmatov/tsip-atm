<?php

$site->RenderHeader();
$site->StartContent();

/* Flashes */
if (true === $site->HasFlashes()) {
    $site->GetFlash();
}

/* Include JS file */
$site->IncludeJS('js/register.js');

/* Form */
$site->StartDiv('', [
    'class' => 'form-wrapper',
]);

$site->StartForm('register.php', 'POST', [
    'class' => 'active-form',
    'style' => '
        margin-top: 20px;
    ',
]);

$site->StartDiv('', [
    'class' => 'form-block',
]);

$site->RenderInput('email', [
    'class'       => 'form-input',
    'placeholder' => 'Email', 
    'name'        => 'email',
    'required'    => 'true',
]);
$site->RenderInput('password', [
    'class'       => 'form-input',
    'placeholder' => 'Password', 
    'name'        => 'password',
    'onchange'    => 'validateRegistrationForm()',
    'pattern'     => '.{6,}',
    'title'       => 'Password must be at least 6 characters, maximum 20 characters',
    'required'    => 'true',
]);
$site->RenderInput('password', [
    'class'       => 'form-input',
    'placeholder' => 'Repeat password', 
    'name'        => 'repeat_password',
    'onchange'    => 'validateRegistrationForm()',
    'pattern'     => '.{6,}',
    'title'       => 'Passwords must be the same.',
    'required'    => 'true',
]);

$site->EndDiv();

$site->StartDiv('', [
    'class' => 'form-block',
]);

$userCredentialsMessage = 'Item can only contain letters (3 - 32 letters)';

$site->RenderInput('text', [
    'class'       => 'form-input',
    'name'        => 'name',
    'placeholder' => 'Name',
    'message'     => str_replace('Item', 'Name', $userCredentialsMessage),
    'required'    => 'true',
]);

$site->RenderInput('text', [
    'class'       => 'form-input',
    'name'        => 'patronymic',
    'placeholder' => 'Patronymic',
    'required'    => 'true',
    'message'     => str_replace('Item', 'Patronymic', $userCredentialsMessage),
]);

$site->RenderInput('text', [
    'class'       => 'form-input',
    'name'        => 'surname',
    'placeholder' => 'Surname',
    'message'     => str_replace('Item', 'Surname', $userCredentialsMessage),
    'required'    => 'true',
]);

$site->EndDiv();

$site->RenderInput('submit', [
    'class' => 'button',
    'value' => 'Register',
]);

/* End of form */
$site->EndForm();
$site->EndDiv();

$site->EndContent();
$site->RenderFooter();
