<?php
session_start();

$node_id = rand(1, 3); // Asigna un nodo al azar (en un sistema real esto sería la identificación del servidor actual)
$nodes = $_SESSION['nodes'];

echo "Nodo $node_id está iniciando la elección...\n";

// Simular que el nodo está participando en la elección
$nodes_to_notify = array_filter($nodes, function($id) use ($node_id) {
    return $id !== $node_id;
});

foreach ($nodes_to_notify as $id => $node) {
    $url = "http://{$node['ip']}:{$node['port']}/respond_election.php?node_id=$node_id";
    @file_get_contents($url); // Intentar contactar al nodo
}

echo "Proceso de elección iniciado...\n";

// Seleccionar un líder (para simplificación, el nodo con el ID más bajo gana)
$leader = min(array_keys($nodes));

$_SESSION['leader'] = $leader; // Guardar líder en sesión
echo "Líder elegido: Nodo $leader\n";
?>
