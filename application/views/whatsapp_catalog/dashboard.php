<?php
// application/views/whatsapp_catalog/dashboard.php
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>WhatsApp Catalog Integration</h2>
            <div class="alert alert-info">
                <p>Manage your product synchronization with WhatsApp Business Catalog.</p>
                <p>Unsynced Products: <span class="badge bg-warning"><?php echo $unsynced_count; ?></span></p>
                <button id="sync-all-btn" class="btn btn-primary">Sync All Products</button>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>WhatsApp Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <img src="<?php echo base_url('assets/uploads/' . $product->image); ?>" 
                                 alt="<?php echo $product->name; ?>" width="50" class="me-2">
                            <?php echo $product->name; ?>
                        </td>
                        <td>$<?php echo number_format($product->price, 2); ?></td>
                        <td>
                            <?php if ($product->is_synced_with_whatsapp): ?>
                                <span class="badge bg-success">Synced</span>
                                <small class="text-muted">ID: <?php echo $product->whatsapp_catalog_id; ?></small>
                            <?php else: ?>
                                <span class="badge bg-warning">Not Synced</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product->is_synced_with_whatsapp): ?>
                                <button class="btn btn-sm btn-outline-primary update-product" 
                                        data-product-id="<?php echo $product->id; ?>">
                                    Update
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-success sync-product" 
                                        data-product-id="<?php echo $product->id; ?>">
                                    Sync to WhatsApp
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Sync individual product
    $('.sync-product, .update-product').click(function() {
        var productId = $(this).data('product-id');
        var button = $(this);
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Processing...');
        
        $.post('<?php echo site_url("whatsapp_catalog/sync_product/"); ?>' + productId, function(response) {
            if (response.success) {
                alert('Product synced successfully!');
                location.reload();
            } else {
                alert('Error: ' + response.message);
                button.prop('disabled', false).html('Try Again');
            }
        }).fail(function() {
            alert('An error occurred. Please try again.');
            button.prop('disabled', false).html('Try Again');
        });
    });
    
    // Sync all products
    $('#sync-all-btn').click(function() {
        var button = $(this);
        
        if (!confirm('This will sync all unsynced products to WhatsApp. Continue?')) {
            return;
        }
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Syncing...');
        
        $.post('<?php echo site_url("whatsapp_catalog/sync_all"); ?>', function(response) {
            if (response.success) {
                alert('Sync process completed!');
                location.reload();
            } else {
                alert('Error: ' + response.message);
                button.prop('disabled', false).html('Sync All Products');
            }
        }).fail(function() {
            alert('An error occurred. Please try again.');
            button.prop('disabled', false).html('Sync All Products');
        });
    });
});
</script>