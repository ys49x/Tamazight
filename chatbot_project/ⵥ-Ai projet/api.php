<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autorise les requêtes CORS

$data = json_decode(file_get_contents('news.json'), true);
$term = $_GET['term'] ?? '';

// Recherche insensible à la casse et aux accents
$normalizedTerm = mb_strtolower($term, 'UTF-8');
$results = [];

foreach ($data as $tamazight => $translations) {
    if (mb_stripos($tamazight, $normalizedTerm) !== false || mb_stripos($translations, $normalizedTerm) !== false) {
        $results[] = [
            'tamazight' => $tamazight,
            'translations' => $translations,
        ];
    }
}

echo json_encode($results ?: ['error' => 'Aucun résultat pour "' . htmlspecialchars($term) . '"']);








?>