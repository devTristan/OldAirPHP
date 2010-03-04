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
class CI {
public $config;
	public function __get($name)
		{
		return s('CI_'.ucfirst($name));
		}
}
class codeigniter_config {
	public function item($name)
		{
		return s('config')->codeigniter[$name];
		}
}
class codeigniter_load {
	public function __call($type,$params)
		{
		s('codeigniter_'.$type)
		}
	public function language($file)
		{
		s('codeigniter_language')->load($file);
		}
	public function library()
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
//codeigniter constants
define('BASEPATH',DIR_COMPATIBILITY.'codeigniter/');
define('APPPATH',DIR_COMPATIBILITY.'codeigniter/');
//codeigniter functions
function get_instance()
	{
	return s('CI');
	}
function log_message($type,$msg = null)
	{
	if ($msg === null)
		{
		s('console')->log($type);
		}
	else
		{
		s('console')->log($msg,$type);
		}
	}
function show_error($msg)
	{
	//to be changed when framework has error handling
	echo $msg;
	die();
	}
