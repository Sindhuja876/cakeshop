<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_catalog extends CI_Controller {

    private $whatsapp_config;

    public function __construct() {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->library('upload');
        
        // Load WhatsApp configuration (you should store this in config file)
        $this->whatsapp_config = array(
            'business_id' => 'YOUR_BUSINESS_ID',
            'phone_number_id' => 'YOUR_PHONE_NUMBER_ID',
            'access_token' => 'YOUR_ACCESS_TOKEN',
            'api_version' => 'v16.0',
            'catalog_id' => 'YOUR_CATALOG_ID'
        );
    }

    public function index() {
        // Admin page to manage WhatsApp integration
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $data['products'] = $this->product_model->get_products_for_whatsapp();
        $data['unsynced_count'] = count($this->product_model->get_unsynced_products());
        
        $this->load->view('templates/header');
        $this->load->view('whatsapp_catalog/dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function sync_product($product_id) {
        if (!$this->session->userdata('admin_logged_in')) {
            echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
            return;
        }

        $product = $this->product_model->get_product($product_id);
        if (!$product) {
            echo json_encode(array('success' => false, 'message' => 'Product not found'));
            return;
        }

        // Check if product already exists in WhatsApp catalog
        if (!empty($product->whatsapp_catalog_id)) {
            $result = $this->update_whatsapp_product($product);
        } else {
            $result = $this->create_whatsapp_product($product);
        }

        echo json_encode($result);
    }

    public function sync_all() {
        if (!$this->session->userdata('admin_logged_in')) {
            echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
            return;
        }

        $unsynced_products = $this->product_model->get_unsynced_products();
        $results = array();

        foreach ($unsynced_products as $product) {
            $results[$product->id] = $this->create_whatsapp_product($product);
        }

        echo json_encode(array('success' => true, 'results' => $results));
    }

    private function create_whatsapp_product($product) {
        $api_url = "https://graph.facebook.com/{$this->whatsapp_config['api_version']}/{$this->whatsapp_config['catalog_id']}/products";

        $post_data = array(
            'name' => $product->name,
            'description' => $product->description,
            'currency' => 'USD',
            'price' => $product->price,
            'url' => site_url('shop/product/' . $product->id),
            'retailer_id' => 'CAKE_' . $product->id
        );

        // If product has an image, upload it to WhatsApp
        if (!empty($product->image)) {
            $image_url = base_url('assets/uploads/' . $product->image);
            $image_upload_result = $this->upload_image_to_whatsapp($image_url);
            
            if ($image_upload_result['success']) {
                $post_data['image_url'] = $image_upload_result['image_handle'];
            }
        }

        $response = $this->call_whatsapp_api($api_url, 'POST', $post_data);
        $response_data = json_decode($response, true);

        if (isset($response_data['id'])) {
            $this->product_model->update_whatsapp_id($product->id, $response_data['id']);
            $this->product_model->log_sync_action($product->id, 'create', 'success', $response);
            return array('success' => true, 'message' => 'Product synced successfully', 'whatsapp_id' => $response_data['id']);
        } else {
            $this->product_model->log_sync_action($product->id, 'create', 'failed', $response);
            return array('success' => false, 'message' => 'Failed to sync product: ' . json_encode($response_data));
        }
    }

    private function update_whatsapp_product($product) {
        $api_url = "https://graph.facebook.com/{$this->whatsapp_config['api_version']}/{$product->whatsapp_catalog_id}";

        $post_data = array(
            'name' => $product->name,
            'description' => $product->description,
            'currency' => 'USD',
            'price' => $product->price,
            'url' => site_url('shop/product/' . $product->id)
        );

        $response = $this->call_whatsapp_api($api_url, 'POST', $post_data);
        $response_data = json_decode($response, true);

        if (isset($response_data['success']) && $response_data['success']) {
            $this->product_model->log_sync_action($product->id, 'update', 'success', $response);
            return array('success' => true, 'message' => 'Product updated successfully');
        } else {
            $this->product_model->log_sync_action($product->id, 'update', 'failed', $response);
            return array('success' => false, 'message' => 'Failed to update product: ' . json_encode($response_data));
        }
    }

    private function upload_image_to_whatsapp($image_url) {
        $api_url = "https://graph.facebook.com/{$this->whatsapp_config['api_version']}/{$this->whatsapp_config['business_id']}/message_attachments";
        
        $post_data = array(
            'message' => json_encode(array(
                'attachment' => array(
                    'type' => 'image',
                    'payload' => array(
                        'url' => $image_url,
                        'is_reusable' => true
                    )
                )
            ))
        );

        $response = $this->call_whatsapp_api($api_url, 'POST', $post_data);
        $response_data = json_decode($response, true);

        if (isset($response_data['attachment_id'])) {
            return array('success' => true, 'image_handle' => $response_data['attachment_id']);
        } else {
            return array('success' => false, 'error' => $response_data);
        }
    }

    private function call_whatsapp_api($url, $method = 'GET', $data = array()) {
        $ch = curl_init();
        
        $headers = array(
            'Authorization: Bearer ' . $this->whatsapp_config['access_token'],
            'Content-Type: application/json'
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;
    }

    public function webhook() {
        // Handle incoming webhooks from WhatsApp
        $payload = json_decode(file_get_contents('php://input'), true);
        
        log_message('debug', 'WhatsApp Webhook: ' . print_r($payload, true));
        
        // Verify the webhook request
        if ($this->verify_webhook()) {
            // Process different webhook types
            if (isset($payload['object']) && $payload['object'] == 'whatsapp_business_account') {
                foreach ($payload['entry'] as $entry) {
                    foreach ($entry['changes'] as $change) {
                        $this->process_webhook_change($change);
                    }
                }
            }
            
            http_response_code(200);
            echo 'WEBHOOK_PROCESSED';
        } else {
            http_response_code(403);
            echo 'VERIFICATION_FAILED';
        }
    }
    
    private function verify_webhook() {
        // Verify webhook request from WhatsApp
        $verify_token = 'YOUR_VERIFY_TOKEN'; // Set this in WhatsApp app settings
        
        if (isset($_GET['hub_mode']) && 
            isset($_GET['hub_verify_token']) && 
            isset($_GET['hub_challenge'])) {
            
            if ($_GET['hub_mode'] === 'subscribe' && 
                $_GET['hub_verify_token'] === $verify_token) {
                echo $_GET['hub_challenge'];
                return true;
            }
        }
        
        return false;
    }
    
    private function process_webhook_change($change) {
        // Process different types of webhook changes
        $field = $change['field'];
        
        switch ($field) {
            case 'messages':
                $this->process_message_webhook($change['value']);
                break;
            case 'message_template_status_update':
                $this->process_template_status_webhook($change['value']);
                break;
            case 'phone_number_quality_update':
                $this->process_quality_webhook($change['value']);
                break;
            default:
                log_message('debug', 'Unhandled webhook field: ' . $field);
        }
    }
    
    // Add other webhook processing methods as needed
}