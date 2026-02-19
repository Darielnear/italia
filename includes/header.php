<div class="fixed top-0 w-full z-[60] bg-anthracite text-white text-[9px] font-black py-2.5 text-center uppercase tracking-[0.25em]">
    OFFERTA ESCLUSIVA: SPEDIZIONE GRATUITA SOPRA I 500€
</div>

<header x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="scrolled ? 'glass py-3' : 'bg-transparent py-6'" class="fixed top-[31px] w-full z-[50] transition-all duration-500">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
        <a href="index.php" class="text-2xl font-black tracking-tighter uppercase text-anthracite group">
            CICLI<span class="text-accent italic transition-colors group-hover:text-anthracite">VOLANTE</span>
        </a>
        
        <nav class="hidden lg:flex items-center gap-10">
            <a href="index.php" class="text-[10px] font-bold uppercase tracking-[0.2em] text-anthracite hover:text-accent transition-colors">Tutte le bici</a>
            <a href="index.php?brand=Specialized" class="text-[10px] font-bold uppercase tracking-[0.2em] text-anthracite hover:text-accent transition-colors">E-MTB</a>
            <a href="#" class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent hover:text-anthracite transition-colors">Promozioni</a>
        </nav>

        <div class="flex items-center gap-4">
            <a href="checkout.php" class="group relative flex items-center justify-center w-12 h-12 rounded-full glass hover:bg-anthracite transition-all duration-500 shadow-sm">
                <svg class="group-hover:stroke-white transition-colors" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"/>
                    <path d="M3 6H21"/><path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                <span id="cart-badge" class="<?= $cart_count > 0 ? '' : 'hidden' ?> absolute -top-1 -right-1 bg-accent text-white text-[9px] font-black h-5 w-5 flex items-center justify-center rounded-full border-2 border-white soft-shadow">
                    <?= $cart_count ?>
                </span>
            </a>
            
            <button class="lg:hidden w-12 h-12 rounded-full glass flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
            </button>
        </div>
    </div>
</header>