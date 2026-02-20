<?php
session_start();
header('Content-Type: application/json'); // Indispensable pour une communication propre avec le JS

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Suppression complète de l'article
    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$id]);
        echo json_encode([
            'success' => true,
            'totalItems' => array_sum($_SESSION['cart'] ?? [])
        ]);
        exit;
    }

    // Modification de la quantité (+1 ou -1)
    $delta = (int)($_POST['delta'] ?? 0);
    if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 0;
    
    $_SESSION['cart'][$id] += $delta;
    
    // Si la quantité tombe à 0 ou moins, on retire le produit
    if ($_SESSION['cart'][$id] <= 0) {
        unset($_SESSION['cart'][$id]);
        $newQty = 0;
    } else {
        $newQty = $_SESSION['cart'][$id];
    }

    echo json_encode([
        'success' => true, 
        'newQty' => $newQty,
        'totalItems' => array_sum($_SESSION['cart'] ?? [])
    ]);
    exit;
}

// En cas d'accès direct ou d'erreur
echo json_encode(['success' => false, 'message' => 'Invalid request']);