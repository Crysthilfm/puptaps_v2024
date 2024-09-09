const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');

const password_confirmation = document.querySelector('#password_confirmation');
const eye_icon_confirm = document.querySelector('#eye_icon_confirm');

toggleConfirmPassword.addEventListener('click', () => {

    // Toggle the type attribute using
    // getAttribure() method
    const type = password_confirmation
        .getAttribute('type') === 'password' ?
        'text' : 'password';

    password_confirmation.setAttribute('type', type);

    // Toggle the eye and bi-eye icon
    // this.classList.toggle('fa-eye');
    eye_icon_confirm.classList.toggle("fa-eye-slash");
});
