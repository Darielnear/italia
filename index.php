<?php
require 'db_config.php';

// Fetch all products
$stmt = $db->query("SELECT * FROM products");
$products = $stmt->fetchAll();

// Group by category if needed, or handle in JS. 
// For "4 onglets dynamiques", we can render all and filter with JS, or fetch by category.
// Let's render all and use JS for the "App-like" fluid switching.
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative h-screen w-full overflow-hidden flex items-center justify-center">
    <!-- Background Image with Fixed Attachment for Parallax -->
    <div class="absolute inset-0 z-0 bg-fixed bg-center bg-cover" style="background-image: url('public/img/hero.jpg');"></div>
    
    <!-- Dark Overlay -->
    <div class="absolute inset-0 z-10 bg-black/40"></div>
    
    <!-- Hero Content -->
    <div class="relative z-20 text-center px-6 max-w-4xl fade-in">
        <h1 class="text-5xl md:text-8xl font-bold tracking-tight mb-8 text-white">
            L'arte della <span class="text-accent">velocità</span>.
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 max-w-2xl mx-auto mb-12 leading-relaxed">
            Ingegneria di precisione e design italiano. Scopri la nuova collezione Cicli Volante.
        </p>
        <a href="#product-grid" class="inline-block bg-accent hover:bg-green-500 text-anthracite font-bold px-10 py-5 rounded-full text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-[0_0_20px_rgba(46,204,113,0.4)] active:scale-95">
            Esplora la Collezione
        </a>
    </div>
</section>

<!-- Filters (Sticky Tabs) -->
<section class="sticky top-20 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 py-4">
    <div class="max-w-7xl mx-auto px-6 overflow-x-auto no-scrollbar">
        <div class="flex space-x-8 md:justify-center min-w-max" id="category-filters">
            <button class="filter-btn active relative py-2 text-anthracite font-bold transition-all duration-300" data-filter="all">
                Tutti
                <span class="absolute bottom-0 left-0 w-full h-1 bg-accent rounded-full transition-all duration-300 opacity-100"></span>
            </button>
            <button class="filter-btn relative py-2 text-gray-400 font-medium hover:text-anthracite transition-all duration-300" data-filter="E-MTB">
                E-MTB
                <span class="absolute bottom-0 left-0 w-0 h-1 bg-accent rounded-full transition-all duration-300 opacity-0"></span>
            </button>
            <button class="filter-btn relative py-2 text-gray-400 font-medium hover:text-anthracite transition-all duration-300" data-filter="MTB">
                MTB
                <span class="absolute bottom-0 left-0 w-0 h-1 bg-accent rounded-full transition-all duration-300 opacity-0"></span>
            </button>
            <button class="filter-btn relative py-2 text-gray-400 font-medium hover:text-anthracite transition-all duration-300" data-filter="Road">
                Road
                <span class="absolute bottom-0 left-0 w-0 h-1 bg-accent rounded-full transition-all duration-300 opacity-0"></span>
            </button>
            <button class="filter-btn relative py-2 text-gray-400 font-medium hover:text-anthracite transition-all duration-300" data-filter="Accessori">
                Accessori
                <span class="absolute bottom-0 left-0 w-0 h-1 bg-accent rounded-full transition-all duration-300 opacity-0"></span>
            </button>
        </div>
    </div>
</section>

<!-- Product Grid -->
<main class="max-w-7xl mx-auto px-6 py-24" id="product-grid">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10" id="product-grid">
        <?php foreach ($products as $product): ?>
        <a href="product.php?id=<?= $product['id'] ?>" class="product-card group block fade-in" data-category="<?= $product['categoria'] ?>">
            <div class="bg-gray-50 rounded-2xl p-8 mb-6 transition-transform duration-300 group-hover:scale-[1.02] relative overflow-hidden aspect-[4/3] flex items-center justify-center">
                <img src="<?= htmlspecialchars($product['immagine_principale']) ?>" alt="<?= htmlspecialchars($product['nome_modello']) ?>" class="object-contain w-full h-full mix-blend-multiply">
            </div>
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-bold text-accent uppercase tracking-wider mb-1"><?= htmlspecialchars($product['brand']) ?></div>
                    <h3 class="text-xl font-bold text-anthracite mb-2"><?= htmlspecialchars($product['nome_modello']) ?></h3>
                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($product['categoria']) ?></p>
                </div>
                <div class="text-lg font-semibold bg-gray-100 px-3 py-1 rounded-lg">
                    €<?= number_format($product['prezzo'] / 100, 2, ',', '.') ?>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
