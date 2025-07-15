<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["message"])) {
    $userMessage = strtolower(trim($_POST["message"]));
    $json = file_get_contents("data.json");
    $data = json_decode($json, true);

    $response = "Je n'ai pas compris. Pouvez-vous reformuler ?";

    // Fonction de concaténation
    function enrichirReponse($base, $ajouts = []) {
        return $base . ' ' . implode(' ', $ajouts) . '.';
    }

    foreach ($data as $key => $value) {
        if (strpos($userMessage, $key) !== false) {
            $response = $value;

            // Si c'est la première interaction
            if (!isset($_SESSION['accueil_envoye'])) {
                $motsSupp = ["Je", "suis", "là", "pour", "vous", "aider"];
                $response = enrichirReponse($value, $motsSupp);
                $_SESSION['accueil_envoye'] = true; // Marque que l'accueil a été envoyé
            }

            break;
        }
    }

    // Sauvegarde de l'historique
    $_SESSION['chat'][] = [
        "user" => $userMessage,
        "bot" => $response
    ];

    echo $response;
}
?>

