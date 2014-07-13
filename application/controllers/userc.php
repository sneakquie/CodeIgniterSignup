<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Controller (not Controller in OOP meaning, because my Controller is so thick)
 *
 * @author sneakquie (Salabai Dmitrii)
 * @package Vinc
 * @subpackage Controllers
 */
class UserC extends CI_Controller
{
    protected $_user        = array();
    protected $_isMyProfile = false;

    public function index($user = '', $mod = '')
    {
        $user = trim($user);

        if( !in_array($mod = trim(strtolower($mod)), array('followers', 'followings', 'comments', 'posts', 'main'))
         || !$this->config->item('user_show_' . $mod)
         || !method_exists($this, '_' . $mod)
         || empty($user)
         || !preg_match('/^[a-z0-9\-_.]{4,30}$/i', $user)
        ) {
            show_404();
        }
        elseif($this->vinc_auth->logged()) {
            $query = $this->db->select('1', false)
                              ->where('login', $user)
                              ->where('id', $this->vinc_auth->_('id'))
                              ->get('users');

            if($query->num_rows > 0) {
                $this->_isMyProfile = true;
                $this->_user        = $this->vinc_auth->getData();
            }
            else {
                $this->_user = $this->db->select('users.*, followings.user_follower as is_follower, complaints.author_id as already_flagged, notes.text as note')
                                        ->join('followings', 'followings.user_id = users.id AND followings.user_follower = ' . $this->vinc_auth->_('id'), 'left')
                                        ->join('complaints', 'complaints.user_id = users.id AND complaints.author_id = ' . $this->vinc_auth->_('id'), 'left')
                                        ->join('notes', 'notes.user_id = users.id AND notes.author_id = ' . $this->vinc_auth->_('id'), 'left');
            }
            unset($query);
        }
        else {
            $this->_user = $this->db->select();
        }

        if(!$this->_isMyProfile) {
            $this->_user = $this->_user->where('login', $user)
                                       ->get('users');

            if($this->_user->num_rows() == 0) {
                show_404();
            }

            $this->_user = $this->_user->row_array();
        }

        $this->db->flush_cache();

        call_user_func_array(array(&$this, '_' . $mod), array());

        $this->load->view($this->application->_('theme') . '/head', array('title' => $this->_user['login'],
                                                                          'js'    => array('auth', 'user', 'jquery.fs.selecter', 'jquery.fs.scroller'),
                                                                          'css'   => array('jquery.fs.selecter', 'jquery.fs.scroller')));
        $this->load->view($this->application->_('theme') . '/up');
        $this->load->view($this->application->_('theme') . '/user/profile_sm', array('user' => $this->_user, 'is_my_profile' => $this->_isMyProfile,));
        $this->load->view($this->application->_('theme') . '/user/' . $mod, array('user' => $this->_user, 'is_my_profile' => $this->_isMyProfile,));
        $this->load->view($this->application->_('theme') . '/user', array('user' => $this->_user, 'is_my_profile' => $this->_isMyProfile,));
        $this->load->view($this->application->_('theme') . '/bottom');
    }

    public function _main()
    {
        if( $this->config->item('show_signup_date')
         && intval($this->_user['inviter']) > 0
        ) {
            $inviter = $this->db->select('login')
                                ->where('id', $this->_user['inviter'])
                                ->get('users');
            $inviter->num_rows() > 0 && $this->_user['inviter'] = $inviter->row_array();
            unset($inviter);
        }

        if($this->config->item('show_invited_users')) {
            $invited = $this->db->select('login')
                                ->where('inviter', $this->_user['id'])
                                ->get('users');

            $invited->num_rows() > 0 && $this->_user['invited_users'] = $invited->result_array();
            unset($invited);
        }

        if($this->config->item('user_show_followers')) {
            $followers = $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS users.login, users.photo_avatar', false)
                                  ->join('users', 'followings.user_follower = users.id', 'left')
                                  ->where('user_id', $this->_user['id'])
                                  ->limit(15)
                                  ->get('followings');

            if($followers->num_rows() > 0)  {
                $this->_user['followers'] = $followers->result_array();

                $this->_user['followers_number'] = (($size = sizeof($this->_user['followers'])) < 15)
                                                    ? $size
                                                    : $this->db->query('SELECT FOUND_ROWS()')->num_rows();
            }
            unset($followers);
        }

        if($this->config->item('user_show_followings')) {
            $followings = $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS users.login, users.photo_avatar', false)
                                   ->join('users', 'followings.user_id = users.id', 'left')
                                   ->where('user_follower', $this->_user['id'])
                                   ->limit(15)
                                   ->get('followings');
            if($followings->num_rows() > 0) {
                $this->_user['followings'] = $followings->result_array();

                $this->_user['followings_number'] = (($size = sizeof($this->_user['followings'])) < 15)
                                                     ? $size
                                                     : $this->db->query('SELECT FOUND_ROWS()')->num_rows();
            }
            unset($followings);
        }
    }

    public function _followers()
    {
        $this->_user['followers'] = $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS users.login, users.location, users.website, users.photo_avatar, users.real_name, users.rating, users.verified', false)
                                             ->join('users', 'users.id = followings.user_follower', 'left')
                                             ->where('user_id', $this->_user['id'])
                                             ->limit($this->config->item('followings_number'))
                                             ->get('followings')
                                             ->result_array();

        if(($size = sizeof($this->_user['followers'])) < $this->config->item('followings_number')) {
            $this->_user['followers_number'] = $size;
        }
        else {
            $this->_user['followers_number'] = $this->db->query('SELECT FOUND_ROWS() as `number`')->row_array();
            $this->_user['followers_number'] = $this->_user['followers_number']['number'];
        }
    }

    public function _followings()
    {
        $this->_user['followings'] = $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS users.login, users.location, users.website, users.photo_avatar, users.real_name, users.rating, users.verified', false)
                                              ->join('users', 'users.id = followings.user_id', 'left')
                                              ->where('user_follower', $this->_user['id'])
                                              ->limit($this->config->item('followings_number'))
                                              ->get('followings')
                                              ->result_array();

        if(($size = sizeof($this->_user['followings'])) < $this->config->item('followings_number')) {
            $this->_user['followings_number'] = $size;
        }
        else {
            $this->_user['followings_number'] = $this->db->query('SELECT FOUND_ROWS() as `number`')->row_array();
            $this->_user['followings_number'] = $this->_user['followings_number']['number'];
        }
    }

    public function _posts()
    {
        if(!$this->config->item('user_posts_page')) {
            show_404();
        }
    }

    public function _comments()
    {
        if(!$this->config->item('user_comments_page')) {
            show_404();
        }
    }

    public function follow()
    {
        if( !isset($_POST['user_id'])
         || ($user_id = intval($this->input->post('user_id'))) <= 0
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_follow_error')));
        }

        elseif(!$this->vinc_auth->logged()) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_follow_not_logged')));
        }

        elseif($user_id == intval($this->vinc_auth->_('id'))) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_follow_error')));
        }

        $query = $this->db->select('1', false)
                          ->where('user_id', $user_id)
                          ->where('user_follower', $this->vinc_auth->_('id'))
                          ->get('followings');

        if($query->num_rows() > 0) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_follow_already')));
        }

        $this->db->insert('followings', array('user_id'       => $user_id,
                                              'user_follower' => $this->vinc_auth->_('id'),
                                              'date'          => $_SERVER['REQUEST_TIME'],));

        $this->application->_sendAJAX(array('success' => $this->lang->line('user_follow_success')));
    }

    public function unfollow()
    {
        if( !isset($_POST['user_id'])
         || ($user_id = intval($this->input->post('user_id'))) <= 0
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_follow_error')));
        }

        elseif(!$this->vinc_auth->logged()) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_follow_not_logged')));
        }

        $query = $this->db->delete('followings', array('user_id'       => $user_id,
                                                       'user_follower' => $this->vinc_auth->_('id')));

        $this->application->_sendAJAX(array('success' => $this->lang->line('user_unfollow_success')));
    }

    public function email()
    {
        if(($user_id = intval($this->input->post('user_id'))) <= 0
         || ($this->vinc_auth->logged()
         && $user_id == $this->vinc_auth->_('id'))) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }

        $query = $this->db->select('allow_email, language, email, login')
                          ->where('id', $user_id)
                          ->get('users');

        if( $query->num_rows() == 0
         || (($query = $query->row_array())
         && !$query['allow_email'])
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }

        $this->load->library('email');
        $this->lang->load($this->application->_('language') . '_email', 'list');

        /*
         * Type of email is HTML
         */
        if($this->config->item('contact_email_html_confirm')) {
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
    }

    public function flag()
    {
        if(($user_id = intval($this->input->post('user_id'))) <= 0
         || ($this->vinc_auth->logged()
         && $user_id == $this->vinc_auth->_('id'))
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }
        elseif(($flag_spam = intval($this->session->userdata('flag_spam')))
            && $flag_spam > $_SERVER['REQUEST_TIME']
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_spam')));
        }
        else {
            $this->session->unset_userdata('flag_spam');
        }

        $type    = in_array($type = intval($this->input->post('type')), range(1, 5))
                    ? $type
                    : 1;

        if($this->vinc_auth->logged()) {
            $query = $this->db->where('author_id', $this->vinc_auth->_('id'))
                              ->where('user_id', $user_id)
                              ->get('complaints');

            if($query->num_rows() > 0) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_already')));
            }

            $this->db->insert('complaints', array('user_id'   => $user_id,
                                                  'author_id' => $this->vinc_auth->_('id'),
                                                  'type'      => $type,));
        }
        else {
            if(!$this->config->item('allow_flag_unlogged')) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_unlogged')));
            }

            $this->db->insert('complaints', array('user_id' => $user_id,
                                                  'type'    => $type,));
        }

        $this->session->set_userdata('flag_spam', $_SERVER['REQUEST_TIME'] + $this->config->item('user_flag_antispam'));
        $this->application->_sendAJAX(array('success' => $this->lang->line('user_flag_success')));
    }

    public function note()
    {
        if( ($user_id = intval($this->input->post('user_id'))) <= 0
         || !$this->vinc_auth->logged()
         || intval($this->vinc_auth->_('id')) == $user_id
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }

        $text = substr(htmlspecialchars($this->input->post('text'), ENT_QUOTES), 0, 127);

        $query = $this->db->select('1', false)
                          ->where('user_id', $user_id)
                          ->where('author_id', $this->vinc_auth->_('id'))
                          ->get('notes');

        if($query->num_rows() > 0) {
            $this->db->where('user_id', $user_id)
                     ->where('author_id', $this->vinc_auth->_('id'))
                     ->update('notes', array('text' => $text));
        }
        else {
            $this->db->insert('notes', array('text' => $text, 'author_id' => $this->vinc_auth->_('id'), 'user_id' => $user_id));
        }

        $this->application->_sendAJAX(array('success' => $this->lang->line('user_note_saved'), 'note' => $text));
    }

    public function edit()
    {
        if( !$this->vinc_auth->logged()
         || ($user_id = intval($this->input->post('user_id'))) <= 0
         || $user_id == intval($this->vinc_auth->_('id'))
         || !$this->vinc_auth->_('group_edit_users')
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }

        $data = array();

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

        $data['born_date'] = $birth['day'] . '.' . $birth['month'] . '.' . $birth['year'];

        $data['email']     = trim($this->input->post('email'));

        /*
         * Email isn't valid
         */
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_email')));
        }

        $query = $this->db->select('1', false)
                          ->where('email', $data['email'])
                          ->where('id !=', $user_id)
                          ->get('users');

        /*
         * Email already exists
         */
        if($query->num_rows() > 0) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_email_exists')));
        }


        $data['location'] = trim(strip_tags($this->security->xss_clean($this->input->post('location'))));

        isset($data['location'][127]) && $data['location'] = substr($data['location'], 0, 127);

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
                          ->where('id !=', $user_id)
                          ->get('users');

        /*
         * Login already exists
         */
        if($query->num_rows() > 0) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('settings_login_exists')));
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

        $data['photo_avatar'] = trim($this->security->xss_clean($this->input->post('avatar')));

        $data['photo_cover']  = trim($this->security->xss_clean($this->input->post('cover')));

        $data['verified']     = (boolean) $this->input->post('verified');

        if($this->input->post('password')) {
            $new_password = trim($this->input->post('password'));

            if(!isset($new_password[5])) {
                $this->application->_sendAJAX(array('error' => $this->lang->line('settings_wrong_new_password')));
            }

            $this->load->helper('string');
            $data['salt'] = random_string('alnum', 6);
            $data['hash'] = $this->vinc_auth->makeHash($new_password, $data['salt']);
            $data['last_change'] = $_SERVER['REQUEST_TIME'];
        }

        $this->db->where('id', $user_id)
                 ->update('users', $data);

        $data['born_date'] = str_ireplace(array(':day', ':month', ':year',),
                                          array($birth['day'],
                                                $this->lang->line('user_month_' . $birth['month']),
                                                $birth['year']),
                                          $this->lang->line('user_born_format'));
        $data['photo_avatar'] = $this->vinc_auth->getUserAvatar($data['photo_avatar']);
        $data['photo_cover']  = $this->vinc_auth->getUserCover($data['photo_cover']);
        $data['success'] = $this->lang->line('settings_saved');
        $this->application->_sendAJAX($data);
    }

    public function followingsLoad()
    {
        if( !$this->config->item('user_show_followings')
         || ($user_id = intval($this->input->post('user_id'))) <= 0
         || ($offset  = intval($this->input->post('offset')))  < $this->config->item('followings_number')
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }

        $result = array('followings' => array(), 'success' => 'All right:)');
        $query = $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS users.login, users.location, users.website, users.photo_avatar, users.real_name, users.rating, users.verified', false)
                          ->join('users', 'users.id = followings.user_id', 'left')
                          ->where('user_follower', $user_id)
                          ->limit($this->config->item('followings_number'), $offset)
                          ->get('followings')
                          ->result_array();

        if(sizeof($query) > 0) {
            $number = $this->db->query('SELECT FOUND_ROWS() as `number`')->row_array();
            if($number['number'] > $offset + $this->config->item('followings_number')) {
                $result['offset'] = $offset + $this->config->item('followings_number');
            }
            foreach ($query as $key => $value) {
                $result['followings'][$key] = <<<HTML
<div class="media follow-user">
<a class="pull-left follow-user-blocklink" target="_blank" href="{$this->vinc_auth->userURL($value['login'])}">
<img class="media-object img-rounded" src="{$this->vinc_auth->getUserAvatar($value['photo_avatar'])}">
</a>
<div class="media-body">
<h4 class="media-heading">
<a href="{$this->vinc_auth->userURL($value['login'])}">{$value['login']}</a>
HTML;
                if(!is_null($value['real_name']) && !empty($value['real_name'])) {
                    $result['followings'][$key] .= ' <small>(' . $value['real_name'] . ')</small>';
                }
                if($this->config->item('allow_verified') && $value['verified']) {
                    $result['followings'][$key] .= ' <span class="label label-success verified-badge" data-toggle="tooltip" data-original-title="' . $this->lang->line('user_verified') . '"><span class="glyphicon glyphicon-ok"></span></span>';
                }
                $result['followings'][$key] .= '</h4><span class="glyphicon glyphicon-star"></span> ' . $this->lang->line('user_rating') . ': <b>' . $value['rating'] . '</b>';

                if($this->config->item('allow_website') && !is_null($value['website']) && !empty($value['website'])) {
                    $result['followings'][$key] .= '<span class="divider">/</span>
<span class="glyphicon glyphicon-globe"></span> ' . $this->lang->line('user_homepage') . ': <a href="http://' . $value['website'] . '" target="_blank">
<b>' . $value['website'] . '</b><span class="glyphicon glyphicon-new-window" style="font-size:11px;"></span></a>';
                }
                if(!is_null($value['location']) && !empty($value['location'])) {
                    $result['followings'][$key] .= '<span class="divider">/</span>
<span class="glyphicon glyphicon-map-marker"></span> ' . $this->lang->line('user_location') . ': <b>' . $value['location'] . '</b>';
                }
                $result['followings'][$key] .= '</div></div>';
            }
        }
        unset($query);

        $this->application->_sendAJAX($result);
    }

    public function followersLoad()
    {
        if( !$this->config->item('user_show_followers')
         || ($user_id = intval($this->input->post('user_id'))) <= 0
         || ($offset  = intval($this->input->post('offset')))  < $this->config->item('followings_number')
        ) {
            $this->application->_sendAJAX(array('error' => $this->lang->line('user_flag_error')));
        }

        $result = array('followers' => array(), 'success' => 'All right:)');
        $query = $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS users.login, users.location, users.website, users.photo_avatar, users.real_name, users.rating, users.verified', false)
                          ->join('users', 'users.id = followings.follower_id', 'left')
                          ->where('user_id', $user_id)
                          ->limit($this->config->item('followings_number'), $offset)
                          ->get('followings')
                          ->result_array();

        if(sizeof($query) > 0) {
            $number = $this->db->query('SELECT FOUND_ROWS() as `number`')->row_array();
            if($number['number'] > $offset + $this->config->item('followings_number')) {
                $result['offset'] = $offset + $this->config->item('followings_number');
            }
            foreach ($query as $key => $value) {
                $result['followers'][$key] = <<<HTML
<div class="media follow-user">
<a class="pull-left follow-user-blocklink" target="_blank" href="{$this->vinc_auth->userURL($value['login'])}">
<img class="media-object img-rounded" src="{$this->vinc_auth->getUserAvatar($value['photo_avatar'])}">
</a>
<div class="media-body">
<h4 class="media-heading">
<a href="{$this->vinc_auth->userURL($value['login'])}">{$value['login']}</a>
HTML;
                if(!is_null($value['real_name']) && !empty($value['real_name'])) {
                    $result['followers'][$key] .= ' <small>(' . $value['real_name'] . ')</small>';
                }
                if($this->config->item('allow_verified') && $value['verified']) {
                    $result['followers'][$key] .= ' <span class="label label-success verified-badge" data-toggle="tooltip" data-original-title="' . $this->lang->line('user_verified') . '"><span class="glyphicon glyphicon-ok"></span></span>';
                }
                $result['followers'][$key] .= '</h4><span class="glyphicon glyphicon-star"></span> ' . $this->lang->line('user_rating') . ': <b>' . $value['rating'] . '</b>';

                if($this->config->item('allow_website') && !is_null($value['website']) && !empty($value['website'])) {
                    $result['followers'][$key] .= '<span class="divider">/</span>
<span class="glyphicon glyphicon-globe"></span> ' . $this->lang->line('user_homepage') . ': <a href="http://' . $value['website'] . '" target="_blank">
<b>' . $value['website'] . '</b><span class="glyphicon glyphicon-new-window" style="font-size:11px;"></span></a>';
                }
                if(!is_null($value['location']) && !empty($value['location'])) {
                    $result['followers'][$key] .= '<span class="divider">/</span>
<span class="glyphicon glyphicon-map-marker"></span> ' . $this->lang->line('user_location') . ': <b>' . $value['location'] . '</b>';
                }
                $result['followers'][$key] .= '</div></div>';
            }
        }
        unset($query);

        $this->application->_sendAJAX($result);
    }
}