<?php
class config extends simple_iterator {
private $functions = array();
protected $conf = array();
	public function __construct()
		{
		$this->extend('include',array($this,'_include'));
		$this->load('system/config/damien.conf');
		$this->set_iterator('conf');
		define('CONFIG_LOADED',true);
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
		$this->load('system/config/'.$file);
		}
	public function load($file)
		{
		$handle = fopen($file, 'r');
		$line_number = 0;
		while (!feof($handle))
			{
			$line = fgets($handle);
			$line = trim($line);
			$line_number++;
		
			$pos = strrpos($line,'//');
			if ($pos !== false)
				{
				$line = substr($line,0,$pos);
				}
		
			if ($line)
				{
				$sep = strpos($line,':');
				if ($sep)
					{
					$field = substr($line,0,$sep);
					$value = substr($line,$sep+1);
					if ($field && $value)
						{
						$field = trim($field);
						$value = trim($value);
						$field = explode('.',$field);
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
					elseif (!$field)
						{
						s('console')->fatal_error('Expecting field name',$file,$line_number);
						}
					elseif (!$value)
						{
						$value = '';
						//s('console')->fatal_error('Expecting value for '.$field,$file,$line_number);
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
