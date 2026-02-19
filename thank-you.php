<?php
include 'includes/header.php';

// On récupère la référence générée dans process_order.php
$order_ref = isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : 'ITA-ORD-001';
?>

<main class="max-w-3xl mx-auto px-6 py-32 text-center">
    <div class="mb-8 flex justify-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl md:text-5xl font-extrabold text-anthracite mb-4 uppercase tracking-tighter">
        Ordine Ricevuto!
    </h1>
    <p class="text-xl text-gray-500 mb-10 font-light">
        Grazie per aver scelto <span class="font-bold text-anthracite">Cicli Volante</span>. La tua passione per il ciclismo è in buone mani.
    </p>

    <div class="bg-gray-50 border border-gray-100 rounded-3xl p-8 mb-12 shadow-inner">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Riferimento del tuo ordine</p>
        <p class="text-3xl font-mono font-black text-accent mb-6 select-all"><?= $order_ref ?></p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mb-3">📩</div>
                <p class="text-gray-600">Riceverai una <b>mail di conferma</b> con il riepilogo.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mb-3">🔍</div>
                <p class="text-gray-600">Verificheremo la tua <b>ricevuta di pagamento</b> entro 24h.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mb-3">🚚</div>
                <p class="text-gray-600">Ti invieremo il <b>codice di tracciamento</b> per la spedizione.</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4 justify-center">
        <a href="index.php" class="bg-anthracite hover:bg-black text-white px-10 py-4 rounded-2xl font-bold transition-all transform active:scale-95 uppercase tracking-widest text-sm">
            Torna alla Home
        </a>
        <button onclick="window.print()" class="bg-white border-2 border-gray-100 hover:border-gray-200 text-gray-600 px-10 py-4 rounded-2xl font-bold transition-all text-sm uppercase tracking-widest">
            Stampa Ricevuta
        </button>
    </div>

    <p class="mt-16 text-gray-400 text-xs italic">
        Hai domande? Contattaci su WhatsApp al nostro numero ufficiale.
    </p>
</main>

<?php include 'includes/footer.php'; ?>