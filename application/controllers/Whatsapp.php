<?php
// application/controllers/Whatsapp.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('product_model');
    }
    
    public function sync_products() {
        // Sync all products to WhatsApp catalog
        $products = $this->product_model->get_products();
        $results = array();
        
        foreach ($products as $product) {
            $results[$product->id] = $this->product_model->sync_with_whatsapp($product->id);
        }
        
        echo json_encode($results);
    }
    
    public function webhook() {
        // Webhook for receiving messages from WhatsApp
        $input = json_decode(file_get_contents('php://input'), true);
        
        log_message('debug', 'WhatsApp Webhook: ' . print_r($input, true));
        
        // Process different types of messages
        if (isset($input['entry'][0]['changes'][0]['value']['messages'])) {
            $messages = $input['entry'][0]['changes'][0]['value']['messages'];
            
            foreach ($messages as $message) {
                $this->process_message($message);
            }
        }
        
        // Return 200 OK to acknowledge receipt
        header("HTTP/1.1 200 OK");
        echo "OK";
    }
    
    private function process_message($message) {
        $from = $message['from']; // Customer's WhatsApp number
        $type = $message['type'];
        
        if ($type === 'interactive') {
            // Handle interactive messages (e.g., product selections)
            $interactive_type = $message['interactive']['type'];
            
            if ($interactive_type === 'button_reply') {
                $this->handle_button_reply($from, $message);
            } else if ($interactive_type === 'list_reply') {
                $this->handle_list_reply($from, $message);
            }
        } else if ($type === 'text') {
            // Handle text messages
            $this->handle_text_message($from, $message['text']['body']);
        }
    }
    
    private function handle_text_message($from, $text) {
        // Simple keyword-based responses
        $text = strtolower(trim($text));
        
        if (strpos($text, 'menu') !== false || strpos($text, 'cakes') !== false) {
            $this->send_product_list($from);
        } else if (strpos($text, 'order') !== false || strpos($text, 'buy') !== false) {
            $this->send_order_instructions($from);
        } else if (strpos($text, 'hours') !== false || strpos($text, 'open') !== false) {
            $this->send_business_hours($from);
        } else {
            $this->send_welcome_message($from);
        }
    }
    
    private function send_product_list($to) {
        $products = $this->product_model->get_products();
        
        // Create interactive product list
        $sections = array();
        $product_items = array();
        
        foreach ($products as $product) {
            $product_items[] = array(
                'id' => 'product_' . $product->id,
                'title' => $product->name,
                'description' => '$' . number_format($product->price, 2)
            );
            
            // WhatsApp lists have a limit of 10 items per section
            if (count($product_items) >= 10) {
                $sections[] = array(
                    'title' => 'Our Cakes',
                    'rows' => $product_items
                );
                $product_items = array();
            }
        }
        
        if (count($product_items) > 0) {
            $sections[] = array(
                'title' => 'Our Cakes',
                'rows' => $product_items
            );
        }
        
        // Send the list message via WhatsApp API
        $this->send_whatsapp_message($to, array(
            'type' => 'interactive',
            'interactive' => array(
                'type' => 'list',
                'header' => array(
                    'type' => 'text',
                    'text' => 'Our Cake Menu'
                ),
                'body' => array(
                    'text' => 'Browse our delicious cakes and select one to view details'
                ),
                'footer' => array(
                    'text' => 'Sweet Delights Cake Shop'
                ),
                'action' => array(
                    'button' => 'View Menu',
                    'sections' => $sections
                )
            )
        ));
    }
    
    private function send_whatsapp_message($to, $message) {
        // Implementation for sending messages via WhatsApp Business API
        $api_url = 'https://graph.facebook.com/v13.0/{your-phone-number-id}/messages';
        $access_token = 'YOUR_WHATSAPP_BUSINESS_ACCESS_TOKEN';
        
        $data = array(
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => $message['type'],
            $message['type'] => $message[$message['type']]
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    // ... other message handling methods ...
}