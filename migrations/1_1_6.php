<?php
/**
*
* @package phpBB Gallery Feed Extension
* @copyright (c) 2013 nickvergessen
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class phpbb_ext_gallery_feed_migrations_1_1_6 extends phpbb_db_migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'gallery_albums', 'album_feed');
	}

	static public function depends_on()
	{
		return array('phpbb_db_migration_data_310_dev');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'gallery_albums'			=> array(
					'album_feed'		=> array('BOOL', 1),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'gallery_albums'			=> array(
					'album_feed',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array(&$this, 'install_config'))),
		);
	}

	public function install_config()
	{
		global $config;

		foreach (self::$configs as $name => $value)
		{
			if (isset(self::$is_dynamic[$name]))
			{
				$config->set('phpbb_gallery_' . $name, $value, true);
			}
			else
			{
				$config->set('phpbb_gallery_' . $name, $value);
			}
		}

		return true;
	}

	static public $is_dynamic = array();

	static public $configs = array(
		'feed_enable'			=> true,
		'feed_enable_pegas'		=> true,
		'feed_limit'			=> 10,
	);
}
