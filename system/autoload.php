<?php
function __autoload($class)
	{
	$checked = array();
	$folders = array('system/libraries','system/core','application/controllers','application/models','system/helpers');
	if (defined('CONFIG_LOADED'))
		{
		$folders = (s('config')->autoload_folders) ? s('config')->autoload_folders : $folders;
		$predefined = (s('config')->class_locations) ? s('config')->class_locations : array();
		if (s('config')->compatibility['enabled'])
			{
			foreach (s('config')->compatibility['class_prefixes'] as $prefix => $class_name)
				{
				if (substr($class,0,strlen($prefix)) == $prefix)
					{
					$folders = array();
					foreach (s('config')->compatibility['autoload_folders'][$class_name] as $folder)
						{
						$folders[] = DIR_COMPATIBILITY.$class_name.'/'.$folder;
						}
					$file_noprefix = substr($class,strlen($prefix));
					break;
					}
				}
			}
		}
	if (isset($predefined[$class]))
		{
		$folders = array($predefined[$class]);
		}
	foreach ($folders as $folder)
		{
		$file = $folder.'/'.((isset($file_noprefix)) ? $file_noprefix :$class).'.php';
		$checked[] = $file;
		if (file_exists($file))
			{
			require_once($file);
			break;
			}
		}
	if (!class_exists($class))
		{
		echo "Couldn't find class $class<br/>\n";
		echo "Checked the following locations:<br/>\n";
		echo "<ul>\n";
		foreach ($checked as $file)
			{
			echo "<li>$file</li>\n";
			}
		echo "</ul>";
		die();
		}
	}
