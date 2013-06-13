<?php
/**
*
* @package phpBB Gallery Feed Extension
* @copyright (c) 2013 nickvergessen
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class phpbb_ext_gallery_feed_controller_main
{
	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*
	* @param phpbb_auth		$auth		Auth object
	* @param phpbb_cache_service	$cache		Cache object
	* @param phpbb_config	$config		Config object
	* @param phpbb_db_driver	$db		Database object
	* @param phpbb_request	$request	Request object
	* @param phpbb_template	$template	Template object
	* @param phpbb_user		$user		User object
	* @param phpbb_controller_helper		$helper		Controller helper object
	* @param string			$root_path	phpBB root path
	* @param string			$php_ext	phpEx
	*/
	public function __construct(phpbb_auth $auth, phpbb_cache_service $cache, phpbb_config $config, phpbb_db_driver $db, phpbb_request $request, phpbb_template $template, phpbb_user $user, phpbb_controller_helper $helper, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		if (!class_exists('bbcode'))
		{
			include($this->root_path . 'includes/bbcode.' . $this->php_ext);
		}
		if (!function_exists('get_user_rank'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}
	}

	/**
	* Base controller to be accessed with the URL /newspage/{page}
	* (where {page} is the placeholder for a value)
	*
	* @param int	$page	Page number taken from the URL
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function index($page = null)
	{
		$feed = new phpbb_ext_gallery_feed();
		$feed->send_images();

		$this->send_header($this->helper->url('gallery'), $this->helper->url('gallery/feed'), $feed->get_last_modified());

		/*
		* The render method takes up to three other arguments
		* @param	string		Name of the template file to display
		*						Template files are searched for two places:
		*						- phpBB/styles/<style_name>/template/
		*						- phpBB/ext/<all_active_extensions>/styles/<style_name>/template/
		* @param	string		Page title
		* @param	int			Status code of the page (200 - OK [ default ], 403 - Unauthorized, 404 - Page not found)
		*/
		return $this->helper->render('gallery/feed_body.html', 'feeeed'/*@todo: Fix page title*/);
	}

	/**
	* Base controller to be accessed with the URL /newspage/{page}
	* (where {page} is the placeholder for a value)
	*
	* @param int	$album_id	Page number taken from the URL
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function album($album_id)
	{
		$feed = new phpbb_ext_gallery_feed($album_id);
		$feed->send_images();

		$this->send_header($this->helper->url('gallery/album/' . $album_id), $this->helper->url('gallery/feed/' . $album_id), $feed->get_last_modified());

		/*
		* The render method takes up to three other arguments
		* @param	string		Name of the template file to display
		*						Template files are searched for two places:
		*						- phpBB/styles/<style_name>/template/
		*						- phpBB/ext/<all_active_extensions>/styles/<style_name>/template/
		* @param	string		Page title
		* @param	int			Status code of the page (200 - OK [ default ], 403 - Unauthorized, 404 - Page not found)
		*/
		return $this->helper->render('gallery/feed_body.html', $album_id/*@todo: Fix page title*/);
	}

	/**
	* Send Last-Modified header and Content-Type
	*/
	protected function send_header($self_link, $back_link, $last_modified)
	{
		global $template;

		header("Content-Type: application/atom+xml; charset=UTF-8");
		if ($last_modified !== false)
		{
			header("Last-Modified: " . gmdate('D, d M Y H:i:s', $last_modified) . ' GMT');
		}

		$template->assign_vars(array(
			'TITLE'			=> $this->config['sitename'],
			'DESCRIPTION'	=> $this->config['site_desc'],

			'U_SELF_LINK'		=> $self_link,
			'U_BACK_LINK'		=> $back_link,
		));
	}
}