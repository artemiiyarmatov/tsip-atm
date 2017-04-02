/**
 * Validate password in registration form.
 *
 * @return {bool} true if validation successful; false in other case
 */
function validatePassword()
{
    var password = document.getElementsByName('password')[0];
    var passwordRepeat = document.getElementsByName('repeat_password')[0];

    return password.value === passwordRepeat.value;
}

/**
 * Validates registration form
 */
function validateRegistrationForm()
{
    var submitButton = document.querySelectorAll('[type="submit"]')[0];
    submitButton.disabled = (false === validatePassword());
}
