<?php
session_start();
require 'db_config.php';

// --- RÉACTIVATION DE PHPMAILER ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    
    // --- 1. GÉNÉRATION DU NUMÉRO DE COMMANDE ---
    $order_id_display = "VOL-" . date("Y") . "-" . strtoupper(bin2hex(random_bytes(3)));

    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['tel']); 
    $citta = htmlspecialchars($_POST['citta']);
    $cap = htmlspecialchars($_POST['cap']);
    $indirizzo = htmlspecialchars($_POST['indirizzo']);
    
    // --- 2. GESTION DU FICHIER (Upload) ---
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

    // --- 3. CALCUL DU TOTAL ---
    $total_ordine = 0;
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, nome_modello, prezzo FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    
    foreach($products as $row) {
        $qty = $_SESSION['cart'][$row['id']];
        $total_ordine += ($row['prezzo'] * $qty);
    }

    // --- 4. SAUVEGARDE DANS MYSQL ---
    try {
        $sql = "INSERT INTO orders (order_number, nome, email, tel, citta, cap, indirizzo, totale, ricevuta_path, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'In Elaborazione', NOW())";
        $stmt_save = $pdo->prepare($sql);
        $stmt_save->execute([$order_id_display, $nome, $email, $telefono, $citta, $cap, $indirizzo, $total_ordine, $documento_url]);
    } catch (Exception $e) {
        die("Errore Database: " . $e->getMessage());
    }

    // --- 5. ENVOI DE L'EMAIL VIA MAILTRAP ---
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '85b4ee1bfb95bb'; 
        $mail->Password   = '20d4741da0e81d'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 2525;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('shop@ciclivolante.it', 'Cicli Volante');
        $mail->addAddress($email, $nome);

        if ($documento_path && file_exists($documento_path)) {
            $mail->addAttachment($documento_path);
        }

        // --- DESIGN PREMIUM DU MAIL ---
        $mail->isHTML(true);
        $mail->Subject = "Conferma Ordine #$order_id_display - Cicli Volante";
        
        $mail->Body = "
        <div style='background-color: #f4f4f4; padding: 40px 0; font-family: sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);'>
                
                <div style='background-color: #2D5A27; padding: 30px; text-align: center;'>
                    <h1 style='color: #ffffff; margin: 0; font-style: italic; font-weight: 900; letter-spacing: -1px; text-transform: uppercase;'>CICLI <span style='color: #A3D133;'>VOLANTE</span></h1>
                </div>
                
                <div style='padding: 40px;'>
                    <h2 style='color: #333333; text-transform: uppercase; font-size: 18px; border-bottom: 2px solid #2D5A27; padding-bottom: 10px; margin-top: 0;'>Grazie $nome!</h2>
                    <p style='color: #666666; line-height: 1.6;'>Il tuo ordine <strong style='color: #2D5A27;'>#$order_id_display</strong> è stato registrato con successo nel nostro atelier.</p>
                    
                    <div style='background-color: #f9f9f9; padding: 25px; border-radius: 8px; margin: 25px 0; border-left: 4px solid #2D5A27;'>
                        <p style='margin: 0; font-size: 12px; color: #888; text-transform: uppercase; font-weight: bold;'>Totale dell'ordine</p>
                        <p style='margin: 5px 0 0 0; font-size: 28px; font-weight: 900; color: #333;'>€ " . number_format($total_ordine, 2, ',', '.') . "</p>
                    </div>

                    <p style='color: #666666; line-height: 1.6;'>Abbiamo ricevuto la tua ricevuta di pagamento. Il nostro team verificherà il documento e ti invierà una notifica non appena il tuo pacco sarà pronto per la spedizione.</p>
                    
                    <div style='text-align: center; margin-top: 30px;'>
                        <a href='http://localhost/fin/index.php' style='display: inline-block; background-color: #000000; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 13px; text-transform: uppercase;'>Torna al Sito</a>
                    </div>
                </div>

                <div style='background-color: #f4f4f4; padding: 20px; text-align: center;'>
                    <p style='font-size: 11px; color: #999999; margin: 0;'>Cicli Volante - Atelier de vélos d'exception<br>Messaggio generato automaticamente, si prega di non rispondere.</p>
                </div>
            </div>
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
    <title>Conferma Ordine | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex items-center justify-center min-h-screen text-center px-6">
    <div class="max-w-md w-full">
        <div class="mb-8 flex justify-center">
            <div class="bg-[#2D5A27] p-5 rounded-full shadow-2xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
        </div>
        <h1 class="text-4xl font-black uppercase italic mb-2 tracking-tighter">Grazie!</h1>
        <p class="text-gray-400 font-bold uppercase text-[10px] mb-8 tracking-widest">Ordine #<?= $order_id_display ?> completato</p>
        
        <?php if(isset($mail_error)): ?>
            <div class="bg-red-50 p-4 rounded-xl text-red-600 text-xs mb-4">
                <strong>Errore Email:</strong> <?= $mail_error ?>
            </div>
        <?php else: ?>
            <div class="bg-green-50 p-4 rounded-xl text-green-600 text-[10px] mb-4 font-black uppercase tracking-widest border border-green-100">
                Email di conferma inviata con successo!
            </div>
        <?php endif; ?>

        <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100 text-left mb-8">
            <p class="text-xs text-gray-500 leading-relaxed text-center">
                I tuoi dati sono stati registrati. Riceverai presto aggiornamenti via email sulla spedizione della tua Cicli Volante.
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