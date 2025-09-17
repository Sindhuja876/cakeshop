<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card">
            <div class="card-body py-5">
                <div class="text-success mb-4">
                    <i class="fas fa-check-circle fa-5x"></i>
                </div>
                <h2>Thank You for Your Order!</h2>
                <p class="lead">Your order number is: <strong><?php echo $order_number; ?></strong></p>
                <p>We will contact you shortly to confirm your order details and arrange for payment.</p>
                
                <div class="mt-4">
                    <p>You can also contact us directly on WhatsApp:</p>
                    <a href="https://wa.me/9750719814?text=Hi,%20I%20just%20placed%20order%20<?php echo $order_number; ?>%20and%20would%20like%20to%20confirm%20details" 
                       class="btn btn-success btn-lg" target="_blank">
                        <i class="fab fa-whatsapp"></i> Message Us on WhatsApp
                    </a>
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo site_url('shop'); ?>" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>