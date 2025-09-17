<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('cart_model'); // ✅ load here
    }

    public function index() {
        $data['products'] = $this->product_model->get_products();
        $data['cart_count'] = count($this->cart_model->get_cart_items()); // ✅ add this

        $this->load->view('templates/header', $data);  // ✅ pass to header
        $this->load->view('shop/index', $data);
        $this->load->view('templates/footer');
    }

    public function product($id) {
        $data['product'] = $this->product_model->get_product($id);
        if (empty($data['product'])) {
            show_404();
        }

        $data['cart_count'] = count($this->cart_model->get_cart_items()); // ✅ add this

        $this->load->view('templates/header', $data); // ✅ pass to header
        $this->load->view('shop/product_detail', $data);
        $this->load->view('templates/footer');
    }
}
