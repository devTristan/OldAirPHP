<?php
abstract class base {
	protected function hook($event,$method,$params = array())
		{
		s('event')->bind($this,$event,$method,$params);
		return $this;
		}
	protected function unhook($event,$method,$params = array())
		{
		s('event')->unbind($this,$event,$method,$params);
		return $this;
		}
}

abstract class library extends base {
private $_driver_cache = array();
	public function driver($name)
		{
		if (isset($this->_driver_cache[$name])) {return $this->_driver_cache[$name];}
		$thisclass = get_class($this);
		$driverclass = $thisclass.'_'.$name;
		$dir = (isset($this->driverFolder)) ? $this->driverFolder : DIR_LIBRARIES.'drivers/';
		if (!class_exists($driverclass,false))
			{
			if (is_dir($dir))
				{
				$file = $dir.((isset($this->driverParent)) ? $this->driverParent : $thisclass).'/'.$name.'.php';
				if (file_exists($file))
					{
					require_once($file);
					}
				}
			}
		if (class_exists($driverclass,false))
			{
			s($driverclass)->driverFolder = $dir.((isset($this->driverParent)) ? get_class($this->driverParent) : $thisclass).'/';
			s($driverclass)->driverParent = $name;
			$this->_driver_cache[$name] = s($driverclass);
			return s($driverclass);
			}
		else
			{
			$this->_driver_cache[$name] = false;
			return false;
			}
		}
}

abstract class driver extends library {
}