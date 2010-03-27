<?php
abstract class has_drivers {
	public function driver($name)
		{
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
			return s($driverclass);
			}
		else
			{
			return false;
			}
		}
}
