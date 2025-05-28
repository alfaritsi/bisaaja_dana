<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muf extends CI_Controller {
    public function index() {
        $token = $this->get_token_api_muf();
        $data['areas'] = $this->get_area_from_api($token);
        $this->load->view('muf', $data);
    }
    private function get_token_api_muf() {    
        $username = 'bisaaja_id';
        $password = 'u4cg8MQnQom2spDFjQDHaerH';

        $url = 'https://mufdana.muf.co.id/dev/partners/login';
        $payload = json_encode([
            'username' => $username,
            'password' => $password
        ]);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);
        return $result['token'];
    }

    private function get_area_from_api($token) {
        $url = 'https://mufdana.muf.co.id/dev/partners/api/area';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        // Sesuaikan format tergantung respon API
        return isset($result['data']) ? $result['data'] : [];
    }    
}
