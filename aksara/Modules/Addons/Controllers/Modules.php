<?php namespace Aksara\Modules\Addons\Controllers;
/**
 * Addons > Modules Manager
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
class Modules extends \Aksara\Laboratory\Core
{
	private $_table									= 'app__menus';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(1);
		$this->set_theme('backend');
		
		$this->_primary								= service('request')->getGet('item');
	}
	
	public function index()
	{
		$this->set_title('Module Manager')
		->set_icon('mdi mdi-puzzle')
		->set_output
		(
			array
			(
				'installed'							=> $this->_installed()
			)
		)
		
		->render();
	}
	
	public function detail()
	{
		$manifest									= json_decode(file_get_contents(ROOTPATH . 'modules/' . service('request')->uri->getSegment(4) . '/manifest.json'));
		
		if($manifest)
		{
			$manifest->folder						= service('request')->uri->getSegment(4);
			$manifest->integrity					= sha1($manifest->folder . ENCRYPTION_KEY . get_userdata('session_generated'));
		}
		
		$this->set_title('Module Detail')
		->set_icon('mdi mdi-palette')
		->set_output
		(
			array
			(
				'detail'							=> $manifest
			)
		)
		->modal_size('modal-lg')
		
		->render();
	}
	
	public function activate()
	{
	}
	
	public function delete()
	{
		//$this->set_permission(array(1, 2));
		
		$this->permission->must_ajax(current_page('../'));
		
		/* check if module is exists */
		if(service('request')->uri->getSegment(4) && !is_dir(ROOTPATH . 'modules/' . service('request')->uri->getSegment(4)))
		{
			return throw_exception(404, phrase('the_theme_you_would_to_delete_is_not_exists_or_already_removed'), curent_page('../'));
		}
		
		/* delete confirmation */
		elseif(service('request')->uri->getSegment(4) && service('request')->uri->getSegment(4) != service('request')->getPost('module'))
		{
			$html									= '
				<div class="p-3">
					<form action="' . current_page() . '" method="POST" class="--validate-form">
						<div class="text-center">
							Are you sure would to delete this module?
						</div>
						<hr class="row" />
						<div class="--validation-callback mb-0"></div>
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0)" data-dismiss="modal" class="btn btn-light btn-block">
									<i class="mdi mdi-window-close"></i>
									' . phrase('cancel') . '
								</a>
							</div>
							<div class="col-6">
								<input type="hidden" name="module" value="' . service('request')->uri->getSegment(4) . '" />
								<button type="submit" class="btn btn-danger btn-block">
									<i class="mdi mdi-check"></i>
									' . phrase('continue') . '
								</button>
							</div>
						</div>
					</form>
				</div>
			';
			
			return make_json
			(
				array
				(
					'status'						=> 206,
					'meta'							=> array
					(
						'title'						=> phrase('action_warning'),
						'icon'						=> 'mdi mdi-alert-outline'
					),
					'html'							=> $html
				)
			);
		}
		
		/* check if requested module to delete is match */
		if(service('request')->getPost('module') && is_dir(ROOTPATH . 'modules/' . service('request')->getPost('module')))
		{
			/* check if module property is exists */
			if(file_exists(ROOTPATH . 'modules/' . service('request')->getPost('module') . '/manifest.json'))
			{
				$manifest							= json_decode(file_get_contents(ROOTPATH . 'modules/' . service('request')->getPost('module') . '/manifest.json'));
				
				if(isset($manifest->type) && 'backend' == $manifest->type)
				{
					$type							= 'backend_theme';
				}
				else
				{
					$type							= 'frontend_theme';
				}
				
				if(service('request')->getPost('module') == get_setting($type))
				{
					return throw_exception(403, phrase('unable_to_delete_the_theme_that_are_in_use'), current_page('../'));
				}
				
				/* delete module */
				helper('filesystem');
				
				if(!delete_files(ROOTPATH . 'modules/' . service('request')->getPost('module'), true) || is_dir(ROOTPATH . 'modules/' . service('request')->getPost('module')))
				{
					/* Unable to delete module. Get FTP configuration */
					$site_id						= get_setting('id');
					
					$query							= $this->model->get_where
					(
						'app__ftp',
						array
						(
							'site_id'				=> $site_id
						),
						1
					)
					->row();
					
					if($query)
					{
						/* configuration found, decrypt password */
						$query->password			= $this->encrypter->decrypt(base64_decode($query->password));
						
						/* trying to delete module using ftp instead */
						$this->ftp					= new \FtpClient\FtpClient();
						
						if($this->ftp->connect($query->hostname, false, $query->port) && $this->ftp->login($query->username, $query->password))
						{
							/* yay! FTP is connected, try to delete module */
							$this->ftp->rmdir(ROOTPATH . 'modules/' . service('request')->getPost('module'));
						}
					}
					
					/* uh oh! still unable to delete module */
					return throw_exception(403, phrase('unable_to_delete_the_selected_theme_due_to_folder_permission'), current_page('../'));
				}
			}
			else
			{
				/* module property is not found */
				return throw_exception(404, phrase('a_theme_without_manifest_cannot_be_removed_from_theme_manager'), current_page('../'));
			}
		}
		
		return throw_exception(301, phrase('the_selected_theme_was_successfully_removed'), current_page('../'));
	}
	
	private function _installed()
	{
		/* load required helper */
		helper('filesystem');
		
		$data										= directory_map(ROOTPATH . 'modules');
		
		if(!$data) return false;
		
		$output										= array();
		
		foreach($data as $key => $val)
		{
			if(is_array($val))
			{
				foreach($val as $_key => $_val)
				{
					if($_val != 'manifest.json') continue;
					
					$manifest						= json_decode(file_get_contents(ROOTPATH . 'modules/' . $key . $_val));
					
					if($manifest)
					{
						$manifest->folder			= str_replace(array('/', '\\'), array(null, null), $key);
						$manifest->integrity		= sha1($manifest->folder . ENCRYPTION_KEY . get_userdata('session_generated'));
						
						$output[]					= $manifest;
					}
				}
			}
		}
		
		return $output;
	}
}
