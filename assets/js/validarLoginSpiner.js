//Validacion Login correo
document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');

    emailInput.addEventListener('input', () => {
        if (emailInput.validity.valid) {
            emailError.classList.remove('opacity-100');
            emailError.classList.add('opacity-0');
        } else {
            emailError.classList.remove('opacity-0');
            emailError.classList.add('opacity-100');
        }
    });
});

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Mostrar spinner
    document.getElementById('spinner').classList.remove('hidden');

    // Enviar datos del formulario a validarLogin.php
    fetch('validarLogin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'email': email,
            'password': password
        })
    })
    .then(response => response.json())
    .then(data => {
        // No ocultar el spinner inmediatamente, sino después de la redirección
        if (data.status === 'success') {
            setTimeout(function() {
                document.getElementById('spinner').classList.add('hidden'); // Ocultar spinner
                window.location.href = 'src/home.html'; // Redirigir al home
            }, 2000); // Esperar 2 segundos
        } else {
            document.getElementById('spinner').classList.add('hidden');
            // Mostrar mensaje de error con SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        document.getElementById('spinner').classList.add('hidden');
        console.error('Error:', error);
    });
});
