<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Users extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->_post_handler();

        $this->load->model('mdl_users');

    }

    function index() {

        $this->redir->set_last_index();

        $tmp = array(
            'users' =>	$this->mdl_users->get_all()
        );

        $data = array();
        $data['details'] = $this->pp->parse('index.tpl', $tmp, true, 'smarty', 'users');
        
        $data['site_url'] = site_url($this->uri->uri_string());
        $data['actions_panel'] = $this->pp->parse('actions_panel.tpl', $data, true, 'smarty', 'users');
        
        
        $this->load->view('index', $data);

    }

    function form() {

    }

    function delete() {

		$this->mdl_users->delete(uri_assoc('user_id'));

        $this->redir->redirect('users');

    }

    function get($params = NULL) {

        return $this->mdl_users->get($params);

    }

    function change_password() {

		$user_id = uri_assoc('user_id');

        if (!$this->mdl_users->validate_change_password() and $user_id) {

            $this->load->view('change_password');

        }

        else {

            $this->mdl_users->save_change_password($user_id);

            $this->redir->redirect('users');

        }

    }

    function _post_handler() {

        if ($this->input->post('btn_add')) {

            redirect('users/form');

        }

        elseif ($this->input->post('btn_cancel')) {

            redirect('users/index');

        }

    }

}

?>