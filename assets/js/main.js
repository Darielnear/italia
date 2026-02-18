// Main JS logic
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    // Filters on Index
    const filterBtns = document.querySelectorAll('.filter-btn');
    const products = document.querySelectorAll('.product-card');
    const grid = document.getElementById('product-grid');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // UI Update (Tabs style)
            filterBtns.forEach(b => {
                b.classList.remove('active', 'text-anthracite', 'font-bold');
                b.classList.add('text-gray-400', 'font-medium');
                const span = b.querySelector('span');
                if (span) {
                    span.style.width = '0';
                    span.style.opacity = '0';
                }
            });
            
            btn.classList.add('active', 'text-anthracite', 'font-bold');
            btn.classList.remove('text-gray-400', 'font-medium');
            const activeSpan = btn.querySelector('span');
            if (activeSpan) {
                activeSpan.style.width = '100%';
                activeSpan.style.opacity = '1';
            }

            // Logic with Fade Animation
            const filter = btn.dataset.filter;
            
            // Start fade out
            if (grid) grid.style.opacity = '0';
            
            setTimeout(() => {
                products.forEach(p => {
                    if (filter === 'all' || p.dataset.category === filter) {
                        p.style.display = 'block';
                    } else {
                        p.style.display = 'none';
                    }
                });
                // Fade back in
                if (grid) {
                    grid.style.transition = 'opacity 0.4s ease-in-out';
                    grid.style.opacity = '1';
                }
            }, 300);
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
