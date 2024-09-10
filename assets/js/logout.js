document.getElementById('logout').addEventListener('click', function (event) {
    event.preventDefault();

    // Mostrar la alerta de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cerrar sesión?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            fetch('../../logout.php')
                .then(response => {
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        title: 'Sesión cerrada',
                        text: 'Tu sesión ha sido cerrada exitosamente.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Mostrar el spinner
                        const spinner = document.getElementById('spinner-logout');
                        spinner.classList.remove('hidden');

                        setTimeout(() => {
                            window.location.href = '../../index.html';
                        }, 1500);
                    });
                })
                .catch(error => {
                    console.error('Error al cerrar sesión:', error);
                });
        }
    });
});
