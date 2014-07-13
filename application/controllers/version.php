<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Version extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function mobile($redirect)
    {
        $this->_run('mobile', $redirect);
    }

    public function full($redirect)
    {
        $this->_run('full', $redirect);
    }

    public function _run($version, $redirect)
    {
        $this->load->library('session');
        $this->load->helper('url');
        
        $this->session->set_userdata('is_mobile', ($version === 'mobile'));

        $redirect = base64_decode($redirect);

        if( !empty($redirect)
         && filter_var($redirect, FILTER_VALIDATE_URL)
        ) {
            $redirect = '';
        }
        redirect($redirect);
        exit;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */