<?php
// Simula la falla de un nodo específico
if (isset($_GET['node_id'])) {
    $node_id = $_GET['node_id'];
    $file = 'nodes_status.json'; // Archivo que guarda el estado de los nodos

    // Cargar estados de los nodos
    $nodes = json_decode(file_get_contents($file), true);

    // Marcar el nodo como inactivo
    if (isset($nodes[$node_id])) {
        $nodes[$node_id]['active'] = false;
        file_put_contents($file, json_encode($nodes));
        echo "Nodo $node_id marcado como inactivo.";
    } else {
        echo "Nodo no encontrado.";
    }
} else {
    echo "Especifique un nodo.";
}
?>
