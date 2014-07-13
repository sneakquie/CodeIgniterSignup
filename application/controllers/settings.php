<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Settings Controller (not Controller in OOP meaning, because my Controller is so thick)
 *
 * @author sneakquie (Salabai Dmitrii)
 * @package Vinc
 * @subpackage Controllers
 */
class Settings extends CI_Controller
{

    /**
     * Check is user logged in
     * If isn't logged - redirect to login page
     */
    public function __construct()
    {
        parent::__construct();

        if(!$this->vinc_auth->logged()) {
            redirect('auth/login');
        }
    }

    /**
     * Index page in Settings contr. (account method)
     */
    public function index()
    {
        $this->account();
    }

    /**
     * Account page in Settings
     */
    public function account()
    {

        /*
         * Allow to select timezone in settings - load it to get timezone list in output
         */
        if($this->config->item('allow_change_timezone')) {
            $this->load->helper('date');
        }

        /*
         * User pressed button
         */
        if(isset($_POST['settings_show_date'])) {

            $data = array();

            /*
             * Allow to change language in settings
             */
            if($this->config->item('allow_change_language_settings')) {
                $data['language'] = trim($this->input->post('settings_language'));

                /*
                 * Language is invalid
                 */
                if( empty($data['language'])
                 || !array_key_exists($data['language'], $this->config->item('languages'))
                ) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_wrong_language'),));
                    return;
                }
            }

            /*
             * Allow to change timezone
             */
            if($this->config->item('allow_change_timezone')) {
                $data['timezone'] = trim($this->input->post('settings_timezone'));

                /*
                 * Timezone is invalid
                 */
                if( empty($data['timezone'])
                 || !array_key_exists($data['timezone'], my_timezones())
                ) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_wrong_timezone'),));
                    return;
                }
            }

            /*
             * Set array with info about born date
             */
            $birth = array('day'   => intval($this->input->post('settings_born_day')),
                           'month' => intval($this->input->post('settings_born_month')),
                           'year'  => intval($this->input->post('settings_born_year')),);

            /*
             * Something is wrong
             */
            if( $birth['day'] <= 0
             || $birth['day'] > 31
             || $birth['month'] <= 0
             || $birth['month'] > 12
             || $birth['year'] < 1900
            ) {
                $this->_showOutput('account', null, array('error' => $this->lang->line('settings_wrong_birth'),));
                return;
            }

            $data['born_date']      = $birth['day'] . '.' . $birth['month'] . '.' . $birth['year'];

            $data['show_born_date'] = (boolean) $this->input->post('settings_show_date');
            $data['show_email']     = (boolean) $this->input->post('settings_show_email');

            /*
             * Allow to change email
             */
            if($this->config->item('allow_change_email')) {
                $data['email'] = trim($this->input->post('settings_email'));

                /*
                 * Email isn't valid
                 */
                if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_wrong_email'),));
                    return;
                }

                /*
                 * Select same email with another id
                 */
                $query = $this->db->select('1', false)
                                  ->where('email', $data['email'])
                                  ->where('id !=', $this->vinc_auth->_('id'))
                                  ->get('users');

                /*
                 * Email is already exists
                 */
                if($query->num_rows() > 0) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_email_exists'),));
                    return;
                }

                /*
                 * User really changed email - set it to uncofirmed
                 */
                if($data['email'] != $this->vinc_auth->_('email')) {
                    $data['confirmed'] = 0;
                }
            }

            $data['location'] = trim(strip_tags($this->security->xss_clean($this->input->post('settings_location'))));

            // Location is too long, cut it
            isset($data['location'][127]) && $data['location'] = substr($data['location'], 0, 127);

            /*
             * Allow to change login
             */
            if($this->config->item('allow_change_login')) {
                $data['login'] = trim($this->input->post('settings_login'));

                /*
                 * Login is short
                 */
                if(strlen($data['login']) < 4) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_short_login'),));
                    return;
                }

                /*
                 * Login is invalid
                 */
                elseif(!preg_match('/^[a-z0-9\-_.]{4,30}$/i', $data['login'])) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_login_wrong_format'),));
                    return;
                }

                $query = $this->db->select('1', false)
                                  ->where('login', $data['login'])
                                  ->where('id !=', $this->vinc_auth->_('id'))
                                  ->get('users');

                if($query->num_rows() > 0) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_login_exists'),));
                    return;
                }
            }

            $data['real_name'] = trim(strip_tags($this->security->xss_clean($this->input->post('settings_name'))));

            // Real name is too long, cut it
            isset($data['real_name'][63]) && $data['real_name'] = substr($data['real_name'], 0, 63);

            /*
             * Field "about me" is allowed
             */
            if($this->config->item('allow_about')) {
                $data['about'] = trim(strip_tags($this->security->xss_clean($this->input->post('settings_about'))));

                // Too long
                isset($data['about'][160]) && $data['about'] = substr($data['about'], 0, 160);
            }

            /*
             * Gender is valid - set it to user's, else set it to 0
             */
            $data['gender'] = (in_array($gender = intval($this->input->post('settings_gender')), range(0, 2)))
                                ? $gender
                                : 0;

            /*
             * Website in profile is allowed
             */
            if($this->config->item('allow_website')) {
                $data['website'] = trim(str_ireplace(array('http://', 'https://'), '', $this->input->post('settings_website')));

                if( !empty($data['website'])
                 && !filter_var('http://' . $data['website'], FILTER_VALIDATE_URL)
                ) {
                    $this->_showOutput('account', null, array('error' => $this->lang->line('settings_wrong_website'),));
                    return;
                }
            }

            /*
             * Update info
             */
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', $data);

            /*
             * Set flash data, redirect
             */
            $this->session->set_flashdata('settings_success', $this->lang->line('settings_saved'));
            redirect('settings/account');
        }

        $this->_showOutput('account');
    }

    /**
     * Notification page in Settings Controller
     */
    public function notifications()
    {
        /*
         * User pressed button
         */
        if(isset($_POST['settings_notify_comments'])) {
            $data     = array();

            $settings = array('settings_last_login'            => 'show_last_login',
                              'settings_allow_email'           => 'allow_email',
                              'settings_notify_comments'       => 'notify_comments',
                              'settings_notify_comments_email' => 'notify_comments_email',
                              'settings_notify_answers'        => 'notify_answers',
                              'settings_notify_answers_email'  => 'notify_answers_email',
                              'settings_notify_messages'       => 'notify_messages',
                              'settings_notify_messages_email' => 'notify_messages_email',
                              'settings_notify_follow'         => 'notify_follow_news',
                              'settings_notify_cats'           => 'notify_cats_news',
                              'settings_notify_likes'          => 'notify_likes',);

            foreach ($settings as $key => $value) {
                if(isset($_POST[$key])) {
                    $data[$value] = (boolean) $this->input->post($key);
                }
            }

            /*
             * Something changed
             */
            if(sizeof($data) > 0) {
                $this->db->where('id', $this->vinc_auth->_('id'))
                         ->update('users', $data);

                /*
                 * Set flash data, redirect
                 */
                $this->session->set_flashdata('settings_success', $this->lang->line('settings_saved'));
                redirect('settings/notifications');
            }
        }
        $this->_showOutput('notifications', 'not');
    }

    /**
     * Password change
     */
    public function password()
    {
        /*
         * Changing password disabled in settings
         */
        if(!$this->config->item('allow_change_password')) {
            show_404();
        }

        /*
         * User pressed button
         */
        elseif(isset($_POST['settings_new_password'])) {
            $new_password    = trim($this->input->post('settings_new_password'));
            $repeat_password = trim($this->input->post('settings_repeat_password'));

            /*
             * New password is short
             */
            if(!isset($new_password[5])) {
                $this->_showOutput('password', null, array('error' => $this->lang->line('settings_wrong_new_password')));
                return;
            }

            /*
             * Passwords not equal
             */
            elseif($new_password !== $repeat_password) {
                $this->_showOutput('password', null, array('error' => $this->lang->line('settings_not_equal')));
                return;
            }

            /*
             * Old password required, user registered without social networks or already changed password
             * And old password is wrong
             */
            elseif($this->config->item('old_password_required')
                && (is_null($l = $this->vinc_auth->_('social_login'))
                || empty($l)
                || $this->vinc_auth->_('last_change') > 0)
                && (strlen($old_password = trim($this->input->post('settings_old_password'))) < 6
                || $this->vinc_auth->makeHash($old_password, $this->vinc_auth->_('salt')) !== $this->vinc_auth->_('hash'))
            ) {
                $this->_showOutput('password', null, array('error' => $this->lang->line('settings_wrong_password')));
                return;
            }

            /*
             * Create salt, hash new password
             */
            $this->load->helper('string');
            $salt = random_string('alnum', 6);
            $hash = $this->vinc_auth->makeHash($new_password, $salt);

            /*
             * Update info
             */
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', array('hash'        => $hash,
                                             'salt'        => $salt,
                                             'last_change' => $_SERVER['REQUEST_TIME'],));

            $this->vinc_auth->destroySession();
            $this->vinc_auth->setSession($this->vinc_auth->_('id'), $hash, (boolean) get_cookie('user_id'));

            /*
             * Set flash data, redirect
             */
            $this->session->set_flashdata('settings_success', $this->lang->line('settings_saved'));
            redirect('settings/password');
        }

        $this->_showOutput('password');
    }

    /**
     * Avatar page in Settings
     */
    public function avatar()
    {
        $this->_showOutput('avatar');
    }

    /**
     * Cover page in Settings
     */
    public function cover()
    {
        $this->_showOutput('cover');
    }

    /**
     * Social page in Settings
     */
    public function social()
    {
        /*
         * Social profiles disabled by Config
         */
        if(!$this->config->item('allow_social_profiles')) {
            show_404();
        }

        /*
         * User pressed button
         */
        elseif(isset($_POST['social_save'])) {
            $data = array();

            /*
             * Fill data array
             */
            foreach($this->config->item('profile_social_networks') as $key => $value) {
                if(isset($_POST['settings_social_' . $key])) {
                    $data['network_' . $key] = trim(strip_tags($this->security->xss_clean($this->input->post('settings_social_' . $key))));
                }
            }

            /*
             * Something changed, write it into DB
             */
            if(sizeof($data) > 0) {
                $this->db->where('id', $this->vinc_auth->_('id'))
                         ->update('users', $data);
            }

            /*
             * Set flash data, redirect
             */
            $this->session->set_flashdata('settings_success', $this->lang->line('settings_saved'));
            redirect('settings/social');
        }

        $this->_showOutput('social');
    }

    /**
     * Activate page in Settings
     */
    public function activate()
    {
        /*
         * User email already confirmed
         */
        if( $this->vinc_auth->_('confirmed')
         || !$this->config->item('signup_confirm_email')
        ) {
            show_404();
        }

        /*
         * User pressed button
         */
        elseif(isset($_POST['activate_send'])) {

            $this->load->library('email');
            $this->lang->load($this->application->_('language') . '_email', 'list');

            /*
             * Generate PSEUDOrandom int and calculate hash sum
             */
            $confirm_code = md5(uniqid());

            /*
             * Type of email is HTML
             */
            if($this->config->item('signup_html_confirm')) {
                $this->load->library('registry');

                /*
                 * Set info to registry
                 */
                $this->registry->set('username', $this->vinc_auth->_('login'))
                               ->set('email', $this->vinc_auth->_('email'))
                               ->set('confirm', $confirm_code)
                               ->set('date', date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i'));

                /*
                 * Set email type to HTML
                 */
                $this->email->initialize(array('mailtype' => 'html'));

                /*
                 * Send email
                 */
                $this->email->message($this->load->file(FCPATH . APPPATH . 'views/full/email/confirm.php', true));
            }
            else {

                /*
                 * Message - is simple text, sending
                 */
                $this->email->message(str_ireplace(array('%username%',
                                                         '%email%',
                                                         '%confirm%',
                                                         '%date%',),
                                                   array($this->vinc_auth->_('login'),
                                                         $this->vinc_auth->_('email'),
                                                         site_url('auth/confirm/' . $confirm_code),
                                                         date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i')),
                                                   $this->lang->line('email_confirm_message')));
            }

            $this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
            $this->email->to($this->vinc_auth->_('email'));

            $this->email->subject($this->lang->line('email_confirm_subject'));

            $this->email->send();

            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', array('confirm_code' => $confirm_code));

            /*
             * Set flash data, redirect
             */
            $this->session->set_flashdata('settings_activate_success', $this->lang->line('settings_activate_send'));
            redirect('settings/activate');
        }

        $this->_showOutput('activate');
    }

    /**
     * AJAX activate page in Settings
     */
    public function checkActivate()
    {

        /*
         * User email already confirmed
         */
        if( $this->vinc_auth->_('confirmed')
         || !$this->config->item('signup_confirm_email')
        ) {
            show_404();
        }

        $this->load->library('email');
        $this->lang->load($this->application->_('language') . '_email', 'list');

        /*
         * Generate PSEUDOrandom int and calculate hash sum
         */
        $confirm_code = md5(uniqid());

        /*
         * Type of email is HTML
         */
        if($this->config->item('signup_html_confirm')) {
            $this->load->library('registry');

            /*
             * Set info to registry
             */
            $this->registry->set('username', $this->vinc_auth->_('login'))
                           ->set('email', $this->vinc_auth->_('email'))
                           ->set('confirm', $confirm_code)
                           ->set('date', date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i'));

            /*
             * Set email type to HTML
             */
            $this->email->initialize(array('mailtype' => 'html'));

            /*
             * Send email
             */
            $this->email->message($this->load->file(FCPATH . APPPATH . 'views/full/email/confirm.php', true));
        }
        else {

            /*
             * Message - is simple text, sending
             */
            $this->email->message(str_ireplace(array('%username%',
                                                     '%email%',
                                                     '%confirm%',
                                                     '%date%',),
                                               array($this->vinc_auth->_('login'),
                                                     $this->vinc_auth->_('email'),
                                                     site_url('auth/confirm/' . $confirm_code),
                                                     date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i')),
                                               $this->lang->line('email_confirm_message')));
        }

        $this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
        $this->email->to($this->vinc_auth->_('email'));

        $this->email->subject($this->lang->line('email_confirm_subject'));

        $this->email->send();

        $this->db->where('id', $this->vinc_auth->_('id'))
                 ->update('users', array('confirm_code' => $confirm_code));

        $this->application->_sendAJAX(array('success' => $this->lang->line('settings_activate_send')));
    }

    /**
     * AJAX Social page in Settings
     */
    public function checkSocial()
    {
        /*
         * Disabled by Config
         */
        if(!$this->config->item('allow_social_profiles')) {
            show_404();
        }

        $data = array();

        /*
         * Fill array
         */
        foreach($this->config->item('profile_social_networks') as $key => $value) {
            if(isset($_POST['social_' . $key])) {
                $data['network_' . $key] = trim(strip_tags($this->security->xss_clean($this->input->post('social_' . $key))));
            }
        }

        /*
         * Something changed
         */
        if(sizeof($data) > 0) {
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', $data);
        }

        $this->application->_sendAJAX(array('success' => $this->lang->line('settings_saved')));
    }

    /**
     * AJAX Notifications page in settings controller
     */
    public function checkNotifications()
    {
        $data     = array();

        $settings = array('show_last_login',
                          'allow_email',
                          'notify_comments',
                          'notify_comments_email',
                          'notify_answers',
                          'notify_answers_email',
                          'notify_messages',
                          'notify_messages_email',
                          'notify_follow_news',
                          'notify_cats_news',
                          'notify_likes',);

        foreach ($settings as $value) {
            if(isset($_POST[$value])) {
                $data[$value] = (boolean) $this->input->post($value);
            }
        }

        /*
         * Update info
         */
        if(sizeof($data) > 0) {
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', $data);
        }

        $this->application->_sendAJAX(array('success' => $this->lang->line('settings_saved')));
    }

    /**
     * AJAX changePassword page in Settings
     */
    public function changePassword()
    {
        /*
         * Disabled by Config
         */
        if(!$this->config->item('allow_change_password')) {
            show_404();
        }

        $new_password    = trim($this->input->post('new_password'));
        $repeat_password = trim($this->input->post('repeat_password'));

        /*
         * New password is short
         */
        if(!isset($new_password[5])) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_new_password')));
        }

        /*
         * Passwords not equal
         */
        elseif($new_password !== $repeat_password) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_not_equal')));
        }

        /*
         * Old password required
         */
        elseif($this->config->item('old_password_required')
            && (is_null($l = $this->vinc_auth->_('social_login'))
            || empty($l)
            || $this->vinc_auth->_('last_change') > 0)
            && (strlen($old_password = trim($this->input->post('old_password'))) < 6
            || $this->vinc_auth->makeHash($old_password, $this->vinc_auth->_('salt')) !== $this->vinc_auth->_('hash'))
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_password')));
        }

        /*
         * Generate salt, hash password
         */
        $this->load->helper('string');
        $salt = random_string('alnum', 6);
        $hash = $this->vinc_auth->makeHash($new_password, $salt);

        /*
         * Update info
         */
        $this->db->where('id', $this->vinc_auth->_('id'))
                 ->update('users', array('hash'        => $hash,
                                         'salt'        => $salt,
                                         'last_change' => $_SERVER['REQUEST_TIME'],));

        $this->vinc_auth->destroySession();
        $this->vinc_auth->setSession($this->vinc_auth->_('id'), $hash, (boolean) get_cookie('user_id'));
        $this->application->_sendAJAX(array('success' => $this->lang->line('settings_saved')));
    }

    /**
     * AJAX checkAccount page
     */
    public function checkAccount()
    {
        $data = array();

        /*
         * Allow to change language in settings
         */
        if($this->config->item('allow_change_language_settings')) {
            $data['language'] = trim($this->input->post('language'));

            /*
             * Language is invalid or doesn't exists
             */
            if( empty($data['language'])
             || !array_key_exists($data['language'], $this->config->item('languages'))
            ) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_language')));
            }
        }

        /*
         * Allow to change timezone
         */
        if($this->config->item('allow_change_timezone')) {
            $this->load->helper('date');
            $data['timezone'] = trim($this->input->post('timezone'));

            /*
             * Timezone isn't valid or doesn't exists
             */
            if( empty($data['timezone'])
             || !array_key_exists($data['timezone'], my_timezones())
            ) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_timezone')));
            }
        }

        /*
         * Info about born date
         */
        $birth = array('day'   => intval($this->input->post('birth_day')),
                       'month' => intval($this->input->post('birth_month')),
                       'year'  => intval($this->input->post('birth_year')),);

        /*
         * Something went wrong
         */
        if( $birth['day'] <= 0
         || $birth['day'] > 31
         || $birth['month'] <= 0
         || $birth['month'] > 12
         || $birth['year'] < 1900
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_birth')));
        }

        $data['born_date']      = $birth['day'] . '.' . $birth['month'] . '.' . $birth['year'];

        $data['show_born_date'] = (boolean) $this->input->post('show_date');
        $data['show_email']     = (boolean) $this->input->post('show_email');

        /*
         * Allow to change email
         */
        if($this->config->item('allow_change_email')) {
            $data['email'] = trim($this->input->post('email'));

            /*
             * Email isn't valid
             */
            if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_email')));
            }

            $query = $this->db->select('1', false)
                              ->where('email', $data['email'])
                              ->where('id !=', $this->vinc_auth->_('id'))
                              ->get('users');

            /*
             * Email already exists
             */
            if($query->num_rows() > 0) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_email_exists')));
            }
            elseif($data['email'] != $this->vinc_auth->_('email')) {
                $data['confirmed'] = 0;
            }
        }

        $data['location'] = trim(strip_tags($this->security->xss_clean($this->input->post('location'))));

        isset($data['location'][127]) && $data['location'] = substr($data['location'], 0, 127);

        /*
         * Allow to change login
         */
        if($this->config->item('allow_change_login')) {
            $data['login'] = trim($this->input->post('login'));

            /*
             * Login is short
             */
            if(strlen($data['login']) < 4) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_short_login')));
            }

            /*
             * Login is invalid
             */
            elseif(!preg_match('/^[a-z0-9\-_.]{4,30}$/i', $data['login'])) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_login_wrong_format')));
            }

            $query = $this->db->select('1', false)
                              ->where('login', $data['login'])
                              ->where('id !=', $this->vinc_auth->_('id'))
                              ->get('users');

            /*
             * Login already exists
             */
            if($query->num_rows() > 0) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_login_exists')));
            }
        }

        $data['real_name'] = trim(strip_tags($this->security->xss_clean($this->input->post('name'))));

        isset($data['real_name'][63]) && $data['real_name'] = substr($data['real_name'], 0, 63);

        /*
         * About field is allowed
         */
        if($this->config->item('allow_about')) {
            $data['about'] = trim(strip_tags($this->security->xss_clean($this->input->post('about'))));

            isset($data['about'][160]) && $data['about'] = substr($data['about'], 0, 160);
        }

        $data['gender'] = (in_array($gender = intval($this->input->post('gender')), range(0, 2)))
                            ? $gender
                            : 0;

        /*
         * Website field is allowed
         */
        if($this->config->item('allow_website')) {
            $data['website'] = trim(str_ireplace(array('http://', 'https://'), '', $this->input->post('website')));

            /*
             * URL isn't valid
             */
            if( !empty($data['website'])
             && !filter_var('http://' . $data['website'], FILTER_VALIDATE_URL)
            ) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_website')));
            }
        }

        /*
         * Update info
         */
        $this->db->where('id', $this->vinc_auth->_('id'))
                 ->update('users', $data);
        $this->application->_sendAJAX(array('success' => $this->lang->line('settings_saved')));
    }

    /**
     * AJAX Delete avatar
     */
    public function deleteAvatar()
    {
        /*
         * User have avatar - delete it and update data in DB
         */
        if( !is_null($l = $this->vinc_auth->_('photo_avatar'))
         && !empty($l)
        ) {
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', array('photo_avatar' => ''));

            /*
             * Delete avatar if exists
             */
            if(file_exists(FCPATH . 'uploads/avatars/' . $this->vinc_auth->_('photo_avatar'))) {
                @unlink(FCPATH . 'uploads/avatars/' . $this->vinc_auth->_('photo_avatar'));
            }
        }
        $this->application->_sendAJAX(array('image_url' => site_url($this->config->item('noavatar_url'))));
    }

    /**
     * AJAX Delete Cover
     */
    public function deleteCover()
    {
        /*
         * User have cover - delete it and update data in DB
         */
        if( !is_null($l = $this->vinc_auth->_('photo_cover'))
         && !empty($l)
        ) {
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', array('photo_cover' => ''));

            /*
             * Delete cover if exists
             */
            if(file_exists(FCPATH . 'uploads/covers/' . $this->vinc_auth->_('photo_cover'))) {
                @unlink(FCPATH . 'uploads/covers/' . $this->vinc_auth->_('photo_cover'));
            }
        }
        $this->application->_sendAJAX(array('image_url' => site_url($this->config->item('nocover_url'))));
    }

    /**
     * AJAX Upload avatar
     */
    public function uploadAvatar()
    {
        $this->load->helper('file');

        /*
         * Path to avatars
         */
        $upload_path             = base_url() . 'uploads/avatars/';

        /*
         * Settings for file
         */
        $config['upload_path']   = FCPATH . 'uploads/avatars/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = $this->config->item('avatar_max_size') * 1048576;
        $config['file_name']     = md5(uniqid($this->vinc_auth->_('login')));

        $this->load->library('upload', $config);

        /*
         * Set max file size in INI file
         */
        @ini_set('upload_max_filesize', $this->config->item('avatar_max_size'));
        @ini_set('post_max_size', $this->config->item('avatar_max_size') + 2);

        /*
         * Something went wrong - show error
         */
        if (!$this->upload->do_upload('settings_upload_avatar')) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_upload_error')));
        }
        else {

            /*
             * User already have avatar - delete it
             */
            if( !is_null($l = $this->vinc_auth->_('photo_avatar'))
             && !empty($l)
             && file_exists(FCPATH . 'uploads/avatars/' . $l)
            ) {
                @unlink(FCPATH . 'uploads/avatars/' . $l);
            }

            $data = $this->upload->data();

            /*
             * Settings for resizing image
             */
            $config = array();
            $config['image_library']  = 'gd2';
            $config['source_image']   = $data['full_path'];
            $config['create_thumb']   = true;
            $config['maintain_ratio'] = false;
            $config['thumb_marker']   = '';
            $config['width']          = 200;
            $config['height']         = 200;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            /*
             * Set info in DB
             */
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', array('photo_avatar' => $data['file_name'],));

            /*
             * Return image URL
             */
            $this->application->_sendAJAX(array('image_url' => $upload_path . $data['file_name'],));
        }
    }

    /**
     * AJAX Upload Cover
     */
    public function uploadCover()
    {
        $this->load->helper('file');

        /*
         * Path to image (cover)
         */
        $upload_path = base_url() . 'uploads/covers/';

        /*
         * Settings for image uploading
         */
        $config['upload_path']   = FCPATH . 'uploads/covers/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = $this->config->item('cover_max_size') * 1048576;
        $config['file_name']     = md5(uniqid($this->vinc_auth->_('login')));

        $this->load->library('upload', $config);

        /*
         * Set size in INI file
         */
        @ini_set('upload_max_filesize', $this->config->item('avatar_max_size'));
        @ini_set('post_max_size', $this->config->item('avatar_max_size') + 2);

        /*
         * There is some error
         */
        if (!$this->upload->do_upload('settings_upload_cover')) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_upload_error')));
        }
        else {

            /*
             * Already have cover and file exists - delete it
             */
            if( !is_null($l = $this->vinc_auth->_('photo_cover'))
             && !empty($l)
             && file_exists(FCPATH . 'uploads/covers/' . $l)
            ) {
                @unlink(FCPATH . 'uploads/covers/' . $l);
            }

            $data = $this->upload->data();

            /*
             * Settings for image resizing
             */
            $config = array();
            $config['image_library']  = 'gd2';
            $config['source_image']   = $data['full_path'];
            $config['maintain_ratio'] = false;
            $config['height']         = 300;
            $config['width']          = 300;

            $this->load->library('image_lib', $config);

            // Resize image
            $this->image_lib->resize();

            /*
             * Update info in DB
             */
            $this->db->where('id', $this->vinc_auth->_('id'))
                     ->update('users', array('photo_cover' => $data['file_name'],));

            /*
             * Return cover URL
             */
            $this->application->_sendAJAX(array('image_url' => $upload_path . $data['file_name'],));
        }
    }

    /**
     * Shows output
     *
     * @param string $module
     * @param string|null $lang_key (null by default)
     * @param mixed $data (null by default)
     */
    private function _showOutput($module, $lang_key = null, $data = null)
    {
        if(!in_array($module = trim($module), array('account', 'notifications', 'avatar', 'cover', 'password', 'social', 'activate'))) {
            return;
        }

        $lang_key = (is_null($lang_key))
                     ? $module
                     : trim($lang_key);

        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('topbar_settings_' . $lang_key),
                                                                          'css'   => array('jquery.fs.selecter', 'jquery.fs.scroller'),
                                                                          'js'    => array('jquery.fs.selecter',
                                                                                           'jquery.fs.scroller',
                                                                                           'history',
                                                                                           'settings',
                                                                                           'auth',),));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/settings');
        $this->load->view($this->application->_('theme') . '/settings/' . $module, array('data' => $data));
        $this->load->view($this->application->_('theme') . '/bottom');
    }
}