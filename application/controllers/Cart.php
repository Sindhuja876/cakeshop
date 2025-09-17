<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('product_model');
    }

    public function index() {
        $data['cart_items'] = $this->cart_model->get_cart_items();
        $this->load->view('templates/header');
        $this->load->view('cart/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity');
        
        $product = $this->product_model->get_product($product_id);
        if ($product) {
            $this->cart_model->add_to_cart($product_id, $quantity);
            $this->session->set_flashdata('success', 'Product added to cart!');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function update() {
        $cart_items = $this->input->post('cart');
        foreach ($cart_items as $id => $item) {
            $this->cart_model->update_cart_item($id, $item['quantity']);
        }
        
        $this->session->set_flashdata('success', 'Cart updated!');
        redirect('cart');
    }

    public function remove($id) {
        $this->cart_model->remove_from_cart($id);
        $this->session->set_flashdata('success', 'Item removed from cart!');
        redirect('cart');
    }
      
}