<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $prompt = $data['prompt'] ?? '';

    // Ici, vous devriez implémenter l'appel à l'API Leonardo.ai
    // Ceci est un exemple simplifié
    $apiKey = 'f1929ea3-b169-4c18-a16c-5d58b4292c69';
    $apiUrl = 'https://cloud.leonardo.ai/api/rest/v1/generations';

    // Configuration de la requête
    $options = [
        'http' => [
            'header' => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ],
            'method' => 'POST',
            'content' => json_encode([
                'prompt' => $prompt,
                'modelId' => 'e316348f-7773-490e-adcd-46757c738eb7', // ID d'un modèle par défaut
                'width' => 512,
                'height' => 512
            ])
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);

    if ($result === FALSE) {
        echo json_encode(['error' => 'Erreur lors de la génération']);
        exit;
    }

    $response = json_decode($result, true);
    
    // Ici, vous devriez traiter la réponse et récupérer l'URL de l'image générée
    // Ceci est un exemple simplifié
    echo json_encode([
        'imageUrl' => 'https://cdn.leonardo.ai/users/.../generated_images/...png'
    ]);
} else {
    echo json_encode(['error' => 'Méthode non autorisée']);
}
?>