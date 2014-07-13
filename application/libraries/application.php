<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application
{
    /**
     * Contains user data
     *
     * @var array
     * @access protected
     */
    protected $_data = array('mobile' => false,);

    /**
     * __construct
     *
     * @return void
     **/
    public function __construct()
    {
        header('Content-type:text/html; charset=UTF-8');

        /*
         * Load helpers and libraries
         */
        $this->load->helper('cookie');
        $this->load->helper('date');
        $this->load->library('session');

        /*
         * Load list of timezones to set timezone
         */
        $this->config->load('timezones');
        $zones = $this->config->item('timezones');

        /*
         * Check is user logged in or set default timezone
         */
        if( ($this->vinc_auth->logged()
         && $this->config->item('allow_change_timezone')
         && in_array($new = $this->vinc_auth->_('timezone'), $zones))
         || in_array($new = $this->config->item('timezone'), $zones)
        ) {
            date_default_timezone_set($new);
        }
        unset($zones, $new);

        /*
         * Language start
         */
        $list = $this->config->item('languages');

        /*
         * Get language from info (userinfo, cookies, browser data)
         */
        if( ($this->vinc_auth->logged()
         && $this->config->item('allow_change_language_settings')
         && isset($list[$language = $this->vinc_auth->_('language')]))
         || ($this->config->item('allow_change_language_menu')
         && ($language = get_cookie('selected_language'))
         && isset($list[$language]))
         || ($this->config->item('allow_change_language_auto')
         && ($language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))
         && isset($list[$language]))
        ) {
            $this->_data['language'] = $language;
            $this->_data = array_merge($this->_data, $list[$language]);
        }

        /*
         * There are no importent data - set language to default
         */
        else {
            $this->_data['language'] = $this->config->item('site_language');
            $this->_data = array_merge($this->_data, $list[$this->_data['language']]);
        }
        $this->lang->load($this->_data['language'], 'list');
        unset($list);

        /*
         * Mobile theme detecting
         */
        if($this->session->has('is_mobile')) {

            /*
             * User already choose mobile version
             */
            if($this->session->userdata('is_mobile') == true) {
                $this->_data['theme']  = $this->config->item('mobile_theme');
                $this->_data['mobile'] = true;
            }

            /*
             * Default theme
             */
            else {
                $this->_data['theme']  = $this->config->item('theme');
            }
        }

        /*
         * Session doesn't exists, load library
         */
        else {
            $this->load->library('mobile_detect');

            /*
             * Using mobile or tablet device
             */
            if( $this->mobile_detect->isTablet()
             || $this->mobile_detect->isMobile()
            ) {
                $this->session->set_userdata('is_mobile', true);
                $this->_data['mobile'] = true;
                $this->_data['theme']  = $this->config->item('mobile_theme');
            }
            else {
                $this->session->set_userdata('is_mobile', false);
                $this->_data['theme']  = $this->config->item('theme');
            }
        }

        $list = $this->config->item('templates');
        if( isset($list[$this->_data['theme']])
         && $list[$this->_data['theme']] == true
        ) {
            if( $this->_data['is_rtl'] == true
             && !$this->_data['is_mobile']
            ) {
                $this->_data['theme'] .= '/rtl';
            }
        }
        else {
            show_error("Template doesn't exists. Please, contact with administrator.");
            exit;
        }
    }

    public function _($value)
    {
        return (isset($this->_data[$value]))
                ? $this->_data[$value]
                : null;
    }

    public function _sendAJAX($data)
    {
        header('Content-type:application/json; charset=UTF-8');
        die(json_encode($data));
    }

    /**
     * __get
     *
     * Позволяет использовать CI super-global без определения дополнительных переменных.
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}   