<?php
function __autoload($class)
	{
	$checked = array();
	$folders = array('system/libraries','system/core','application/controllers','application/models','system/helpers');
	if (defined('CONFIG_LOADED'))
		{
		$folders = (s('config')->autoload_folders) ? s('config')->autoload_folders : $folders;
		if (defined('DIR_BASE'))
			{
			foreach ($folders as $key => $folder)
				{
				$folders[$key] = DIR_BASE.$folder;
				}
			}
		$predefined = (s('config')->class_locations) ? s('config')->class_locations : array();
		if (isset($predefined[$class]))
			{
			$folders = array(((defined('DIR_BASE') ? DIR_BASE : '')).$predefined[$class]);
			}
		else
			{
			if (substr($class,0,strlen('controller_')) == 'controller_')
				{
				$folders = array(DIR_CONTROLLERS.substr($class,strlen('controller_')));
				}
			elseif (s('config')->compatibility['enabled'])
				{
				$type = classmanager::compatibility_type($class);
				$prefix = classmanager::compatibility_prefix($class);
				if ($type)
					{
					foreach (s('config')->compatibility['autoload_folders'][$type] as $folder)
						{
						$folders[] = DIR_COMPATIBILITY.$type.'/'.$folder;
						}
					$file_noprefix = substr($class,strlen($prefix));
					foreach (s('config')->compatibility['required_classes'][$type] as $needed)
						{
						__autoload($needed);
						}
					}
				}
			}
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
