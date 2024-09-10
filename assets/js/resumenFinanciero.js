document.addEventListener('DOMContentLoaded', function () {
    console.log('Script JavaScript cargado y ejecutado.');

    function actualizarWidgets() {
        // Obtener la fecha local del dispositivo
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
        const day = String(now.getDate()).padStart(2, '0');
        const fechaActual = `${year}-${month}-${day}`;

        // Imprimir la fecha en la consola
        console.log(`Fecha actual enviada al servidor: ${fechaActual}`);

        // Actualizar ingresos del día con la fecha local
        fetch(`get_ventas.php?fecha=${fechaActual}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos del servidor:', data); // Verificar los datos recibidos

                const ingresosHoy = Number(data.ingresos_hoy);
                const ingresosAyer = Number(data.ingresos_ayer);
                const porcentajeCambioDiario = Number(data.porcentaje_cambio);

                document.getElementById('total-ventas').textContent = `$${ingresosHoy.toFixed(2)}`;
                document.getElementById('ingresos-ayer').textContent = `$${ingresosAyer.toFixed(2)}`;

                const spanPorcentajeDiario = document.getElementById('porcentaje-cambio');
                actualizarPorcentaje(spanPorcentajeDiario, porcentajeCambioDiario);

                // Actualizar ventas semanales
                return fetch(`venta_semanal.php?fecha=${fechaActual}`);
            })
            .then(response => response.json())
            .then(data => {
                const ventasEstaSemana = Number(data.ventas_esta_semana);
                const ventasSemanaPasada = Number(data.ventas_semana_pasada);
                const porcentajeCambioSemanal = Number(data.porcentaje_cambio);

                document.getElementById('ventas-semanales').textContent = `$${ventasEstaSemana.toFixed(2)}`;
                document.getElementById('ventas-semana-pasada').textContent = `$${ventasSemanaPasada.toFixed(2)}`;

                const spanPorcentajeSemanal = document.getElementById('porcentaje-cambio-semanal');
                actualizarPorcentaje(spanPorcentajeSemanal, porcentajeCambioSemanal);

                // Actualizar ventas mensuales
                return fetch(`venta_mensual.php?fecha=${fechaActual}`);
            })
            .then(response => response.json())
            .then(data => {
                const ventasMesActual = Number(data.ventas_mes_actual);
                const ventasMesPasado = Number(data.ventas_mes_pasado);
                const porcentajeCambioMensual = Number(data.porcentaje_cambio);

                document.getElementById('venta-mes-actual').textContent = `$${ventasMesActual.toFixed(2)}`;
                document.getElementById('venta-mes-pasado').textContent = `$${ventasMesPasado.toFixed(2)}`;

                const spanPorcentajeMensual = document.getElementById('porcentaje-cambio-mes');
                actualizarPorcentaje(spanPorcentajeMensual, porcentajeCambioMensual);
            })
            .catch(error => console.error('Error al obtener los datos:', error));
    }

    function actualizarPorcentaje(element, porcentajeCambio) {
        const porcentaje = porcentajeCambio.toFixed(2);
        if (porcentajeCambio > 0) {
            element.textContent = `+${porcentaje}%`;
            element.classList.remove('text-red-500');
            element.classList.add('text-green-500');
        } else if (porcentajeCambio < 0) {
            element.textContent = `${porcentaje}%`;
            element.classList.remove('text-green-500');
            element.classList.add('text-red-500');
        } else {
            element.textContent = '0%';
            element.classList.remove('text-red-500', 'text-green-500');
        }
    }

    actualizarWidgets();
});
