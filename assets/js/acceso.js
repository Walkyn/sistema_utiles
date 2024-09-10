document.addEventListener('DOMContentLoaded', function () {
    function checkAccessAndToggleMenu(event) {
        const button = event.currentTarget;
        const menu = document.getElementById('dropdown-auth');

        if (menu.classList.contains('hidden')) {
            fetch('../validarRol.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Acceso denegado',
                            text: data.message
                        }).then(() => {
                            // Si el acceso está denegado, ocultar el menú
                            menu.classList.add('hidden');
                        });
                    } else {
                        // Alternar visibilidad del menú
                        menu.classList.toggle('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error al verificar el rol:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al verificar el acceso.'
                    });
                });
        } else {
            // Si el menú ya está visible, simplemente ocultarlo
            menu.classList.add('hidden');
        }
    }

    function checkAccessAndRedirect(event) {
        event.preventDefault();

        const href = event.target.closest('a').getAttribute('href');

        fetch('../validarRol.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Acceso denegado',
                        text: data.message
                    }).then(() => {
                        // No redirigir si el acceso está denegado
                    });
                } else {
                    // Redirigir a la página solicitada si el acceso es exitoso
                    window.location.href = href;
                }
            })
            .catch(error => {
                console.error('Error al verificar el rol:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al verificar el acceso.'
                });
            });
    }

    // Solo aplicar el evento al botón con la clase 'access-btn'
    const accessButton = document.querySelector('.access-btn');
    if (accessButton) {
        accessButton.addEventListener('click', checkAccessAndToggleMenu);
    }

    // Aplicar el evento a los botones de navegación con la clase 'navigate-button'
    document.querySelectorAll('.navigate-button').forEach(button => {
        button.addEventListener('click', checkAccessAndRedirect);
    });
});
