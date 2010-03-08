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
function is_php($version = '5.0.0')
	{
	static $_is_php;
	$version = (string) $version;
	
	if (!isset($_is_php[$version]))
		{
		$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
		}
	
	return $_is_php[$version];
	}
function is_really_writable($file)
	{	
	// If we're on a Unix server with safe_mode off we call is_writable
	if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == false)
		{
		return is_writable($file);
		}
	
	// For windows servers and safe_mode "on" installations we'll actually
	// write a file then read it.  Bah...
	if (is_dir($file))
		{
		$file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));
		
		if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false)
			{
			return false;
			}
		
		fclose($fp);
		@chmod($file, DIR_WRITE_MODE);
		@unlink($file);
		return true;
		}
	elseif (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false)
		{
		return false;
		}
	
	fclose($fp);
	return true;
	}
function &load_class($class, $instantiate = true)
	{
	static $objects = array();
	
	// Does the class exist?  If so, we're done...
	if (isset($objects[$class]))
		{
		return $objects[$class];
		}
	
	// If the requested class does not exist in the application/libraries
	// folder we'll load the native class from the system/libraries folder.	
	if (file_exists(APPPATH.'libraries/'.config_item('subclass_prefix').$class.EXT))
		{
		require(BASEPATH.'libraries/'.$class.EXT);
		require(APPPATH.'libraries/'.config_item('subclass_prefix').$class.EXT);
		$is_subclass = true;
		}
	else
		{
		if (file_exists(APPPATH.'libraries/'.$class.EXT))
			{
			require(APPPATH.'libraries/'.$class.EXT);
			$is_subclass = false;
			}
		else
			{
			require(BASEPATH.'libraries/'.$class.EXT);
			$is_subclass = false;
			}
		}
	
	if ($instantiate == false)
		{
		$objects[$class] = true;
		return $objects[$class];
		}
	
	if ($is_subclass == true)
		{
		$name = config_item('subclass_prefix').$class;
		
		$objects[$class] =& instantiate_class(new $name());
		return $objects[$class];
		}

	$name = ($class != 'Controller') ? 'CI_'.$class : $class;
	
	$objects[$class] =& instantiate_class(new $name());
	return $objects[$class];
	}
function &instantiate_class(&$class_object)
	{
	classmanager::set(get_class($class_object),$class_object);
	return $class_object;
	}
function &get_config()
	{
	static $main_conf;
	if (!isset($main_conf))
		{
		if (!file_exists(APPPATH.'config/config'.EXT))
			{
			exit('The configuration file config'.EXT.' does not exist.');
			}
		require(APPPATH.'config/config'.EXT);
		if (!isset($config) || !is_array($config))
			{
			exit('Your config file does not appear to be formatted correctly.');
			}
		$main_conf[0] =& $config;
		}
	return $main_conf[0];
	}
function config_item($item)
	{
	static $config_item = array();
	
	if (!isset($config_item[$item]))
		{
		$config =& get_config();
		
		if (!isset($config[$item]))
			{
			return false;
			}
		$config_item[$item] = $config[$item];
		}
	
	return $config_item[$item];
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
function show_error($msg,$status_code = 500)
	{
	//to be changed when framework has error handling
	echo $status_code.' error: '.$msg;
	die();
	}
function show_404($page = '')
	{
	$error =& load_class('Exceptions');
	$error->show_404($page);
	exit;
	}
function set_status_header($code = 200, $text = '')
	{
	$stati = array(
						200	=> 'OK',
						201	=> 'Created',
						202	=> 'Accepted',
						203	=> 'Non-Authoritative Information',
						204	=> 'No Content',
						205	=> 'Reset Content',
						206	=> 'Partial Content',

						300	=> 'Multiple Choices',
						301	=> 'Moved Permanently',
						302	=> 'Found',
						304	=> 'Not Modified',
						305	=> 'Use Proxy',
						307	=> 'Temporary Redirect',

						400	=> 'Bad Request',
						401	=> 'Unauthorized',
						403	=> 'Forbidden',
						404	=> 'Not Found',
						405	=> 'Method Not Allowed',
						406	=> 'Not Acceptable',
						407	=> 'Proxy Authentication Required',
						408	=> 'Request Timeout',
						409	=> 'Conflict',
						410	=> 'Gone',
						411	=> 'Length Required',
						412	=> 'Precondition Failed',
						413	=> 'Request Entity Too Large',
						414	=> 'Request-URI Too Long',
						415	=> 'Unsupported Media Type',
						416	=> 'Requested Range Not Satisfiable',
						417	=> 'Expectation Failed',

						500	=> 'Internal Server Error',
						501	=> 'Not Implemented',
						502	=> 'Bad Gateway',
						503	=> 'Service Unavailable',
						504	=> 'Gateway Timeout',
						505	=> 'HTTP Version Not Supported'
						);
	
	if ($code == '' OR ! is_numeric($code))
		{
		show_error('Status codes must be numeric', 500);
		}
	
	if (isset($stati[$code]) AND $text == '')
		{				
		$text = $stati[$code];
		}
	
	if ($text == '')
		{
		show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
		}
	
	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
	
	if (substr(php_sapi_name(), 0, 3) == 'cgi')
		{
		header("Status: {$code} {$text}", TRUE);
		}
	elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
		{
		header($server_protocol." {$code} {$text}", TRUE, $code);
		}
	else
		{
		header("HTTP/1.1 {$code} {$text}", TRUE, $code);
		}
	}
function _exception_handler($severity, $message, $filepath, $line)
	{	
	 // We don't bother with "strict" notices since they will fill up
	 // the log file with information that isn't normally very
	 // helpful.  For example, if you are running PHP 5 and you
	 // use version 4 style class functions (without prefixes
	 // like "public", "private", etc.) you'll get notices telling
	 // you that these have been deprecated.
	
	if ($severity == E_STRICT)
		{
		return;
		}
	
	$error =& load_class('Exceptions');
	
	// Should we display the error?
	// We'll get the current error_reporting level and add its bits
	// with the severity bits to find out.
	
	if (($severity & error_reporting()) == $severity)
		{
		$error->show_php_error($severity, $message, $filepath, $line);
		}
	
	// Should we log the error?  No?  We're done...
	$config =& get_config();
	if ($config['log_threshold'] == 0)
		{
		return;
		}
	
	$error->log_exception($severity, $message, $filepath, $line);
	}
