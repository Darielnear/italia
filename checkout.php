<?php
require 'db_config.php';

// 1. Récupération sécurisée des données de la page produit
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$color = isset($_GET['color']) ? htmlspecialchars($_GET['color']) : 'Originale';

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Errore database: " . $e->getMessage());
}

$image_principale = !empty($product['immagine_main']) ? $product['immagine_main'] : 'default.jpg';
$prix_unitaire = $product['prezzo'];
?>

<?php include 'includes/header.php'; ?>

<main class="max-w-7xl mx-auto px-6 py-20 min-h-screen bg-gray-50/30">
    <div class="flex flex-col lg:flex-row gap-12 items-start">
        
        <div class="lg:w-1/3 w-full sticky top-32">
            <div class="bg-white rounded-3xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8 text-center italic">Riepilogo Ordine</h3>
                
                <div class="flex flex-col items-center mb-8">
                    <img src="public/img/<?= htmlspecialchars($image_principale) ?>" 
                         class="w-56 h-56 object-contain mix-blend-multiply drop-shadow-2xl transition-transform hover:scale-105 duration-500">
                    <h2 class="text-2xl font-black text-anthracite mt-4 text-center"><?= htmlspecialchars($product['nome_modello']) ?></h2>
                    <span class="mt-2 px-4 py-1 bg-accent/10 text-accent rounded-full text-xs font-bold uppercase italic">Colore: <?= $color ?></span>
                </div>

                <div class="space-y-4 border-t border-gray-50 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold text-xs uppercase">Quantità</span>
                        <div class="flex items-center gap-4 bg-gray-50 rounded-full px-3 py-1 border border-gray-100">
                            <button type="button" onclick="updateQty(-1)" class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:text-anthracite">-</button>
                            <span id="qty-display" class="font-black text-lg w-4 text-center">1</span>
                            <button type="button" onclick="updateQty(1)" class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:text-anthracite">+</button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-gray-400 font-bold text-xs uppercase">Totale</span>
                        <span id="total-display" class="text-3xl font-black text-anthracite">€ <?= number_format($prix_unitaire, 2, ',', '.') ?></span>
                    </div>
                </div>

                <a href="product.php?id=<?= $id ?>" class="block w-full mt-10 py-3 text-center text-[10px] font-black text-gray-300 hover:text-red-500 transition-colors uppercase tracking-[0.2em]">
                    ✕ Annulla ordine e torna indietro
                </a>
            </div>
        </div>

        <div class="lg:w-2/3 w-full space-y-8">
            
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-yellow-400 p-5 flex items-center justify-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span class="font-black text-yellow-900 uppercase tracking-widest text-sm italic">Guida al Pagamento Postepay / BBVA</span>
                </div>

                <div class="p-8 md:p-10 bg-gradient-to-br from-yellow-50/50 to-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-xl mb-3 border border-yellow-100 group-hover:rotate-12 transition-transform">📱</div>
                            <h4 class="text-[10px] font-black uppercase text-yellow-800">1. Accedi</h4>
                            <p class="text-[11px] text-gray-500 leading-tight">Apri la tua App Postepay o la tua banca</p>
                        </div>
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-xl mb-3 border border-yellow-100 group-hover:rotate-12 transition-transform">💳</div>
                            <h4 class="text-[10px] font-black uppercase text-yellow-800">2. Invia</h4>
                            <p class="text-[11px] text-gray-500 leading-tight">Effettua il bonifico o ricarica Postepay</p>
                        </div>
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-xl mb-3 border border-yellow-100 group-hover:rotate-12 transition-transform">📤</div>
                            <h4 class="text-[10px] font-black uppercase text-yellow-800">3. Conferma</h4>
                            <p class="text-[11px] text-gray-500 leading-tight">Carica la ricevuta nel modulo qui sotto</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-3xl border-2 border-yellow-200 shadow-lg relative">
                        <div class="space-y-6">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Intestatario Beneficiario</span>
                                <span class="text-lg font-bold text-anthracite">Cicli Volante</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 italic text-accent">IBAN per Bonifico o Ricarica Evolution</span>
                                <div class="flex items-center justify-between gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <span id="iban-text" class="font-mono font-bold text-sm md:text-lg text-blue-800 select-all tracking-tighter">IT52 PO35 7601 6010 1000 8072 943</span>
                                    <button type="button" onclick="copyIban()" class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 text-[10px] font-black px-4 py-2 rounded-lg transition-all uppercase active:scale-95">Copia</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-50">
                                <div>
                                    <span class="text-[9px] font-black text-gray-400 uppercase mb-1 block">Banca</span>
                                    <span class="text-sm font-bold">BBVA</span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-black text-gray-400 uppercase mb-1 block">BIC / SWIFT</span>
                                    <span class="text-sm font-bold">BBVAITM2XXX</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="process_order.php" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-8 bg-white border-t border-gray-100">
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <input type="hidden" name="color" value="<?= $color ?>">
                    <input type="hidden" name="quantity" id="input-qty" value="1">
                    <input type="hidden" name="final_price" id="input-total" value="<?= $prix_unitaire ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black uppercase text-gray-300 ml-4 mb-1 block">Dati Personali</label>
                            <input type="text" name="name" required placeholder="Nome e Cognome" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4 focus:border-accent focus:bg-white transition-all outline-none">
                        </div>
                        <input type="email" name="email" required placeholder="Indirizzo Email" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4 focus:border-accent focus:bg-white transition-all outline-none">
                        <input type="tel" name="phone" required placeholder="Telefono / WhatsApp" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4 focus:border-accent focus:bg-white transition-all outline-none">
                        
                        <div class="md:col-span-2 mt-4">
                            <label class="text-[10px] font-black uppercase text-gray-300 ml-4 mb-1 block">Indirizzo di Spedizione in Italia</label>
                            <input type="text" name="address" required placeholder="Via, Numero Civico, Interno" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4 focus:border-accent focus:bg-white transition-all outline-none mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="city" required placeholder="Città" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4 focus:border-accent focus:bg-white transition-all outline-none">
                                <input type="text" name="zip" required placeholder="CAP" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4 focus:border-accent focus:bg-white transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <label class="block text-xs font-black text-center text-accent uppercase mb-6 tracking-[0.2em]">Carica Prova di Pagamento</label>
                        <div class="relative">
                            <input type="file" name="receipt" id="receipt" required accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                            <label for="receipt" id="dropzone" class="flex flex-col items-center justify-center w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] cursor-pointer hover:bg-gray-100 hover:border-accent transition-all duration-300 group">
                                <div class="bg-white p-3 rounded-xl shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                </div>
                                <span id="file-name" class="text-xs font-bold text-gray-400 group-hover:text-anthracite transition-colors italic">Seleziona JPG, PNG o PDF (Max 5MB)</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-anthracite hover:bg-black text-white font-black py-7 rounded-[2rem] transition-all shadow-2xl uppercase tracking-[0.3em] text-sm mt-10 active:scale-95 transform">
                        Conferma ed Invia Ordine
                    </button>
                    
                    <p class="text-[9px] text-center text-gray-400 uppercase tracking-widest italic pt-4">La transazione è protetta e verificata manualmente dal nostro team.</p>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    let qty = 1;
    const unitPrice = <?= $prix_unitaire ?>;

    function updateQty(val) {
        qty += val;
        if (qty < 1) qty = 1;
        
        const total = (qty * unitPrice).toFixed(2);
        
        document.getElementById('qty-display').textContent = qty;
        document.getElementById('total-display').textContent = '€ ' + total.replace('.', ',');
        
        document.getElementById('input-qty').value = qty;
        document.getElementById('input-total').value = total;
    }

    function copyIban() {
        const iban = "IT52 PO35 7601 6010 1000 8072 943";
        navigator.clipboard.writeText(iban).then(() => {
            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = "COPIATO!";
            btn.classList.replace('bg-yellow-400', 'bg-green-400');
            setTimeout(() => {
                btn.innerText = originalText;
                btn.classList.replace('bg-green-400', 'bg-yellow-400');
            }, 2000);
        });
    }

    document.getElementById('receipt').onchange = function() {
        if(this.files[0]) {
            const file = this.files[0];
            const nameDisplay = document.getElementById('file-name');
            const dropzone = document.getElementById('dropzone');
            
            if (file.size > 5 * 1024 * 1024) {
                alert("File troppo grande! Massimo 5MB.");
                this.value = "";
                return;
            }

            nameDisplay.textContent = "File pronto: " + file.name;
            nameDisplay.classList.replace('text-gray-400', 'text-accent');
            dropzone.classList.replace('border-gray-200', 'border-accent');
            dropzone.classList.add('bg-accent/5');
        }
    };
</script>

<?php include 'includes/footer.php'; ?>