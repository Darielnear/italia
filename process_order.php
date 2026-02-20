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
    $nome      = htmlspecialchars($_POST['nome']);
    $email     = htmlspecialchars($_POST['email']);
    $telefono  = htmlspecialchars($_POST['tel']); 
    $citta     = htmlspecialchars($_POST['citta']);
    $cap       = htmlspecialchars($_POST['cap']);
    $indirizzo = htmlspecialchars($_POST['indirizzo']);
    
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
    // Note: Le total sera mis à jour par le calcul ci-dessous

    // 5. PRÉPARATION DU CONTENU VISUEL DES PRODUITS
    $html_produits = "";
    foreach($products_in_cart as $row) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['prezzo'] * $qty;
        $total_ordine += $subtotal;
        
        // URL de l'image (À adapter avec ton domaine final : https://www.tonsite.it/)
        $img_url = "http://" . $_SERVER['HTTP_HOST'] . "/fin/images/" . $row['immagine_main']; 
        
        $html_produits .= "
        <div style='display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;'>
            <img src='$img_url' width='70' height='50' style='border-radius: 8px; margin-right: 15px; object-fit: cover;'>
            <div style='text-align: left;'>
                <p style='margin: 0; font-weight: bold; font-size: 14px;'>{$row['nome_modello']}</p>
                <p style='margin: 0; font-size: 11px; color: #888;'>Quantità: $qty</p>
                <p style='margin: 0; font-weight: bold; color: #2D5A27;'>€ " . number_format($row['prezzo'], 2, ',', '.') . "</p>
            </div>
        </div>";
    }

    // Mise à jour du total réel en base
    $pdo->prepare("UPDATE orders SET totale = ? WHERE order_number = ?")->execute([$total_ordine, $order_id_display]);

    // 6. ENVOI DES EMAILS
    $mail = new PHPMailer(true);
    try {
        // --- CONFIGURATION SERVEUR ---
        $mail->isSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->SMTPAuth   = true;
        
        // Configuration actuelle (MAILTRAP)
        $mail->Host       = '';
        $mail->Username   = ''; 
        $mail->Password   = ''; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = ;

        $mail->isHTML(true);

        // --- ENVOI 1 : AU CLIENT (FACTURE PREMIUM) ---
        $mail->setFrom('shop@ciclivolante.it', 'Cicli Volante');
        $mail->addAddress($email, $nome);
        $mail->Subject = "Conferma Ordine #$order_id_display - Cicli Volante";
        
        $mail->Body = "
        <div style='background-color: #f8f8f8; padding: 30px; font-family: sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>
                <div style='background-color: #000; padding: 30px; text-align: center;'>
                    <h1 style='color: #fff; margin: 0; font-style: italic; text-transform: uppercase;'>CICLI <span style='color: #2D5A27;'>VOLANTE</span></h1>
                </div>
                <div style='padding: 40px;'>
                    <h2 style='font-size: 18px; text-transform: uppercase; text-align: center;'>Grazie per il tuo acquisto</h2>
                    <p style='text-align: center; color: #666;'>Ecco il riepilogo del tuo ordine effettuato presso il nostro atelier.</p>
                    
                    <div style='margin: 30px 0;'>$html_produits</div>

                    <div style='background-color: #f9f9f9; padding: 20px; border-radius: 12px; text-align: center;'>
                        <span style='font-size: 10px; font-weight: bold; color: #2D5A27; text-transform: uppercase;'>Totale Ordine</span>
                        <p style='margin: 5px 0 0; font-size: 26px; font-weight: 900;'>€ " . number_format($total_ordine, 2, ',', '.') . "</p>
                    </div>
                </div>
                <div style='background-color: #2D5A27; padding: 15px; text-align: center;'>
                    <p style='color: #fff; font-size: 10px; margin: 0; text-transform: uppercase;'>L'atelier della passione ciclistica</p>
                </div>
            </div>
        </div>";
        $mail->send();

        // --- ENVOI 2 : À L'ADMINISTRATION (RÉCAPITULATIF COMPLET) ---
        $mail->clearAddresses();
        $mail->addAddress('ton-mail-pro@domaine.it'); // TON MAIL PRO
        $mail->addCC('votre1@gmail.com');
        $mail->addCC('votre2@gmail.com');
        $mail->Subject = "NOUVELLE COMMANDE - #$order_id_display";
        
        if ($documento_path) $mail->addAttachment($documento_path);

        $mail->Body = "
        <div style='font-family: sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
            <h2 style='color: #2D5A27;'>Détails de la commande à traiter</h2>
            <p><strong>Numéro :</strong> #$order_id_display</p>
            <p><strong>Client :</strong> $nome ($email)</p>
            <p><strong>Tel :</strong> $telefono</p>
            <p><strong>Lieu :</strong> $indirizzo, $cap $citta</p>
            <hr>
            <p><strong>Montant à encaisser :</strong> " . number_format($total_ordine, 2, ',', '.') . " €</p>
            <p><em>Le reçu de paiement est joint à ce mail.</em></p>
        </div>";
        $mail->send();

    } catch (Exception $e) {
        $mail_error = $mail->ErrorInfo;
    }

    $_SESSION['cart'] = [];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
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
            <p class="text-xs text-gray-500 leading-relaxed italic">Fattura inviata al cliente e notifica inviata all'amministrazione. Grazie Dariel!</p>
        </div>
        <a href="index.php" class="block w-full bg-black text-white font-black py-5 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-[#2D5A27] transition-all">Torna alla Home</a>
    </div>
</body>
</html>
<?php 
} else { header('Location: index.php'); } 
?>