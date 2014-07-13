<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends CI_Controller {

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
    public function set($language)
    {
        if($this->config->item('allow_change_language_menu')) {
            $language = trim($language);

            if(array_key_exists($language, $this->config->item('languages'))) {
                set_cookie('selected_language', $language, strtotime('+5 days'));
            }
        }
        redirect();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */