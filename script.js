document.getElementById('convertir').addEventListener('click', () => {
    const from = document.getElementById('moneda-origen').value;
    const to = document.getElementById('moneda-destino').value;
    const amount = parseFloat(document.getElementById('cantidad').value);

    if (isNaN(amount)) {
        alert("Por favor, ingresa una cantidad válida.");
        return;
    }

    // RUTA ABSOLUTA a practica_3/backend
    fetch(`http://localhost/practica_3/backend/convertidor.php?from=${from}&to=${to}`)
        .then(res => {
            if (!res.ok) throw new Error("Respuesta del servidor no OK");
            return res.json();
        })
        .then(data => {
            if (data.rate) {
                const resultado = amount * data.rate;
                document.getElementById('resultado').textContent = `${resultado.toFixed(2)} ${to}`;
            } else {
                document.getElementById('resultado').textContent = "Error: " + data.error;
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error de conexión con el servidor.");
        });
});
