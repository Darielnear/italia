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

$current_cat = $_GET['cat'] ?? 'Tutti';

// LOGIQUE DES TITRES DYNAMIQUES
$titolo_sezione = "Selezione <span class='editorial-font italic font-light text-[#2D5A27] lowercase'>Premium</span>";
if($current_cat === 'MTB') $titolo_sezione = "Gamma <span class='editorial-font italic font-light text-[#2D5A27] lowercase'>E-MTB</span>";
if($current_cat === 'CITY') $titolo_sezione = "Mobilità <span class='editorial-font italic font-light text-[#2D5A27] lowercase'>Urbana</span>";
if($current_cat === 'TREKKING') $titolo_sezione = "Spirito <span class='editorial-font italic font-light text-[#2D5A27] lowercase'>Avventura</span>";
if($current_cat === 'Accessori') $titolo_sezione = "Dettagli <span class='editorial-font italic font-light text-[#2D5A27] lowercase'>di Stile</span>";

if ($current_cat === 'Tutti') {
    $stmt = $pdo->query("SELECT * FROM products WHERE categoria NOT LIKE '%Accessori%' LIMIT 8");
    $products = $stmt->fetchAll();
} elseif ($current_cat === 'All_Bikes') {
    $stmt = $pdo->query("SELECT * FROM products WHERE categoria NOT LIKE '%Accessori%' ORDER BY prezzo DESC");
    $products = $stmt->fetchAll();
} elseif ($current_cat === 'Accessori') {
    $stmt = $pdo->query("SELECT * FROM products WHERE categoria LIKE '%Accessori%' ORDER BY prezzo DESC");
    $products = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE categoria LIKE ? ORDER BY prezzo DESC");
    $stmt->execute(["%$current_cat%"]);
    $products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Cicli Volante | L'Atelier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;700;800&family=Playfair+Display:italic,wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .editorial-font { font-family: 'Playfair Display', serif; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { animation: marquee 30s linear infinite; }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 1s cubic-bezier(0.2, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .testimonial-wrapper { display: flex; transition: transform 0.8s cubic-bezier(0.65, 0, 0.35, 1); }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

    <?php include 'includes/header.php'; ?>

    <?php if($current_cat === 'Tutti'): ?>
        <section class="relative h-screen flex items-center bg-zinc-950 overflow-hidden">
            <div class="absolute right-0 top-0 w-2/3 h-full opacity-40 grayscale hover:grayscale-0 transition-all duration-[2s]">
                <img src="public/img/hero.jpg" class="w-full h-full object-cover">
            </div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-6 w-full">
                <div class="max-w-2xl">
                    <p class="text-[#2D5A27] font-bold tracking-[0.5em] uppercase text-[10px] mb-6">Ingegneria Italiana</p>
                    <h1 id="hero-text" class="text-white text-6xl md:text-9xl font-extrabold leading-[0.9] tracking-tighter mb-8">
                        VELOCITÀ <br> <span class="editorial-font text-gray-400 italic font-light">Sartoriale.</span>
                    </h1>
                    <div class="mt-12 flex items-center gap-8">
                        <a href="#selection" class="bg-[#2D5A27] text-white px-10 py-5 rounded-sm font-bold uppercase text-[10px] tracking-widest hover:bg-white hover:text-black transition-all">
                            Inizia l'Esperienza
                        </a>
                    </div>
                </div>
            </div>

            <div class="absolute left-10 bottom-10 [writing-mode:vertical-lr] text-white font-black text-9xl uppercase opacity-5 select-none">
                VOLANTE
            </div>
        </section>

        <div class="py-10 bg-white border-b border-gray-100">
            <div class="flex whitespace-nowrap animate-marquee items-center opacity-30 grayscale">
                <div class="flex gap-20 pr-20 items-center text-xl font-bold uppercase tracking-tighter">
                    <span>Specialized</span> <span>/</span> <span>Orbea</span> <span>/</span> <span>Trek</span> <span>/</span> <span>Cannondale</span> <span>/</span> <span>Pinarello</span>
                </div>
                <div class="flex gap-20 pr-20 items-center text-xl font-bold uppercase tracking-tighter">
                    <span>Specialized</span> <span>/</span> <span>Orbea</span> <span>/</span> <span>Trek</span> <span>/</span> <span>Cannondale</span> <span>/</span> <span>Pinarello</span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <main id="selection" class="max-w-7xl mx-auto px-6 py-24">
        <div class="mb-16 reveal">
            <h2 class="text-5xl font-extrabold uppercase tracking-tighter">
                <?= $titolo_sezione ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            <?php foreach($products as $p): ?>
            <a href="product.php?id=<?= $p['id'] ?>" class="group reveal">
                <div class="bg-gray-50 p-8 transition-all duration-500 group-hover:bg-white group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.1)] rounded-2xl">
                    <div class="aspect-square mb-8 overflow-hidden flex items-center justify-center">
                        <img src="<?= getProductImage($p['id']) ?>" class="w-full h-full object-contain mix-blend-multiply transition-transform duration-700 group-hover:scale-110">
                    </div>
                    <div class="border-t border-gray-100 pt-6">
                        <p class="text-[9px] font-black text-[#2D5A27] uppercase tracking-[0.3em] mb-2"><?= $p['brand'] ?></p>
                        <h3 class="text-xl font-bold uppercase tracking-tight"><?= $p['nome_modello'] ?></h3>
                        <p class="mt-4 text-gray-400 font-light italic">€<?= number_format($p['prezzo'], 0, '', '.') ?></p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </main>

    <section class="bg-zinc-900 py-32 text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-[#2D5A27] text-[10px] font-bold uppercase tracking-[0.6em] mb-12 italic">Il Nostro Manifesto</h2>
            <p class="editorial-font text-3xl md:text-5xl leading-tight">
                "La perfezione non è un traguardo, ma un modo di <span class="text-[#2D5A27]">vivere la strada</span>. Ogni fibra di carbonio racconta una storia di pura ambizione."
            </p>
        </div>
        <div class="absolute top-0 right-0 text-white/[0.02] text-[20rem] font-black -mr-32 -mt-32 select-none italic">ELITE</div>
    </section>

    <section class="bg-black py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20 reveal">
                <h2 class="text-white text-4xl md:text-5xl font-extrabold uppercase tracking-tighter">
                    Cosa dicono <span class="text-[#2D5A27] editorial-font italic font-light lowercase">i nostri clienti</span>
                </h2>
                <div class="h-[1px] w-20 bg-[#2D5A27] mx-auto mt-6"></div>
            </div>
            <div class="relative">
                <div id="testimonial-slider" class="testimonial-wrapper gap-8">
                    <?php 
                    $testimonials = [
                        ['name' => 'Marco R.', 'img' => 'four.jpeg', 'note' => 5, 'text' => 'Un’esperienza sartoriale. La cura nel setup initiale è stata maniacale.'],
                        ['name' => 'Giulia S.', 'img' => 'two.jpeg', 'note' => 5, 'text' => 'Professionalità rara e consegna impeccabile. Una vera boutique.'],
                        ['name' => 'Luca M.', 'img' => 'three.jpeg', 'note' => 4, 'text' => 'Il punto di riferimento per l’alta gamma. Qualità dei prodotti eccelsa.'],
                        ['name' => 'Sofia B.', 'img' => 'one.jpeg', 'note' => 5, 'text' => 'Assistenza incredibile, hanno trovato la bici perfetta per le mie esigenze.']
                    ];
                    foreach($testimonials as $t): ?>
                    <div class="testimonial-item min-w-full md:min-w-[calc(33.333%-1.5rem)]">
                        <div class="bg-zinc-900/40 border border-zinc-800 p-10 rounded-3xl h-full flex flex-col items-center text-center group">
                            <div class="w-24 h-24 rounded-full overflow-hidden mb-8 border-2 border-[#2D5A27] p-1">
                                <img src="public/testimonials/<?= $t['img'] ?>" class="w-full h-full object-cover rounded-full grayscale group-hover:grayscale-0 transition-all duration-500" onerror="this.src='public/img/placeholder.png'">
                            </div>
                            <div class="flex gap-1 mb-6 text-yellow-500">
                                <?php for($i=0; $i<5; $i++): ?>
                                    <svg class="w-4 h-4 <?= $i < $t['note'] ? 'fill-current' : 'text-zinc-700 fill-current' ?>" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-zinc-400 font-light leading-relaxed mb-8 italic">"<?= $t['text'] ?>"</p>
                            <p class="text-white text-[10px] font-bold uppercase tracking-[0.3em] border-t border-zinc-800 pt-6 w-full"><?= $t['name'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        const slider = document.getElementById('testimonial-slider');
        let index = 0;
        function moveSlider() {
            const isMobile = window.innerWidth < 768;
            const itemsVisible = isMobile ? 1 : 3;
            const totalItems = <?= count($testimonials) ?>;
            index++;
            if (index > totalItems - itemsVisible) index = 0;
            const card = document.querySelector('.testimonial-item');
            if (card) {
                const gap = 32; 
                slider.style.transform = `translateX(-${index * (card.offsetWidth + gap)}px)`;
            }
        }
        setInterval(moveSlider, 4000);

        window.addEventListener('scroll', () => {
            const scroll = window.pageYOffset;
            const heroText = document.getElementById('hero-text');
            if(heroText) {
                heroText.style.transform = `translateY(${scroll * 0.2}px)`;
                heroText.style.opacity = 1 - (scroll / 900);
            }
        });
    </script>
    <?php include 'includes/chat.php'; ?>
</body>
</html>