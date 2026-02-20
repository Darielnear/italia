<?php
session_start();
require 'db_config.php';

function getProductImage($id) {
    $extensions = ['webp', 'jpg', 'jpeg', 'png', 'JPG', 'PNG', 'WEBP'];
    foreach ($extensions as $ext) {
        if (file_exists("public/img/{$id}.{$ext}")) return "public/img/{$id}.{$ext}";
    }
    return "public/img/placeholder.png"; 
}

$cart_products = [];
$total_general = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $cart_products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white antialiased">

    <?php include 'includes/header.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12 text-gray-900">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <div>
                <h1 class="text-5xl font-black uppercase italic tracking-tighter mb-8">Pagamento</h1>
                
                <form action="process_order.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="nome" placeholder="Nome e Cognome" class="bg-gray-50 p-5 rounded-2xl w-full outline-none border border-transparent focus:border-[#2D5A27] transition-all" required>
                        <input type="text" name="tel" placeholder="Cellulare" class="bg-gray-50 p-5 rounded-2xl w-full outline-none border border-transparent focus:border-[#2D5A27]" required>
                    </div>
                    <input type="email" name="email" placeholder="Email" class="bg-gray-50 p-5 rounded-2xl w-full outline-none border border-transparent focus:border-[#2D5A27]" required>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <input type="text" name="citta" placeholder="Città" class="col-span-2 bg-gray-50 p-5 rounded-2xl w-full outline-none border border-transparent focus:border-[#2D5A27]" required>
                        <input type="text" name="cap" placeholder="CAP" class="bg-gray-50 p-5 rounded-2xl w-full outline-none border border-transparent focus:border-[#2D5A27]" required>
                    </div>
                    <input type="text" name="indirizzo" placeholder="Indirizzo esatto" class="bg-gray-50 p-5 rounded-2xl w-full outline-none border border-transparent focus:border-[#2D5A27]" required>

                    <div class="bg-[#FFD500] p-8 rounded-[2.5rem] shadow-xl text-black border-2 border-yellow-300 relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-[10px] font-black uppercase tracking-widest bg-black text-white px-3 py-1 rounded-full">Postepay Evolution / Bonifico</span>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-bold uppercase opacity-60">Intestatario</p>
                                <p class="text-xl font-black uppercase italic tracking-tight">Cicli Volante</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase opacity-60">IBAN</p>
                                <p class="text-lg font-black font-mono select-all tracking-tighter">IT52 PO35 7601 6010 1000 8072 943</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-black/10">
                                <div>
                                    <p class="text-[9px] font-bold uppercase opacity-60">BIC / SWIFT</p>
                                    <p class="font-black text-sm">BBVAITM2XXX</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase opacity-60">Banca</p>
                                    <p class="font-black text-sm uppercase">BBVA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-blue-50 rounded-2xl border border-blue-100 flex gap-4">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex-shrink-0 flex items-center justify-center font-black italic text-xs">i</span>
                        <p class="text-[10px] text-blue-900 font-bold uppercase leading-relaxed">
                            Paga ora e carica qui sotto lo screenshot o il PDF della ricevuta.
                        </p>
                    </div>

                    <div class="relative">
                        <input type="file" name="documento" id="file-upload" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required>
                        <label for="file-upload" class="flex items-center justify-between bg-gray-50 border-2 border-dashed border-gray-200 p-4 rounded-2xl cursor-pointer hover:border-[#2D5A27] transition-all group">
                            <span id="file-name" class="text-[10px] font-black text-gray-400 uppercase">Seleziona la ricevuta</span>
                            <span class="bg-black text-white text-[10px] font-black px-6 py-3 rounded-xl uppercase group-hover:bg-[#2D5A27] transition-colors">Sfoglia</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-black py-7 rounded-[2rem] uppercase tracking-[0.2em] hover:bg-[#2D5A27] transition-all shadow-2xl">
                        Conferma Ordine
                    </button>
                </form>
            </div>

            <div class="lg:sticky lg:top-40 h-fit">
                <div class="bg-gray-50 rounded-[3rem] p-10 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-black uppercase italic mb-8 tracking-tighter">Riepilogo Ordine</h2>
                    <div id="cart-container" class="space-y-6">
                        <?php if (empty($cart_products)): ?>
                            <p class="text-gray-400 font-black italic uppercase text-xs">Carrello vuoto.</p>
                        <?php else: ?>
                            <?php foreach ($cart_products as $p): 
                                $qty = $_SESSION['cart'][$p['id']];
                                $total_general += ($p['prezzo'] * $qty);
                            ?>
                            <div class="flex items-center gap-5 bg-white p-4 rounded-3xl shadow-sm border border-transparent transition-all" id="item-<?= $p['id'] ?>">
                                <div class="w-20 h-20 bg-gray-50 rounded-2xl p-2 flex-shrink-0">
                                    <img src="<?= getProductImage($p['id']) ?>" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-black uppercase text-[11px] leading-tight"><?= $p['nome_modello'] ?></h3>
                                        <button onclick="updateQty(<?= $p['id'] ?>, 'remove')" class="text-gray-300 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </div>
                                    <div class="flex justify-between items-end mt-2">
                                        <div class="flex items-center gap-3 bg-gray-100 rounded-lg px-2 py-1">
                                            <button onclick="updateQty(<?= $p['id'] ?>, -1)" class="text-[10px] font-black hover:text-[#2D5A27]">-</button>
                                            <span class="text-[10px] font-black qty-val"><?= $qty ?></span>
                                            <button onclick="updateQty(<?= $p['id'] ?>, 1)" class="text-[10px] font-black hover:text-[#2D5A27]">+</button>
                                        </div>
                                        <span class="font-black italic text-[#2D5A27]">€ <?= number_format($p['prezzo'], 0, '', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between text-[10px] font-black uppercase text-gray-400">
                            <span>Subtotale</span>
                            <span class="text-black italic">€ <span id="subtotal"><?= number_format($total_general, 0, '', '.') ?></span></span>
                        </div>
                        <div class="flex justify-between text-[10px] font-black uppercase text-gray-400">
                            <span>Spedizione</span>
                            <span class="text-[#2D5A27] italic font-bold">Gratuita</span>
                        </div>
                        <div class="pt-6 mt-4 border-t-2 border-dashed border-gray-200 flex justify-between items-center">
                            <span class="text-3xl font-black uppercase italic tracking-tighter">Totale</span>
                            <span class="text-3xl font-black text-[#2D5A27]">€ <span id="total-final"><?= number_format($total_general, 0, '', '.') ?></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    async function updateQty(id, action) {
        const formData = new FormData();
        formData.append('id', id);
        
        if(action === 'remove') {
            formData.append('remove', true);
        } else {
            formData.append('delta', action);
        }

        const response = await fetch('update_cart.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if(data.success) {
            // Recharger la page pour recalculer les prix proprement ou modifier le DOM ici
            // Pour un rendu parfait sans bug de calcul, on recharge :
            window.location.reload();
        }
    }

    document.getElementById('file-upload').addEventListener('change', function(e){
        if(e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            const display = document.getElementById('file-name');
            display.innerText = fileName;
            display.classList.remove('text-gray-400');
            display.classList.add('text-black');
        }
    });
    </script>
</body>
</html>