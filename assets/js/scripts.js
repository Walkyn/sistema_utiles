document.getElementById('toggle-sidebar').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const icon = document.getElementById('icon');
    
    // Alternar la visibilidad del sidebar
    sidebar.classList.toggle('-translate-x-full');
    
    // Alternar la visibilidad del overlay con transición
    if (overlay.classList.contains('opacity-0')) {
        overlay.classList.remove('opacity-0', 'invisible');
        overlay.classList.add('opacity-100', 'visible');
    } else {
        overlay.classList.remove('opacity-100', 'visible');
        overlay.classList.add('opacity-0', 'invisible');
    }
    
    // Cambiar el icono según el estado
    if (sidebar.classList.contains('-translate-x-full')) {
        icon.textContent = 'menu';
    } else {
        icon.textContent = 'menu_open';
    }
});

// Agregar evento para cerrar el sidebar al hacer clic en el overlay
document.getElementById('overlay').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const icon = document.getElementById('icon');
    
    // Ocultar el sidebar y el overlay
    sidebar.classList.add('-translate-x-full');
    overlay.classList.remove('opacity-100', 'visible');
    overlay.classList.add('opacity-0', 'invisible');
    
    // Cambiar el icono al estado inicial
    icon.textContent = 'menu';
});

// Función para manejar la acción de eliminación
function handleDeleteAction(itemType, deleteItemCallback) {
    const buttons = document.querySelectorAll(`.delete-${itemType}-button`);

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const swalWithTailwindButtons = Swal.mixin({
                customClass: {
                    confirmButton: "bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none",
                    cancelButton: "bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none",
                    actions: "flex gap-4 justify-end"
                },
                buttonsStyling: false
            });

            swalWithTailwindButtons.fire({
                title: `¿Estás seguro de eliminar este ${itemType}?`,
                text: `¡No podrás revertir esto!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: `Sí, eliminar ${itemType}!`,
                cancelButtonText: "No, cancelar!",
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItemCallback();
                    swalWithTailwindButtons.fire({
                        title: "¡Eliminado!",
                        text: `El ${itemType} ha sido eliminado.`,
                        icon: "success"
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithTailwindButtons.fire({
                        title: "Cancelado",
                        text: `El ${itemType} está seguro :)`,
                        icon: "error"
                    });
                }
            });
        });
    });
}

// Configuración de los event listeners para cada tipo de botón
handleDeleteAction("producto", function () {
    console.log("Producto eliminado.");
});

handleDeleteAction("cliente", function () {
    console.log("Cliente eliminado.");
});

handleDeleteAction("articulo", function () {
    console.log("Producto eliminado del ticket.");
});

handleDeleteAction("usuario", function () {
    console.log("Usuario eliminado.");
});


//cambiar iamgen de avatar 
function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
        const userImage = document.getElementById('userImage');
        userImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
}



// Spinner JS
document.querySelectorAll('.navigate-button').forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault();
        const spinner = document.getElementById('spinner');

        spinner.classList.remove('hidden');

        setTimeout(() => {
            window.location.href = this.href;
        }, 1000);
    });
});

