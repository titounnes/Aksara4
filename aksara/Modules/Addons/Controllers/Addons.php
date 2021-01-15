<?php namespace Aksara\Modules\Addons\Controllers;
/**
 * Addons
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
class Addons extends \Aksara\Laboratory\Core
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
		$this->set_title('Add-Ons Market')
		->set_icon('mdi mdi-cart')
		->set_output
		(
			array
			(
				'listing'							=> $this->_listing()
			)
		)
		
		->render();
	}
	
	public function detail()
	{
		$manifest									= array
		(
			'id'									=> 1,
			'type'									=> 'theme',
			'manifest'								=> array
			(
				'name'								=> 'Sample Theme',
				'description'						=> 'This is a sample theme',
				'version'							=> '1.0.0',
				'author'							=> 'Aby Dahana',
				'website'							=> 'https://abydahana.github.io',
				'demo_url'							=> 'https://aksaracms.com/theme_preview',
				'type'								=> 'frontend',
				'compatibility'						=> array
				(
					2
				),
				'screenshot'						=> array
				(
					array
					(
						'src'						=> '',
						'alt'						=> ''
					)
				)
			)
		);
		
		$this->set_title('Theme Detail')
		->set_icon('mdi mdi-palette')
		->set_output
		(
			array
			(
				'detail'							=> $manifest
			)
		)
		->modal_size('modal-lg')
		
		->render(null, 'detail');
	}
	
	public function install()
	{
	}
	
	private function _listing()
	{
		/**
		 * Grab addons from Aksara site
		 */
		
		return array
		(
			array
			(
				'id'								=> 1,
				'type'								=> 'theme',
				'manifest'							=> array
				(
					'name'							=> 'Sample Theme',
					'description'					=> 'This is a sample theme',
					'version'						=> '1.0.0',
					'author'						=> 'Aby Dahana',
					'website'						=> 'https://abydahana.github.io',
					'demo_url'						=> 'https://aksaracms.com/theme_preview',
					'type'							=> 'frontend',
					'compatibility'					=> array
					(
						2
					),
					'screenshot'					=> array
					(
						array
						(
							'src'					=> '',
							'alt'					=> ''
						)
					)
				)
			),
			array
			(
				'id'								=> 2,
				'type'								=> 'module',
				'manifest'							=> array
				(
					'name'							=> 'Sample Theme',
					'description'					=> 'This is a sample theme',
					'version'						=> '1.0.0',
					'author'						=> 'Aby Dahana',
					'website'						=> 'https://abydahana.github.io',
					'demo_url'						=> 'https://aksaracms.com/module_preview',
					'type'							=> 'frontend',
					'compatibility'					=> array
					(
						2
					),
					'screenshot'					=> array
					(
						array
						(
							'src'					=> '',
							'alt'					=> ''
						)
					)
				)
			)
		);
	}
}
