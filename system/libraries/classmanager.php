<?php
class classmanager {
static private $instances = array();
static private $cache_compatibility_type = array();
	static public function single($args)
		{
		$id = sha1(serialize($args));
		if (!isset(self::$instances[$id]))
			{
			self::$instances[$id] = self::new_instance($args);
			}
		return self::$instances[$id];
		}
	static public function new_instance($args)
		{
		$class = array_shift($args);
		if ($class == 'ReflectionClass')
			{
			$id = sha1(serialize(array('ReflectionClass',$args[0])));
			if (!isset(self::$instances[$id]))
				{
				self::$instances[$id] = new ReflectionClass($args[0]);
				}
			return self::$instances[$id];
			}
		if (defined('CONFIG_LOADED'))
			{
			$id = sha1(serialize(array('config')));
			if (isset(self::$instances[$id]) &&
			isset(self::$instances[$id]->compatibility['required_classes']) &&
			isset(self::$instances[$id]->compatibility['required_classes'][self::compatibility_type($class)])
			)
				{
				foreach (self::$instances[sha1(serialize(array('config')))]->compatibility['required_classes'] as $required)
					{
					__autoload($required);
					}
				}
			}
		return call_user_func_array(array(self::single(array('ReflectionClass',$class)), 'newInstance'),$args);
		}
	static public function compatibility_type($class)
		{
		if (!isset(self::$cache_compatibility_type[$class]))
			{
			if (!defined('CONFIG_LOADED')) {return '';}
			foreach (s('config')->compatibility['class_prefixes'] as $prefix => $type)
				{
				if (substr($class,0,strlen($prefix)) == $prefix)
					{
					self::$cache_compatibility_type[$class] = array($prefix,$type);
					break;
					}
				}
			return '';
			}
		return self::$cache_compatibility_type[$class][1];
		}
	static public function compatibility_prefix($class)
		{
		if (isset(self::$cache_compatibility_type[$class]))
			{
			return self::$cache_compatibility_type[$class][0];
			}
		else
			{
			self::compatibility_type($class);
			return (isset(self::$cache_compatibility_type[$class])) ? self::$cache_compatibility_type[$class][0] : '';
			}
		}
	static public function set($name,$value)
		{
		self::$instances[sha1(serialize(array($name)))] = $value;
		}
	private function __construct() {}
	private function __destruct() {}
	private function __clone() {}
	private function __wakeup() {}
}
