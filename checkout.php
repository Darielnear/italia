<?php
require 'db_config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $indirizzo = $_POST['indirizzo'] ?? '';
        $cart = json_decode($_POST['cart_data'], true);
        $total = 0;

        if (empty($cart)) throw new Exception("Il carrello è vuoto.");
        
        // Calculate total securely
        foreach ($cart as $item) {
            $stmt = $db->prepare("SELECT prezzo FROM products WHERE id = ?");
            $stmt->execute([$item['id']]);
            $product = $stmt->fetch();
            if ($product) {
                $total += $product['prezzo'] * $item['quantity'];
            }
        }

        // Handle File Upload
        if (!isset($_FILES['proof']) || $_FILES['proof']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Carica lo screenshot del bonifico.");
        }

        $uploadDir = 'uploads/';
        $fileName = uniqid() . '_' . basename($_FILES['proof']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['proof']['tmp_name'], $uploadFile)) {
            throw new Exception("Errore nel caricamento del file.");
        }

        // Save Order
        $db->beginTransaction();

        $stmt = $db->prepare("INSERT INTO orders (nome_cliente, email_cliente, indirizzo_spedizione, totale, prova_pagamento) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $indirizzo, $total, $uploadFile]);
        $orderId = $db->lastInsertId();

        $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_id, quantita, prezzo_unitario, variante_scelta) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($cart as $item) {
            // Fetch price again for item record
            $stmtPrice = $db->prepare("SELECT prezzo FROM products WHERE id = ?");
            $stmtPrice->execute([$item['id']]);
            $prod = $stmtPrice->fetch();

            $stmtItem->execute([
                $orderId,
                $item['id'],
                $item['quantity'],
                $prod['prezzo'],
                $item['variant']
            ]);
        }

        $db->commit();

        // Send Emails
        $to = $email;
        $subject = "Conferma Ordine #$orderId - Cicli Volante";
        $message = "Ciao $nome,\n\nGrazie per il tuo ordine su Cicli Volante.\n\nAbbiamo ricevuto la tua richiesta e la prova di pagamento. Stiamo verificando i dati.\n\nTotale: €" . number_format($total / 100, 2, ',', '.') . "\n\nA presto,\nIl team Cicli Volante";
        $headers = "From: no-reply@ciclivolante.it\r\n";
        
        // Send to customer
        mail($to, $subject, $message, $headers);

        // Send to admin
        $adminEmail = "admin@ciclivolante.it";
        $adminSubject = "Nuovo Ordine #$orderId - Pagamento Caricato";
        $adminMessage = "Nuovo ordine da $nome ($email).\nTotale: €" . number_format($total / 100, 2, ',', '.') . "\n\nLa prova di pagamento è stata caricata in: $uploadFile";
        mail($adminEmail, $adminSubject, $adminMessage, $headers);
        
        // Redirect to thank you
        header('Location: thank-you.php');
        exit;

    } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();
        $error = $e->getMessage();
    }
}
?>
<?php include 'includes/header.php'; ?>

<main class="pt-32 pb-24 max-w-3xl mx-auto px-6">
    <h1 class="text-3xl font-bold mb-10 text-center">Checkout</h1>

    <?php if ($error): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div id="checkout-container" class="grid gap-8">
        <!-- Cart Items (JS populated) -->
        <div class="bg-white p-8 rounded-2xl smooth-shadow">
            <h2 class="text-xl font-bold mb-6">Riepilogo Ordine</h2>
            <div id="cart-items-list" class="space-y-6">
                <!-- JS will inject items here -->
                <div class="text-center text-gray-400 py-4">Caricamento carrello...</div>
            </div>
            <div class="border-t border-gray-100 mt-6 pt-6 flex justify-between items-center font-bold text-xl">
                <span>Totale</span>
                <span id="cart-total">€0,00</span>
            </div>
        </div>

        <!-- Checkout Form -->
        <form action="checkout.php" method="POST" enctype="multipart/form-data" class="space-y-8" id="checkout-form">
            <input type="hidden" name="cart_data" id="cart-data-input">
            
            <!-- Customer Details -->
            <div class="bg-white p-8 rounded-2xl smooth-shadow space-y-6">
                <h2 class="text-xl font-bold mb-4">I tuoi dati</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                        <input type="text" name="nome" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Indirizzo di Spedizione</label>
                        <textarea name="indirizzo" required rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="bg-white p-8 rounded-2xl smooth-shadow space-y-6">
                <h2 class="text-xl font-bold mb-4">Pagamento</h2>
                
                <!-- Bank Details Box -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 font-mono text-sm space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Beneficiario:</span>
                        <span class="font-bold">Cicli Volante</span>
                    </div>
                    <div class="flex justify-between flex-wrap gap-2">
                        <span class="text-gray-500">IBAN:</span>
                        <span class="font-bold break-all">IT52 PO35 7601 6010 1000 8072 943</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Banca:</span>
                        <span class="font-bold">BBVA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">BIC:</span>
                        <span class="font-bold">BBVAITM2XXX</span>
                    </div>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Carica lo screenshot del bonifico effettuato <span class="text-red-500">*</span></label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-accent transition-colors cursor-pointer group">
                        <input type="file" name="proof" id="proof" required accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName(this)">
                        <div class="space-y-2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-400 group-hover:text-accent"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <div class="text-sm text-gray-500" id="file-name">Trascina il file o clicca per caricare</div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-accent text-white font-bold py-4 rounded-2xl hover:bg-green-600 transition-colors duration-300 shadow-lg hover:shadow-xl transform active:scale-[0.98]">
                Conferma Ordine
            </button>
        </form>
    </div>
</main>

<script>
    // Load Cart logic
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartList = document.getElementById('cart-items-list');
    const totalEl = document.getElementById('cart-total');
    const inputData = document.getElementById('cart-data-input');

    if (cart.length === 0) {
        document.getElementById('checkout-container').innerHTML = `
            <div class="text-center py-20">
                <h2 class="text-2xl font-bold mb-4">Il carrello è vuoto</h2>
                <a href="index.php" class="text-accent hover:underline">Torna allo shopping</a>
            </div>
        `;
    } else {
        inputData.value = JSON.stringify(cart);
        
        let html = '';
        let total = 0;
        
        cart.forEach(item => {
            total += item.price * item.quantity;
            html += `
                <div class="flex gap-4 items-center">
                    <div class="flex-1">
                        <div class="font-bold text-anthracite">${item.name}</div>
                        <div class="text-sm text-gray-500">Colore: ${item.variant} | Qtà: ${item.quantity}</div>
                    </div>
                    <div class="font-semibold">€${(item.price * item.quantity / 100).toLocaleString('it-IT', {minimumFractionDigits: 2})}</div>
                </div>
            `;
        });
        
        cartList.innerHTML = html;
        totalEl.textContent = '€' + (total / 100).toLocaleString('it-IT', {minimumFractionDigits: 2});
    }

    function updateFileName(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-name').textContent = input.files[0].name;
            document.getElementById('file-name').classList.add('text-accent', 'font-medium');
        }
    }
</script>

<?php include 'includes/footer.php'; ?>
