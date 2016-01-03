<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
    {
        parent::__construct();   
        $this->load->model('Categories_model','datamodel');     
    }
	   
	public function index()
	{
		$data['title']='List Of Categories';	
		$data['array_categories'] = $this->datamodel->get_categories();
		$this->mytemplate->loadBackend('categories',$data);
	}

	
	
	public function cek_login()
	{
		$u=$this->input->POST("username", true);
		$p=$this->input->POST("password", true);
	
		if($u=="123" && $p=="123")
		{
			$this->session->set_userdata(array('user'=>'Admin'));
			redirect(site_url('backend/categories'),'location');	
		}	
		else redirect(site_url('login'),'location');
	}
	
	public function logout()
	{
		//$this->session->unset_userdata(array('user'=>''));
		//redirect(site_url('login'),'location');
		$data=array('user'=>'');
		$this->session->unset_userdata($data);
		$data['pesan']='Anda sudah Logout';
		$this->load->view('login', $data);
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

