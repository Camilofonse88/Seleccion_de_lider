<?php
// Simula el reinicio de un nodo específico
if (isset($_GET['node_id'])) {
    $node_id = $_GET['node_id'];
    $file = 'nodes_status.json'; // Archivo que guarda el estado de los nodos

    // Cargar estados de los nodos
    $nodes = json_decode(file_get_contents($file), true);

    // Marcar el nodo como activo
    if (isset($nodes[$node_id])) {
        $nodes[$node_id]['active'] = true;
        file_put_contents($file, json_encode($nodes));

        // Informar a los demás nodos
        foreach ($nodes as $id => $node) {
            if ($id != $node_id && $node['active']) {
                // Simula un mensaje a los nodos activos
                echo "Nodo $id notificado del reinicio de Nodo $node_id.<br>";
            }
        }

        echo "Nodo $node_id reiniciado y reintegrado.";
    } else {
        echo "Nodo no encontrado.";
    }
} else {
    echo "Especifique un nodo.";
}
?>
