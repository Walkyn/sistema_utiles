<?php
$correo = $_GET['correo'];

include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// Consulta SQL actualizada para obtener la información del usuario y su rol
$query = "
    SELECT u.id_usuario, u.nombre, u.apellido, u.pais, u.ciudad, u.telefono, u.correo, ur.id_rol 
    FROM usuarios u
    LEFT JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
    WHERE u.correo = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $correo);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!-- Formulario de Modificación de Usuarios -->
<div class="container mx-auto my-auto p-6 bg-white rounded-2xl border-t-2 border-slate-500 shadow-md">
    <h2 class="text-xl font-bold mb-4">Editar Usuario</h2>
    <form id="edit-user-form" class="space-y-4">
        <!-- Campo ID del Usuario y Nombre -->
        <div class="flex flex-col md:flex-row md:space-x-4">
            <div class="md:w-1/2">
                <label for="user-id" class="block text-base font-medium text-gray-700 mb-1">ID del Usuario</label>
                <input type="number" id="user-id" name="user-id"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese el ID del usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>" readonly />
            </div>
            <div class="md:w-1/2">
                <label for="user-name" class="block text-base font-medium text-gray-700 mb-1">Nombres</label>
                <input type="text" id="user-name" name="user-name"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese el nombre del usuario" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" />
            </div>
        </div>

        <!-- Campos Apellidos, País y Ciudad -->
        <div class="flex flex-col md:flex-row md:space-x-4">
            <div class="md:w-1/2">
                <label for="user-lastname" class="block text-base font-medium text-gray-700 mb-1">Apellidos</label>
                <input type="text" id="user-lastname" name="user-lastname"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese los apellidos del usuario" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" />
            </div>
            <div class="md:w-1/2">
                <label for="user-country" class="block text-base font-medium text-gray-700 mb-1">País</label>
                <input type="text" id="user-country" name="user-country"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese el país del usuario" value="<?php echo htmlspecialchars($usuario['pais']); ?>" />
            </div>
        </div>

        <!-- Campos Ciudad, Teléfono y Correo -->
        <div class="flex flex-col md:flex-row md:space-x-4">
            <div class="md:w-1/2">
                <label for="user-city" class="block text-base font-medium text-gray-700 mb-1">Ciudad</label>
                <input type="text" id="user-city" name="user-city"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese la ciudad del usuario" value="<?php echo htmlspecialchars($usuario['ciudad']); ?>" />
            </div>
            <div class="md:w-1/2">
                <label for="user-phone" class="block text-base font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" id="user-phone" name="user-phone"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="Ingrese el teléfono del usuario" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" />
            </div>
        </div>

        <!-- Campo Correo Electrónico y Contraseña -->
        <div class="flex flex-col md:flex-row gap-4 mt-4">
            <div class="md:flex-1">
                <label for="user-email" class="block text-base font-medium text-gray-700 mb-2.5">Correo Electrónico</label>
                <input type="email" id="user-email" name="user-email"
                    class="block w-full border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-slate-500 focus:border-slate-500 text-base"
                    placeholder="example@gmail.com" value="<?php echo htmlspecialchars($usuario['correo']); ?>" />
                <p id="emailError" class="text-red-500 text-sm opacity-0 transition-opacity duration-500 mt-2">
                    Por favor, ingrese un email válido.</p>
            </div>
            <div class="md:flex-1 relative">
                <div class="flex items-center space-x-2">
                    <!-- Interruptor -->
                    <label for="enablePassword" class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="enablePassword" name="enablePassword" class="sr-only" onchange="updatePasswordToggle()" />
                        <div id="switchContainer" class="relative w-12 h-6 bg-gray-400 rounded-full shadow-inner transition-colors duration-300">
                            <div id="dot" class="absolute top-0 left-0 bg-white w-6 h-6 rounded-full transform transition-transform duration-300"></div>
                        </div>
                        <!-- Texto -->
                        <span class="text-base font-medium text-gray-700"></span>
                    </label>
                </div>


                <div class="relative mt-2">
                    <input id="user-password" type="password" name="user-password"
                        class="block w-full border border-gray-300 rounded-md p-2 pr-10"
                        placeholder="••••••••" disabled />

                    <button id="togglePassword" type="button"
                        class="absolute inset-y-0 right-0 flex items-center h-full px-2 text-gray-500">
                        <i id="passwordIcon" class="material-icons">visibility</i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección para asignar roles -->
        <div class="mt-2">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Asignar Rol</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <input type="radio" id="rol-admin" name="role" value="1" class="hidden" required>
                    <div class="toggle-switch w-12 h-6 bg-gray-400 rounded-full cursor-pointer relative"
                        onclick="toggleSwitch('rol-admin')">
                        <div
                            class="toggle-thumb w-6 h-6 bg-white rounded-full shadow-md transform transition-transform">
                        </div>
                    </div>
                    <label for="rol-admin" class="ml-3 text-gray-700">Administrador</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="rol-empleado" name="role" value="2" class="hidden" required>
                    <div class="toggle-switch w-12 h-6 bg-gray-400 rounded-full cursor-pointer relative"
                        onclick="toggleSwitch('rol-empleado')">
                        <div
                            class="toggle-thumb w-6 h-6 bg-white rounded-full shadow-md transform transition-transform">
                        </div>
                    </div>
                    <label for="rol-empleado" class="ml-3 text-gray-700">Empleado</label>
                </div>
            </div>
            <p id="roleError" class="text-red-500 text-sm opacity-0 transition-opacity duration-500 mt-2">
                Por favor, seleccione un rol.</p>
        </div>

        <!-- Botón de Guardar Cambios -->
        <div class="flex justify-center w-full">
            <button type="button" id="submitEditUserBtn"
                class="bg-slate-800 text-white py-2 px-4 rounded-lg hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 w-full sm:w-auto">
                <span class="material-icons mr-2">save</span>
                Guardar cambios
            </button>
        </div>
    </form>

</div>

<input type="hidden" id="usuarioRol" value="<?php echo $usuario['id_rol']; ?>">

<script>
    function updatePasswordToggle() {
        const enablePasswordSwitch = document.getElementById('enablePassword');
        const passwordInput = document.getElementById('user-password');
        const switchContainer = document.getElementById('switchContainer');
        const dot = document.getElementById('dot');

        if (enablePasswordSwitch && passwordInput && switchContainer && dot) {
            if (enablePasswordSwitch.checked) {
                passwordInput.disabled = false;
                switchContainer.classList.remove('bg-gray-400');
                switchContainer.classList.add('bg-green-500');
                dot.classList.add('translate-x-full');
            } else {
                passwordInput.disabled = true;
                passwordInput.value = '';
                switchContainer.classList.add('bg-gray-400');
                switchContainer.classList.remove('bg-green-500');
                dot.classList.remove('translate-x-full');
            }
        }
    }

    updatePasswordToggle();
</script>



<script>
    // Función para activar el interruptor correspondiente
    function activateSwitch(role) {
        const roleSwitch = document.getElementById(`rol-${role}`);
        if (roleSwitch) {
            const switchElem = roleSwitch.nextElementSibling;
            roleSwitch.checked = true;
            switchElem.classList.add('bg-green-500');
            switchElem.classList.remove('bg-gray-400');
            switchElem.querySelector('.toggle-thumb').style.transform = 'translateX(1.5rem)';
        }
    }

    // Ejecutar la activación del interruptor
    (function() {
        const usuarioRol = document.getElementById('usuarioRol')?.value;
        if (usuarioRol) {
            activateSwitch(usuarioRol === '1' ? 'admin' : 'empleado');
        }
    })();
</script>

<script>
    document.getElementById('togglePassword')?.addEventListener('click', () => {
        const passwordInput = document.getElementById('user-password');
        const passwordIcon = document.getElementById('passwordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            passwordIcon.textContent = 'visibility';
        }
    });

    function toggleSwitch(id) {
        const radio = document.getElementById(id);
        document.querySelectorAll(`input[name="${radio.name}"]`).forEach(r => {
            r.checked = false;
            const switchElem = r.nextElementSibling;
            switchElem.classList.remove('bg-green-500');
            switchElem.classList.add('bg-gray-400');
            switchElem.querySelector('.toggle-thumb').style.transform = 'translateX(0)';
        });
        radio.checked = true;

        const switchElem = radio.nextElementSibling;
        switchElem.classList.add('bg-green-500');
        switchElem.classList.remove('bg-gray-400');
        switchElem.querySelector('.toggle-thumb').style.transform = 'translateX(1.5rem)';
    }

    document.getElementById('submitEditUserBtn').addEventListener('click', function() {
        // Validación del formulario
        const emailInput = document.getElementById('user-email');
        const emailError = document.getElementById('emailError');
        const roleError = document.getElementById('roleError');
        let isValid = true;

        // Validación del email
        if (!emailInput.value || !validateEmail(emailInput.value)) {
            emailError.classList.remove('opacity-0');
            isValid = false;
        } else {
            emailError.classList.add('opacity-0');
        }

        // Validación del rol
        const roleSelected = document.querySelector('input[name="role"]:checked');
        if (!roleSelected) {
            roleError.classList.remove('opacity-0');
            isValid = false;
        } else {
            roleError.classList.add('opacity-0');
        }

        if (isValid) {
            // Mostrar confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas guardar los cambios?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    container: 'sweet-alert-container',
                    confirmButton: 'btn-confirm',
                    cancelButton: 'btn-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(document.getElementById('edit-user-form'));
                    fetch('actualizarUsuario.php', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '¡Éxito!',
                                    'El usuario ha sido actualizado correctamente.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    'Hubo un problema al editar el usuario.',
                                    'error'
                                );
                            }
                        }).catch(error => {
                            Swal.fire(
                                'Error',
                                'Hubo un problema con la solicitud.',
                                'error'
                            );
                        });
                }
            });
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
</script>