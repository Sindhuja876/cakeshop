<div class="row">
    <div class="col-md-8">
        <h2>Checkout</h2>
        
        <?php echo form_open(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required 
                       value="<?php echo set_value('name'); ?>">
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required 
                       value="<?php echo set_value('email'); ?>">
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required 
                       value="<?php echo set_value('phone'); ?>">
            </div>
            
            <h4 class="mt-4">Order Summary</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo $item->name; ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td>$<?php echo number_format($item->price, 2); ?></td>
                        <td>$<?php echo number_format($item->price * $item->quantity, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($this->cart_model->get_cart_total(), 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Place Order</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>