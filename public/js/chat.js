// public/js/chat.js
function toggleChat() {
    const win = document.getElementById('chat-window');
    win.classList.toggle('hidden');
    win.classList.toggle('flex');
}

const botResponses = {
    'spedizione': "Offriamo una spedizione assicurata in tutta Europa. Le nostre bici viaggiano in box 'Safety-First' pre-assemblate al 95%.",
    'garanzia': "Tutti i nostri telai in carbonio sono garantiti a vita per il primo proprietario. I componenti seguono la garanzia ufficiale di 2 anni.",
    'test_drive': "È possibile prenotare un test drive presso il nostro showroom di Milano o durante i nostri eventi 'Volante Experience'.",
    'custom': "Il nostro programma 'Sartoria' permette di personalizzare colori e componenti. Contattaci per un preventivo dedicato.",
    'default': "La vostra richiesta è stata inoltrata ai nostri esperti. Riceverete una risposta entro 15 minuti."
};

function askChat(type) {
    const content = document.getElementById('chat-content');
    
    // Message de l'utilisateur
    const userDiv = document.createElement('div');
    userDiv.className = "flex justify-end";
    userDiv.innerHTML = `<div class="bg-black text-white p-3 rounded-2xl rounded-tr-none text-[11px] font-bold uppercase tracking-tight">${type.replace('_', ' ')}</div>`;
    content.appendChild(userDiv);

    // Petit effet de "chargement"
    const typing = document.createElement('div');
    typing.className = "text-[10px] text-gray-400 animate-pulse italic bot-typing";
    typing.innerText = "L'Atelier sta scrivendo...";
    content.appendChild(typing);
    
    content.scrollTop = content.scrollHeight;

    setTimeout(() => {
        // Enlever l'indicateur de chargement
        document.querySelectorAll('.bot-typing').forEach(el => el.remove());

        // Message du Bot
        const botDiv = document.createElement('div');
        botDiv.className = "flex justify-start";
        botDiv.innerHTML = `<div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-[11px] leading-relaxed italic">${botResponses[type] || botResponses['default']}</div>`;
        content.appendChild(botDiv);
        
        content.scrollTop = content.scrollHeight;
    }, 1000);
}