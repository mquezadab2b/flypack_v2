<?php

class CWelcome extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('MWelcome', 'modelo');
		$this->load->model('MSession', 'modelo_session');
	}

	public function index()
	{
		
		if($this->session->userdata('user'))
		{
			$this->load->view('header');
			$this->load->view('aside');
			$this->load->view('wrapper');
			$this->load->view('footer');
		}
		else
			$this->load->view('login');
		
		//$this->load->view('login');	
	}

	public function Login()
	{
		$date_time_now = date('Y-m-d H:i:s');
		$dt = new DateTime($date_time_now);
		$date_now = $dt->format('Y-m-d');
		$time_now = $dt->format('H:i:s');

		$user = trim($this->input->post('user', TRUE));
		$password = md5(trim($this->input->post('password', TRUE)));
		$ip = trim($this->input->post('ip', TRUE));

		$data = $this->modelo->getUserSession($user, $password);

		if(!empty($data[0]['rol_id']))
		{
			$asession_array = array();
			if(!empty($data))
			{
				if($data[0]['user_state_id'] == 1)
				{
					$session_array = array(
						'user' => $user,
						'password' => $password,
						'rol' => $data[0]['rol'],
						'rol_id' => $data[0]['rol_id'],
						'users_id' => $data[0]['id'],
						'name'=> $data[0]['name'],
						'lastname' => $data[0]['lastname'],
						'state' => $data[0]['state'],
						'ip' => $ip,
						'people_id' => $data[0]['people_id'],
						'rut' => $data[0]['rut'], 
						'dv' => $data[0]['dv'], 
						'address' => $data[0]['address'], 
						'email' => $data[0]['email'], 
						'phone' => $data[0]['phone']
					);
					
					//SESSION START
					$this->session->set_userdata($session_array);
					header('Location: '.base_url().'index.php/CWelcome/index');
				}
				else if($data[0]['user_state_id'] == 2)
				{
					$message = array('message' => '<h6><font color="red">Tu cuenta esta Eliminada.<br>Acercate al Administrador para regularizar tu situación.</font></h6>');
					$this->load->view('login',$message);
				}
				else
				{
					$message = array('message' => '<h6><font color="red">Tu cuenta esta temporalmente suspendida.<br>Acercate al Administrador para regularizar tu situación.</font></h6>');
					$this->load->view('login',$message);
				}
				
			}
			else
			{	
				$message = array('message' => '<h6><font color="red">Usuario o Contraseña Incorrecto</font></h6>');
				$this->load->view('login',$message);
			}	
		}
		else
		{
			$message = array('message' => '<h6><font color="red">Usuario o Contraseña Incorrecto</font></h6>');
			$this->load->view('login',$message);
		}
			

	}

	public function Logout()
	{
		$this->session->userdata = array();
		$this->session->sess_destroy();
		header('Location: '.site_url("CWelcome"));
	}
		
}

?>
