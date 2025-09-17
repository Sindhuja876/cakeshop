<div class="row">
    <div class="col-12 text-center mb-4">
        <h1>Our Delicious Cakes</h1>
        <p class="lead">Browse our selection of handcrafted cakes</p>
    </div>
</div>

<div class="row">
    <?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 product-card">
            <img src="<?php echo base_url('assets/uploads/' . $product->image); ?>" class="card-img-top" alt="<?php echo $product->name; ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $product->name; ?></h5>
                <p class="card-text"><?php echo character_limiter($product->description, 100); ?></p>
                <p class="price">$<?php echo number_format($product->price, 2); ?></p>
            </div>
           <div class="card-footer bg-white">
             <div class="d-flex justify-content-between">
                        <a href="<?php echo site_url('shop/product/' . $product->id); ?>" class="btn btn-outline-primary">View Details</a>
                        <?php if (!empty($product->whatsapp_catalog_id)): ?>
                        <a href="https://wa.me/9750719814?text=Hi,%20I'm%20interested%20in%20<?php echo urlencode($product->name); ?>%20(Product ID: <?php echo $product->whatsapp_catalog_id; ?>)" 
                        class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp"></i> Inquire
                        </a>
                        
                            <?php else: ?>
                            <span class="text-muted">Not on WhatsApp yet</span>
                            <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

jhkjhjkh