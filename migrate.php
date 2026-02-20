<?php
require_once 'db_config.php';
$products = json_decode(file_get_contents('products.json'), true);

try {
    $pdo->exec("DROP TABLE IF EXISTS products");
    $pdo->exec("CREATE TABLE products (id INTEGER PRIMARY KEY, nome_modello TEXT, brand TEXT, categoria TEXT, prezzo REAL, descrizione_lunga TEXT, caratteristiche_tecniche TEXT, varianti TEXT, immagine_main TEXT)");

    $stmt = $pdo->prepare("INSERT INTO products VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmt->execute([
            $p['id'], $p['nome_modello'], $p['brand'], $p['categoria'], $p['prezzo'], 
            $p['descrizione_lunga'], json_encode($p['caratteristiche_tecniche']), 
            json_encode($p['varianti']), $p['id'] . ".jpg"
        ]);
    }
    echo "Database sincronizzato: 75 prodotti pronti.";
} catch (Exception $e) { die($e->getMessage()); }