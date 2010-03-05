<?php
abstract class has_drivers {
	public function driver($name)
		{
		$thisclass = get_class($this);
		$driverclass = $thisclass.'_'.$name;
		if (!class_exists($driverclass,false))
			{
			$dir = DIR_LIBRARIES.'drivers/'.$thisclass;
			if (is_dir($dir))
				{
				$file = $dir.'/'.$name.'.php';
				if (file_exists($file))
					{
					require_once($file);
					}
				}
			}
		if (class_exists($driverclass,false))
			{
			return s($driverclass);
			}
		else
			{
			return false;
			}
		}
}
