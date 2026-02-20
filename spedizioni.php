<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spedizioni e Resi | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white antialiased">

    <?php include 'includes/header.php'; ?>

    <main class="max-w-4xl mx-auto px-6 py-24">
        <h1 class="text-6xl font-black uppercase italic tracking-tighter mb-12 text-zinc-900 text-center">
            Spedizioni <span class="text-[#2D5A27]">&</span> Resi
        </h1>

        <div class="space-y-16 mt-20">
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="col-span-1">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#2D5A27]">Tempi di Consegna</h3>
                </div>
                <div class="col-span-2">
                    <p class="text-lg font-bold text-zinc-800 leading-relaxed italic">
                        "Ogni Cicli Volante è un pezzo unico, assemblato con cura maniacale."
                    </p>
                    <p class="text-zinc-500 mt-4 text-sm leading-relaxed">
                        Le nostre e-bike richiedono un controllo finale di 48 ore prima della spedizione. La consegna avviene solitamente entro 5-7 giorni lavorativi in tutta Italia tramite corriere specializzato in trasporti di valore.
                    </p>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start border-y border-gray-100 py-16">
                <div class="col-span-1">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#2D5A27]">Costi di Spedizione</h3>
                </div>
                <div class="col-span-2">
                    <div class="flex justify-between items-center bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <span class="font-black uppercase text-xs">Ordini superiori a 500€</span>
                        <span class="text-[#2D5A27] font-black italic">GRATUITA</span>
                    </div>
                    <p class="text-zinc-500 mt-4 text-xs font-bold uppercase">Assicurazione inclusa su ogni spedizione.</p>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="col-span-1">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#2D5A27]">Politica di Reso</h3>
                </div>
                <div class="col-span-2 text-zinc-500 text-sm leading-relaxed">
                    <p>Hai 14 giorni di tempo per innamorarti della tua Cicli Volante. Se decidi di restituirla, il prodotto deve essere nelle condizioni originali, non utilizzato e nel suo imballaggio protettivo.</p>
                    <a href="supporto.php" class="inline-block mt-6 text-black font-black border-b-2 border-[#2D5A27] hover:bg-[#2D5A27] hover:text-white transition-all px-2">Contatta il Supporto per un reso</a>
                </div>
            </section>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>