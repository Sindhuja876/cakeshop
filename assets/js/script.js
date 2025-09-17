document.addEventListener('DOMContentLoaded', function() {
    // Update cart count in navbar
    function updateCartCount() {
        fetch('<?php echo site_url("cart/get_count"); ?>')
            .then(response => response.json())
            .then(data => {
                document.querySelector('.cart-count').textContent = data.count;
            });
    }
    
    // Add to cart with AJAX
    const addToCartForms = document.querySelectorAll('form[action*="cart/add"]');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    alert('Product added to cart!');
                }
            });
        });
    });
});