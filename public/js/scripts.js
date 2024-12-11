document.addEventListener('DOMContentLoaded', () => {
    const tabla = document.getElementById('tabla-tareas');
    const encabezados = tabla.querySelectorAll('thead th');

    encabezados.forEach((encabezado, indice) => {
        encabezado.addEventListener('click', () => {
            ordenarTabla(tabla, indice, encabezado.getAttribute('data-tipo'));

            // Limpiar las clases de los demás encabezados
            encabezados.forEach((otroEncabezado, i) => {
                if (i !== indice) {
                    otroEncabezado.classList.remove('ascendente', 'descendente');
                }
            });
        });
    });

    function ordenarTabla(tabla, indice, tipo) {
        const filas = Array.from(tabla.querySelectorAll('tbody tr'));
        const comparar = obtenerComparador(tipo);

        // Alternar orden
        const ordenAscendente = encabezados[indice].classList.toggle('ascendente');
        encabezados[indice].classList.toggle('descendente', !ordenAscendente);

        filas.sort((filaA, filaB) => {
            const celdaA = filaA.children[indice].innerText.trim();
            const celdaB = filaB.children[indice].innerText.trim();
            return comparar(celdaA, celdaB) * (ordenAscendente ? 1 : -1);
        });

        filas.forEach(fila => tabla.querySelector('tbody').appendChild(fila));
    }

    function obtenerComparador(tipo) {
        switch (tipo) {
            case 'numero':
                return (a, b) => parseFloat(a) - parseFloat(b);
            case 'fecha':
                return (a, b) => new Date(a.split('-').reverse().join('-')) - new Date(b.split('-').reverse().join('-'));
            case 'texto':
            default:
                return (a, b) => a.localeCompare(b);
        }
    }
    const deleteButtons = document.querySelectorAll(".eliminar-categoria");

    deleteButtons.forEach(button => {
        button.addEventListener("click", (event) => {
            if (!confirm("¿Estás seguro de que deseas eliminar esta categoría?")) {
                event.preventDefault();
            }
        });
    });

});
