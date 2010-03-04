<?php
class codeigniter extends helper {
	static public function add_helper($helper)
		{
		if (is_array($helper))
			{
			foreach ($helper as $one)
				{
				self::add_helper($one);
				}
			}
		else
			{
			require_once(DIR_COMPATIBILITY.'codeigniter/helpers/'.$helper.'_helper.php');
			}
		return self;
		}
	static public function add_plugin($plugin)
		{
		if (is_array($plugin))
			{
			foreach ($plugin as $one)
				{
				self::add_plugin($one);
				}
			}
		else
			{
			require_once(DIR_COMPATIBILITY.'codeigniter/plugins/'.$plugin.'_pi.php');
			}
		return self;
		}
	static public function library($name)
		{
		return s('CI_'.ucfirst($name));
		}
}
class codeigniter_language {
private $lang;
private $loaded;
	public function load($file)
		{
		if (!isset($this->loaded[$file]))
			{
			$this->loaded[$file] = false;
			$lang = $this->lang;
			include(DIR_COMPATIBILITY.'codeigniter/language/'.s('config')->language.'/'.$file.'_lang.php');
			$this->lang = $lang;
			}
		}
	public function line($field)
		{
		return (isset($this->lang[$field])) ? $this->lang[$field] : $field;
		}
}
