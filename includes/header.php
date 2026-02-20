<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<style>
    @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.4); } 100% { transform: scale(1); } }
    .animate-pop { animation: pop 0.3s ease-out; }
    
    /* Animation pour le menu mobile */
    #mobile-menu { transition: all 0.3s ease-in-out; }
    .menu-open { transform: translateY(0); opacity: 1; pointer-events: auto; }
    .menu-closed { transform: translateY(-10px); opacity: 0; pointer-events: none; }
</style>

<div class="fixed top-0 w-full z-50">
    <div class="bg-[#2D5A27] text-white text-[8px] md:text-[10px] font-bold uppercase tracking-[0.2em] py-2 text-center px-4">
        Offerta Esclusiva: Spedizione Gratuita sopra i 500€
    </div>

    <div class="hidden md:flex bg-black text-white py-2 px-6 justify-between items-center border-b border-white/10">
        <div class="flex gap-6 items-center">
            <span class="text-[9px] text-zinc-400 uppercase font-black tracking-widest">Ingegneria Italiana</span>
            <span class="text-[9px] text-zinc-400 uppercase font-black tracking-widest">Assistenza: +39 02 1234 5678</span>
        </div>
        <a href="track_order.php" class="text-[9px] text-white uppercase font-black hover:text-[#2D5A27] transition-colors tracking-widest">
            Traccia il tuo ordine
        </a>
    </div>

    <header class="bg-white/95 backdrop-blur-md border-b-4 border-[#2D5A27] py-4 md:py-6">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            
            <button onclick="toggleMenu()" class="md:hidden text-black p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <a href="index.php" class="text-xl md:text-2xl font-black uppercase tracking-tighter italic">
                CICLI<span class="text-[#2D5A27]">VOLANTE</span>
            </a>

            <nav class="hidden md:flex gap-10 text-[10px] font-black uppercase tracking-widest">
                <a href="index.php?cat=All_Bikes" class="hover:text-[#2D5A27] transition-colors">Tutte le bici</a>
                <a href="index.php?cat=MTB" class="hover:text-[#2D5A27] transition-colors">E-MTB</a>
                <a href="index.php?cat=CITY" class="hover:text-[#2D5A27] transition-colors">City</a>
                <a href="index.php?cat=TREKKING" class="hover:text-[#2D5A27] transition-colors">Trekking</a>
                <a href="index.php?cat=Accessori" class="hover:text-[#2D5A27] transition-colors">Accessori</a>
            </nav>

            <a href="checkout.php" class="relative group p-2">
                <svg class="w-6 h-6 text-gray-900 group-hover:text-[#2D5A27] transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <?php $cart_total = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                <span id="cart-badge" style="display: <?= ($cart_total > 0) ? 'flex' : 'none' ?>;" 
                      class="absolute top-0 right-0 bg-[#2D5A27] text-white text-[9px] font-bold h-5 w-5 flex items-center justify-center rounded-full shadow-lg border-2 border-white">
                    <?= $cart_total ?>
                </span>
            </a>
        </div>

        <div id="mobile-menu" class="md:hidden absolute top-full left-0 w-full bg-white border-b border-gray-100 shadow-xl menu-closed">
            <nav class="flex flex-col p-6 gap-4 text-xs font-black uppercase tracking-widest">
                <a href="index.php?cat=All_Bikes" class="py-3 border-b border-gray-50">Tutte le bici</a>
                <a href="index.php?cat=MTB" class="py-3 border-b border-gray-50">E-MTB</a>
                <a href="index.php?cat=CITY" class="py-3 border-b border-gray-50">City</a>
                <a href="index.php?cat=TREKKING" class="py-3 border-b border-gray-50">Trekking</a>
                <a href="index.php?cat=Accessori" class="py-3">Accessori</a>
            </nav>
        </div>
    </header>
</div>

<div class="h-32 md:h-44"></div>

<script>
function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');
    
    if (menu.classList.contains('menu-closed')) {
        menu.classList.remove('menu-closed');
        menu.classList.add('menu-open');
        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12'); // Icône "X"
    } else {
        menu.classList.remove('menu-open');
        menu.classList.add('menu-closed');
        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16'); // Icône "Bars"
    }
}
</script>