<?php
require 'db_config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

$specs = json_decode($product['caratteristiche_tecniche'], true);
$variants = json_decode($product['varianti'], true);
?>
<?php include 'includes/header.php'; ?>

<main class="pt-32 pb-24 max-w-7xl mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Image Section -->
        <div class="relative bg-gray-50 rounded-2xl p-10 aspect-square flex items-center justify-center smooth-shadow">
            <img id="main-image" src="<?= htmlspecialchars($product['immagine_principale']) ?>" alt="<?= htmlspecialchars($product['nome_modello']) ?>" class="w-full h-full object-contain mix-blend-multiply transition-opacity duration-300">
        </div>

        <!-- Details Section -->
        <div class="fade-in">
            <div class="text-sm font-bold text-accent uppercase tracking-wider mb-2"><?= htmlspecialchars($product['brand']) ?></div>
            <h1 class="text-4xl md:text-5xl font-bold text-anthracite mb-4"><?= htmlspecialchars($product['nome_modello']) ?></h1>
            <div class="text-3xl font-semibold text-gray-900 mb-8">€<?= number_format($product['prezzo'] / 100, 2, ',', '.') ?></div>

            <p class="text-gray-600 text-lg leading-relaxed mb-10">
                <?= htmlspecialchars($product['descrizione_lunga']) ?>
            </p>

            <!-- Variants -->
            <?php if (!empty($variants)): ?>
            <div class="mb-10">
                <label class="block text-sm font-medium text-gray-700 mb-4">Colore</label>
                <div class="flex gap-4">
                    <?php foreach ($variants as $variant): 
                        // Color mapping for visual representation
                        $bg = match(strtolower($variant)) {
                            'rosso' => 'bg-red-500',
                            'nero' => 'bg-black',
                            'titanio' => 'bg-gray-500',
                            'bianco' => 'bg-white border-2 border-gray-200',
                            'blu' => 'bg-blue-600',
                            'verde' => 'bg-green-600',
                            'giallo' => 'bg-yellow-400',
                            default => 'bg-gray-300'
                        };
                    ?>
                    <button 
                        class="variant-btn w-10 h-10 rounded-full <?= $bg ?> ring-2 ring-offset-2 ring-transparent transition-all duration-200 hover:scale-110 focus:outline-none"
                        data-color="<?= htmlspecialchars($variant) ?>"
                        data-id="<?= $product['id'] ?>"
                        onclick="selectVariant(this, '<?= htmlspecialchars($variant) ?>')"
                        title="<?= htmlspecialchars($variant) ?>">
                    </button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="selected-variant" value="<?= htmlspecialchars($variants[0]) ?>">
            </div>
            <?php endif; ?>

            <!-- Add to Cart -->
            <button onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['nome_modello']) ?>', <?= $product['prezzo'] ?>)" class="w-full bg-anthracite text-white font-bold py-4 rounded-2xl hover:bg-accent transition-colors duration-300 mb-12 shadow-lg hover:shadow-xl transform active:scale-[0.98]">
                Aggiungi al Carrello
            </button>

            <!-- Technical Specs -->
            <div class="bg-gray-50 rounded-2xl p-8">
                <h3 class="font-bold text-lg mb-6">Caratteristiche Tecniche</h3>
                <dl class="space-y-4">
                    <?php foreach ($specs as $key => $value): 
                          // Handle simple key-value or array of objects based on JSON structure
                          // The JSON structure in migrate.php was simple object key-value or array of objects with label/value
                          // Let's handle both
                          $label = is_array($value) ? $value['label'] : $key;
                          $val = is_array($value) ? $value['value'] : $value;
                    ?>
                    <div class="flex justify-between border-b border-gray-200 pb-2 last:border-0 last:pb-0">
                        <dt class="text-gray-500"><?= htmlspecialchars($label) ?></dt>
                        <dd class="font-medium text-anthracite"><?= htmlspecialchars($val) ?></dd>
                    </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        </div>
    </div>
</main>

<script>
    function selectVariant(btn, color) {
        // Remove active ring from all
        document.querySelectorAll('.variant-btn').forEach(b => b.classList.replace('ring-accent', 'ring-transparent'));
        // Add active ring to clicked
        btn.classList.replace('ring-transparent', 'ring-accent');
        
        // Update hidden input
        document.getElementById('selected-variant').value = color;
        
        // Change image (Simulation)
        // In a real scenario: src = `/public/img/${btn.dataset.id}_${color}.webp`
        // Here we just toggle opacity to simulate loading
        const img = document.getElementById('main-image');
        img.style.opacity = '0.5';
        setTimeout(() => {
            img.style.opacity = '1';
            // img.src = ... 
        }, 300);
    }

    // Select first variant by default
    const firstVariant = document.querySelector('.variant-btn');
    if (firstVariant) {
        selectVariant(firstVariant, firstVariant.dataset.color);
    }
</script>

<?php include 'includes/footer.php'; ?>
