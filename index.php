<?php
// Archivo de registro de votos
$log_path = 'logs/votos.log';
// Archivo de estados de los nodos
$node_status_path = 'logs/nodos.json';

// Inicializar nodos con base en Docker Compose
$expected_nodes = ["node1", "node2", "node3", "node4", "node5"];

// Verificar si el archivo JSON existe
if (!file_exists($node_status_path)) {
    $initial_states = array_fill_keys($expected_nodes, "activo");
    file_put_contents($node_status_path, json_encode($initial_states, JSON_PRETTY_PRINT));
} else {
    $node_states = json_decode(file_get_contents($node_status_path), true);

    // Asegurar que todos los nodos esperados estén en el archivo JSON
    foreach ($expected_nodes as $node) {
        if (!isset($node_states[$node])) {
            $node_states[$node] = "activo";
        }
    }

    // Guardar cualquier ajuste necesario
    file_put_contents($node_status_path, json_encode($node_states, JSON_PRETTY_PRINT));
}

// Procesar actualización de estados desde el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_states']) && isset($_POST['states'])) {
    $updated_states = $_POST['states'];
    file_put_contents($node_status_path, json_encode($updated_states, JSON_PRETTY_PRINT));
    echo "<p>Estados de los nodos actualizados.</p>";
    header("Refresh:0");
    exit;
}

// Procesar votación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar'])) {
    $voto = $_POST['voto'];

    // Registrar el voto si se seleccionó un líder
    if (!empty($voto)) {
        $log_entry = "$voto\n";
        file_put_contents($log_path, $log_entry, FILE_APPEND);
        echo "<p>Voto registrado por: $voto</p>";
    } else {
        echo "<p>Error: No se seleccionó un líder.</p>";
    }
}

// Leer estados actuales de los nodos
$node_states = json_decode(file_get_contents($node_status_path), true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Elección de Líder</title>
    <style>
        /* Estilos generales */
        body {
            background: linear-gradient(135deg, #2b2b2b, #000000);
            color: #ffffff;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        /* Títulos */
        h2, h3 {
            color: #ff4747;
            text-shadow: 0 2px 5px rgba(255, 71, 71, 0.8);
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff4747;
            display: inline-block;
            padding-bottom: 5px;
        }
        /* Formularios */
        form {
            max-width: 600px;
            margin: 20px auto;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #ff4747;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(255, 71, 71, 0.5);
        }
        /* Etiquetas y selectores */
        label, select {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
            color: #ffffff;
        }
        select {
            background: #1e1e1e;
            color: #ffffff;
            border: 2px solid #ff4747;
            border-radius: 5px;
            padding: 8px;
            width: 100%;
        }
        /* Botones */
        button {
            display: inline-block;
            background: #ff4747;
            color: #ffffff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            transition: background 0.3s ease-in-out, transform 0.2s;
            margin-top: 10px;
            width: 100%;
        }
        button:hover {
            background: #d94040;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 71, 71, 0.8);
        }
        /* Estados de los nodos */
        p {
            font-size: 18px;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.05);
        }
        p select {
            width: auto;
            margin-left: 10px;
        }
        /* Sección de votos */
        h3 {
            text-align: center;
            margin: 20px 0;
        }
        h3 + p {
            text-align: center;
            font-size: 20px;
            color: #00ff6e;
            text-shadow: 0 2px 5px rgba(0, 255, 110, 0.8);
        }
        /* Tablas y listas */
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
            border: 2px solid #ff4747;
            background: rgba(0, 0, 0, 0.8);
        }
        td, th {
            padding: 10px;
            border: 1px solid #ff4747;
            color: #ffffff;
        }
        th {
            background: #ff4747;
            color: #000000;
            text-transform: uppercase;
        }
        /* Enlaces (si los hay) */
        a {
            color: #ff4747;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-shadow: 0 0 5px #ff4747;
            color: #d94040;
        }
        /* Responsivo */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }
            label, select, button, p {
                font-size: 16px;
            }
        }
    </style></head>
<body>
    <h2>Votar por un líder</h2>
    <form method="post" action="">
        <label for="voto">Selecciona un líder:</label>
        <select name="voto" id="voto" required>
            <option value="">-- Elige un líder --</option>
            <option value="Camilo Fonseca">Camilo Fonseca</option>
            <option value="Andres Bernal">Andres Bernal</option>
            <option value="Mauricio Narvaez">Mauricio Narvaez</option>
            <option value="Voto en Blanco">Voto en Blanco</option>
        </select>
        <br><br>
        <button type="submit" name="enviar">Votar</button>
    </form>

    <h2>Estados de los nodos</h2>
    <form method="post" action="">
        <?php
        foreach ($node_states as $node => $status) {
            echo "<p>$node: 
                <select name='states[$node]'>
                    <option value='activo'" . ($status === "activo" ? " selected" : "") . ">Activo</option>
                    <option value='inactivo'" . ($status === "inactivo" ? " selected" : "") . ">Inactivo</option>
                </select>
            </p>";
        }
        ?>
        <button type="submit" name="update_states">Actualizar Estados</button>
    </form>

    <h3>Votos registrados:</h3>
    <div id="votosRegistrados">
        <p>No hay votos registrados aún.</p>
    </div>
    <h3 id="liderActual"></h3>

    <script>
        function actualizarVotos() {
            fetch('get_votos.php')
                .then(response => response.json())
                .then(data => {
                    const contenedorVotos = document.getElementById('votosRegistrados');
                    contenedorVotos.innerHTML = ''; // Limpiar contenido actual
                    if (Object.keys(data).length > 0) {
                        for (const [candidato, cantidad] of Object.entries(data)) {
                            const votoElemento = document.createElement('p');
                            votoElemento.textContent = `${candidato}: ${cantidad} votos`;
                            contenedorVotos.appendChild(votoElemento);
                        }
                        // Determinar el líder actual
                        const lider = Object.keys(data)[0];
                        const liderElemento = document.getElementById('liderActual');
                        liderElemento.textContent = `El líder actual es: ${lider}`;
                    } else {
                        contenedorVotos.innerHTML = '<p>No hay votos registrados aún.</p>';
                    }
                })
                .catch(error => console.error('Error al actualizar votos:', error));
        }

        // Actualizar votos cada 5 segundos
        setInterval(actualizarVotos, 5000);
        actualizarVotos();
    </script>
</body>
</html>
