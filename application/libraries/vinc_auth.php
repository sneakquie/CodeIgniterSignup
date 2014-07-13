<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vinc_auth
{
    /**
     * Contains user data
     *
     * @var array
     * @access protected
     */
    protected $_data = array();

    /**
     * Is user logged in
     *
     * @var boolean
     * @access protected
     */
    protected $_loggedIn = false;

    /**
     * __construct
     *
     * @return void
     **/
    public function __construct()
    {
        $this->load->database();

        if( (($id = intval(get_cookie('user_id')))
         && strlen($hash = trim(get_cookie('user_hash')))) == 32
         || (($id = intval($this->session->userdata('user_id')))
         && strlen($hash = trim($this->session->userdata('user_hash'))) == 32)
        ) {
            $user  = $this->db->select('groups.*, groups.id as group_id, users.*', false)
                              ->from('users')
                              ->join('groups', 'groups.id = users.group')
                              ->where('users.id', $id)
                              ->where('users.hash', $hash)
                              ->get();

            if( $user->num_rows() > 0) {
                $user = $user->row_array();
                $this->_loggedIn      = true;
                $this->_data          = $user;
                $this->_data['token'] = md5($this->_data['signup_date'] . md5($this->_data['id'] . $this->_data['signup_ip']));
            }
            else {
                $this->destroySession();
            }
        }
    }

    public function destroySession()
    {
        delete_cookie('user_id');
        delete_cookie('user_hash');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_hash');
    }

    public function setSession($id, $hash, $cookies = true)
    {
        $this->session->set_userdata('user_id', $id);
        $this->session->set_userdata('user_hash', $hash);

        if($cookies == true) {
            set_cookie('user_id', $id, strtotime('+5 days'));
            set_cookie('user_hash', $hash, strtotime('+5 days'));
        }
    }

    public function makeHash($password, $salt)
    {
        return md5('vinc' . md5($salt . $password) . 'cniv');
    }

    public function updateActivity()
    {
        if(!$this->_loggedIn) {
            return false;
        }

        $this->db->where('id', $this->_data['id']);
        $this->db->set('last_activity', $_SERVER['REQUEST_TIME']);
        $this->db->update('users');
        return (boolean) $this->db->affected_rows();
    }
    
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function _($value)
    {
        return (isset($this->_data[$value]))
                ? $this->_data[$value]
                : null;
    }

    public function logged()
    {
        return (boolean) $this->_loggedIn;
    }

    public function getUserAvatar($url)
    {
        return (is_null($url) || empty($url))
                ? site_url($this->config->item('noavatar_url'))
                : site_url('uploads/avatars/' . $url);
    }

    public function getUserCover($url)
    {
        return (is_null($url) || empty($url))
                ? site_url($this->config->item('nocover_url'))
                : site_url('uploads/covers/' . $url);
    }

    public function getAvatar()
    {
        return ($this->_loggedIn)
                ? $this->getUserAvatar($this->_data['photo_avatar'])
                : '';
    }

    public function getCover()
    {
        return ($this->_loggedIn)
                ? $this->getUserCover($this->_data['photo_cover'])
                : '';
    }

    public function userURL($login)
    {
        $type = $this->config->item('user_url_type');
        switch ($type) {
            case 0:
                return site_url('u/' . $login);
            case 1:
                return site_url('user/' . $login);
            default:
                return site_url('profile/' . $login);
        }
    }

    public function getData()
    {
        return $this->_data;
    }
}