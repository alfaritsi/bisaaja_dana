<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Cache $cache
 */
class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache', ['adapter' => 'file']);
    }

}