<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// On chargera PHPMailer ici (via require 'vendor/autoload.php' ou manuellement)
// Pour l'instant, on prépare la logique.

require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. RÉCUPÉRATION DES DONNÉES
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $city = htmlspecialchars($_POST['city']);
    $zip = htmlspecialchars($_POST['zip']);
    $address = htmlspecialchars($_POST['address']);
    $color = htmlspecialchars($_POST['color']);
    $product_id = (int)$_POST['product_id'];
    
    // Génération d'un numéro de commande unique
    $order_ref = "ITA-" . strtoupper(substr(md5(uniqid()), 0, 8));

    // 2. GESTION DU FICHIER (REÇU)
    $upload_dir = "uploads/";
    $file = $_FILES['receipt'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
    
    if (!in_array($file_ext, $allowed)) {
        die("Errore: Estensione file non permessa.");
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        die("Errore: Il file è troppo grande.");
    }

    $new_file_name = $order_ref . "." . $file_ext;
    $dest_path = $upload_dir . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $dest_path)) {
        
        // 3. RÉCUPÉRATION INFOS PRODUIT POUR LE MAIL
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        // 4. PRÉPARATION DU MAIL (DESIGN)
        $subject = "Conferma Ordine #$order_ref - Cicli Volante";
        
        // Corps du mail en HTML avec ton design
        $mail_body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee;'>
            <div style='background: #1a1a1a; padding: 40px; text-align: center;'>
                <h1 style='color: #ffffff; margin: 0; letter-spacing: 4px;'>CICLI VOLANTE</h1>
            </div>
            <div style='padding: 30px;'>
                <h2 style='color: #333;'>Grazie per il tuo ordine, $name!</h2>
                <p style='color: #666;'>Abbiamo ricevuto la tua prova di pagamento. La tua bicicletta è ora in fase di preparazione.</p>
                
                <div style='background: #f9f9f9; padding: 20px; border-radius: 10px; margin: 20px 0;'>
                    <h3 style='margin-top: 0;'>Dettagli Ordine:</h3>
                    <p><strong>Rif. Ordine:</strong> $order_ref</p>
                    <p><strong>Modello:</strong> {$product['nome_modello']}</p>
                    <p><strong>Colore:</strong> $color</p>
                    <p><strong>Prezzo:</strong> € " . number_format($product['prezzo'], 2, ',', '.') . "</p>
                </div>

                <div style='margin: 20px 0;'>
                    <h3 style='margin-top: 0;'>Indirizzo di Spedizione:</h3>
                    <p>$address<br>$zip, $city<br>Italia</p>
                    <p><strong>Telefono:</strong> $phone</p>
                </div>

                <p style='font-size: 12px; color: #999; border-top: 1px solid #eee; pt-20;'>
                    Riceverai il codice di tracciamento (tracking) non appena il corriere prenderà in carico il pacco.
                </p>
            </div>
            <div style='background: #f1f1f1; padding: 20px; text-align: center; font-size: 12px; color: #777;'>
                Cicli Volante S.R.L - Italia
            </div>
        </div>
        ";

        // 5. ENVOI VIA PHPMAILER (À CONFIGURER PLUS TARD)
        /*
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.tonhebergement.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'votre-mail-pro@ciclivolante.it';
            $mail->Password   = 'votre-mot-de-passe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('votre-mail-pro@ciclivolante.it', 'Cicli Volante');
            $mail->addAddress($email, $name); // Au client
            $mail->addAddress('admin1@votre-gestion.it'); // Copie 1
            $mail->addAddress('admin2@votre-gestion.it'); // Copie 2
            $mail->addReplyTo('votre-mail-pro@ciclivolante.it', 'Informazioni');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $mail_body;
            $mail->addAttachment($dest_path); // On joint la preuve de paiement

            $mail->send();
            header("Location: thank-you.php?ref=$order_ref");
        } catch (Exception $e) {
            echo "Errore nell'invio della mail: {$mail->ErrorInfo}";
        }
        */
        
        // Pour tester SANS SMTP pour l'instant :
        header("Location: thank-you.php?ref=$order_ref");
        exit;

    } else {
        echo "Errore durante l'upload del file.";
    }
}
?>