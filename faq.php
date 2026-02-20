<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white antialiased">

    <?php include 'includes/header.php'; ?>

    <main class="max-w-3xl mx-auto px-6 py-24">
        <h1 class="text-5xl font-black uppercase italic tracking-tighter mb-16 text-zinc-900">Domande <span class="text-[#2D5A27]">Frequenti</span></h1>

        <div class="space-y-8">
            <div class="border-b border-zinc-100 pb-8">
                <h3 class="font-black uppercase text-sm mb-4 tracking-wide text-zinc-900 italic">Come posso fidarmi del pagamento tramite bonifico/postepay?</h3>
                <p class="text-zinc-500 text-sm leading-relaxed">Cicli Volante è un atelier certificato. Caricando la ricevuta direttamente sul sito, il nostro sistema blocca immediatamente il prodotto scelto nel magazzino, garantendoti la priorità assoluta sull'assemblaggio.</p>
            </div>

            <div class="border-b border-zinc-100 pb-8">
                <h3 class="font-black uppercase text-sm mb-4 tracking-wide text-zinc-900 italic">La bici arriva già montata?</h3>
                <p class="text-zinc-500 text-sm leading-relaxed">Sì, la bici arriva montata al 95%. Dovrai solo raddrizzare il manubrio e montare i pedali (inclusi nel kit di benvenuto). Riceverai un video tutorial personalizzato via email.</p>
            </div>

            <div class="border-b border-zinc-100 pb-8">
                <h3 class="font-black uppercase text-sm mb-4 tracking-wide text-zinc-900 italic">Posso modificare il mio ordine dopo il pagamento?</h3>
                <p class="text-zinc-500 text-sm leading-relaxed">Puoi richiedere modifiche entro 12 ore dal caricamento della ricevuta contattando il nostro servizio clienti WhatsApp o tramite il Centro Assistenza.</p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>