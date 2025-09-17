<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_order($order_data, $cart_items) {
        $this->db->trans_start();
        
        // Insert order
        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();
        
        // Insert order items
        foreach ($cart_items as $item) {
            $order_item = array(
                'order_id' => $order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            );
            $this->db->insert('order_items', $order_item);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $order_id : false;
    }
}