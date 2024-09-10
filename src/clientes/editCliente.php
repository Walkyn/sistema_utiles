<?php

$id_cliente = $_GET['id_cliente'];

include '../../conexion.php';

$query = "SELECT * FROM clientes WHERE id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../assets/js/tailwindcss.js"></script>
    <script src="../../assets/js/flowbite.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/material-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>Papelería Ángel - Registrar Cliente</title>
</head>

<body>
    <!-- Formulario de Modificación de Clientes -->
    <div class="container mx-auto my-auto p-6 bg-white rounded-2xl border-t-2 border-slate-500 shadow-md">
        <h2 class="text-xl font-bold mb-4">Editar Cliente</h2>
        <form id="edit-client-form" class="space-y-4">
            <!-- Campo ID del Cliente y Nombre del Cliente -->
            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="md:w-1/2">
                    <label for="client-id" class="block text-base font-medium text-gray-700 mb-1">ID del Cliente</label>
                    <input type="number" id="client-id" name="client-id"
                        class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                        placeholder="Ingrese el ID del cliente" value="<?php echo htmlspecialchars($cliente['id_cliente']); ?>" readonly />
                </div>
                <div class="md:w-1/2">
                    <label for="client-name" class="block text-base font-medium text-gray-700 mb-1">Nombres y Apellidos</label>
                    <input type="text" id="client-name" name="client-name"
                        class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                        placeholder="Ingrese el nombre del cliente" value="<?php echo htmlspecialchars($cliente['nombre_apellido']); ?>" />
                </div>
            </div>

            <!-- Campo Correo Electrónico -->
            <div class="w-full">
                <label for="client-email" class="block text-base font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input type="email" id="client-email" name="client-email"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese el correo electrónico del cliente" value="<?php echo htmlspecialchars($cliente['correo']); ?>" />
            </div>
            <div class="w-full">
                <label for="client-phone" class="block text-base font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="number" id="client-phone" name="client-phone"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese el teléfono del cliente" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" />
            </div>

            <!-- Botón de Guardar Cambios -->
            <div class="flex justify-center">
                <button type="button" id="submitEditClientBtn"
                    class="bg-slate-800 text-white py-2 px-4 rounded-lg hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <span class="material-icons mr-2">save</span>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('submitEditClientBtn').addEventListener('click', function () {
        const form = document.getElementById('edit-client-form');
        const formData = new FormData(form);

        // Validación de campos vacíos
        const camposVacios = [];
        formData.forEach((value, key) => {
            if (!value.trim()) {
                camposVacios.push(key);
            }
        });

        if (camposVacios.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, complete todos los campos antes de continuar.',
            });
            return;
        }

        fetch('./actualizarCliente.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: true,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'clientes.html';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar el cliente',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al procesar la solicitud',
                    text: error.message,
                });
            });
    });
</script>

</body>

</html>