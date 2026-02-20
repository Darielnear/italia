<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supporto | Cicli Volante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#fcfcfc] text-gray-900">

    <nav class="p-8 border-b border-gray-100 flex justify-between items-center bg-white">
        <a href="index.php" class="text-xl font-black italic uppercase tracking-tighter">
            CICLI <span class="text-[#2D5A27]">VOLANTE</span>
        </a>
        <a href="index.php" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors">Torna all'atelier</a>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-20">
        
        <div class="text-center mb-20">
            <h1 class="text-5xl font-black uppercase italic tracking-tighter mb-4">Supporto <span class="text-[#2D5A27]">Tecnico</span></h1>
            <p class="text-gray-400 uppercase text-[10px] font-bold tracking-[0.3em]">Siamo qui per aiutarti a pedalare meglio</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <div>
                <h2 class="text-xl font-black uppercase italic mb-8 border-l-4 border-[#2D5A27] pl-4">Contatti Diretti</h2>
                
                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                            <svg class="w-5 h-5 text-[#2D5A27]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Email</p>
                            <p class="font-bold text-sm">supporto@ciclivolante.it</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                            <svg class="w-5 h-5 text-[#2D5A27]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Orari Atelier</p>
                            <p class="font-bold text-sm">Lun - Ven / 09:00 - 18:00</p>
                        </div>
                    </div>

                    <div class="bg-[#2D5A27] p-8 rounded-[2rem] text-white mt-10">
                        <h3 class="font-black uppercase italic text-sm mb-2">Assistenza Prioritaria</h3>
                        <p class="text-xs text-white/70 leading-relaxed">Se hai già effettuato un ordine, tieni a portata di mano il tuo numero di ordine (es: VOL-2024-XXXX) per velocizzare la procedura.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-50">
                <form action="#" method="POST" class="space-y-5">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-2 mb-2 block">Nome Completo</label>
                        <input type="text" placeholder="Mario Rossi" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-[#2D5A27] transition-all text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-2 mb-2 block">Indirizzo Email</label>
                        <input type="email" placeholder="mario@email.com" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-[#2D5A27] transition-all text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-2 mb-2 block">Messaggio</label>
                        <textarea rows="4" placeholder="Come possiamo aiutarti?" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-[#2D5A27] transition-all text-sm"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-black text-white font-black py-5 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-[#2D5A27] transition-all shadow-lg">Invia Messaggio</button>
                </form>
            </div>

        </div>

        <div class="mt-32">
            <h2 class="text-xl font-black uppercase italic mb-10 text-center">Domande Frequenti</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="border-t border-gray-100 pt-6">
                    <p class="font-bold text-sm mb-2">Tempi di consegna?</p>
                    <p class="text-xs text-gray-500 leading-relaxed">In media 5-7 giorni lavorativi dopo la verifica del pagamento.</p>
                </div>
                <div class="border-t border-gray-100 pt-6">
                    <p class="font-bold text-sm mb-2">Garanzia?</p>
                    <p class="text-xs text-gray-500 leading-relaxed">Tutti i nostri telai sono garantiti 5 anni per difetti di fabbricazione.</p>
                </div>
                <div class="border-t border-gray-100 pt-6">
                    <p class="font-bold text-sm mb-2">Resi?</p>
                    <p class="text-xs text-gray-500 leading-relaxed">Hai 14 giorni per cambiare idea, a patto che la bici non sia stata utilizzata.</p>
                </div>
            </div>
        </div>

    </main>

    <footer class="py-10 text-center text-gray-300 text-[10px] font-bold uppercase tracking-widest">
        &copy; 2024 Cicli Volante - Officina Meccanica d'Avanguardia
    </footer>

</body>
</html>