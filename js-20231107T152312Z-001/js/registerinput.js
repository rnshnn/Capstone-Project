$(document).ready(function() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm-password');
    const passwordRequirement = document.querySelector('.password-requirement');
    const passwordMatch = document.querySelector('.password-match');
    const registerButton = document.querySelector('.confirmRegistration');

    function validatePasswords() {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        if (password === confirmPassword && password.length >= 8) {
            passwordField.classList.add('green-border');
            confirmPasswordField.classList.add('green-border');
            passwordRequirement.style.display = 'none';
            passwordMatch.style.display = 'block';
            registerButton.disabled = false;
        } else {
            passwordField.classList.remove('green-border');
            confirmPasswordField.classList.remove('green-border');
            passwordRequirement.style.display = 'block';
            passwordMatch.style.display = 'none';
            registerButton.disabled = true;
        }
    }

    passwordField.addEventListener('input', validatePasswords);
    confirmPasswordField.addEventListener('input', validatePasswords);
});


    // Get all input elements
    const inputElements = document.querySelectorAll('.form-control');

    // Add event listeners to toggle 'has-value' class on input
    inputElements.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });


    
function preventBack(){window.history.forward()};
setTimeout("preventBack()",0);
windiw.onunload=function(){null;}
