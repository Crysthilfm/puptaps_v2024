const togglePassword = document.querySelector('#togglePassword');

const password = document.querySelector('#password');
const eye_icon = document.querySelector('#eye_icon');

togglePassword.addEventListener('click', () => {

    // Toggle the type attribute using
    // getAttribure() method
    const type = password
        .getAttribute('type') === 'password' ?
        'text' : 'password';

    password.setAttribute('type', type);

    // Toggle the eye and bi-eye icon
    // this.classList.toggle('fa-eye');

    eye_icon.classList.toggle("fa-eye-slash");
});
