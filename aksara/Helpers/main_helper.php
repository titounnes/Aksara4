<?php
/**
 * Main Helper
 * A helper that required by Aksara
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */

if(!function_exists('generate_token'))
{
	/**
	 * Generate security token to validate the query string values
	 */
	function generate_token($data = null)
	{
		if(is_array($data))
		{
			$data									= http_build_query($data);
		}
		
		return substr(sha1($data . ENCRYPTION_KEY . get_userdata('session_generated')), 6, 6);
	}
}

if(!function_exists('aksara_header'))
{
	/**
	 * include additional css
	 */
	function aksara_header()
	{
		$output										= '<meta name="_token" content="' . sha1(current_page() . ENCRYPTION_KEY . get_userdata('session_generated')) . '" />' . "\n";
		$output										.= '<link rel="stylesheet" type="text/css" href="' . base_url('assets/css/styles.min.css') . '" />' . "\n";
		$output										.= '<link rel="stylesheet" type="text/css" href="' . base_url('assets/materialdesignicons/css/materialdesignicons.min.css') . '" />' . "\n";
		$output										.= '<script type="text/javascript">(function(w,d,u){w.readyQ=[];w.bindReadyQ=[];function p(x,y){if(x=="ready"){w.bindReadyQ.push(y)}else{w.readyQ.push(x)}};var a={ready:p,bind:p};w.$=w.jQuery=function(f){if(f===d||f===u){return a}else{p(f)}}})(window,document)</script>' . "\n";
		
		return $output;
	}
}

if(!function_exists('aksara_footer'))
{
	/**
	 * include additional js
	 */
	function aksara_footer()
	{
		$output										= show_flashdata() . "\n";
		$output										.= '<script type="text/javascript" src="' . base_url('assets/js/scripts.min.js') . '"></script>' . "\n";
		$output										.= '<script type="text/javascript">(function($,d){$.each(readyQ,function(i,f){$(f)});$.each(bindReadyQ,function(i,f){$(d).bind("ready",f)})})(jQuery,document)</script>' . "\n";
		
		return $output;
	}
}

if(!function_exists('throw_exception'))
{
	/**
	 * A message exception that will throw as JSON
	 */
	function throw_exception($code = 500, $data = array(), $target = null, $redirect = false)
	{
		/* check if the request isn't through xhr */
		if(!service('request')->isAJAX())
		{
			if(!$target)
			{
				$target								= base_url();
			}
			
			/* check if data isn't an array */
			if($data && !is_array($data))
			{
				/* set the flashdata */
				if(in_array($code, array(200, 301)))
				{
					/* success */
					service('session')->setFlashdata('success', $data);
				}
				elseif(in_array($code, array(403, 404)))
				{
					/* warning */
					service('session')->setFlashdata('warning', $data);
				}
				else
				{
					/* unexpected error */
					service('session')->setFlashdata('error', $data);
				}
			}
			
			/* redirect into target */
			redirect_to($target);
		}
		
		$exception									= array();
		
		if(is_array($data))
		{
			foreach($data as $key => $val)
			{
				$key								= str_replace('[]', null, $key);
				$exception[$key]					= $val;
			}
		}
		else
		{
			$exception								= $data;
		}
		
		$output										= json_encode
		(
			array
			(
				'status'							=> $code,
				'exception'							=> $exception,
				'target'							=> $target,
				'redirect'							=> $redirect
			)
		);
		
		header('Content-Type: application/json');
		exit($output);
	}
}

if(!function_exists('show_flashdata'))
{
	/**
	 * Generate flashdata
	 */
	function show_flashdata()
	{
		if(service('session')->getFlashdata())
		{
			return '
				<div class="alert ' . (service('session')->getFlashdata('success') ? 'alert-success' : (service('session')->getFlashdata('warning') ? 'alert-warning' : 'alert-danger')) . ' alert-dismissable fade' . (service('session')->getFlashdata() ? ' show' : null) . ' exception text-center rounded-0 fixed-top">
					<i class="mdi mdi-' . (service('session')->getFlashdata('success') ? 'check' : (service('session')->getFlashdata('warning') ? 'alert-octagram-outline' : 'emoticon-sad-outline')) . '"></i>
					' . (service('session')->getFlashdata('success') ? service('session')->getFlashdata('success') : (service('session')->getFlashdata('warning') ? service('session')->getFlashdata('warning') : service('session')->getFlashdata('error'))) . '
				</div>
			';
		}
		
		return false;
	}
}

if(!function_exists('format_slug'))
{
	/**
	 * Generate slug from given string
	 */
	function format_slug($string = null)
	{
		$string										= strtolower(preg_replace('/[\-\s]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', trim($string))));
		
		if(!preg_match('/(\d{10})/', $string))
		{
			$string									= $string;
		}
		
		return $string;
	}
}

if(!function_exists('strtolower_callback'))
{
	/**
	 * Callback to make the string is lowercase after matches "/Views/"
	 */
	function strtolower_callback($string = array())
	{
		return '/Views/' . strtolower($string[1]);
	}
}
