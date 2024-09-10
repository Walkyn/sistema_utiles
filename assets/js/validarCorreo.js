document.addEventListener('DOMContentLoaded', () => {

    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const saveButton = document.querySelector('.bg-slate-500');

    if (emailInput && emailError && saveButton) {
        const validateEmail = () => {
            if (emailInput.validity.valid) {
                emailError.classList.remove('opacity-100');
                emailError.classList.add('opacity-0');
            } else {
                emailError.classList.remove('opacity-0');
                emailError.classList.add('opacity-100');
            }
        };

        emailInput.addEventListener('input', validateEmail);

        saveButton.addEventListener('click', (event) => {
            if (!emailInput.validity.valid) {
                event.preventDefault();
                validateEmail();
            }
        });
    } else {
        console.error('Elementos necesarios no encontrados en el DOM.');
    }
});
