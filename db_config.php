<?php
// Paramètres de connexion MySQL
$host = 'localhost';
$db   = 'italia'; // Assure-toi que c'est le nom exact créé dans phpMyAdmin
$user = 'root';        // Utilisateur par defaut (XAMPP/WAMP)
$pass = '';            // Laisse vide sur Windows, mets 'root' si tu es sur Mac (MAMP)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lance une erreur si un truc cloche
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les données sous forme de tableau propre
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Utilise les vraies requêtes préparées (plus sécure)
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Si la connexion échoue, on affiche l'erreur
     die("Errore di connessione a MySQL: " . $e->getMessage());
}