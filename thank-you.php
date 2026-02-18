<?php include 'includes/header.php'; ?>

<main class="min-h-screen flex items-center justify-center px-6 pt-20">
    <div class="text-center max-w-lg fade-in">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-accent"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1 class="text-4xl font-bold text-anthracite mb-4">Grazie!</h1>
        <p class="text-gray-600 text-lg mb-10">
            Il tuo ordine è stato ricevuto. Abbiamo inviato una email di conferma. 
            Stiamo verificando il pagamento e spediremo il tuo ordine al più presto.
        </p>
        <a href="index.php" class="inline-block bg-anthracite text-white font-bold py-3 px-8 rounded-full hover:bg-accent transition-colors duration-300">
            Torna alla Home
        </a>
    </div>
</main>

<script>
    // Clear cart on success
    localStorage.removeItem('cart');
    // Update cart badge
    document.getElementById('cart-count').style.opacity = '0';
</script>

<?php include 'includes/footer.php'; ?>
