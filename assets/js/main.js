// Main JS logic
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    // Filters on Index
    const filterBtns = document.querySelectorAll('.filter-btn');
    const products = document.querySelectorAll('.product-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // UI
            filterBtns.forEach(b => {
                b.classList.remove('bg-anthracite', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-600');
            });
            btn.classList.remove('bg-gray-100', 'text-gray-600');
            btn.classList.add('bg-anthracite', 'text-white');

            // Logic
            const filter = btn.dataset.filter;
            products.forEach(p => {
                if (filter === 'all' || p.dataset.category === filter) {
                    p.style.display = 'block';
                    p.classList.add('fade-in');
                } else {
                    p.style.display = 'none';
                    p.classList.remove('fade-in');
                }
            });
        });
    });
});

// Cart Logic
function addToCart(id, name, price) {
    const variantInput = document.getElementById('selected-variant');
    const variant = variantInput ? variantInput.value : 'Default';
    
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    const existing = cart.find(item => item.id === id && item.variant === variant);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ id, name, price, variant, quantity: 1 });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    
    // Simple feedback
    const btn = document.querySelector('button[onclick^="addToCart"]');
    const originalText = btn.innerText;
    btn.innerText = "Aggiunto!";
    btn.classList.add('bg-accent');
    setTimeout(() => {
        btn.innerText = originalText;
        btn.classList.remove('bg-accent');
    }, 2000);
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const count = cart.reduce((acc, item) => acc + item.quantity, 0);
    const badge = document.getElementById('cart-count');
    
    if (badge) {
        badge.innerText = count;
        badge.style.opacity = count > 0 ? '1' : '0';
    }
}
