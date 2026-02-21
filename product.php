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

function getEliteMarketingText($categoria, $current_desc) {
    $cat = strtoupper($categoria);
    $text = "";
    if (strpos($cat, 'MTB') !== false) {
        $text = "<p class='mb-4'><b>Prestazioni senza limiti:</b> Nata nelle officine d'eccellenza, questa E-MTB è il risultato di anni di ricerca...</p>";
    } elseif (strpos($cat, 'CITY') !== false) {
        $text = "<p class='mb-4'><b>L'Arte del Movimento Urbano:</b> Spostarsi in città non è più una nécessité, ma una dichiarazione di stile...</p>";
    } else {
        $text = "<p class='mb-4'><b>L'Eccellenza in ogni Dettaglio:</b> Standard qualitativo Cicli Volante...</p>";
    }
    return "<div class='text-gray-900 font-bold mb-6 text-base border-b border-gray-100 pb-4'>" . nl2br($current_desc) . "</div>
            <div class='space-y-4 text-gray-600 italic leading-relaxed text-sm'>" . $text . "</div>";
}

$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) die("Prodotto non trovato.");

$specs = json_decode($p['specifiche_tecniche'] ?? '{}', true) ?: [];
$variant_files = glob("public/img/{$id}_*.*");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $p['nome_modello'] ?> | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-success { animation: slideIn 0.4s ease-out; }
        @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.4); } 100% { transform: scale(1); } }
        .animate-pop { animation: pop 0.3s ease-out; }
        html, body { overflow-x: hidden; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white antialiased">

    <?php include 'includes/header.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-10 md:py-20 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-20">
        
        <div class="w-full">
            <div class="bg-gray-50 rounded-[2rem] md:rounded-[3rem] p-6 md:p-12 aspect-square flex items-center justify-center border border-gray-100 shadow-inner">
                <img id="main-view" src="<?= getProductImage($id) ?>" class="w-full h-full object-contain mix-blend-multiply transition-all duration-500">
            </div>
            <?php if(!empty($variant_files)): ?>
            <div class="flex gap-4 mt-8 justify-center flex-wrap">
                <?php foreach(array_merge([getProductImage($id)], $variant_files) as $img): ?>
                <button onclick="document.getElementById('main-view').src='<?= $img ?>'" class="w-12 h-12 md:w-14 md:h-14 rounded-full border-2 border-gray-200 overflow-hidden bg-white p-1 hover:border-[#2D5A27] transition-all">
                    <img src="<?= $img ?>" class="w-full h-full object-cover rounded-full">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col">
            <p class="text-[#2D5A27] font-black uppercase text-[10px] md:text-xs tracking-[0.2em] mb-2"><?= $p['brand'] ?></p>
            
            <h1 class="text-4xl md:text-6xl font-black uppercase mb-4 md:mb-6 italic leading-none tracking-tighter text-gray-900"><?= $p['nome_modello'] ?></h1>
            
            <p class="text-3xl md:text-4xl font-black italic mb-8 md:mb-12 text-gray-900">€ <?= number_format($p['prezzo'], 0, '', '.') ?></p>
            
            <div class="mb-12 border-l-4 border-[#2D5A27] pl-4 md:pl-6 py-2">
                <?= getEliteMarketingText($p['categoria'], $p['descrizione'] ?? '') ?>
            </div>

            <?php if (!empty($specs)): ?>
            <div class="mt-4 mb-12">
                <div class="flex items-center gap-4 mb-8">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-gray-400 italic whitespace-nowrap">Specifiche Tecniche</h3>
                    <div class="h-px bg-gray-100 w-full"></div>
                </div>
                
                <div class="grid grid-cols-1 gap-y-1">
                    <?php foreach ($specs as $label => $valeur): ?>
                        <div class="group flex justify-between items-center py-3 border-b border-gray-50 hover:bg-gray-50 transition-all px-3 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-1 bg-[#2D5A27] rounded-full opacity-0 group-hover:opacity-100 transition-all"></div>
                                <span class="text-[10px] font-bold uppercase text-gray-400 group-hover:text-black transition-colors tracking-widest italic">
                                    <?= htmlspecialchars($label) ?>
                                </span>
                            </div>
                            <span class="text-sm font-black uppercase text-gray-900 italic tracking-tight">
                                <?= htmlspecialchars($valeur) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <button onclick="addToCart(<?= $p['id'] ?>)" class="w-full bg-black text-white font-black py-5 md:py-7 rounded-2xl mt-4 uppercase text-xs tracking-widest hover:bg-[#2D5A27] transition-all shadow-xl active:scale-95">
                Aggiungi al Carrello
            </button>
        </div>
    </main>

    <div id="toast-container" class="fixed bottom-6 right-6 md:top-32 md:bottom-auto z-[100] flex flex-col gap-4 w-[calc(100%-3rem)] md:w-auto"></div>

    <?php include 'includes/footer.php'; ?>

    <script>
    function addToCart(id) {
        const f = new FormData(); 
        f.append('product_id', id);

        fetch('add_to_cart.php', { method: 'POST', body: f })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.innerText = d.total;
                    badge.style.display = 'flex';
                    badge.classList.remove('animate-pop');
                    void badge.offsetWidth; 
                    badge.classList.add('animate-pop');
                }
                showToast("Prodotto aggiunto all'Atelier");
            }
        })
        .catch(err => console.error("Errore:", err));
    }

    function showToast(message) {
        const container = document.getElementById('toast-container');
        const t = document.createElement('div');
        t.className = "toast-success bg-white border-l-4 border-[#2D5A27] shadow-2xl p-5 flex items-center gap-4 min-w-[280px] rounded-r-lg transition-all duration-500";
        t.innerHTML = `
            <div class="bg-[#2D5A27] text-white rounded-full p-1 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase text-zinc-400 tracking-widest">Notifica</p>
                <p class="text-xs font-bold uppercase">${message}</p>
            </div>`;
        container.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transform = 'translateX(20px)';
            setTimeout(() => t.remove(), 500);
        }, 3000);
    }
    </script>
</body>
</html>