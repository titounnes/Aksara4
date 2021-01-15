<?php namespace Aksara\Modules\Home\Controllers;
/**
 * Welcome
 * The default landing page of default routes
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
class Home extends \Aksara\Laboratory\Core
{
	public function __construct()
	{
	}
	
	public function index()
	{
		$this->set_title(phrase('welcome_to') . ' ' . get_setting('app_name'))
		->set_description(get_setting('app_description'))
		
		->render();
	}
}
