<?php namespace Aksara\Modules\Assets\Controllers;
/**
 * Test Aksara module
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
class Assets extends \Aksara\Laboratory\Core
{
	private $_rtl									= false;
	
	public function __construct()
	{
		parent::__construct();
		
		if(get_userdata('language') && in_array(get_userdata('language'), array('arabic')))
		{
			$this->_rtl								= true;
		}
	}
	
	public function themes()
	{
		$extension									= strtolower(pathinfo($this->request->uri->getPath(), PATHINFO_EXTENSION));
		
		if(in_array($extension, ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'bmp']) && file_exists('../' . $this->request->uri->getPath()))
		{
			helper('download');
			
			return force_download($this->request->uri->getPath(), file_get_contents('../' . $this->request->uri->getPath()), true);
		}
	}
	
	public function styles()
	{
		$user_agent									= $this->request->getUserAgent();
		
		$file_list									= array
		(
			'assets/bootstrap/bootstrap' . ($this->_rtl ? '.rtl' : null) . '.min.css',
			'assets/mcustomscrollbar/jquery.mCustomScrollbar.min.css',
			'assets/select2/select2.min.css',
			'assets/select2/select2.bootstrap4.min.css',
			'assets/datepicker/datepicker.min.css',
			'assets/fileuploader/fileuploader.min.css',
			'assets/local/css/override.min.css',
			(strtolower($user_agent->getBrowser()) == 'internet explorer' ? 'assets/local/css/ie.fix.min.css' : null) /* only applied to IE */
		);
		
		/**
		 * Ideally, you wouldn't need to change any code beyond this point.
		 */
		$output										= '';
		
		foreach($file_list as $key => $source)
		{
			$output									.= @file_get_contents($source);
		}
		
		if($this->_rtl)
		{
			$output									.= @file_get_contents('local/css/override.rtl.min.css');
		}
		
		$credits									= '
		/**
		 * سْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
		 *
		 * This site is built with Aksara!
		 * A powerful framework engine to realize what\'s only
		 * in your dream becomes something real.
		 *
		 * @author Aby Dahana
		 * @profile abydahana.github.io
		 * @website www.aksaracms.com
		 * @copyright (c) 2021 - Aksara Laboratory
		 */
		';
		
		service('response')->setHeader('Content-Type', 'text/css');
		service('response')->setBody(trim(preg_replace('/\t+/', '', $credits)) . "\n" . trim(preg_replace('/\s+/S', ' ', $output)));
		
		return service('response')->send();
	}
	
	public function scripts()
	{
		$user_agent									= $this->request->getUserAgent();
		
		$file_list									= array
		(
			'assets/jquery/jquery.min.js',
			'assets/local/js/require.min.js',
			'assets/popper/popper.min.js',
			'assets/bootstrap/bootstrap.min.js',
			'assets/actual/actual.min.js',
			'assets/mcustomscrollbar/jquery.mousewheel.min.js',
			'assets/mcustomscrollbar/jquery.mCustomScrollbar.min.js',
			'assets/select2/select2.min.js',
			'assets/datepicker/datepicker.min.js',
			'assets/fileuploader/fileuploader.min.js',
			(strtolower($user_agent->getBrowser()) == 'internet explorer' ? 'assets/local/js/ie.fix.min.js' : null), /* only applied to IE */
			'assets/visible/visible.min.js',
			'assets/scanner/scanner.min.js',
			'assets/lazyload/lazyload.min.js',
			'assets/jszip/jszip-utils.min.js',
			'assets/jszip/jszip.min.js',
			'assets/local/js/function.min.js',
			'assets/local/js/global.min.js',
			'assets/local/js/component.min.js'
		);
		
		/**
		 * Ideally, you wouldn't need to change any code beyond this point.
		 */
		$output										= '
			var config =
			{
				base_url: "' . htmlspecialchars(base_url()) . '",
				asset_url: "' . htmlspecialchars(base_url('assets')) . '/",
				app_name: "' . htmlspecialchars(get_setting('app_name')) . '",
				app_icon: "' . htmlspecialchars(get_image('settings', get_setting('app_icon'), 'icon')) . '",
				content_wrapper: "#content-wrapper",
				registration_enabled: ' . (int) get_setting('frontend_registration') . ',
				language: "' . htmlspecialchars(get_setting('language_code')) . '",
				openlayers_search_provider: "' . htmlspecialchars(get_setting('openlayers_search_provider')) . '",
				openlayers_search_key: "' . htmlspecialchars(get_setting('openlayers_search_key')) . '",
				map_center: ' . (json_decode(get_setting('office_map')) ? get_setting('office_map') : '{}') . ',
				google_auth: ' . (get_setting('google_client_id') && get_setting('google_client_secret') ? 'true' : 'false') . ',
				facebook_auth: ' . (get_setting('facebook_app_id') && get_setting('facebook_app_secret') ? 'true' : 'false') . '
				
			},
			phrase									= ' . json_encode(json_decode($this->_i18n()), JSON_UNESCAPED_SLASHES) . ';
		';
		
		foreach($file_list as $key => $source)
		{
			$output									.= @file_get_contents($source);
		}
		
		$credits									= '
		/**
		 * سْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
		 *
		 * This site is built with Aksara!
		 * A powerful framework engine to realize what\'s only
		 * in your dream becomes something real.
		 *
		 * @author Aby Dahana
		 * @profile abydahana.github.io
		 * @website www.aksaracms.com
		 * @copyright (c) 2021 - Aksara Laboratory
		 */
		';
		
		service('response')->setHeader('Content-Type', 'text/javascript');
		service('response')->setBody(trim(preg_replace('/\t+/', '', $credits)) . "\n" . trim(preg_replace('/\s+/S', ' ', $output)));
		
		return service('response')->send();
	}
	
	private function _i18n()
	{
		if(file_exists(WRITEPATH . 'translations' . DIRECTORY_SEPARATOR . get_setting('language_code') . '.json'))
		{
			return file_get_contents(WRITEPATH . 'translations' . DIRECTORY_SEPARATOR . get_setting('language_code') . '.json');
		}
		
		return '[]';
	}
}
