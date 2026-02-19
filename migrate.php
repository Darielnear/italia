<?php
// migrate.php - Script di migrazione per Cicli Volante
require_once 'db_config.php';

$json_file = 'products.json';
if (!file_exists($json_file)) {
    die("File products.json non trovato.");
}

$products = json_decode(file_get_contents($json_file), true);

try {
    // Creazione tabelle
    $pdo->exec("DROP TABLE IF EXISTS order_items CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS orders CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS products CASCADE");

    $pdo->exec("CREATE TABLE products (
        id SERIAL PRIMARY KEY,
        external_id INTEGER UNIQUE,
        nome_modello TEXT NOT NULL,
        brand TEXT,
        categoria TEXT,
        prezzo DECIMAL(10,2),
        descrizione_lunga TEXT,
        caratteristiche_tecniche JSONB,
        varianti JSONB,
        immagine_main TEXT
    )");

    $pdo->exec("CREATE TABLE orders (
        id SERIAL PRIMARY KEY,
        customer_name TEXT NOT NULL,
        customer_email TEXT NOT NULL,
        total_amount DECIMAL(10,2),
        tracking_code TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE order_items (
        id SERIAL PRIMARY KEY,
        order_id INTEGER REFERENCES orders(id),
        product_id INTEGER REFERENCES products(id),
        quantity INTEGER,
        price DECIMAL(10,2)
    )");

    // Scannerizza le immagini per trovare l'estensione corretta
    $img_dir = 'public/img/';
    $img_files = [];
    if (is_dir($img_dir)) {
        $files = scandir($img_dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $path_parts = pathinfo($file);
                $img_files[$path_parts['filename']] = $file;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO products 
        (external_id, nome_modello, brand, categoria, prezzo, descrizione_lunga, caratteristiche_tecniche, varianti, immagine_main) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($products as $p) {
        $id = $p['id'];
        $immagine = null;
        
        // Cerca l'immagine corrispondente all'ID (es: "1.jpg", "1.png", "1.webp")
        if (isset($img_files[$id])) {
            $immagine = $img_files[$id];
        }

        $stmt->execute([
            $id,
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

    echo "Migrazione completata con successo!\n";

} catch (PDOException $e) {
    die("Errore durante la migrazione: " . $e->getMessage());
}
?>