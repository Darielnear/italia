<?php
session_start();
header('Content-Type: application/json');

$id = $_POST['product_id'] ?? null;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1; // On récupère la quantité (1 par défaut)

if ($id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Au lieu de faire +1, on ajoute la quantité choisie
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    
    echo json_encode([
        'status' => 'success',
        'total' => array_sum($_SESSION['cart']) 
    ]);
} else {
    echo json_encode(['status' => 'error']);
}