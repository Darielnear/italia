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
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        @media screen and (max-width: 768px) {
            input, select, textarea { font-size: 16px !important; }
        }
        /* Style des inputs focus */
        .input-elite:focus { border-color: #2D5A27; background-color: white; box-shadow: 0 4px 20px rgba(45, 90, 39, 0.08); }
    </style>
</head>
<body class="bg-white antialiased">

    <?php include 'includes/header.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-8 md:py-12 text-gray-900">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
            
            <div class="order-2 lg:order-1">
                <h1 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter mb-8 text-gray-900">Pagamento</h1>
                
                <form action="process_order.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] ml-2">Intestatario dell'ordine</label>
                        <input type="text" name="nome" placeholder="Es: Mario Rossi" 
                               class="input-elite bg-gray-50 p-5 rounded-2xl w-full outline-none border border-gray-100 transition-all font-bold italic" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] ml-2">Contatto Telefonico</label>
                            <input type="tel" name="tel" placeholder="+39 333 123 4567" 
                                   class="input-elite bg-gray-50 p-5 rounded-2xl w-full outline-none border border-gray-100 transition-all font-bold italic" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] ml-2">Indirizzo Email</label>
                            <input type="email" name="email" placeholder="mario.rossi@esempio.it" 
                                   class="input-elite bg-gray-50 p-5 rounded-2xl w-full outline-none border border-gray-100 transition-all font-bold italic" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] ml-2">Città</label>
                            <input type="text" name="citta" placeholder="Es: Milano (MI)" 
                                   class="input-elite bg-gray-50 p-5 rounded-2xl w-full outline-none border border-gray-100 transition-all font-bold italic" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] ml-2">C.A.P.</label>
                            <input type="text" name="cap" placeholder="00100" maxlength="5" pattern="[0-9]{5}"
                                   class="input-elite bg-gray-50 p-5 rounded-2xl w-full outline-none border border-gray-100 transition-all font-bold italic text-center" required>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] ml-2">Indirizzo di Consegna</label>
                        <input type="text" name="indirizzo" placeholder="Via, Piazza o Corso, civico, interno" 
                               class="input-elite bg-gray-50 p-5 rounded-2xl w-full outline-none border border-gray-100 transition-all font-bold italic" required>
                    </div>

                    <div class="bg-[#FFD500] p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-xl text-black border-2 border-yellow-300 relative overflow-hidden mt-8">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-[8px] md:text-[10px] font-black uppercase tracking-widest bg-black text-white px-3 py-1 rounded-full italic">Metodo: Bonifico o Ricarica Postepay</span>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-bold uppercase opacity-60">Intestatario</p>
                                <p class="text-lg md:text-xl font-black uppercase italic tracking-tight">Cicli Volante</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase opacity-60">IBAN / Numero Carta</p>
                                <p class="text-sm md:text-lg font-black font-mono select-all tracking-tighter break-all">IT52 PO35 7601 6010 1000 8072 943</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-black/10">
                                <div>
                                    <p class="text-[9px] font-bold uppercase opacity-60">BIC / SWIFT</p>
                                    <p class="font-black text-xs md:text-sm">BBVAITM2XXX</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase opacity-60">Banca</p>
                                    <p class="font-black text-xs md:text-sm uppercase italic">BBVA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-blue-50 rounded-2xl border border-blue-100 flex gap-4">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex-shrink-0 flex items-center justify-center font-black italic text-xs">i</span>
                        <p class="text-[10px] text-blue-900 font-bold uppercase leading-relaxed">
                            Effettua il pagamento e carica lo screenshot della ricevuta per confermare immediatamente la spedizione.
                        </p>
                    </div>

                    <div class="relative">
                        <input type="file" name="documento" id="file-upload" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required>
                        <label for="file-upload" class="flex flex-col md:flex-row items-center justify-between bg-white border-2 border-dashed border-gray-200 p-4 rounded-2xl cursor-pointer hover:border-[#2D5A27] hover:bg-gray-50 transition-all group gap-4">
                            <span id="file-name" class="text-[10px] font-black text-gray-400 uppercase text-center md:text-left tracking-widest italic">Carica Ricevuta (JPG, PNG, PDF)</span>
                            <span class="w-full md:w-auto text-center bg-black text-white text-[10px] font-black px-6 py-3 rounded-xl uppercase group-hover:bg-[#2D5A27] transition-colors shadow-lg">Sfoglia</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-black py-6 md:py-8 rounded-[1.5rem] md:rounded-[2rem] uppercase tracking-[0.2em] hover:bg-[#2D5A27] transition-all shadow-2xl active:scale-95 text-xs md:text-sm italic">
                        Conferma Ordine e Invia
                    </button>
                </form>
            </div>

            <div class="order-1 lg:order-2 lg:sticky lg:top-40 h-fit">
                <div class="bg-gray-50 rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 border border-gray-100 shadow-sm">
                    <h2 class="text-xl md:text-2xl font-black uppercase italic mb-6 md:mb-8 tracking-tighter">Riepilogo Ordine</h2>
                    <div id="cart-container" class="space-y-4">
                        <?php if (empty($cart_products)): ?>
                            <p class="text-gray-400 font-black italic uppercase text-xs">Carrello vuoto.</p>
                        <?php else: ?>
                            <?php foreach ($cart_products as $p): 
                                $qty = $_SESSION['cart'][$p['id']];
                                $total_general += ($p['prezzo'] * $qty);
                            ?>
                            <div class="flex items-center gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100" id="item-<?= $p['id'] ?>">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl p-2 flex-shrink-0">
                                    <img src="<?= getProductImage($p['id']) ?>" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-black uppercase text-[10px] leading-tight pr-2"><?= $p['nome_modello'] ?></h3>
                                    </div>
                                    <div class="flex justify-between items-end mt-1">
                                        <span class="text-[10px] font-black text-gray-400">Qtà: <?= $qty ?></span>
                                        <span class="font-black italic text-[#2D5A27] text-sm">€ <?= number_format($p['prezzo'], 0, '', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200 space-y-4">
                        <div class="flex justify-between text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            <span>Subtotale</span>
                            <span class="text-black italic">€ <?= number_format($total_general, 0, '', '.') ?></span>
                        </div>
                        <div class="flex justify-between text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            <span>Spedizione Express</span>
                            <span class="text-[#2D5A27] italic font-bold">Gratuita</span>
                        </div>
                        <div class="pt-6 mt-4 border-t-2 border-dashed border-gray-200 flex justify-between items-center">
                            <span class="text-3xl font-black uppercase italic tracking-tighter text-gray-900">Totale</span>
                            <span class="text-3xl font-black text-[#2D5A27]">€ <?= number_format($total_general, 0, '', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Ajout automatique du +39 au focus sur le champ téléphone
    const phoneInput = document.querySelector('input[name="tel"]');
    phoneInput.addEventListener('focus', function(e) {
        if(!e.target.value) e.target.value = '+39 ';
    });

    document.getElementById('file-upload').addEventListener('change', function(e){
        if(e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            const display = document.getElementById('file-name');
            display.innerText = "File caricato: " + fileName;
            display.classList.remove('text-gray-400');
            display.classList.add('text-[#2D5A27]', 'font-bold');
        }
    });
    </script>
</body>
</html>