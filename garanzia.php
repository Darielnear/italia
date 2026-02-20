<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garanzia Premium | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white antialiased">

    <?php include 'includes/header.php'; ?>

    <main class="max-w-4xl mx-auto px-6 py-24">
        <div class="text-center mb-20">
            <h1 class="text-6xl font-black uppercase italic tracking-tighter mb-4 text-zinc-900">
                Garanzia <span class="text-[#2D5A27]">Premium</span>
            </h1>
            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-400">Protezione totale per la tua performance</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="p-10 bg-zinc-50 rounded-[2.5rem] border border-zinc-100">
                <div class="text-[#2D5A27] mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04kM12 21.48l0.356-0.017a11.948 11.948 0 005.274-2.433L12 21.48z" stroke-width="1.5"/></svg>
                </div>
                <h3 class="text-xl font-black uppercase italic mb-4 text-zinc-900">Telaio e Forcella</h3>
                <p class="text-sm text-zinc-500 leading-relaxed italic">Garantiamo l'integrità strutturale dei nostri telai in carbonio e alluminio aeronautico per <strong>5 anni</strong>. La perfezione non ha scadenza breve.</p>
            </div>

            <div class="p-10 bg-zinc-900 rounded-[2.5rem] text-white">
                <div class="text-[#2D5A27] mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="1.5"/></svg>
                </div>
                <h3 class="text-xl font-black uppercase italic mb-4">Power Unit</h3>
                <p class="text-sm text-zinc-400 leading-relaxed italic">Motore e batteria sono coperti da una garanzia ufficiale di <strong>2 anni</strong>. In caso di calo di performance superiore al 20%, la sostituzione è immediata.</p>
            </div>
        </div>

        <div class="mt-20 border-t border-zinc-100 pt-16">
            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#2D5A27] mb-8 italic text-center">Come attivare la garanzia</h4>
            <p class="text-zinc-500 text-center max-w-2xl mx-auto text-sm leading-relaxed">
                Ogni bici Cicli Volante viene consegnata con un certificato di autenticità numerato. Per attivare la garanzia, basta conservare la ricevuta caricata durante il checkout. Il tuo numero d'ordine è la tua chiave di accesso all'assistenza prioritaria.
            </p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>