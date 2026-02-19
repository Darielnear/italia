<?php
// migrate.php - Migrazione SQLite per Cicli Volante
require_once 'db_config.php';

$json_file = 'products.json';
if (!file_exists($json_file)) {
    die("Errore: products.json non trovato.");
}

$products = json_decode(file_get_contents($json_file), true);

try {
    // Reset database
    $pdo->exec("DROP TABLE IF EXISTS orders");
    $pdo->exec("DROP TABLE IF EXISTS products");

    // Tabella Prodotti
    $pdo->exec("CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        external_id INTEGER UNIQUE,
        nome_modello TEXT NOT NULL,
        brand TEXT,
        categoria TEXT,
        prezzo REAL,
        descrizione_lunga TEXT,
        caratteristiche_tecniche TEXT,
        varianti TEXT,
        immagine_main TEXT
    )");

    // Tabella Ordini Semplificata
    $pdo->exec("CREATE TABLE orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        customer_name TEXT NOT NULL,
        customer_email TEXT NOT NULL,
        id_prodotto INTEGER,
        colore_scelto TEXT,
        prezzo_finale REAL,
        tracking_code TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Scannerizza immagini con estensioni miste
    $img_dir = 'public/img/';
    $img_files = [];
    if (is_dir($img_dir)) {
        $files = scandir($img_dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $info = pathinfo($file);
                // Mappa il nome file (ID) all'estensione trovata
                $img_files[$info['filename']] = $file;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO products 
        (external_id, nome_modello, brand, categoria, prezzo, descrizione_lunga, caratteristiche_tecniche, varianti, immagine_main) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($products as $p) {
        $ext_id = $p['id'];
        $immagine = isset($img_files[$ext_id]) ? $img_files[$ext_id] : null;

        $stmt->execute([
            $ext_id,
            $p['nome_modello'],
            $p['brand'] ?? null,
            $p['categoria'] ?? null,
            $p['prezzo'],
            $p['descrizione_lunga'] ?? null,
            json_encode($p['caratteristiche_tecniche'] ?? []),
            json_encode($p['varianti'] ?? []),
            $immagine
        ]);
    }

    echo "Migrazione SQLite completata con successo!\n";

} catch (Exception $e) {
    die("Errore: " . $e->getMessage());
}
?>