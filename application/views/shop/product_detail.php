<div class="row">
    <div class="col-md-6">
        <img src="<?php echo base_url('assets/uploads/' . $product->image); ?>" class="img-fluid rounded" alt="<?php echo $product->name; ?>">
    </div>
    <div class="col-md-6">
        <h1><?php echo $product->name; ?></h1>
        <p class="lead">$<?php echo number_format($product->price, 2); ?></p>
        <p><?php echo $product->description; ?></p>

        <div class="mt-3">
    <p>Share this product on WhatsApp:</p>
    <a href="whatsapp://send?text=Check out this delicious cake from Sweet Delights: <?php echo urlencode($product->name); ?> - <?php echo site_url('shop/product/' . $product->id); ?>" 
       class="btn btn-outline-success">
        <i class="fab fa-whatsapp"></i> Share via WhatsApp
    </a>
</div>
        
        <div class="d-flex mt-4">
            <?php echo form_open('cart/add', array('class' => 'd-flex')); ?>
                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                <div class="me-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 80px;">
                </div>
                <div class="align-self-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            <?php echo form_close(); ?>
            
            <?php if (!empty($product->whatsapp_catalog_id)): ?>
            <div class="align-self-end ms-3">
                <a href="https://wa.me/9750719814?text=Hi,%20I'm%20interested%20in%20<?php echo urlencode($product->name); ?>%20(Product ID: <?php echo $product->whatsapp_catalog_id; ?>)" 
                   class="btn btn-success" target="_blank">
                    <i class="fab fa-whatsapp"></i> Inquire on WhatsApp
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>