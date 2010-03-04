<?php
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
		if ($type == 'lang') {$type = 'language';}
		call_user_func_array(array(s('codeigniter_'.$type), 'load'),$params);
		}
	public function helper($helper)
		{
		require_once(DIR_COMPATIBILITY.'codeigniter/helpers/'.$helper.'_helper'.EXT);
		}
	public function plugin($plugin)
		{
		require_once(DIR_COMPATIBILITY.'codeigniter/plugins/'.$plugin.'_pi.php');
		}
	public function library($library)
		{
		__autoload('CI_'.ucfirst($name));
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
