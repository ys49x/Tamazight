<?php
if (isset($_GET['term'])) {
    $term = strtolower(trim($_GET['term']));
    $json = file_get_contents('data.json');
    $data = json_decode($json, true);
    $suggestions = [];

    foreach ($data as $key => $value) {
        if (strpos($key, $term) !== false) {
            $suggestions[] = $key;
        }
    }

    // Limiter à 10 suggestions maximum
    echo json_encode(array_slice($suggestions, 0, 10));
}
?>
