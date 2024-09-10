document.addEventListener('DOMContentLoaded', function () {
    // Función para obtener los datos del usuario
    function loadUserData() {
        fetch('getImageUser.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    // Actualizar el nombre, correo y la imagen del usuario
                    document.getElementById('userName').textContent = data.nombre + ' ' + data.apellido;
                    document.getElementById('userEmail').textContent = data.correo;
                    document.getElementById('userImage').src = data.image;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    loadUserData();
});

document.addEventListener('DOMContentLoaded', function () {
    fetch('getDataUser.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
            } else {
                // Actualizar los campos de entrada
                document.getElementById('firstName').value = data.nombre;
                document.getElementById('lastName').value = data.apellido;
                document.getElementById('country').value = data.pais;
                document.getElementById('city').value = data.ciudad;
                document.getElementById('phone').value = data.telefono;
                document.getElementById('email').value = data.correo;
                document.getElementById('userId').value = data.id_usuario;

                // Actualizar la información del perfil
                document.getElementById('userName').textContent = data.nombre + ' ' + data.apellido;
                document.getElementById('userEmail').textContent = data.correo;
            }
        })
        .catch(error => console.error('Error:', error));
});
