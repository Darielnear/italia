<?php
session_start();
require 'db_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    
    // 1. DONNÉES DE LA COMMANDE
    $order_id_display = "VOL-" . date("Y") . "-" . strtoupper(bin2hex(random_bytes(3)));
    $nome      = htmlspecialchars($_POST['nome'] ?? '');
    $email     = htmlspecialchars($_POST['email'] ?? '');
    $telefono  = htmlspecialchars($_POST['tel'] ?? ''); 
    $citta     = htmlspecialchars($_POST['citta'] ?? '');
    $cap       = htmlspecialchars($_POST['cap'] ?? '');
    $indirizzo = htmlspecialchars($_POST['indirizzo'] ?? '');
    
    // 2. GESTION DU REÇU (UPLOAD)
    $documento_url = "Nessun file";
    $documento_path = ""; 
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === 0) {
        $upload_dir = 'uploads/payments/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $extension = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
        $filename = "ricevuta_" . $order_id_display . "." . $extension; 
        if (move_uploaded_file($_FILES['documento']['tmp_name'], $upload_dir . $filename)) {
            $documento_url = $upload_dir . $filename;
            $documento_path = realpath($documento_url);
        }
    }

    // 3. CALCUL DU TOTAL ET RÉCUPÉRATION DES PRODUITS
    $total_ordine = 0;
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, nome_modello, prezzo, immagine_main FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products_in_cart = $stmt->fetchAll();

    // 4. SAUVEGARDE EN BASE DE DONNÉES
    $sql = "INSERT INTO orders (order_number, nome, email, tel, citta, cap, indirizzo, totale, ricevuta_path, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'In Elaborazione', NOW())";
    $pdo->prepare($sql)->execute([$order_id_display, $nome, $email, $telefono, $citta, $cap, $indirizzo, 0, $documento_url]);

    // 5. PRÉPARATION DU CONTENU VISUEL
    $html_produits = "";
    foreach($products_in_cart as $row) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['prezzo'] * $qty;
        $total_ordine += $subtotal;
        
        $img_url = "http://" . $_SERVER['HTTP_HOST'] . "/fin/images/" . $row['immagine_main']; 
        
        $html_produits .= "
        <div style='display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;'>
            <div style='text-align: left;'>
                <p style='margin: 0; font-weight: bold; font-size: 14px;'>{$row['nome_modello']}</p>
                <p style='margin: 0; font-size: 11px; color: #888;'>Quantità: $qty</p>
                <p style='margin: 0; font-weight: bold; color: #2D5A27;'>€ " . number_format($row['prezzo'], 2, ',', '.') . "</p>
            </div>
        </div>";
    }

    $pdo->prepare("UPDATE orders SET totale = ? WHERE order_number = ?")->execute([$total_ordine, $order_id_display]);

    // 6. ENVOI DES EMAILS (AVEC PROTECTION SI SMTP VIDE)
    $mail_host = ''; // METS TON HOST ICI (ex: sandbox.smtp.mailtrap.io)
    
    if (!empty($mail_host)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet    = 'UTF-8';
            $mail->SMTPAuth   = true;
            $mail->Host       = $mail_host;
            $mail->Username   = ''; // TON USER
            $mail->Password   = ''; // TON PASS
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587; // OU 2525
            $mail->isHTML(true);

            // ENVOI AU CLIENT
            $mail->setFrom('shop@ciclivolante.it', 'Cicli Volante');
            $mail->addAddress($email, $nome);
            $mail->Subject = "Conferma Ordine #$order_id_display - Cicli Volante";
            $mail->Body = "<div style='font-family: sans-serif;'><h1>CICLI VOLANTE</h1><p>Grazie $nome, il tuo ordine è in fase di elaborazione.</p>$html_produits<p><b>Totale: € " . number_format($total_ordine, 2, ',', '.') . "</b></p></div>";
            $mail->send();

            // ENVOI ADMIN
            $mail->clearAddresses();
            $mail->addAddress('admin@ciclivolante.it');
            $mail->Subject = "NUOVA PRENOTAZIONE - #$order_id_display";
            if ($documento_path) $mail->addAttachment($documento_path);
            $mail->Body = "Nuovo ordine da $nome. Totale: $total_ordine €";
            $mail->send();

        } catch (Exception $e) {
            // Log l'erreur si besoin : $e->getMessage();
        }
    }

    // Vider le panier après succès
    $_SESSION['cart'] = [];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex items-center justify-center min-h-screen text-center px-6">
    <div class="max-w-md w-full">
        <div class="bg-[#2D5A27] w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl shadow-[#2D5A27]/20">
            <svg class="text-white w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h1 class="text-4xl font-black uppercase italic tracking-tighter mb-2">Successo!</h1>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mb-10">Ordine #<?= $order_id_display ?></p>
        <div class="bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100 mb-8">
            <p class="text-xs text-gray-500 leading-relaxed italic">La tua prenotazione per l'atelier è stata inviata. Ti ricontatteremo a breve!</p>
        </div>
        <a href="index.php" class="block w-full bg-black text-white font-black py-5 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-[#2D5A27] transition-all">Torna alla Home</a>
    </div>
</body>
</html>
<?php 
} else { header('Location: index.php'); } 
?>