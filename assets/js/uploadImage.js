// Función para previsualizar la imagen
function previewImage(event) {
    const fileInput = event.target;
    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('userImage').src = e.target.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

// Función para previsualizar y subir la imagen
function previewAndUploadImage(event) {
    const fileInput = event.target;
    const file = fileInput.files[0];

    // Previsualizar la imagen
    if (file) {
        previewImage(event);

        // Crear un FormData y cargar la imagen
        const formData = new FormData();
        formData.append('profileImage', file);

        console.log('Enviando imagen:', file);

        fetch('uploadImage.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    console.log('Imagen subida con éxito');
                } else {
                    console.error('Error al subir la imagen:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
