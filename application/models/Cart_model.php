<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        
        // Create session if not exists
        if (!$this->session->userdata('session_id')) {
            $this->session->set_userdata('session_id', session_id());
        }
    }

    public function get_cart_items() {
        $session_id = $this->session->userdata('session_id');
        $this->db->select('cart.*, products.name, products.price, products.image');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id');
        $this->db->where('cart.user_session', $session_id);
        return $this->db->get()->result();
        
    }

    public function add_to_cart($product_id, $quantity = 1) {
        $session_id = $this->session->userdata('session_id');
        
        // Check if product already in cart
        $existing = $this->db->get_where('cart', array(
            'user_session' => $session_id,
            'product_id' => $product_id
        ))->row();
        
        if ($existing) {
            // Update quantity
            $this->db->where('id', $existing->id);
            $this->db->update('cart', array(
                'quantity' => $existing->quantity + $quantity
            ));
        } else {
            // Add new item
            $this->db->insert('cart', array(
                'user_session' => $session_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ));
        }
        
        return true;
    }

    public function update_cart_item($id, $quantity) {
        if ($quantity <= 0) {
            $this->remove_from_cart($id);
        } else {
            $this->db->where('id', $id);
            $this->db->update('cart', array('quantity' => $quantity));
        }
    }

    public function remove_from_cart($id) {
        $this->db->where('id', $id);
        $this->db->delete('cart');
    }

    public function get_cart_total() {
        $items = $this->get_cart_items();
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item->price * $item->quantity;
        }
        
        return $total;
    }

    public function clear_cart() {
        $session_id = $this->session->userdata('session_id');
        $this->db->where('user_session', $session_id);
        $this->db->delete('cart');
    }
}