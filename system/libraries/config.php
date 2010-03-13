<?php
class config extends simple_iterator {
private $functions = array();
public $conf = array();
	public function __construct($file = 'damien')
		{
		$this->extend('include',array($this,'_include'));
		$this->load($file);
		$this->set_iterator('conf');
		if (!defined('CONFIG_LOADED')) {define('CONFIG_LOADED',true);}
		}
	public function __get($var)
		{
		return $this->conf[$var];
		}
	public function __isset($var)
		{
		return isset($this->conf[$var]);
		}
	public function __unset($var)
		{
		unset($this->conf[$var]);
		}
	public function __toString()
		{
		$str = '';
		foreach ($this->conf as $key => $value)
			{
			$str .= $key.': '.print_r($value,true)."<br/>\n";
			}
		return $str;
		}
	public function _include($file)
		{
		$this->load($file);
		}
	public function load($file)
		{
		if (file_exists('system/config/'.$file.'.conf'))
			{
			$file = 'system/config/'.$file.'.conf';
			$strmode = false;
			}
		elseif (file_exists('system/config/'.$file.'.strconf'))
			{
			$strmode_base = $file;
			$file = 'system/config/'.$file.'.strconf';
			$strmode = true;
			}
		else
			{
			echo 'No such config file: '.$file,'<br/><pre>'.print_r(debug_backtrace(),true).'</pre>';
			exit;
			}
		s('timing')->play('[config] '.$file);
		$handle = fopen($file, 'r');
		$line_number = 0;
		while (!feof($handle))
			{
			$line = rtrim(fgets($handle),"\n");
			$line_number++;
			$pos = strrpos($line,'//');
			if ($pos !== false)
				{
				$line = substr($line,0,$pos);
				}
			$blank_value = (substr($line,-2) == ': ') ? true : false;
			$line = trim($line);
			if ($line)
				{
				$sep = strpos($line,': ');
				if ($sep || $blank_value)
					{
					$field = ($blank_value) ? substr($line,0,strlen($line)-1) : substr($line,0,$sep);
					$value = ($blank_value) ? '' : substr($line,$sep+2);
					if ($field)
						{
						$field = trim($field);
						$value = trim($value);
						$field = ($strmode) ? array($strmode_base,$field) : explode('.',$field);
						$tmp = &$this->conf;
						foreach ($field as $seg)
							{
							if (substr($seg,-2) == '[]')
								{
								$seg = substr($seg,0,-2);
								$push = true;
								}
							else
								{
								$push = false;
								}
							if (!isset($tmp[$seg]))
								{
								$tmp[$seg] = array();
								}
							if ($push)
								{
								$tmp = &$tmp[$seg][];
								}
							else
								{
								$tmp = &$tmp[$seg];
								}
							}
						if ($value == 'null')
							{
							$value = null;
							}
						elseif ($value == 'false')
							{
							$value = false;
							}
						elseif ($value == 'true')
							{
							$value = true;
							}
						elseif (is_numeric($value))
							{
							$value = (float) $value;
							}
						$tmp = $value;
						}
					else
						{
						s('console')->fatal_error('Expecting field name',$file,$line_number);
						}
					}
				else
					{
					if (substr($line,0,1) == ':')
						{
						$args = trim(substr($line,strpos($line,' ')));
						$function = substr($line,1,strpos($line,' ')-1);
						$error = $this->run_function($function,$args);
						if ($error[0] === false)
							{
							s('console')->warning($error[1],$file,$line_number);
							}
						}
					else
						{
						s('console')->warning('Expected : in this line',$file,$line_number);
						}
					}
				}
			}
		fclose($handle);
		s('timing')->pause('[config] '.$file);
		return $this;
		}
	public function extend($name,$function)
		{
		$this->functions[$name] = $function;
		return $this;
		}
	private function run_function($function,$args)
		{
		if (isset($this->functions[$function]))
			{
			return array(true,call_user_func_array($this->functions[$function],$args));
			}
		return array(false,'No such config function: '.$function);
		}
}
