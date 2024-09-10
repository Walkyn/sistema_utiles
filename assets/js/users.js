document.addEventListener('DOMContentLoaded', function () {
    // Obtener el correo del usuario actualmente autenticado
    fetch('getSessionUser.php')
        .then(response => response.json())
        .then(sessionData => {
            const sessionEmail = sessionData.email;

            // Obtener los usuarios
            fetch('getUsers.php')
                .then(response => response.json())
                .then(users => {
                    const tableBody = document.querySelector('table tbody');
                    tableBody.innerHTML = '';

                    users.forEach(user => {
                        const row = document.createElement('tr');
                        row.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-600');

                        // Comparar el email de la sesión con el email del usuario
                        const isActive = user.estado === 'activo';
                        const statusColor = user.correo === sessionEmail ? 'bg-green-500' : (isActive ? 'bg-green-500' : 'bg-red-500');
                        const statusText = user.correo === sessionEmail ? 'Activo' : (isActive ? 'Activo' : 'Inactivo');

                        // Determinar la URL de la imagen del usuario
                        const imageUrl = user.imagen
                            ? '../uploads/' + user.imagen
                            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nombre + '+' + user.apellido);

                        row.innerHTML = `
<td class="w-4 p-4">
    <div class="flex items-center">
        <input id="checkbox-table-${user.correo}" type="checkbox"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="checkbox-table-${user.correo}" class="sr-only">Seleccionar</label>
    </div>
</td>
<th scope="row"
    class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
    <img class="w-10 h-10 rounded-full object-cover"
        src="${imageUrl}" alt="Avatar">
    <div class="ps-3">
        <div class="text-base font-semibold">${user.nombre} ${user.apellido}</div>
        <div class="font-normal text-gray-500">${user.correo}</div>
    </div>
</th>
<td class="px-6 py-4">
    ${user.telefono}
</td>
<td class="px-6 py-4">
    <div class="flex items-center">
        <div class="h-2.5 w-2.5 rounded-full ${statusColor} me-2"></div>
        ${statusText}
    </div>
</td>
<td class="px-6 py-4 flex space-x-4">
    <button data-id="${user.id}" class="edit-modal bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transform transition-transform duration-200 hover:scale-105 flex items-center" aria-label="Editar usuario">
        <span class="material-icons mr-2">edit</span>
        Editar
    </button>
    <button data-correo="${user.correo}" class="delete-usuario-button bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transform transition-transform duration-200 hover:scale-105 flex items-center" aria-label="Eliminar usuario">
        <span class="material-icons mr-2">delete</span>
        Eliminar
    </button>
</td>
`;

                        tableBody.appendChild(row);
                    });

                    // Manejo de los botones de editar
                    document.querySelectorAll('.edit-modal').forEach(button => {
                        button.addEventListener('click', function () {
                            const idCliente = this.getAttribute('data-id');

                            // Limpiar el contenido del modal
                            const modalBody = document.getElementById('edit-modal-body');
                            modalBody.innerHTML = '';

                            fetch(`./editUser.html?id_cliente=${idCliente}`)
                                .then(response => response.text())
                                .then(html => {
                                    const modal = document.getElementById('edit-modal');
                                    modalBody.innerHTML = html;

                                    // Mostrar el modal con animación
                                    modal.classList.remove('hidden');
                                    modal.classList.add('opacity-0', 'scale-100');
                                    setTimeout(() => {
                                        modal.classList.remove('opacity-0', 'scale-90');
                                        modal.classList.add('opacity-100', 'scale-100');
                                    }, 10);

                                    // Ejecutar los scripts dentro del contenido HTML cargado
                                    Array.from(modalBody.querySelectorAll('script')).forEach(script => {
                                        const newScript = document.createElement('script');
                                        if (script.src) {
                                            newScript.src = script.src;
                                        } else {
                                            newScript.textContent = script.textContent;
                                        }
                                        document.body.appendChild(newScript);
                                    });
                                })
                                .catch(error => console.error('Error al cargar el contenido del modal:', error));
                        });
                    });

                    // Manejo de los botones de eliminar usuario
                    document.querySelectorAll('.delete-usuario-button').forEach(button => {
                        button.addEventListener('click', function () {
                            const correoUsuario = this.getAttribute('data-correo');
                            const swalWithTailwindButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: "bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none transition ease-in-out duration-300",
                                    cancelButton: "bg-red-500 text-white px-4 py-2 rounded hover:bg-red-400 focus:outline-none transition ease-in-out duration-300",
                                    actions: "flex gap-4 justify-end"
                                },
                                buttonsStyling: false
                            });


                            swalWithTailwindButtons.fire({
                                title: `¿Estás seguro de eliminar este usuario ${correoUsuario}?`,
                                text: `¡No podrás revertir esto!`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, eliminar',
                                cancelButtonText: 'No, Cancelar',
                                reverseButtons: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Realizar la eliminación
                                    fetch('./deleteUser.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded'
                                        },
                                        body: new URLSearchParams({
                                            'correo': correoUsuario
                                        })
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                swalWithTailwindButtons.fire({
                                                    title: 'Eliminado',
                                                    text: data.message,
                                                    icon: 'success'
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            } else {
                                                swalWithTailwindButtons.fire({
                                                    title: 'Error',
                                                    text: data.message,
                                                    icon: 'error'
                                                });
                                            }
                                        })
                                        .catch(error => console.error('Error al eliminar el usuario:', error));
                                } else {
                                    swalWithTailwindButtons.fire({
                                        title: 'Cancelado',
                                        text: 'El usuario está seguro :).',
                                        icon: 'info'
                                    });
                                }
                            });
                        });
                    });


                })
                .catch(error => console.error('Error al cargar los datos:', error));
        })
        .catch(error => console.error('Error al obtener el correo del usuario en sesión:', error));

    // Función para cerrar el modal con una transición suave
    function closeModal() {
        const modal = document.getElementById('edit-modal');

        // Aplicar la animación de cierre
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-100');

        // Esperar el tiempo de la animación antes de ocultar el modal completamente
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('opacity-0', 'scale-90');
        }, 300); // Tiempo de la animación en milisegundos
    }

    // Event listener para cerrar el modal cuando se haga clic fuera de él o en el botón de cerrar
    document.getElementById('closeModalButtonEdit').addEventListener('click', closeModal);
    window.addEventListener('click', function (e) {
        const modal = document.getElementById('edit-modal');
        if (e.target === modal) {
            closeModal();
        }
    });
});
