<div id="chat-widget" class="fixed bottom-6 right-6 z-[200] flex flex-col items-end">
    <div id="chat-window" class="hidden w-85 bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden mb-4 flex-col">
        <div class="bg-[#2D5A27] p-4 text-white flex justify-between items-center">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-70">Concierge Digitale</p>
                <p class="text-sm font-bold italic editorial-font">Cicli Volante</p>
            </div>
            <button onclick="toggleChat()" class="text-white opacity-50 hover:opacity-100">×</button>
        </div>

        <div id="chat-content" class="h-80 overflow-y-auto p-4 space-y-4 bg-zinc-50 scroll-smooth">
            <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-[11px] leading-relaxed">
                Benvenuti nell'Atelier Cicli Volante. Sono qui per guidarvi nella scelta della vostra prossima bicicletta d'élite. Come posso aiutarvi?
            </div>
        </div>

        <div class="p-4 bg-white border-t border-gray-100 flex flex-wrap gap-2">
            <?php 
            $suggestions = [
                'spedizione' => 'Spedizione',
                'garanzia' => 'Garanzia',
                'test_drive' => 'Test Drive',
                'custom' => 'Su Misura'
            ];
            foreach($suggestions as $key => $label): ?>
                <button onclick="askChat('<?= $key ?>')" 
                        class="text-[9px] font-bold uppercase border border-gray-200 px-3 py-2 rounded-full hover:border-[#2D5A27] hover:text-[#2D5A27] transition-all">
                    <?= $label ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <button onclick="toggleChat()" class="bg-black text-white p-4 rounded-full shadow-2xl hover:bg-[#2D5A27] transition-all active:scale-95 flex items-center gap-3 group">
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-500 text-[10px] font-black uppercase tracking-widest">Chat con l'Atelier</span>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
    </button>
</div>

<script src="public/js/chat.js"></script>