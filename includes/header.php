<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<style>
    @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.4); } 100% { transform: scale(1); } }
    .animate-pop { animation: pop 0.3s ease-out; }
</style>

<div class="fixed top-0 w-full z-50">
    <div class="bg-[#2D5A27] text-white text-[10px] font-bold uppercase tracking-[0.2em] py-2 text-center">
        Offerta Esclusiva: Spedizione Gratuita su tutti gli ordini sopra i 500€
    </div>

    <div class="bg-black text-white py-2 px-6 flex justify-between items-center border-b border-white/10">
        <div class="flex gap-6 items-center">
            <div class="flex items-center gap-2">
                <span class="text-[9px] text-zinc-400 uppercase font-black">Spedizione Gratuita sopra i 500€</span>
            </div>
            <div class="hidden md:flex items-center gap-2">
                <span class="text-[9px] text-zinc-400 uppercase font-black">Assistenza Clienti: +39 02 1234 5678</span>
            </div>
        </div>
        <div>
            <a href="track_order.php" class="text-[9px] text-white uppercase font-black hover:text-[#2D5A27] transition-colors tracking-widest">
                Traccia il tuo ordine
            </a>
        </div>
    </div>

    <header class="bg-white/95 backdrop-blur-md border-b-4 border-[#2D5A27] py-6">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-black uppercase tracking-tighter italic">
                CICLI<span class="text-[#2D5A27]">VOLANTE</span>
            </a>

            <nav class="hidden md:flex gap-10 text-[10px] font-black uppercase tracking-widest">
                <a href="index.php?cat=All_Bikes" class="hover:text-[#2D5A27] transition-colors">Tutte le bici</a>
                <a href="index.php?cat=MTB" class="hover:text-[#2D5A27] transition-colors">E-MTB</a>
                <a href="index.php?cat=CITY" class="hover:text-[#2D5A27] transition-colors">City</a>
                <a href="index.php?cat=TREKKING" class="hover:text-[#2D5A27] transition-colors">Trekking</a>
                <a href="index.php?cat=Accessori" class="hover:text-[#2D5A27] transition-colors">Accessori</a>
            </nav>

            <a href="checkout.php" class="relative group">
                <svg class="w-6 h-6 text-gray-900 group-hover:text-[#2D5A27] transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <?php $cart_total = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                <span id="cart-badge" style="display: <?= ($cart_total > 0) ? 'flex' : 'none' ?>;" 
                      class="absolute -top-2 -right-2 bg-[#2D5A27] text-white text-[9px] font-bold h-5 w-5 flex items-center justify-center rounded-full shadow-lg border-2 border-white">
                    <?= $cart_total ?>
                </span>
            </a>
        </div>
    </header>
</div>
<div class="h-40"></div>