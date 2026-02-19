<?php
session_start();

// On vérifie qu'on a bien reçu un ID de produit
if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    // Si le panier n'existe pas encore dans la session, on le crée
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // On ajoute le produit ou on augmente la quantité
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    // On calcule le nouveau total d'articles
    $total = array_sum($_SESSION['cart']);

    // INDISPENSABLE : On répond au JavaScript avec le nouveau total
    header('Content-Type: application/json');
    echo json_encode(['totalItems' => $total]);
    exit;
}