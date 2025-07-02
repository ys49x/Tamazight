<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['chat'])) {
    echo json_encode($_SESSION['chat']);
} else {
    echo json_encode([]);
}
?>
