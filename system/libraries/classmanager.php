<?php
class classmanager {
static private $instances = array();
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
		return call_user_func_array(array(self::single(array('ReflectionClass',$class)), 'newInstance'),$args);
		}
	private function __construct() {}
	private function __destruct() {}
	private function __clone() {}
	private function __wakeup() {}
}
