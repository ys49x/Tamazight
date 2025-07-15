<?php

// En tête de fichier, après les headers
$allowedDomains = ['https://go.izrangram.com']; // Remplacez par votre domaine
$referer = $_SERVER['HTTP_REFERER'] ?? '';

if (!empty($allowedDomains) && !empty($referer)) {
    $refererDomain = parse_url($referer, PHP_URL_HOST);
    if (!in_array($refererDomain, $allowedDomains)) {
        echo json_encode(['error' => 'Accès non autorisé']);
        exit;
    }
}

// Et remplacer la ligne Access-Control-Allow-Origin par :
header('Access-Control-Allow-Origin: ' . implode(', ', $allowedDomains));






header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Vérifier si le paramètre de recherche est présent
if (!isset($_GET['q']) {
    echo json_encode(['error' => 'Paramètre de recherche manquant']);
    exit;
}

$query = urlencode(trim($_GET['q']));
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

// URL de votre moteur de recherche
$searchUrl = "https://go.izrangram.com/search?q={$query}";

try {
    // Initialisation de cURL
    $ch = curl_init();
    
    // Configuration des options cURL
    curl_setopt($ch, CURLOPT_URL, $searchUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // À désactiver en production avec un vrai certificat
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    
    // Exécution de la requête
    $response = curl_exec($ch);
    
    // Vérification des erreurs
    if (curl_errno($ch)) {
        throw new Exception('Erreur cURL: ' . curl_error($ch));
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        throw new Exception("Erreur HTTP: {$httpCode}");
    }
    
    // Fermeture de la session cURL
    curl_close($ch);
    
    // Décodage de la réponse JSON
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Réponse JSON invalide');
    }
    
    // Formatage des résultats (adaptez selon la structure de votre API)
    $results = [
        'query' => urldecode($query),
        'count' => count($data['results'] ?? []),
        'results' => []
    ];
    
    foreach (array_slice($data['results'] ?? [], 0, $limit) as $item) {
        $results['results'][] = [
            'title' => $item['title'] ?? 'Sans titre',
            'url' => $item['url'] ?? '#',
            'description' => $item['description'] ?? 'Aucune description disponible'
        ];
    }
    
    // Envoi des résultats au format JSON
    echo json_encode($results);
    
} catch (Exception $e) {
    // Gestion des erreurs
    echo json_encode([
        'error' => $e->getMessage(),
        'query' => urldecode($query)
    ]);
}
?>