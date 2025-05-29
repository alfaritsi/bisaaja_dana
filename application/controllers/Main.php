<?php
/** @noinspection DuplicatedCode */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 */
class Main extends MY_Controller
{
    const CACHE_TIME = 900; // in seconds (30 minutes)

    public function index()
    {
        $data['regions'] = $this->get_region_api();
        $data['categories'] = $this->get_category_api();
        $this->load->view('main', $data);
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

    private function get_region_api()
    {
        $key = 'cache_region_api';
        $region = $this->cache->get($key);

        if (!$region) {
            $url = 'https://mufdana.muf.co.id/dev/partners/api/area';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->get_token_api_muf()
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $result = json_decode($response, true);

            // Sesuaikan format tergantung respon API
            $region = $result['data'] ?? [];
            $region = json_encode($region);
            $this->cache->save($key, $region, self::CACHE_TIME);
        }

        return json_decode($region, true);
    }

    //motor/mobil
    private function get_category_api()
    {
        $key = 'cache_category_api';
        $category = $this->cache->get($key);

        if (!$category) {
            $url = 'https://mufdana.muf.co.id/dev/partners/api/kind';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->get_token_api_muf()
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $result = json_decode($response, true);

            // Sesuaikan format tergantung respon API
            $category = $result['data'] ?? [];
            $category = json_encode($category);
            $this->cache->save($key, $category, self::CACHE_TIME);
        }

        return json_decode($category, true);
    }

    public function get_merk_by_category($category_id)
    {
        $key = 'cache_merk_by_category_api';
        $response = $this->cache->get($key);

        if (!$response) {
            $url = 'https://mufdana.muf.co.id/dev/partners/api/brand?D_JENIS_KENDARAAN=' . $category_id;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->get_token_api_muf()
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $this->cache->save($key, $response, self::CACHE_TIME);
        }

        echo $response; // langsung return JSON ke AJAX
    }

    public function get_type_by_merk($merk_kendaraan)
    {
        $jenis_kendaraan = $this->input->get('jenis_kendaraan', true);
        $key = "cache_type_by_merk_api_{$merk_kendaraan}_$jenis_kendaraan";
        $response = $this->cache->get($key);

        if (!$response) {
            $url = 'https://mufdana.muf.co.id/dev/partners/api/vehiclelist?D_BRAND=' . $merk_kendaraan . '&D_JENIS_KENDARAAN=' . $jenis_kendaraan;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->get_token_api_muf()
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $this->cache->save($key, $response, self::CACHE_TIME);
        }

        echo $response; // langsung return JSON ke AJAX
    }

    public function get_tahun($tipe_kendaraan)
    {
        $jenis_kendaraan = $this->input->get('jenis_kendaraan', true);
        $key = "cache_tahun_{$tipe_kendaraan}_$jenis_kendaraan";
        $response = $this->cache->get($key);

        if (!$response) {
            $url = 'https://mufdana.muf.co.id/dev/partners/api/availableyear?D_MODEL=' . $tipe_kendaraan . '&D_JENIS_KENDARAAN=' . $jenis_kendaraan;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->get_token_api_muf()
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $this->cache->save($key, $response, self::CACHE_TIME);
        }

        echo $response; // langsung return JSON ke AJAX
    }

    public function get_max($tahun_kendaraan)
    {
        $jenis_kendaraan = $this->input->get('jenis_kendaraan', true);
        $tipe_kendaraan = $this->input->get('tipe_kendaraan', true);
        $region_kendaraan = $this->input->get('region_kendaraan', true);

        $url = 'https://mufdana.muf.co.id/dev/partners/api/vehiclemrp?D_JENIS_KENDARAAN=' . $jenis_kendaraan . '&D_MODEL=' . $tipe_kendaraan . '&D_AREA=' . $region_kendaraan . '&D_TAHUN=' . $tahun_kendaraan;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->get_token_api_muf()
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response; // langsung return JSON ke AJAX
    }

    public function get_simulasi()
    {
        $jenis_kendaraan = $this->input->get('jenis_kendaraan', true);
        $tipe_kendaraan = $this->input->get('tipe_kendaraan', true);
        $region_kendaraan = $this->input->get('region_kendaraan', true);
        $tahun_kendaraan = $this->input->get('tahun_kendaraan', true);
        $harga_kendaraan = $this->input->get('harga_kendaraan', true);
        $input_pinjam = $this->input->get('input_pinjam', true);

        // $url = 'https://mufdana.muf.co.id/dev/partners/api/simulation'.
        //         '?D_JENIS_KENDARAAN ='. $jenis_kendaraan.
        //         '&D_MODEL='. $tipe_kendaraan.
        //         '&D_AREA='. $region_kendaraan.
        //         '&D_TAHUN='. $tahun_kendaraan.
        //         '&D_HARGA_OTR ='. $harga_kendaraan.
        //         '&D_JUMLAH_PINJAMAN='. $input_pinjam;
        // $url = 'https://mufdana.muf.co.id/dev/partners/api/simulation?D_JENIS_KENDARAAN=2&D_MODEL=2J5&D_AREA=1&D_TAHUN=2016&D_HARGA_OTR=130000000&D_JUMLAH_PINJAMAN=97500000';

        $url = 'https://mufdana.muf.co.id/dev/partners/api/simulation?D_JENIS_KENDARAAN=' . $jenis_kendaraan . '&D_MODEL=' . $tipe_kendaraan . '&D_AREA=' . $region_kendaraan . '&D_TAHUN=' . $tahun_kendaraan . '&D_HARGA_OTR=' . $harga_kendaraan . '&D_JUMLAH_PINJAMAN=' . $input_pinjam;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->get_token_api_muf()
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response; // langsung return JSON ke AJAX
    }

    public function save_data()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'phone' => $this->input->post('nomor'),
            'email' => $this->input->post('email'),
            'dob' => $this->input->post('dob'),
            'ktp_number' => $this->input->post('nomor_ktp'),
            'address' => $this->input->post('address'),
            'area' => $this->input->post('region_kendaraan'),
            'category' => $this->input->post('jenis_kendaraan'),
            'merk' => $this->input->post('merk_kendaraan'),
            'type' => $this->input->post('tipe_kendaraan'),
            'year' => $this->input->post('tahun_kendaraan'),
            'otr' => $this->input->post('harga_kendaraan'),
            'max' => $this->input->post('max_pinjam'),
            'total_loan' => $this->input->post('input_pinjam'),
            'loan' => $this->input->post('angsuran'),
            'tenor' => $this->input->post('tenor_kend'),
            'create_date' => date('Y-m-d H:i:s'),
        );

        // Simpan ke database
        $saved = $this->db->insert('simulation', $data);

        if ($saved) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
}
