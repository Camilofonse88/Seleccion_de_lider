<?php
$log_path = 'logs/votos.log';

function contarVotos($file_path) {
    if (file_exists($file_path)) {
        $votos = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $voto_count = array_count_values($votos);
        arsort($voto_count);
        return $voto_count;
    }
    return [];
}

$voto_count = contarVotos($log_path);
header('Content-Type: application/json');
echo json_encode($voto_count);
?>
