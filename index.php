<?php
session_start();
require 'db_config.php';

// Calcul initial du panier
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// Récupération marques et produits
$brands_stmt = $pdo->query("SELECT DISTINCT brand FROM products WHERE brand != '' ORDER BY brand ASC LIMIT 8");
$brands = $brands_stmt->fetchAll(PDO::FETCH_COLUMN);

$selected_brand = $_GET['brand'] ?? '';
$query = "SELECT * FROM products " . ($selected_brand ? "WHERE brand = ?" : "") . " ORDER BY id DESC LIMIT 20";
$stmt = $pdo->prepare($query);
if($selected_brand) $stmt->execute([$selected_brand]); else $stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cicli Volante | Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { anthracite: '#1A1A1A', accent: '#2ECC71' } } }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .glass-header { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(15px); }
        
        /* Animation du badge */
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }
        .animate-pop { animation: pop 0.3s ease-out; }
    </style>
</head>
<body class="bg-white antialiased">

<div class="fixed top-0 w-full z-[60] bg-anthracite text-white text-[9px] font-bold py-2 text-center uppercase tracking-[0.2em]">
    OFFERTA ESCLUSIVA: SPEDIZIONE GRATUITA SOPRA I 500€
</div>

<header class="fixed top-[28px] md:top-[31px] w-full z-[40] glass-header border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="index.php" class="text-xl font-black tracking-tighter uppercase text-anthracite">
            CICLI<span class="text-accent italic">VOLANTE</span>
        </a>
        
        <nav class="hidden lg:flex items-center gap-8">
            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-anthracite">Tutte le bici</a>
            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-anthracite">E-MTB</a>
            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-anthracite text-accent">Promozioni</a>
        </nav>

        <div class="flex items-center">
            <a href="checkout.php" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 hover:bg-gray-100 transition-all">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"/>
                    <path d="M3 6H21"/><path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                <span id="cart-badge" class="<?= $cart_count > 0 ? '' : 'hidden' ?> absolute -top-1 -right-1 bg-accent text-white text-[9px] font-black h-5 w-5 flex items-center justify-center rounded-full border-2 border-white shadow-sm">
                    <?= $cart_count ?>
                </span>
            </a>
        </div>
    </div>
</header>

<div class="h-[93px] md:h-[95px]"></div>

<main class="max-w-7xl mx-auto px-8 py-20">
    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-x-8 gap-y-16">
        <?php foreach ($products as $p): ?>
            <div class="group block">
                <div class="aspect-square bg-[#F9F9F9] rounded-2xl p-8 mb-4 flex items-center justify-center relative">
                    <img src="public/img/<?= $p['immagine_main'] ?>" class="w-full h-full object-contain mix-blend-multiply transition-transform group-hover:scale-105">
                </div>
                <div class="px-2 text-center">
                    <p class="text-[9px] font-bold text-gray-400 uppercase mb-1"><?= $p['brand'] ?></p>
                    <h3 class="text-[11px] font-extrabold text-anthracite uppercase"><?= $p['nome_modello'] ?></h3>
                    <p class="text-sm font-bold text-anthracite mt-2 italic">€ <?= number_format($p['prezzo'], 0, '', '.') ?></p>
                    
                    <button onclick="addToCart(<?= $p['id'] ?>)" class="mt-4 w-full bg-anthracite text-white text-[9px] font-black py-3 rounded-xl hover:bg-accent transition-all uppercase tracking-widest">
                        Aggiungi
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
function addToCart(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const badge = document.getElementById('cart-badge');
        badge.innerText = data.totalItems;
        badge.classList.remove('hidden');
        
        // Animation "Pop"
        badge.classList.remove('animate-pop');
        void badge.offsetWidth; // Force reflow pour relancer l'animation
        badge.classList.add('animate-pop');
    });
}
</script>

</body>
</html>