<?php
session_start();
header('Content-Type: application/json');

$id = $_POST['product_id'] ?? null;

if ($id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    
    echo json_encode([
        'status' => 'success',
        'total' => array_sum($_SESSION['cart']) // Renvoie la clé 'total'
    ]);
} else {
    echo json_encode(['status' => 'error']);
}