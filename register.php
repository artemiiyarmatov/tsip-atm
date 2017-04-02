<?php

$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}bootstrap.php");

include_once(MODEL_PATH . 'Registration.php');

$formParameters = [
    'email',
    'password',
    'name',
    'surname',
    'patronymic',
];

/* Controller part */
if (true === ValidatePOSTParameters($formParameters)) {
    if (null !== $identity) {
        $site->SetFlash('warning', 'You are already have been registered.');
        header('Location: index.php');
    } else {
        $identity = new \core\Identity();
        $identity->name = $_POST['name'];
        $identity->surname = $_POST['name'];
        $identity->patronymic = $_POST['patronymic'];
        $identity->email = $_POST['email'];
        $identity->password = $_POST['password'];
    }

    $register = new \model\Registration();
    $register->identity = $identity;
    $register->db = $db;

    if (true === $register->Register()) {
        $site->SetFlash('success', 'Registration successful.');
        header('Location: index.php');
    } else {
        $site->SetFlash('error', 'Error during registration. Make sure you\'ve entered all fields correctly.');
    }
}

/**
 * Validate POST parameters.
 *
 * @param array $parametersName name of POST parameters.
 *
 * @return bool true if validation successful,
 * false in other case.
 */
function ValidatePOSTParameters(array $parametersName)
{
    foreach ($parametersName as $parameter) {
        if (!isset($_POST[$parameter])) {
            return false;
        }
    }

    return true;
}

include_once(VIEW_PATH . 'register.php');
