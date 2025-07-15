document.getElementById('convertir').addEventListener('click', function () {
    const cantidad = parseFloat(document.getElementById('cantidad').value);
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const resultado = document.getElementById('resultado');
    const error = document.getElementById('error');

    resultado.textContent = '';
    error.textContent = '';

    if (isNaN(cantidad) || cantidad <= 0) {
        error.textContent = 'Por favor ingresa una cantidad válida.';
        return;
    }

    fetch(`backend/convertidor.php?from=${from}&to=${to}`)
        .then(response => response.json())
        .then(data => {
            if (data.rate) {
                const conversion = cantidad * data.rate;
                resultado.textContent = `Resultado: ${conversion.toFixed(2)} ${to}`;
            } else {
                error.textContent = data.error || 'Error en la conversión.';
            }
        })
        .catch(err => {
            error.textContent = 'Error al contactar con el servidor.';
            console.error(err);
        });
});
