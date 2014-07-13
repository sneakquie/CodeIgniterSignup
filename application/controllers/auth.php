<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Auth Controller (not Controller in OOP meaning, because my Controller is so thick)
 * Social login/signup, recover password, signup, login, confirm email
 *
 * @author sneakquie (Salabai Dmitrii)
 * @package Vinc
 * @subpackage Controllers
 */
class Auth extends CI_Controller
{

    public function index()
    {
        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('login_title')));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/main');
        $this->load->view($this->application->_('theme') . '/bottom');
    }
    
    /**
     * Social login
     * Check id, is user already logged with current social data
     * If no - set session data and redirect to signup page
     * Else redirect to homepage
     *
     * @return void
     */
    public function social()
    {

        /*
         * User logged in - redirect to home page
         */
        if($this->vinc_auth->logged()) {
            redirect();
        }

        /*
         * User isn't logged in
         */
        elseif($this->input->post('token')) {

            /*
             * Get info from ulogin page
             */
            $info = file_get_contents('http://ulogin.ru/token.php?token=' . $this->input->post('token') . '&host=' . $this->config->item('site_url'));
            $info = json_decode($info, true);

            /*
             * Ulogin returned right data
             */
            if( is_array($info)
             && isset($info['network'])
             && in_array($info['network'], $this->config->item('signup_services'))
            ) {

                /*
                 * Select info, is user already registered with this social data
                 */
                $this->db->select('id, hash');

                /*
                 * Tumblr doesn't return uid, m*therf*cker, it returns only network and profile link
                 * The problem is that when user change profile address on Tumblr he become new user for this signup feature
                 */
                if($info['network'] == 'tumblr') {
                    $this->db->where('social_login', $info['profile']);
                }
                else {
                    $this->db->where('social_login', $info['uid'] . '|' . $info['network']);
                }

                $query = $this->db->get('users');

                /*
                 * User isn't registered - set session data and redirect to signup page
                 */
                if($query->num_rows() == 0) {
                    $this->session->set_userdata('social_login', $info);
                }

                /*
                 * User already registered with this data - log him in
                 */
                elseif($this->config->item('login_social')) {
                    $query = $query->row_array();
                    $this->db->where('id', $query['id']);
                    $this->db->update('users', array('last_ip'       => $this->input->ip_address(),
                                                     'last_activity' => $_SERVER['REQUEST_TIME'],
                                                    ));
                    $this->vinc_auth->setSession($query['id'], $query['hash']);
                    redirect();
                }
            }
        }
        redirect('auth/signup');
    }

    /**
     * Delete user social data
     *
     * @return void
     */
    public function unlock()
    {

        /*
         * Unset social data
         */
        $this->session->unset_userdata('social_login');

        if($this->vinc_auth->logged()) {
            redirect();
        }
        redirect('auth/signup');
    }

    /**
     * Default user login with couple of features:
     * Captcha, recaptcha, user block, (email/name/name + email) login
     *
     * @return void
     */
    public function login()
    {

        /*
         * User already logged in
         */
        if($this->vinc_auth->logged()) {
            redirect();
        }

        if($this->session->userdata('login_captcha_pass')) {
            $captcha_passed = true;
        }
        $this->session->set_userdata('login_captcha_pass', false);

        $data = array();

        /*
         * Captcha enabled and recaptcha disabled
         * It's here because of validation states
         */
        if( $this->config->item('login_captcha')
         && !$this->config->item('login_recaptcha')
        ) {

            /*
             * Save current captcha answer for next validation
             */
            $session_captcha = $this->session->userdata('signup_captcha');

            $this->load->helper('captcha');

            /*
             * Settings
             */
            $vals = array('img_path'   => FCPATH . 'captcha/',
                          'img_url'    => base_url() . 'captcha/',
                          'expiration' => 7200,
                          );

            $vals['img_width']  = intval($this->config->item('login_captcha_width'));
            $vals['img_height'] = intval($this->config->item('login_captcha_height'));

            $image = create_captcha($vals);

            /*
             * Image created - return response
             */
            if($image['image'] !== false) {
                $this->session->set_userdata('signup_captcha', $image['word']);
                $data['captcha'] = $image['image'];
            }
            unset($image, $vals);
        }

        /*
         * User typed data
         */
        if(isset($_POST['login'])) {

            /*
             * Block user feature is enabled
             */
            if($this->config->item('login_block_user')) {

                /*
                 * Number of attemps >= in config
                 */
                if(intval($this->session->userdata('login_attemps')) >= $this->config->item('login_block_attemps')) {

                    /*
                     * Set login blocked session, unset number of attemps, return response
                     */
                    $this->session->set_userdata('login_blocked', $_SERVER['REQUEST_TIME'] + $this->config->item('login_block_time') * 60);
                    $this->session->unset_userdata('login_attemps');
                    $data['warning'] = $this->lang->line('login_blocked');
                }

                /*
                 * User has been blocked
                 */
                elseif($this->session->has('login_blocked')) {

                    /*
                     * Still blocked, return response
                     */
                    if($this->session->userdata('login_blocked') > $_SERVER['REQUEST_TIME']) {
                        $data['warning'] = $this->lang->line('login_blocked');
                    }

                    /*
                     * Already free, unset session
                     */
                    else {
                        $this->session->unset_userdata('login_blocked');
                    }
                }

                /*
                 * Already blocked - display template
                 */
                if(isset($data['warning'])) {
                    $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('login_title'), 'js' => array('auth', 'login')));
                    $this->load->view($this->application->_('theme') . '/up');
                    $this->load->view($this->application->_('theme') . '/auth/login', array('data' => $data,));
                    return;
                }
            }

            /*
             * Captcha enabled
             */
            if( $this->config->item('login_captcha')
             && !isset($captcha_passed)
            ) {

                /*
                 * Recaptcha
                 */
                if($this->config->item('login_recaptcha')) {
                    $this->load->library('recaptcha');

                    $this->recaptcha->recaptcha_check_answer();
                }

                /*
                 * Answer is wrong
                 */
                if( (isset($this->recaptcha)
                 && !$this->recaptcha->getIsValid())
                 || (!isset($this->recaptcha)
                 && strtolower(trim($this->input->post('recaptcha_response_field'))) !== strtolower($session_captcha))
                ) {
                    $data['error_captcha'] = $this->lang->line('signup_wrong_captcha');
                    $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('login_title'), 'js' => array('auth', 'login')));
                    $this->load->view($this->application->_('theme') . '/up');
                    $this->load->view($this->application->_('theme') . '/auth/login', array('data' => $data,));
                    return;
                }
            }

            $login    = trim($this->input->post('login'));
            $password = trim($this->input->post('password'));

            /*
             * Password is invalid
             */
            if(strlen($password) < 6) {

                /*
                 * Set error, display template
                 */
                $data['error_login'] = $this->lang->line('login_wrong_data');
                $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('login_title'), 'js' => array('auth', 'login'),));
                $this->load->view($this->application->_('theme') . '/up');
                $this->load->view($this->application->_('theme') . '/auth/login', array('data' => $data,));
                return;
            }

            /*
             * No errors in captcha, no warnings
             * Signing in with email
             */
            elseif(($this->config->item('login_email')
                || $this->config->item('login_email_username'))
                && filter_var($login, FILTER_VALIDATE_EMAIL)
            ) {

                /*
                 * Get user info
                 */
                $query = $this->db->select('hash, salt, id')
                                  ->where('email', $login)
                                  ->get('users');
            }

            /*
             * Signing in with login
             */
            elseif(preg_match('/^[a-z0-9\-_.]{4,30}$/i', $login)
               && ($this->config->item('login_email_username')
               || !$this->config->item('login_email'))
            ) {

                /*
                 * Get info about user
                 */
                $query = $this->db->select('hash, salt, id')
                                  ->where('login', $login)
                                  ->get('users');
            }
            else {

                /*
                 * Data is wrong - set error
                 */
                $data['error_login'] = $this->lang->line('login_wrong_data');
            }

            /*
             * User exists - check password
             */
            if( $query->num_rows() > 0
             && ($query = $query->row_array())
             && !empty($query['hash'])
             && $this->vinc_auth->makeHash($password, $query['salt']) === $query['hash']
            ) {

                /*
                 * Set session, update last ip and activity, redirect user
                 */
                $this->vinc_auth->setSession($query['id'], $query['hash'], isset($_POST['remember']));
                $this->db->where('id', $query['id']);
                $this->db->update('users', array('last_ip'       => $this->input->ip_address(),
                                                 'last_activity' => $_SERVER['REQUEST_TIME'],
                                                ));
                $this->session->unset_userdata('login_attemps');
                redirect();
            }

            /*
             * Data is wrong - set error
             */
            $data['error_login'] = $this->lang->line('login_wrong_data');

            /*
             * We were trying to select info from DB, but data is wrong
             * And block feature is enabled
             */
            if($this->config->item('login_block_user')) {
                $this->session->set_userdata('login_attemps', intval($this->session->userdata('login_attemps')) + 1);
            }
        }

        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('login_title'), 'js' => array('auth', 'login')));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/auth/login', array('data' => $data,));
    }

    /**
     * Signup page
     * With social features, all customized, wo0OW!
     *
     * @return void
     */
    public function signup()
    {

        /*
         * User already logged in
         */
        if($this->vinc_auth->logged()) {
            redirect();
        }

        /*
         * Signup is disabled
         */
        if($this->config->item('signup_disabled')) {
            $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('signup_title'),));
            $this->load->view($this->application->_('theme') . '/up');
            $this->load->view($this->application->_('theme') . '/auth/disabled');
            return;
        }

        $data = array();

        /*
         * Trying to login with social data
         */
        if( $this->config->item('signup_social')
         && is_array($social = $this->session->userdata('social_login'))
         && isset($social['network'])
        ) {
            $data['social'] = $social;
            unset($social);
        }

        /*
         * User posted some data
         */
        if(isset($_POST['login'])) {

            /*
             * Filtrate inputs
             */
            $login    = trim($this->input->post('login'));
            $password = trim($this->input->post('password'));
            $email    = trim($this->input->post('email'));

            /*
             * Repeat password feature enabled
             */
            if($this->config->item('signup_repeat_password')) {
                $password_repeat = trim($this->input->post('password_repeat'));
            }

            /*
             * Login is short
             */
            if(strlen($login) < 4) {
                $data['errors']['login'] = $this->lang->line('signup_short_login');
            }

            /*
             * Login has wrong format
             */
            elseif(!preg_match('/^[a-z0-9\-_.]{4,30}$/i', $login)) {
                $data['errors']['login'] = $this->lang->line('signup_login_wrong_format');
            }

            /*
             * Checking login for existing in DB
             */
            else {
                $query = $this->db->select('1', false)
                                  ->where('login', $login)
                                  ->get('users');

                /*
                 * Login already exists in DB
                 */
                if($query->num_rows() > 0) {
                    $data['errors']['login'] = $this->lang->line('signup_login_exists');
                }
                unset($query);
            }

            /*
             * Not with social - check password
             */
            if(!isset($data['social'])) {

                /*
                 * Password is short
                 */
                if(strlen($password) < 6) {
                    $data['errors']['password'] = $this->lang->line('signup_short_password');
                }

                /*
                 * Passwords doesn't equals
                 */
                if( isset($password_repeat)
                 && $password_repeat !== $password
                ) {
                    $data['errors']['password_repeat'] = $this->lang->line('signup_not_equal');
                }
            }

            /*
             * Checking email, email isn't valid
             */
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['errors']['email'] = $this->lang->line('signup_email_wrong_format');
            }

            /*
             * Check email for existing
             */
            else {
                $query = $this->db->select('1', false)
                                  ->where('email', $email)
                                  ->get('users');

                /*
                 * Email already exists
                 */
                if($query->num_rows() > 0) {
                    $data['errors']['email'] = $this->lang->line('signup_email_exists');
                }
                unset($query);
            }

            /*
             * Captcha is enabled
             */
            if($this->config->item('signup_captcha')) {

                /*
                 * Recaptcha is enabled
                 */
                if($this->config->item('signup_recaptcha')) {
                    $this->load->library('recaptcha');

                    $this->recaptcha->recaptcha_check_answer();

                    /*
                     * Answer is wrong
                     */
                    if(!$this->recaptcha->getIsValid()) {
                        $data['errors']['captcha'] = $this->lang->line('signup_wrong_captcha');
                    }
                }

                /*
                 * Default CI captcha
                 */
                elseif(strtolower(trim($this->input->post('recaptcha_response_field'))) !== strtolower($this->session->userdata('signup_captcha'))) {
                    $data['errors']['captcha'] = $this->lang->line('signup_wrong_captcha');
                }
            }

            /*
             * Invites is on
             */
            if($this->config->item('signup_invite')) {
                $invite = trim($this->input->post('invite'));

                /*
                 * Invite isn't empty
                 */
                if(!empty($invite)) {

                    /*
                     * Invite is valid
                     */
                    if(strlen($invite) == 32) {

                        /*
                         * Get inviter id
                         */
                        $query = $this->db->select('user_id')
                                          ->where('invite', $invite)
                                          ->get('invites');

                        /*
                         * Invite exists
                         */
                        if($query->num_rows() > 0) {
                            $query   = $query->row_array();
                            $inviter = $query['user_id'];
                        }
                        unset($query);
                    }
                    if(!isset($inviter)) {
                        $data['errors']['invite'] = $this->lang->line('signup_invite_doesnt_exists');
                    }
                }

                /*
                 * Invite is empty but required
                 */
                elseif($this->config->item('signup_invite_required')) {
                    $data['errors']['invite'] = $this->lang->line('signup_invite_required');
                }
            }

            /*
             * Terms feature enabled
             */
            if( $this->config->item('signup_terms')
             && !$this->input->post('agree_terms')) {
                $data['errors']['terms'] = $this->lang->line('signup_agree_terms');
            }

            /*
             * All data passed, register user
             */
            elseif(!isset($data['errors'])
                || sizeof($data['errors']) == 0
            ) {
                $this->load->helper('string');

                /*
                 * Signup with social buttons - tryin to find same UID
                 */
                if(isset($data['social'])) {
                    $this->db->select('1', false);

                    if($data['social']['network'] == 'tumblr') {
                        $this->db->where('social_login', $data['social']['profile']);
                    }
                    else {
                        $this->db->where('social_login', $data['social']['uid'] . '|' . $data['social']['network']);
                    }

                    if($this->db->get('users')->num_rows() > 0) {
                        $this->session->unset_userdata('social_login');
                        redirect('auth/signup');
                    }

                    $password = random_string('unique');
                }

                /*
                 * Generate salt and calculate hash sum
                 */
                $salt = random_string('alnum', 6);
                $hash = $this->vinc_auth->makeHash($password, $salt);

                /*
                 * Inserting data
                 */
                $user = array('login'         => $login,
                              'email'         => $email,
                              'hash'          => $hash,
                              'salt'          => $salt,
                              'group'         => intval($this->config->item('signup_default_group')),
                              'language'      => $this->application->_('language'),
                              'signup_date'   => $_SERVER['REQUEST_TIME'],
                              'signup_ip'     => $this->input->ip_address(),
                              'last_ip'       => $this->input->ip_address(),
                              'last_activity' => $_SERVER['REQUEST_TIME'],
                              'rating'        => intval($this->config->item('signup_default_rating')),

                              /*
                               * Notifications by default
                               */
                              'show_born_date'        => (boolean) $this->config->item('show_born_date'),
                              'show_email'            => (boolean) $this->config->item('show_email'),
                              'allow_email'           => (boolean) $this->config->item('allow_email'),
                              'notify_comments'       => (boolean) $this->config->item('notify_comments'),
                              'notify_comments_email' => (boolean) $this->config->item('notify_comments_email'),
                              'notify_answers'        => (boolean) $this->config->item('notify_answers'),
                              'notify_answers_email'  => (boolean) $this->config->item('notify_answers_email'),
                              'notify_messages'       => (boolean) $this->config->item('notify_messages'),
                              'notify_messages_email' => (boolean) $this->config->item('notify_messages_email'),
                              'notify_follow_news'    => (boolean) $this->config->item('notify_follow_news'),
                              'notify_cats_news'      => (boolean) $this->config->item('notify_cats_news'),
                              'notify_likes'          => (boolean) $this->config->item('notify_likes'),
                              'show_last_login'       => (boolean) $this->config->item('show_last_login'),
                              );

                /*
                 * User signed up with social - write it to DB
                 */
                if(isset($data['social'])) {
                    $this->session->unset_userdata('social_login');
                    $user['social_login'] = ($data['social']['network'] == 'tumblr')
                                             ? $data['social']['profile']
                                             : $data['social']['uid'] . '|' . $data['social']['network'];
                }

                /*
                 * User invited by somebody
                 */
                if(isset($inviter)) {

                    /*
                     * Delete invite from DB
                     */
                    $this->db->delete('invites', array('invite' => $invite, 'user_id' => $inviter,));

                    /*
                     * If invite deleted - then write inviter to DB
                     */
                    if($this->db->affected_rows() > 0) {
                        $user['inviter'] = $inviter;
                    }
                }


                /*
                 * Need email confirmation
                 */
                if($this->config->item('signup_confirm_email')) {

                    /*
                     * Generate random int and calculate hash sum
                     */
                    $user['confirm_code'] = md5(uniqid());

                    /*
                     * Send email to user with confirmation link
                     */
                    $this->load->library('email');
                    $this->lang->load($this->application->_('language') . '_email', 'list');

                    /*
                     * Type of email is HTML
                     */
                    if($this->config->item('signup_html_confirm')) {
                        $this->load->library('registry');

                        /*
                         * Set info to registry
                         */
                        $this->registry->set('username', $login)
                                       ->set('email', $email)
                                       ->set('confirm', $user['confirm_code'])
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
                                                           array($login,
                                                                 $email,
                                                                 site_url('auth/confirm/' . $user['confirm_code']),
                                                                 date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i')),
                                                           $this->lang->line('email_confirm_message')));
                    }

                    $this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
                    $this->email->to($email);

                    $this->email->subject($this->lang->line('email_confirm_subject'));

                    $this->email->send();
                }

                $this->db->set($user);

                /*
                 * Insert user to DB
                 */
                $this->db->insert('users');

                /*
                 * Set session and cookies
                 */
                $this->vinc_auth->setSession($this->db->insert_id(), $hash);

                /*
                 * Need to show confirm page after registration
                 */
                if( $this->config->item('signup_confirm_email')
                 && $this->config->item('signup_show_confirm_page')
                ) {
                    $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('signup_title'), 'js' => array('auth', 'signup')));
                    $this->load->view($this->application->_('theme') . '/up');
                    $this->load->view($this->application->_('theme') . '/auth/signup_after');
                    return;
                }

                redirect();
            }
        }

        /*
         * Captcha is enabled and recaptcha is disabled (for output)
         */
        if( $this->config->item('signup_captcha')
         && !$this->config->item('signup_recaptcha')
        ) {
            $this->load->helper('captcha');

            /*
             * Captcha settings
             */
            $vals = array('img_path'   => FCPATH . 'captcha/',
                          'img_url'    => base_url() . 'captcha/',
                          'expiration' => 7200,
                          );

            $vals['img_width']  = intval($this->config->item('signup_captcha_width'));
            $vals['img_height'] = intval($this->config->item('signup_captcha_height'));

            $image = create_captcha($vals);

            /*
             * Success, image created!
             */
            if($image['image'] !== false) {
                $this->session->set_userdata('signup_captcha', $image['word']);
                $data['captcha'] = $image['image'];
            }
            unset($image);
        }
        
        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('signup_title'), 'js' => array('auth', 'signup')));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/auth/signup', array('data' => $data));
    }

    /**
     * Confirm user email by link, sent on this email
     * Uses template auth/confirm
     *
     * @param string $hash Code from email message
     * @return void
     */
    public function confirm($hash = '')
    {
        $hash = trim($hash);

        $data = array();

        /*
         * Code is valid
         */
        if(strlen($hash) === 32) {

            /*
             * Get user id
             */
            $set = array('confirmed'    => true,
                         'confirm_code' => '',
                        );

            if($this->config->item('signup_after_confirmation')) {
                $set['group'] = $this->config->item('signup_after_confirmation');
            }

            $this->db->where('confirm_code', $hash);
            $this->db->where('confirmed', false);
            $this->db->update('users', $set);

            /*
             * There are confirm hash
             */
            if($this->db->affected_rows() > 0) {
                $data['message'] = $this->lang->line('confirm_success');
            }
        }

        if(!isset($data['message'])) {
            $data['error'] = $this->lang->line('confirm_wrong_code');
        }

        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('confirm_title'),));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/auth/confirm', array('data' => $data,));
    }

    /**
     * Log user out if tokens equals
     * Update user activity
     *
     * @param string $token
     * @return void
     */
    public function logout($token = '')
    {

        /*
         * User isn't logged in or token is invalid or tokens not equals
         */
        if( !$this->vinc_auth->logged()
         || empty($token)
         || $token !== $this->vinc_auth->_('token')
        ) {
            redirect();
        }

        /*
         * All right - update last activity, destroy user session and redirect user
         */
        $this->vinc_auth->updateActivity();
        $this->vinc_auth->destroySession();
        redirect();
    }

    /**
     * Recover password, send email, change password
     *
     * @param string $hash (Default empty)
     * @return void
     */
    public function recover($hash = '')
    {
        $data = array();

        /*
         * User is already logged in
         */
        if($this->vinc_auth->logged()) {
            redirect();
        }

        /*
         * Hash code is empty, user want to send email
         */
        elseif(empty($hash)) {

            /*
             * Captcha enabled and recaptcha disabled
             * It's here because of validation states
             */
            if( $this->config->item('recover_captcha')
             && !$this->config->item('recover_recaptcha')
            ) {

                /*
                 * Save current captcha answer for next validation
                 */
                $session_captcha = $this->session->userdata('signup_captcha');

                $this->load->helper('captcha');

                /*
                 * Settings
                 */
                $vals = array('img_path'   => FCPATH . 'captcha/',
                              'img_url'    => base_url() . 'captcha/',
                              'expiration' => 7200,
                              );

                $vals['img_width']  = intval($this->config->item('recover_captcha_width'));
                $vals['img_height'] = intval($this->config->item('recover_captcha_height'));

                $image = create_captcha($vals);

                /*
                 * Image created - return response
                 */
                if($image['image'] !== false) {
                    $this->session->set_userdata('signup_captcha', $image['word']);
                    $data['captcha'] = $image['image'];
                }
                unset($image);
            }

            /*
             * User posted form
             */
            if(isset($_POST['login'])) {

                /*
                 * Captcha feature enabled
                 */
                if($this->config->item('recover_captcha')) {

                    /*
                     * Recaptcha enabled
                     */
                    if($this->config->item('recover_recaptcha')) {
                        $this->load->library('recaptcha');
                        $this->recaptcha->recaptcha_check_answer();
                    }
                    
                    /*
                     * Answer is wrong
                     */
                    if( (isset($this->recaptcha)
                     && !$this->recaptcha->getIsValid())
                     || (!isset($this->recaptcha)
                     && strtolower(trim($this->input->post('recaptcha_response_field'))) !== strtolower($session_captcha))
                    ) {
                        $data['error_captcha'] = $this->lang->line('signup_wrong_captcha');
                        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('recover_title'), 'js' => array('recover', 'auth'),));
                        $this->load->view($this->application->_('theme') . '/up');
                        $this->load->view($this->application->_('theme') . '/auth/recover', array('data' => $data,));
                        return;
                    }
                }

                $login = trim($this->input->post('login'));

                /*
                 * Recover with email, email is valid
                 */
                if( ($this->config->item('recover_email')
                 || $this->config->item('recover_email_username'))
                 && filter_var($login, FILTER_VALIDATE_EMAIL)
                ) {

                    $query = $this->db->select('language, login, id')
                                      ->where('email', $login)
                                      ->get('users');
                }

                /*
                 * Recover with login, login is valid
                 */
                elseif((!$this->config->item('recover_email')
                    || $this->config->item('recover_email_username'))
                    && preg_match('/^[a-z0-9\-_.]{4,30}$/i', $login)
                ) {

                    /*
                     * Get info about user
                     */
                    $query = $this->db->select('language, email, id')
                                      ->where('login', $login)
                                      ->get('users');
                }

                /*
                 * Data is valid, user exists
                 */
                if( isset($query)
                 && $query->num_rows() > 0
                ) {
                    $query = $query->row_array();

                    /*
                     * Selected with email
                     */
                    if(isset($query['login'])) {
                        $email = $login;
                        $login = $query['login'];
                    }

                    /*
                     * Selected with login
                     */
                    else {
                        $email = $query['email'];
                    }

                    $code = md5(uniqid());

                    /*
                     * Delete recover codes created before
                     */
                    $this->db->where('id', $query['id']);
                    $this->db->update('users', array('recover_expire'  => ($_SERVER['REQUEST_TIME'] + 3600 * $this->config->item('recover_expire')),
                                                     'recover_code'    => $code,
                                                    ));

                    /*
                     * Code added to DB
                     */
                    if($this->db->affected_rows() > 0) {

                        /*
                         * Language from DB is valid and exists
                         */
                        if( !empty($query['language'])
                         && array_key_exists($query['language'], $this->config->item('languages'))
                         && file_exists(FCPATH . APPPATH . 'language/list/' . $query['language'] . '_email_lang.php')
                        ) {
                            $this->lang->load($query['language'] . '_email', 'list');
                        }
                        else {
                            $this->lang->load($this->config->item('site_language') . '_email', 'list');
                        }

                        $this->load->library('email');

                        /*
                         * Type of email is HTML
                         */
                        if($this->config->item('recover_html_confirm')) {
                            $this->load->library('registry');

                            /*
                             * Set info to registry
                             */
                            $this->registry->set('username', $login)
                                           ->set('email', $email)
                                           ->set('code', $code)
                                           ->set('date', date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i'));

                            /*
                             * Set email type to HTML
                             */
                            $this->email->initialize(array('mailtype' => 'html'));

                            /*
                             * Send email
                             */
                            $this->email->message($this->load->file(FCPATH . APPPATH . 'views/full/email/recover.php', true));
                        }
                        else {

                            /*
                             * Message - is simple text, sending
                             */
                            $this->email->message(str_ireplace(array('%username%',
                                                                     '%email%',
                                                                     '%code%',
                                                                     '%date%',),
                                                               array($login,
                                                                     $email,
                                                                     site_url('auth/recover/' . $code),
                                                                     date('d.m.Y') . ' ' . $this->lang->line('date_at') . ' ' . date('G:i')),
                                                               $this->lang->line('email_recover_message')));
                        }

                        $this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
                        $this->email->to($email);

                        $this->email->subject($this->lang->line('email_recover_subject'));

                        $this->email->send();

                        $data['success'] = $this->lang->line('recover_sended');
                    }
                    else {
                        $data['warning'] = $this->lang->line('recover_server_error');
                    }
                }

                if( !isset($data['warning'])
                 && !isset($data['success'])
                ) {
                    $data['error_login'] = $this->lang->line('recover_wrong_data');
                }
            }
        }

        /*
         * There is hash code
         */
        else {
            $hash = trim($hash);

            /*
             * Hash is invalid
             */
            if(strlen($hash) !== 32) {
                $data['error'] = $this->lang->line('recover_wrong_code');
                $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('recover_title'), 'js' => array('recover', 'auth'),));
                $this->load->view($this->application->_('theme') . '/up');
                $this->load->view($this->application->_('theme') . '/auth/recover_last', array('data' => $data,));
                return;
            }

            /*
             * Select info about hash and user
             */
            $query = $this->db->select('id, recover_expire, email, login')
                              ->where('recover_code', $hash)
                              ->get('users');

            /*
             * There is no user with same hash
             */
            if($query->num_rows() == 0) {
                $data['error'] = $this->lang->line('recover_wrong_code');
                $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('recover_title'), 'js' => array('recover', 'auth'),));
                $this->load->view($this->application->_('theme') . '/up');
                $this->load->view($this->application->_('theme') . '/auth/recover_last', array('data' => $data,));
                return;
            }

            /*
             * There is user with same hash, but hash is expired
             */
            elseif(($query = $query->row_array())
                && $query['recover_expire'] <= $_SERVER['REQUEST_TIME']
            ) {

                /*
                 * Set hash to empty string, display message
                 */
                $this->db->where('id', $query['id']);
                $this->db->update('users', array('recover_code' => '',));

                $data['error'] = $this->lang->line('recover_expire_error');
                $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('recover_title'), 'js' => array('recover', 'auth'),));
                $this->load->view($this->application->_('theme') . '/up');
                $this->load->view($this->application->_('theme') . '/auth/recover_last', array('data' => $data,));
                return;
            }

            /*
             * User submited the form
             */
            elseif(isset($_POST['password'])) {
                $password        = trim($this->input->post('password'));
                $password_repeat = trim($this->input->post('repeat'));

                /*
                 * Password is invalid
                 */
                if(strlen($password) < 6) {
                    $data['error_password'] = $this->lang->line('signup_short_password');
                }

                /*
                 * Passwords doesn't equal
                 */
                elseif($password !== $password_repeat) {
                    $data['error_repeat'] = $this->lang->line('signup_not_equal');
                }

                /*
                 * All right, generate salt, make a hash
                 */
                else {
                    $this->load->helper('string');

                    $salt = random_string('alnum', 6);
                    $hash = $this->vinc_auth->makeHash($password, $salt);

                    /*
                     * Update userinfo
                     */
                    $this->db->where('id', $query['id']);
                    $this->db->update('users', array('hash'         => $hash,
                                                     'salt'         => $salt,
                                                     'recover_code' => '',
                                                    ));

                    if($this->db->affected_rows() > 0) {
                        $data['success'] = $this->lang->line('recover_changed');
                    }
                    else {
                        $data['warning'] = $this->lang->line('recover_server_error');
                    }
                }
            }

            $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('recover_title'), 'js' => array('recover', 'auth'),));
            $this->load->view($this->application->_('theme') . '/up');
            $this->load->view($this->application->_('theme') . '/auth/recover_last', array('data' => $data,));
            return;
        }

        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->lang->line('recover_title'), 'js' => array('recover', 'auth'),));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/auth/recover', array('data' => $data,));
    }


    /*
     *+-----------------------------------------------------------------------------------------------------------
     *|
     *| AJAX Features: Captcha Reload, Check signup, Check email, Check username
     *|
     *+-----------------------------------------------------------------------------------------------------------
     */


    public function checkLogin()
    {

        /*
         * User already logged in
         */
        if($this->vinc_auth->logged()) {
            $this->application->_sendAJAX(array('error' => 'Already logged in',));
        }

        /*
         * Block user feature is enabled
         */
        if($this->config->item('login_block_user')) {

            /*
             * Number of attemps >= in config
             */
            if(intval($this->session->userdata('login_attemps')) >= $this->config->item('login_block_attemps')) {

                /*
                 * Set login blocked session, unset number of attemps, return response
                 */
                $this->session->set_userdata('login_blocked', $_SERVER['REQUEST_TIME'] + $this->config->item('login_block_time') * 60);
                $this->session->unset_userdata('login_attemps');
                $this->application->_sendAJAX(array('warning' => $this->lang->line('login_blocked'),));
            }

            /*
             * User has been blocked
             */
            elseif($this->session->has('login_blocked')) {

                /*
                 * Still blocked, return response
                 */
                if($this->session->userdata('login_blocked') > $_SERVER['REQUEST_TIME']) {
                    $this->application->_sendAJAX(array('warning' => $this->lang->line('login_blocked'),));
                }

                /*
                 * Already free, unset session
                 */
                else {
                    $this->session->unset_userdata('login_blocked');
                }
            }
        }

        /*
         * Captcha enabled
         */
        if( $this->config->item('login_captcha')
         && !$this->session->userdata('login_captcha_pass')
        ) {

            /*
             * Recaptcha
             */
            if($this->config->item('login_recaptcha')) {
                $this->load->library('recaptcha');

                $this->recaptcha->recaptcha_check_answer($_SERVER['REMOTE_ADDR']
                                                       , $this->input->post('recaptcha_challenge_field')
                                                       , $this->input->post('recaptcha_response_field'));
            }

            /*
             * Answer is wrong
             */
            if( (isset($this->recaptcha)
             && !$this->recaptcha->getIsValid())
             || (!isset($this->recaptcha)
             && strtolower(trim($this->input->post('recaptcha_response_field'))) !== strtolower($this->session->userdata('signup_captcha')))
            ) {
                $this->application->_sendAJAX(array('message' => 'All right:)'));
            }

            $this->session->set_userdata('login_captcha_pass', true);
        }

        $login    = trim($this->input->post('login'));
        $password = trim($this->input->post('password'));

        /*
         * Password is invalid
         */
        if(strlen($password) < 6) {

            /*
             * Set error, display
             */
            $this->application->_sendAJAX(array('error' => $this->lang->line('login_wrong_data')));
        }

        /*
         * No errors in captcha, no warnings
         * Signing in with email
         */
        elseif(($this->config->item('login_email')
            || $this->config->item('login_email_username'))
            && filter_var($login, FILTER_VALIDATE_EMAIL)
        ) {

            /*
             * Get user info
             */
            $query = $this->db->select('login, hash, salt')
                              ->where('email', $login)
                              ->get('users');

            /*
             * User exists - check password
             */
            if( $query->num_rows() > 0
             && ($query = $query->row_array())
             && !empty($query['hash'])
             && $this->vinc_auth->makeHash($password, $query['salt']) === $query['hash']
            ) {
                $this->application->_sendAJAX(array('message' => 'All right:)'));
            }
        }

        /*
         * Signing in with login
         */
        elseif(preg_match('/^[a-z0-9\-_.]{4,30}$/i', $login)
           && ($this->config->item('login_email_username')
           || !$this->config->item('login_email'))
        ) {

            /*
             * Get info about user
             */
            $query = $this->db->select('email, hash, salt')
                              ->where('login', $login)
                              ->get('users');

            /*
             * User exists - check password
             */
            if( $query->num_rows() > 0
             && ($query = $query->row_array())
             && !empty($query['hash'])
             && $this->vinc_auth->makeHash($password, $query['salt']) === $query['hash']
            ) {
                $this->application->_sendAJAX(array('message' => 'All right:)'));
            }
        }

        /*
         * We were trying to select info from DB
         * And block feature is enabled
         */
        if( isset($query)
         && $this->config->item('login_block_user')
        ) {
            $this->session->set_userdata('login_attemps', intval($this->session->userdata('login_attemps')) + 1);
        }
        $this->application->_sendAJAX(array('error' => $this->lang->line('login_wrong_data')));
    }


    /**
     * Returns captcha image HTML code by AJAX
     *
     * @return void
     */
    public function captcha()
    {

        /*
         * Load config and language
         */
        $this->load->helper('captcha');

        /*
         * Settings
         */
        $vals = array('img_path'   => FCPATH . 'captcha/',
                      'img_url'    => base_url() . 'captcha/',
                      'expiration' => 7200,
                      );

        $vals['img_width']  = intval($this->config->item('signup_captcha_width'));
        $vals['img_height'] = intval($this->config->item('signup_captcha_height'));

        $image = create_captcha($vals);

        /*
         * Image created - return response
         */
        if($image['image'] !== false) {
            $this->session->set_userdata('signup_captcha', $image['word']);
            $this->application->_sendAJAX(array('captcha' => $image['image'],));
        }
        $this->application->_sendAJAX(array('error' => 'There are some error',));
    }

    /**
     * Check user data via AJAX
     * Checks username, email for duplicates, password, invite
     *
     * @return void
     */
    public function checkSignup()
    {

        /*
         * User already logged in
         */
        if($this->vinc_auth->logged()) {
            $this->application->_sendAJAX(array('error' => 'Already logged in',));
        }

        $login    = trim($this->input->post('login'));
        $email    = trim($this->input->post('email'));
        $password = trim($this->input->post('password'));
        $repeat   = trim($this->input->post('repeat'));
        $terms    = (boolean) $this->input->post('terms');
        $invite   = trim($this->input->post('invite'));

        $data = array();

        /*
         * Login is short
         */
        if(strlen($login) < 4) {
            $data['login'] = $this->lang->line('signup_short_login');
        }

        /*
         * Login has wrong format
         */
        elseif(!preg_match('/^[a-z0-9\-_.]{4,30}$/i', $login)) {
            $data['login'] = $this->lang->line('signup_login_wrong_format');
        }

        /*
         * Checking login for existing in DB
         */
        else {
            $query = $this->db->select('1', false)
                              ->where('login', $login)
                              ->get('users');

            /*
             * Login already exists in DB
             */
            if($query->num_rows() > 0) {
                $data['login'] = $this->lang->line('signup_login_exists');
            }
            unset($query);
        }

        /*
         * Checking email, email isn't valid
         */
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $this->lang->line('signup_email_wrong_format');
        }

        /*
         * Check email for existing
         */
        else {
            $query = $this->db->select('1', false)
                              ->where('email', $email)
                              ->get('users');

            /*
             * Email already exists
             */
            if($query->num_rows() > 0) {
                $data['email'] = $this->lang->line('signup_email_exists');
            }
            unset($query);
        }

        /*
         * Not with social - check password
         */
        if( !$this->config->item('signup_social')
         || !is_array($social = $this->session->userdata('social_login'))
         || !isset($social['network'])
        ) {

            /*
             * Password is short
             */
            if(strlen($password) < 6) {
                $data['password'] = $this->lang->line('signup_short_password');
            }

            /*
             * Passwords doesn't equals
             */
            if( $this->config->item('signup_repeat_password')
             && $repeat !== $password
            ) {
                $data['password-repeat'] = $this->lang->line('signup_not_equal');
            }
        }

        /*
         * Terms feature enabled
         */
        if( $this->config->item('signup_terms')
         && $terms == false
        ) {
            $data['terms'] = $this->lang->line('signup_agree_terms');
        }

        /*
         * Invite feature enabled
         */
        if($this->config->item('signup_invite')) {

            /*
             * Invite isn't empty
             */
            if(!empty($invite)) {

                /*
                 * Invite is valid
                 */
                if(strlen($invite) == 32) {

                    /*
                     * Get inviter id
                     */
                    $query = $this->db->select('user_id')
                                      ->where('invite', $invite)
                                      ->get('invites');

                    /*
                     * Invite exists
                     */
                    if($query->num_rows() == 0) {
                        $data['invite'] = $this->lang->line('signup_invite_doesnt_exists');
                    }
                    unset($query);
                }
                else {
                    $data['invite'] = $this->lang->line('signup_invite_doesnt_exists');
                }
            }

            /*
             * Invite is empty but required
             */
            elseif($this->config->item('signup_invite_required')) {
                $data['invite'] = $this->lang->line('signup_invite_required');
            }
        }

        if(sizeof($data) > 0) {
            $this->application->_sendAJAX(array('errors' => $data,));            
        }
        $this->application->_sendAJAX(array('message' => 'All right:)',));
    }

    /**
     * Check user name via AJAX
     * Checks username for duplicates
     *
     * @return void
     */
    public function checkUsername()
    {

        /*
         * Posted not empty data
         */
        if($this->input->post('login')) {
            $login = trim($this->input->post('login'));

            /*
             * Login is short
             */
            if(strlen($login) < 4) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('signup_short_login')));
            }

            /*
             * Login is invalid
             */
            elseif(!preg_match('/^[a-z0-9\-_.]{4,30}$/i', $login)) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('signup_login_wrong_format')));
            }

            /*
             * Get same login
             */
            $query = $this->db->select('1', false)
                              ->where('login', $login);

            if($this->vinc_auth->logged()) {
                $query = $query->where('id !=', $this->vinc_auth->_('id'));
            }
            $query = $query->get('users');
            
            /*
             * Same login exists
             */
            if($query->num_rows() > 0) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('signup_login_exists')));
            }
            $this->application->_sendAJAX(array('message' => 'All right:)'));
        }
        $this->application->_sendAJAX(array('error' => $this->lang->line('signup_short_login')));
    }

    /**
     * Check email AJAX method
     * Checks email for duplicates
     *
     * @return void
     */
    public function checkEmail()
    {

        /*
         * User posted not empty email
         */
        if($this->input->post('email')) {
            $email = trim($this->input->post('email'));

            /*
             * Email is invalid
             */
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('signup_email_wrong_format')));
            }
            
            /*
             * Select same email
             */
            $query = $this->db->select('1', false)
                              ->where('email', $email);

            if($this->vinc_auth->logged()) {
                $query = $query->where('id !=', $this->vinc_auth->_('id'));
            }
            $query = $query->get('users');

            /*
             * There are same email
             */
            if($query->num_rows() > 0) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('signup_email_exists')));
            }
            $this->application->_sendAJAX(array('message' => 'All right:)'));
        }
        $this->application->_sendAJAX(array('error' => $this->lang->line('signup_email_wrong_format')));
    }
}