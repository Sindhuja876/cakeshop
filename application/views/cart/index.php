<div class="row">
    <div class="col-12">
        <h1>Shopping Cart</h1>
        
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="<?php echo site_url('shop'); ?>">Continue shopping</a>
            </div>
        <?php else: ?>
            <?php echo form_open('cart/update'); ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo base_url('assets/uploads/' . $item->image); ?>" 
                                 alt="<?php echo $item->name; ?>" width="50" class="me-2">
                            <?php echo $item->name; ?>
                        </td>
                        <td>$<?php echo number_format($item->price, 2); ?></td>
                        <td>
                            <input type="number" name="cart[<?php echo $item->id; ?>][quantity]" 
                                   value="<?php echo $item->quantity; ?>" min="1" class="form-control" style="width: 80px;">
                        </td>
                        <td>$<?php echo number_format($item->price * $item->quantity, 2); ?></td>
                        <td>
                            <a href="<?php echo site_url('cart/remove/' . $item->id); ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $total += $item->price * $item->quantity; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="d-flex justify-content-between">
             <a href="<?php echo site_url('shop'); ?>" class="btn btn-secondary">Continue Shopping</a>
                <div>
                    <button type="submit" class="btn btn-primary">Update Cart</button>
                    <a href="<?php echo site_url('checkout'); ?>" class="btn btn-success">Proceed to Checkout</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        <?php endif; ?>
    </div>
</div>