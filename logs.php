<?php
function registrarEvento($mensaje) {
    $logFile = '/var/www/html/logs/vote.log'; // Cambia la ruta si es necesario
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $mensaje . "\n", FILE_APPEND);
}
?>
