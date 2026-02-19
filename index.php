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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; color: #1A1A1A; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .soft-shadow { box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); }
        .marquee { display: flex; overflow: hidden; user-select: none; gap: 2rem; }
        .marquee-content { flex-shrink: 0; display: flex; justify-content: space-around; min-width: 100%; gap: 2rem; animation: scroll 40s linear infinite; }
        @keyframes scroll { from { transform: translateX(0); } to { transform: translateX(calc(-100% - 2rem)); } }
        .hero-gradient { background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%); }
        
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

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative h-screen w-full overflow-hidden flex items-center justify-center">
    <div class="absolute inset-0">
        <img src="public/img/hero.jpg" class="w-full h-full object-cover" alt="Hero">
        <div class="absolute inset-0 hero-gradient"></div>
    </div>
    <div class="relative z-10 text-center px-6">
        <h1 class="text-white text-6xl md:text-8xl font-black tracking-tighter uppercase leading-none mb-4">
            L'ÉLÉGANCE <br> <span class="font-playfair italic font-normal lowercase">en mouvement</span>
        </h1>
        <p class="text-white/80 text-lg md:text-xl font-light tracking-wide max-w-2xl mx-auto mb-8">
            Découvrez la perfection technologique alliée au design italien. Cicli Volante redéfinit les standards du cyclisme de luxe.
        </p>
        <a href="#shop" class="inline-block bg-white text-anthracite px-10 py-4 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-accent hover:text-white transition-all transform hover:scale-105">
            Explorer la collection
        </a>
    </div>
</section>

<!-- Shop Section -->
<main id="shop" class="max-w-7xl mx-auto px-6 py-24">
    <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
        <div>
            <h2 class="text-4xl font-black tracking-tight uppercase mb-2">La Collection</h2>
            <p class="text-gray-400 font-light">Performance pure, esthétique absolue.</p>
        </div>
        <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
            <a href="index.php" class="whitespace-nowrap px-6 py-2 rounded-full border border-gray-100 text-[10px] font-bold uppercase tracking-widest <?= !$selected_brand ? 'bg-anthracite text-white border-anthracite' : 'text-gray-400 hover:border-gray-300' ?>">Tous les modèles</a>
            <?php foreach ($brands as $brand): ?>
                <a href="index.php?brand=<?= urlencode($brand) ?>" class="whitespace-nowrap px-6 py-2 rounded-full border border-gray-100 text-[10px] font-bold uppercase tracking-widest <?= $selected_brand === $brand ? 'bg-anthracite text-white border-anthracite' : 'text-gray-400 hover:border-gray-300' ?>">
                    <?= $brand ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php foreach ($products as $p): ?>
            <div class="group bg-[#F9F9F9] rounded-[2rem] p-6 transition-all duration-500 hover:bg-white hover:soft-shadow border border-transparent hover:border-gray-50">
                <div class="aspect-[4/3] mb-6 relative overflow-hidden flex items-center justify-center">
                    <img src="public/img/<?= $p['immagine_main'] ?>" class="w-full h-full object-contain mix-blend-multiply transition-transform duration-700 group-hover:scale-110" alt="<?= $p['nome_modello'] ?>">
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold text-accent uppercase tracking-widest mb-1"><?= $p['brand'] ?></p>
                            <h3 class="text-lg font-bold text-anthracite leading-tight"><?= $p['nome_modello'] ?></h3>
                        </div>
                        <p class="text-xl font-black text-anthracite italic">€ <?= number_format($p['prezzo'], 0, '', '.') ?></p>
                    </div>
                    
                    <button onclick="addToCart(<?= $p['id'] ?>)" class="w-full bg-anthracite text-white text-[10px] font-bold py-4 rounded-2xl hover:bg-accent transition-all duration-300 uppercase tracking-widest transform active:scale-95">
                        Aggiungi al carrello
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Testimonials Section -->
<section class="py-24 bg-anthracite overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 mb-16">
        <h2 class="text-white text-3xl font-black uppercase tracking-tight">Expériences <span class="font-playfair italic text-accent lowercase">volante</span></h2>
    </div>
    
    <div class="marquee">
        <div class="marquee-content">
            <?php 
            $testimonials = [
                ['name' => 'Marco Rossi', 'text' => 'Une expérience de conduite inégalée. Le design est sublime.'],
                ['name' => 'Elena Bianchi', 'text' => 'Plus qu\'un vélo, c\'est un bijou technologique.'],
                ['name' => 'Giovanni Silva', 'text' => 'Le service client est à la hauteur de la qualité des vélos.'],
                ['name' => 'Sophia Lorenzi', 'text' => 'Incroyable agilité en montée. Cicli Volante est le futur.'],
                ['name' => 'Alessandro Volta', 'text' => 'Un équilibre parfait entre confort et performance brute.'],
            ];
            foreach (array_merge($testimonials, $testimonials) as $t): ?>
                <div class="flex-none w-80 bg-white/5 backdrop-blur-sm p-8 rounded-3xl border border-white/10">
                    <div class="flex text-accent mb-4">
                        <?php for($i=0; $i<5; $i++): ?>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <p class="text-white/80 text-sm font-light leading-relaxed mb-6 italic">"<?= $t['text'] ?>"</p>
                    <p class="text-white text-xs font-bold uppercase tracking-widest"><?= $t['name'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

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