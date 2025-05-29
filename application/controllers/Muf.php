<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Muf extends MY_Controller
{
    const CACHE_TIME = 900; // in seconds (30 minutes)

    public function index()
    {
        $token = $this->get_token_api_muf();
        $data['areas'] = $this->get_area_from_api($token);
        $this->load->view('muf', $data);
    }

    private function get_token_api_muf()
    {
        $key = 'cache_token_api_muf';
        $token = $this->cache->get($key);

        if (!$token) {
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
            $token = $result['token'] ?? null;
            if ($token) {
                $this->cache->save($key, $token, self::CACHE_TIME);
            }
        }

        return $token;
    }

    private function get_area_from_api($token)
    {
        $key = "cache_area_from_api_$token";
        $area = $this->cache->get($key);

        if (!$area) {
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
            $area = $result['data'] ?? [];
            $area = json_encode($area);
            $this->cache->save($key, $area, self::CACHE_TIME);
        }

        return json_decode($area, true);
    }
}
