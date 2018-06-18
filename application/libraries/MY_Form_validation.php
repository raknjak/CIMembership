<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * is_valid_email: verify validity of e-mail addresses - is also used for AJAX calls
     *
     * @param string $email the e-mail address to be validated
     * @return boolean
     *
     */

    public function is_valid_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $domain = explode("@", $email);
            if(checkdnsrr(array_pop($domain), "MX") != false) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * is_valid_password: verify whether password is strict enough
     *
     * @param string $password the password to be validated
     * @return boolean
     *
     */

    public function is_valid_password($password) {
        if (preg_match("/[\.\@\#\$\%\^\|\?\*\!\:\-\;\&\+\=\{\}\[\]]/", $password) && (strcspn($password, '0123456789') != strlen($password))) {
            return true;
        }
        return false;
    }

    /**
     *
     * is_valid_username: verify validity of username against regular expression: a-z, A-Z, 0-9, .,  _, - are allowed
     *
     * @param string $username the username to be validated
     * @return boolean
     *
     */

    public function is_valid_username($username) {
        if (preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
            return true;
        }
        return false;
    }

    /**
     *
     * is_db_cell_available: check for the existence of a unique field within a database table column
     *
     * @param string $value
     * @param string $info a string
     * @return boolean
     *
     */

    public function is_db_cell_available($value, $info) {

        list($table, $column) = explode('.', $info, 2);

        $this->CI->db->select($column);
        $this->CI->db->from(DB_PREFIX . $table);
        $this->CI->db->where($column, $value);
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if($query->num_rows() == 0) {
            return true;
        }
        return false;
    }

    /**
     *
     * is_db_cell_available_by_id: check for the existence of a unique field within a database table column EXEMPTING
     *                                 the row for which the ID is passed
     *
     * @param string $value
     * @param string $info a string
     * @return boolean
     *
     */

    public function is_db_cell_available_by_id($value, $info) { // do not use for table user (id is user_id)

        list($table, $column, $id, $id_column) = explode('.', $info, 4);

        if ($id != strval(intval($id))) {return false;}

        $this->CI->db->select($column);
        $this->CI->db->from(DB_PREFIX . $table);
        $this->CI->db->where($column, strtolower($value));
        $this->CI->db->where($id_column .' !=', $id);
        $this->CI->db->limit(1);

        $query = $this->CI->db->get();

        if($query->num_rows() == 0) {
            return true;
        }
        return false;
    }

    /**
     *
     * check_captcha: verify the reCaptcha answer
     *
     * @param string $val the input to be validated
     * @return boolean
     *
     */

    function check_captcha($val) {
        return $this->CI->recaptchav2->verifyResponse($val);
	}

    /**
     *
     * is_member_password: check for matching password
     *
     * @param string $password the password to be checked
     * @return boolean
     *
     */

    public function is_member_password($password) {

        $this->CI->db->select('password');
        $this->CI->db->from(DB_PREFIX .'user');
        $this->CI->db->where('user_id', $this->CI->session->userdata('user_id'));

        $q = $this->CI->db->get();
        $row = $q->row();
        return password_verify($password, $row->password);
    }
}