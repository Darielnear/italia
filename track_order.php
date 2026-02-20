<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traccia Ordine | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-zinc-950 text-white antialiased"> <?php include 'includes/header.php'; ?>

    <main class="max-w-xl mx-auto px-6 py-32 text-center">
        <div class="mb-12">
            <div class="w-20 h-20 bg-[#2D5A27] rounded-full flex items-center justify-center mx-auto mb-8 shadow-[0_0_50px_rgba(45,90,39,0.3)]">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3"/></svg>
            </div>
            <h1 class="text-5xl font-black uppercase italic tracking-tighter mb-4">Traccia il tuo <span class="text-[#2D5A27]">Sogno</span></h1>
            <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest">Inserisci il codice ricevuto via email</p>
        </div>

        <form action="order_status.php" method="GET" class="space-y-6">
            <input type="text" name="order_id" placeholder="ES: VOL-2026-XXXX" 
                   class="w-full bg-zinc-900 border-2 border-zinc-800 rounded-2xl px-8 py-6 text-xl font-black uppercase tracking-widest focus:outline-none focus:border-[#2D5A27] transition-all text-center">
            
            <button type="submit" class="w-full bg-[#2D5A27] text-white font-black py-6 rounded-2xl uppercase tracking-[0.2em] hover:bg-white hover:text-black transition-all">
                Verifica Stato Spedizione
            </button>
        </form>

        <p class="mt-12 text-[10px] text-zinc-600 font-bold uppercase tracking-widest">
            Problemi con il codice? <a href="supporto.php" class="text-zinc-400 hover:text-white underline">Contatta l'Atelier</a>
        </p>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>