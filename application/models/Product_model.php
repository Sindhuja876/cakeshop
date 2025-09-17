<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_products() {
        return $this->db->get('products')->result();
    }

    public function get_product($id) {
        return $this->db->get_where('products', array('id' => $id))->row();
    }
public function get_products_for_whatsapp() {
        $this->db->select('id, name, description, price, image, whatsapp_catalog_id, is_synced_with_whatsapp');
        return $this->db->get('products')->result();
    }

    public function update_whatsapp_id($product_id, $whatsapp_catalog_id) {
        $this->db->where('id', $product_id);
        return $this->db->update('products', array(
            'whatsapp_catalog_id' => $whatsapp_catalog_id,
            'is_synced_with_whatsapp' => 1
        ));
    }

    public function log_sync_action($product_id, $action, $status, $response = '') {
        return $this->db->insert('whatsapp_sync_log', array(
            'product_id' => $product_id,
            'action' => $action,
            'status' => $status,
            'response' => $response
        ));
    }

    public function get_unsynced_products() {
        $this->db->where('is_synced_with_whatsapp', 0);
        return $this->db->get('products')->result();
    }

     
    
   
    
   
}