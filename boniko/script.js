 // function to validate user's login information
 function validateLogin() {

    // gets the login information
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value.trim();

    // if there is no input email or password
    if (!email || !password) {
        alert('Please fill in all fields.');
        return;
    }

    // if the user's email do not have @
    if (!email.includes('@')) {
        alert('Please enter a valid email address containing "@"');
        return;
    }

}

// Validates user's sign-up information
function validateSignup() {
    const email = document.getElementById('signup-email').value.trim();
    const username = document.getElementById('signup-username').value.trim();
    const password = document.getElementById('signup-password').value.trim();

    // if the user skip to input info of any of these fields
    if (!email || !username || !password) {
        alert('Please fill in all fields.');
        return;
    }

    // if the user's email do not have @
    if (!email.includes('@')) {
        alert('Please enter a valid email address containing "@"');
        return;
    }
}