document.addEventListener('DOMContentLoaded', () => {
    console.log('loadModal.js script loaded');

    const modal = document.getElementById('modal');
    const modalBody = document.getElementById('modal-body');
    const closeButton = document.getElementById('closeModalButton');

    // Función para ejecutar scripts en el contenido HTML cargado
    function executeScripts() {
        // Ejecutar scripts inline
        const inlineScripts = modalBody.querySelectorAll('script:not([src])');
        inlineScripts.forEach(script => {
            const newScript = document.createElement('script');
            newScript.text = script.textContent || script.innerText;
            document.body.appendChild(newScript);
            document.body.removeChild(newScript);
        });
    
        // Cargar y ejecutar scripts externos
        const externalScripts = modalBody.querySelectorAll('script[src]');
        externalScripts.forEach(script => {
            const newScript = document.createElement('script');
            newScript.src = script.src;
            newScript.onload = () => console.log(`Script cargado y ejecutado: ${script.src}`);
            newScript.onerror = () => console.error(`Error al cargar el script: ${script.src}`);
            document.body.appendChild(newScript);
        });
    }
    
    

    // Función para mostrar el modal
    function showModal(htmlFile) {
        modal.classList.remove('hidden');
        modal.classList.add('opacity-0', 'scale-90');
    
        fetch(htmlFile)
            .then(response => response.text())
            .then(data => {
                console.log('Contenido HTML cargado:', data); // Verifica el contenido HTML
                modalBody.innerHTML = data;
                modal.classList.remove('opacity-0', 'scale-90');
                modal.classList.add('opacity-100', 'scale-100');
    
                // Ejecutar scripts después de que el HTML se ha insertado
                executeScripts();
            })
            .catch(error => {
                console.error(`Error al cargar el formulario de ${htmlFile}:`, error);
            });
    }
    
    

    // Listeners para abrir el modal con clases
    const openButtons = document.querySelectorAll('.open-modal');
    openButtons.forEach(button => {
        button.addEventListener('click', () => {
            const htmlFile = button.getAttribute('data-modal-file');
            showModal(htmlFile);
        });
    });


    if (closeButton) {
        closeButton.addEventListener('click', () => {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            
        });
    }
});


/*
document.addEventListener('DOMContentLoaded', () => {
    console.log('loadModal.js script loaded');

    const modal = document.getElementById('modal');
    const modalBody = document.getElementById('modal-body');
    const createProductButton = document.getElementById('createProductButton');
    const editProductButton = document.getElementById('editProductButton');
    const addClientButton = document.getElementById('addClientButton');
    const editClientButton = document.getElementById('editClientButton');
    const createUserButton = document.getElementById('createUserButton');
    const editUserButton = document.getElementById('editUserButton');
    const closeButton = document.getElementById('closeModalButton');
    const loadingOverlay = document.createElement('div');
    loadingOverlay.classList.add('loading');
    loadingOverlay.innerHTML = '<div class="spinner"></div>';

    // Función para mostrar el modal
    function showModal(htmlFile) {
        modal.classList.remove('hidden');
        modal.classList.add('opacity-0', 'scale-90');
        document.body.appendChild(loadingOverlay);

        fetch(htmlFile)
            .then(response => response.text())
            .then(data => {
                modalBody.innerHTML = data;
                document.body.removeChild(loadingOverlay);
                modal.classList.remove('opacity-0', 'scale-90');
                modal.classList.add('opacity-100', 'scale-100');
            })
            .catch(error => {
                console.error(`Error al cargar el formulario de ${htmlFile}:`, error);
                document.body.removeChild(loadingOverlay);
            });
    }

    // Listener para crear producto
    if (createProductButton) {
        createProductButton.addEventListener('click', () => {
            showModal('./../productos/agregarProducto.html');
        });
    }

    // Listener para agregar cliente
    if (addClientButton) {
        addClientButton.addEventListener('click', () => {
            showModal('./../clientes/registrarClientes.html');
        });
    }

    // Listener para modificar producto
    if (editProductButton) {
        editProductButton.addEventListener('click', () => {
            showModal('./../productos/modificarProducto.html');
        });
    }

    // Listener para modificar cliente
    if (editClientButton) {
        editClientButton.addEventListener('click', () => {
            showModal('./../clientes/modificarCliente.html');
        });
    }

    // Listener para crear usuario
    if (createUserButton) {
        createUserButton.addEventListener('click', () => {
            showModal('./../usuarios/registrarUser.html');
        });
    }

    // Listener para modificar usuario
    if (editUserButton) {
        editUserButton.addEventListener('click', () => {
            showModal('./../usuarios/editUser.html');
        });
    }

    // Listener para cerrar el modal
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        });
    }
}); */
