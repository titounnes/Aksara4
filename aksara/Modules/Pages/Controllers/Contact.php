<?php namespace Aksara\Modules\Pages\Controllers;
/**
 * Pages > Contact
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
class Contact extends \Aksara\Laboratory\Core
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if(service('request')->getPost('_token'))
		{
			return $this->_send_message();
		}
		
		$this->set_title(phrase('contact_us'))
		->set_icon('mdi mdi-phone-classic')
		->set_description(phrase('including_the_credits_of_external_resources'))
		
		->render();
	}
	
	public function _send_message()
	{
		error_reporting(0); // prevent the PHP throw errors before exception
		
		$this->form_validation->setRule('full_name', phrase('full_name'), 'required');
		$this->form_validation->setRule('email', phrase('email'), 'required|valid_email');
		$this->form_validation->setRule('subject', phrase('subject'), 'required');
		$this->form_validation->setRule('messages', phrase('messages'), 'required');
		$this->form_validation->setRule('copy', phrase('send_copy'), 'boolean');
		
		if($this->form_validation->run(service('request')->getPost()) === false)
		{
			return throw_exception(400, $this->form_validation->getErrors());
		}
		
		$this->model->insert
		(
			'inquiries',
			array
			(
				'sender_email'						=> service('request')->getPost('email'),
				'sender_full_name'					=> service('request')->getPost('full_name'),
				'subject'							=> service('request')->getPost('subject'),
				'messages'							=> service('request')->getPost('messages'),
				'timestamp'							=> date('Y-m-d H:i:s')
			)
		);
		
		if(service('request')->getPost('copy'))
		{
			/**
			 * to working with Google SMTP, make sure to activate less secure apps setting
			 */
			$this->load->library('email');
			
			$config['useragent']       				= 'Aksara';
			$config['protocol']						= 'smtp';
			$config['smtp_host']					= get_setting('smtp_host');
			$config['smtp_port']					= get_setting('smtp_port');
			$config['smtp_user']					= get_setting('smtp_username');
			$config['smtp_pass']					= service('encrypter')->decrypt(get_setting('smtp_password'));
			$config['smtp_timeout']					= '7';
			$config['charset']						= 'utf-8';
			$config['newline']						= "\r\n";
			$config['mailtype']						= 'text'; // text or html
			$config['wordwrap']						= true;
			$config['validation']					= true; // bool whether to validate email or not     
			
			$this->email->initialize($config);		
			
			$this->email->from(get_setting('smtp_email_masking'), get_setting('smtp_sender_masking'));
			$this->email->to(service('request')->getPost('email'));
			
			$this->email->subject(service('request')->getPost('subject'));
			$this->email->message(service('request')->getPost('messages'));
			
			if(!$this->email->send())
			{
				//echo $this->email->print_debugger(); exit;
				return throw_exception(400, array('message' => $this->email->print_debugger()));
			}
		}
		
		return throw_exception(301, phrase('your_inquiry_has_been_sent'), current_page());
	}
}
