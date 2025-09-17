<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cart_model');
        $this->load->model('order_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $cart_items = $this->cart_model->get_cart_items();
        if (empty($cart_items)) {
            redirect('shop');
        }
        
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $data['cart_items'] = $cart_items;
            $this->load->view('templates/header');
            $this->load->view('checkout/index', $data);
            $this->load->view('templates/footer');
        } else {
            // Process order
            $order_data = array(
                'order_number' => 'ORD' . time(),
                'customer_name' => $this->input->post('name'),
                'customer_email' => $this->input->post('email'),
                'customer_phone' => $this->input->post('phone'),
                'total_amount' => $this->cart_model->get_cart_total()
            );
            
            $order_id = $this->order_model->create_order($order_data, $cart_items);
            
            if ($order_id) {
                // Clear cart
                $this->cart_model->clear_cart();
                
                // Redirect to success page
                redirect('checkout/success/' . $order_data['order_number']);
            } else {
                $this->session->set_flashdata('error', 'There was a problem with your order. Please try again.');
                redirect('checkout');
            }
        }
    }

    public function success($order_number) {
        $data['order_number'] = $order_number;
        $this->load->view('templates/header');
        $this->load->view('checkout/success', $data);
        $this->load->view('templates/footer');
    }
}