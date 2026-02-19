<?php
require 'db_config.php';

// 1. Récupération de l'ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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

// 2. CONFIGURATION DES VARIANTES (Ton dictionnaire d'IDs)
$config_variantes = [
    1 => ['grigio', 'rosso'], 2 => ['grigio', 'verde'], 3 => ['blu', 'nero', 'rosso'],
    17 => ['blu', 'rosso'], 25 => ['arancione'], 26 => ['rosso'], 27 => ['verde'],
    37 => ['verde'], 50 => ['nero'] // Ajoute les autres IDs ici selon tes besoins
];

$mes_variantes = $config_variantes[$id] ?? [];
$specs = json_decode($product['caratteristiche_tecniche'] ?? '[]', true) ?: [];
$image_principale = !empty($product['immagine_main']) ? $product['immagine_main'] : 'default.jpg';

// --- LOGIQUE DU MOTEUR MARKETING AUTOMATIQUE ---
$nome_clean = strtolower($product['nome_modello']);
$is_mtb = str_contains($nome_clean, 'mtb') || str_contains($nome_clean, 'mountain');
$is_ebike = str_contains($nome_clean, 'e-') || str_contains($nome_clean, 'electric') || str_contains($nome_clean, 'be85');

// Définition des icônes et arguments selon le type de vélo
if ($is_ebike) {
    $h_icon = "⚡"; $h_title = "Potenza Elettrica"; $h_desc = "Autonomia estesa e assistenza fluida per ogni salita.";
} elseif ($is_mtb) {
    $h_icon = "🏔️"; $h_title = "Off-Road Ready"; $h_desc = "Sospensioni tarate per il massimo controllo su ogni terreno.";
} else {
    $h_icon = "🚀"; $h_title = "Velocità Pura"; $h_desc = "Aerodinamica ottimizzata per fendere l'aria senza sforzo.";
}
?>

<?php include 'includes/header.php'; ?>

<main class="max-w-7xl mx-auto px-6 py-24 min-h-screen">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        
        <div class="bg-white rounded-3xl p-10 flex items-center justify-center shadow-sm border border-gray-100 aspect-square sticky top-32">
            <img id="main-product-image" 
                 src="public/img/<?= htmlspecialchars($image_principale) ?>" 
                 alt="<?= htmlspecialchars($product['nome_modello']) ?>" 
                 class="w-full h-auto object-contain transition-opacity duration-300 ease-in-out">
        </div>

        <div class="flex flex-col">
            <div class="text-accent font-bold uppercase tracking-widest mb-2 italic">
                <?= htmlspecialchars($product['brand']) ?>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-black text-anthracite mb-4 leading-tight uppercase tracking-tighter">
                <?= htmlspecialchars($product['nome_modello']) ?>
            </h1>
            
            <p class="text-3xl font-light text-gray-900 mb-8 font-mono">
                € <?= number_format($product['prezzo'], 2, ',', '.') ?>
            </p>

            <div class="mb-10">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 italic border-b pb-2">Esperienza di Guida</h3>
                <div class="prose prose-lg text-gray-600 leading-relaxed font-light italic">
                    <?php 
                    $telaio = $specs['telaio'] ?? $specs['Telaio'] ?? 'materiali compositi premium';
                    $cambio = $specs['cambio'] ?? $specs['Cambio'] ?? 'trasmissione fluida';
                    
                    echo "La <strong>" . htmlspecialchars($product['nome_modello']) . "</strong> rappresenta l'apice dell'ingegneria ciclistica. ";
                    echo "Il suo telaio in <strong>$telaio</strong> è stato scolpito per offrire una risposta immediata ad ogni pedalata. ";
                    echo "Equipaggiata con un sistema <strong>$cambio</strong>, garantisce precisione chirurgica in ogni condizione di guida. ";
                    echo ($is_mtb) ? "Pronta a dominare i sentieri più selvaggi." : "Ideale per chi cerca record di velocità sull'asfalto.";
                    ?>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-10">
                <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
                    <div class="text-xl mb-1"><?= $h_icon ?></div>
                    <h4 class="text-[9px] font-black uppercase text-anthracite"><?= $h_title ?></h4>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
                    <div class="text-xl mb-1">🇮🇹</div>
                    <h4 class="text-[9px] font-black uppercase text-anthracite">Anima Italiana</h4>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
                    <div class="text-xl mb-1">🛡️</div>
                    <h4 class="text-[9px] font-black uppercase text-anthracite">Premium</h4>
                </div>
            </div>

            <?php if (count($mes_variantes) > 0): ?>
            <div class="mb-10 bg-gray-50 p-6 rounded-3xl border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Personalizza Colore</p>
                <div class="flex flex-wrap gap-4 items-center">
                    <button onclick="updateImage('Originale', '<?= $image_principale ?>', this)" 
                            class="color-dot w-10 h-10 rounded-full bg-gray-200 border-2 border-white ring-2 ring-transparent transition-all hover:scale-110 shadow-sm flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </button>
                    <?php foreach ($mes_variantes as $color): 
                        $bg = match(strtolower($color)) {
                            'rosso' => 'bg-red-600', 'nero' => 'bg-black', 'bianco' => 'bg-white border',
                            'blu' => 'bg-blue-700', 'verde' => 'bg-green-600', 'grigio' => 'bg-gray-500',
                            'arancione' => 'bg-orange-500', default => 'bg-gray-400'
                        };
                    ?>
                    <button onclick="updateImage('<?= $color ?>', null, this)" 
                            class="color-dot w-10 h-10 rounded-full <?= $bg ?> border-2 border-white ring-2 ring-transparent shadow-sm transition-all hover:scale-110"></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <input type="hidden" id="selected-color" value="Originale">

            <a href="checkout.php?id=<?= $product['id'] ?>" id="buy-button" 
               class="bg-anthracite hover:bg-black text-white text-center font-black py-6 rounded-2xl transition-all shadow-xl text-lg uppercase tracking-[0.3em]">
                Prenota ora
            </a>

            <div class="mt-16">
                <h3 class="font-black text-sm mb-6 border-b pb-4 uppercase tracking-widest text-anthracite italic">Scheda Tecnica</h3>
                <div class="grid grid-cols-1 gap-y-1">
                    <?php foreach ($specs as $key => $value): ?>
                        <div class="flex justify-between border-b border-gray-50 py-3 group">
                            <span class="text-gray-400 text-xs font-bold uppercase group-hover:text-anthracite transition-colors"><?= str_replace('_', ' ', $key) ?></span>
                            <span class="text-anthracite font-bold text-sm"><?= htmlspecialchars($value) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function updateImage(color, originalSrc, btn) {
    const img = document.getElementById('main-product-image');
    const buyBtn = document.getElementById('buy-button');
    const id = "<?= $product['id'] ?>";
    
    // UI
    document.querySelectorAll('.color-dot').forEach(d => d.classList.replace('ring-anthracite', 'ring-transparent'));
    btn.classList.replace('ring-transparent', 'ring-anthracite');
    document.getElementById('selected-color').value = color;
    buyBtn.href = "checkout.php?id=" + id + "&color=" + encodeURIComponent(color);

    img.style.opacity = '0.3';
    
    setTimeout(() => {
        if (color === 'Originale') {
            img.src = "public/img/" + originalSrc;
            img.style.opacity = '1';
        } else {
            const extensions = ['webp', 'jpg', 'png', 'jpeg'];
            let found = false;
            
            extensions.forEach((ext) => {
                if (found) return;
                const testPath = "public/img/" + id + "_" + color.toLowerCase() + "." + ext;
                const temp = new Image();
                temp.onload = () => {
                    if (!found) {
                        img.src = testPath;
                        img.style.opacity = '1';
                        found = true;
                    }
                };
                temp.src = testPath;
            });

            setTimeout(() => { if (!found) { img.src = "public/img/" + originalSrc; img.style.opacity = '1'; } }, 800);
        }
    }, 200);
}

window.onload = () => {
    const firstDot = document.querySelector('.color-dot');
    if (firstDot) firstDot.click();
};
</script>

<?php include 'includes/footer.php'; ?>