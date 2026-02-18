<?php
require 'db_config.php';

// Create tables
$db->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prezzo INTEGER NOT NULL,
    nome_modello TEXT NOT NULL,
    brand TEXT NOT NULL,
    categoria TEXT NOT NULL,
    descrizione_lunga TEXT NOT NULL,
    caratteristiche_tecniche TEXT NOT NULL,
    varianti TEXT NOT NULL,
    immagine_principale TEXT NOT NULL
)");

$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome_cliente TEXT NOT NULL,
    email_cliente TEXT NOT NULL,
    indirizzo_spedizione TEXT NOT NULL,
    totale INTEGER NOT NULL,
    stato TEXT NOT NULL DEFAULT 'in_attesa',
    prova_pagamento TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantita INTEGER NOT NULL,
    prezzo_unitario INTEGER NOT NULL,
    variante_scelta TEXT,
    FOREIGN KEY(order_id) REFERENCES orders(id),
    FOREIGN KEY(product_id) REFERENCES products(id)
)");

// Import data from JSON
$json = file_get_contents('products.json');
$products = json_decode($json, true);

$stmt = $db->prepare("INSERT INTO products (prezzo, nome_modello, brand, categoria, descrizione_lunga, caratteristiche_tecniche, varianti, immagine_principale) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($products as $product) {
    // Check if product exists to avoid duplicates on multiple runs
    $check = $db->prepare("SELECT id FROM products WHERE nome_modello = ?");
    $check->execute([$product['nome_modello']]);
    if ($check->fetch()) continue;

    $stmt->execute([
        $product['prezzo'],
        $product['nome_modello'],
        $product['brand'],
        $product['categoria'],
        $product['descrizione_lunga'],
        json_encode($product['caratteristiche_tecniche']),
        json_encode($product['varianti']),
        $product['immagine_principale']
    ]);
}

echo "Migrazione completata con successo.";
?>
