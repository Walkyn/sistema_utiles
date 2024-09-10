<?php

include '../../conexion.php';

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

$codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';

$producto = null;

if ($codigo) {

    $stmt = $conn->prepare('SELECT * FROM productos WHERE codigo_producto = ?');
    $stmt->bind_param('s', $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    $stmt->close();
}

if (!$producto) {

    die('Producto no encontrado');
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../assets/js/tailwindcss.js"></script>
    <script src="../../assets/js/flowbite.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/material-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&family=Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <title>Papelería Ángel - Modificar Producto</title>
</head>

<body class="bg-gray-100">
    <div class="w-full mx-auto p-8 bg-white rounded-2xl shadow-lg border-t-4 border-indigo-500">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Modificar Producto</h1>
        <form id="product-form" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label for="codigo" class="mb-2 text-lg font-medium text-gray-700">Código</label>
                    <input type="text" id="codigo" name="codigo" placeholder="Ej. 12345" value="<?= htmlspecialchars($producto['codigo_producto']) ?>" class="px-4 py-3 border border-gray-300 rounded-lg shadow-sm" readonly>
                </div>
                <div class="flex flex-col">
                    <label for="descripcion" class="mb-2 text-lg font-medium text-gray-700">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Ej. Cuaderno tamaño carta" value="<?= htmlspecialchars($producto['descripcion']) ?>" class="px-4 py-3 border border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex flex-col">
                    <label for="precioCompra" class="mb-2 text-lg font-medium text-gray-700">Precio de compra</label>
                    <input type="number" id="precioCompra" name="precioCompra" step="0.01" placeholder="Ej. 50.00" value="<?= htmlspecialchars($producto['precio_compra']) ?>" class="px-4 py-3 border border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex flex-col">
                    <label for="precioVenta" class="mb-2 text-lg font-medium text-gray-700">Precio de venta</label>
                    <input type="number" id="precioVenta" name="precioVenta" step="0.01" placeholder="Ej. 70.00" value="<?= htmlspecialchars($producto['precio_venta']) ?>" class="px-4 py-3 border border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex flex-col md:col-span-2">
                    <label for="existencia" class="mb-2 text-lg font-medium text-gray-700">Stock</label>
                    <input type="number" id="existencia" name="existencia" placeholder="Ej. 100" value="<?= htmlspecialchars($producto['stock']) ?>" class="px-4 py-3 border border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>
            <div class="flex items-center justify-between mt-8 space-x-4">
                <button id="submit-button" type="submit" class="flex items-center justify-center w-full md:w-1/2 py-3 bg-indigo-600 text-white rounded-lg shadow">
                    <span class="material-icons mr-2">save</span>Guardar
                </button>
                <button type="reset" class="flex items-center justify-center w-full md:w-1/2 py-3 bg-gray-300 text-gray-800 rounded-lg shadow">
                    <span class="material-icons mr-2">refresh</span>Restablecer
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('submit-button').addEventListener('click', function(event) {
            event.preventDefault();

            const form = document.getElementById('product-form');
            const formData = new FormData(form);

            // Validación de campos vacíos
            const camposVacios = [];
            formData.forEach((value, key) => {
                if (!value.trim() && key !== 'codigo') {
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

            fetch('update_product.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: true,
                            timer: 2000
                        }).then(() => {
                            window.location.href = 'productos.html';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al modificar el producto',
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