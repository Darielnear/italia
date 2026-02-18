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
<section class="pt-32 pb-20 px-6 max-w-7xl mx-auto text-center fade-in">
    <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 text-anthracite">
        L'arte della <span class="text-accent">velocità</span>.
    </h1>
    <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-10">
        Ingegneria di precisione e design italiano. Scopri la nuova collezione.
    </p>
</section>

<!-- Filters -->
<section class="sticky top-20 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 py-4 mb-12">
    <div class="max-w-7xl mx-auto px-6 overflow-x-auto no-scrollbar">
        <div class="flex space-x-2 md:justify-center min-w-max" id="category-filters">
            <button class="filter-btn active px-6 py-2 rounded-full bg-anthracite text-white font-medium transition-all duration-300 hover:opacity-90" data-filter="all">Tutti</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-medium transition-all duration-300 hover:bg-gray-200" data-filter="E-MTB">E-MTB</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-medium transition-all duration-300 hover:bg-gray-200" data-filter="MTB">MTB</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-medium transition-all duration-300 hover:bg-gray-200" data-filter="Road">Road</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-100 text-gray-600 font-medium transition-all duration-300 hover:bg-gray-200" data-filter="Accessori">Accessori</button>
        </div>
    </div>
</section>

<!-- Product Grid -->
<main class="max-w-7xl mx-auto px-6 pb-24">
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
