<?php
require 'db_config.php';

// 1. Lire le fichier JSON
$jsonData = file_get_contents('products.json');
$products = json_decode($jsonData, true);

if (!$products) {
    die("Erreur : Impossible de lire le fichier JSON.");
}

$count = 0;

foreach ($products as $p) {
    $id = $p['id'];
    $desc = $p['descrizione_lunga'];
    // On transforme le tableau des specs en chaîne JSON pour la base de données
    $specs = json_encode($p['caratteristiche_tecniche'], JSON_UNESCAPED_UNICODE);

    // 2. Préparer la requête SQL : on ne modifie QUE descrizione et specifiche_tecniche
    // On utilise WHERE id = ? pour être sûr de modifier le bon produit
    $stmt = $pdo->prepare("UPDATE products SET descrizione = ?, specifiche_tecniche = ? WHERE id = ?");
    
    if ($stmt->execute([$desc, $specs, $id])) {
        // On vérifie si une ligne a effectivement été modifiée
        if ($stmt->rowCount() > 0) {
            echo "✅ Produit $id mis à jour : " . htmlspecialchars($p['nome_modello']) . "<br>";
            $count++;
        } else {
            echo "ℹ️ Produit $id : Aucune modification (données déjà présentes ou ID inconnu).<br>";
        }
    }
}

echo "<br><b>Opération terminée. $count produits ont été enrichis avec les données du JSON.</b>";