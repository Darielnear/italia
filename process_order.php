<?php
session_start();
require 'db_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    
    // --- 1. GÉNÉRATION DU NUMÉRO DE COMMANDE UNIQUE ---
    $order_id_display = "VOL-" . date("Y") . "-" . strtoupper(bin2hex(random_bytes(3)));

    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['tel']); 
    $citta = htmlspecialchars($_POST['citta']);
    $cap = htmlspecialchars($_POST['cap']);
    $indirizzo = htmlspecialchars($_POST['indirizzo']);
    
    // --- 2. GESTION DU FICHIER ---
    $documento_url = "Nessun file";
    $documento_path = ""; 
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === 0) {
        $upload_dir = 'uploads/payments/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $extension = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
        $filename = "ricevuta_" . $order_id_display . "." . $extension; // Nom propre avec le N° de commande
        
        if (move_uploaded_file($_FILES['documento']['tmp_name'], $upload_dir . $filename)) {
            $documento_url = $upload_dir . $filename;
            $documento_path = realpath($documento_url);
        }
    }

    // --- 3. CALCUL DU TOTAL ET RÉCAPITULATIF ---
    $total_ordine = 0;
    $cart_details_html = "";
    
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, nome_modello, prezzo FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    
    $cart_details_html = '<table style="width:100%; border-collapse:collapse; font-family:sans-serif;">';
    foreach($products as $row) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['prezzo'] * $qty;
        $total_ordine += $subtotal;
        
        $cart_details_html .= "
            <tr>
                <td style='padding:10px; border-bottom:1px solid #eee;'><b>{$row['nome_modello']}</b> (x{$qty})</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:right;'>€ " . number_format($subtotal, 0, '', '.') . "</td>
            </tr>";
    }
    $cart_details_html .= "</table>";

    // --- 4. SAUVEGARDE EN BASE DE DONNÉES (IMPORTANT POUR LE SUIVI) ---
    try {
        $stmt_save = $pdo->prepare("INSERT INTO orders (order_number, nome, email, tel, citta, cap, indirizzo, totale, ricevuta_path, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'In Elaborazione', NOW())");
        $stmt_save->execute([$order_id_display, $nome, $email, $telefono, $citta, $cap, $indirizzo, $total_ordine, $documento_url]);
    } catch (Exception $e) {
        // Optionnel : logger l'erreur
    }

    // --- 5. ENVOI DES EMAILS ---
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.ton-hebergeur.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ton-email@domaine.com';    
        $mail->Password   = 'ton-mot-de-passe';         
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('ton-email@domaine.com', 'Cicli Volante');
        $mail->addAddress($email, $nome);
        $mail->addAddress('ton-premier-mail@gmail.com');

        if ($documento_path) {
            $mail->addAttachment($documento_path, 'Ricevuta_Pagamento.'.$extension);
        }

        $mail->isHTML(true);
        $mail->Subject = "Conferma Ordine #$order_id_display - Cicli Volante"; // Sujet avec le vrai numéro
        
        $mail->Body = "
        <div style='max-width:600px; margin:auto; border:1px solid #eee; padding:40px; font-family:sans-serif;'>
            <center><h1 style='font-style:italic; font-weight:900;'>CICLI <span style='color:#2D5A27;'>VOLANTE</span></h1></center>
            <h2 style='text-transform:uppercase; border-bottom:4px solid #2D5A27; padding-bottom:10px;'>Ordine Confermato</h2>
            <p>Grazie <b>$nome</b>, il tuo ordine <b>#$order_id_display</b> è stato registrato.</p>
            
            <div style='background:#f9f9f9; padding:20px; border-radius:10px; margin:20px 0;'>
                <p style='font-size:10px; font-weight:bold; text-transform:uppercase; color:#2D5A27; margin:0;'>Spedito a:</p>
                <p style='margin:5px 0;'>$nome<br>$indirizzo<br>$cap $citta</p>
            </div>

            <h3>Dettagli:</h3>
            $cart_details_html
            <h2 style='text-align:right; font-style:italic;'>TOTALE: € " . number_format($total_ordine, 0, '', '.') . "</h2>
            <hr style='border:none; border-top:1px solid #eee; margin:30px 0;'>
            <p style='font-size:11px; color:#999; text-align:center;'>Puoi tracciare il tuo ordine sul sito con il codice: <b>$order_id_display</b></p>
        </div>";

        $mail->send();
    } catch (Exception $e) {}

    // Vider le panier
    $_SESSION['cart'] = [];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Conferma Ordine | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen text-center px-6">
    <div class="max-w-md w-full">
        <div class="mb-8 flex justify-center">
            <div class="bg-[#2D5A27] p-5 rounded-full shadow-2xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
        </div>
        
        <h1 class="text-4xl font-black uppercase italic tracking-tighter mb-2">Grazie!</h1>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mb-8">Ordine #<?= $order_id_display ?> ricevuto</p>
        
        <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100 text-left mb-8">
            <p class="text-[10px] font-black uppercase text-[#2D5A27] mb-4 italic">Cosa succede ora?</p>
            <p class="text-xs text-gray-600 leading-relaxed">
                Abbiamo inviato una conferma a <b><?= $email ?></b>. Verificheremo la ricevuta entro 24h e ti invieremo il codice tracking non appena la tua Cicli Volante lascerà l'atelier.
            </p>
        </div>

        <a href="index.php" class="block w-full bg-black text-white font-black px-10 py-5 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-[#2D5A27] transition-all">
            Torna alla Home
        </a>
    </div>
</body>
</html>
<?php 
} else { header('Location: index.php'); } 
?>