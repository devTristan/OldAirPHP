<?php
class event {
	public function __construct()
		{
		$this->events = array();
		$this->vars = array();
		$this->triggered = 0;
		}
	public function bind($class,$event,$method,$params = array())
		{
		$this->events[$event][] = array($class,$method,$params);
		return $this;
		}
	public function unbind($class,$event,$method = '*',$params = array())
		{
		if (isset($this->events[$event]))
			{
			foreach ($this->events[$event] as $key => $value)
				{
				if ($value == array($class,$method,$params))
					{
					unset($this->events[$event][$key]);
					}
				}
			}
		return $this;
		}
	public function set($data)
		{
		$this->vars = array_merge($this->vars,$data);
		return $this;
		}
	public function exists($event)
		{
		return isset($this->events[$event]);
		}
	public function triggered()
		{
		return $this->triggered;
		}
	public function trigger($event,$params = array())
		{
		s('timing')->play('[event] '.$event);
		$params = array_merge($this->vars,$params);
		if (isset($this->events[$event]))
			{
			foreach ($this->events[$event] as $data)
				{
				$class = $data[0];
				$method = $data[1];
				$required = $data[2];
				$match = (count($required) == 0) ? true : false;
				foreach ($required as $key => $value)
					{
					if (is_array($value))
						{
						if (isset($params[$key]))
							{
							foreach ($value as $single)
								{
								if ($this->match($params[$key],$single))
									{
									$match = true;
									break 2;
									}
								}
							}
						}
					else
						{
						if (isset($params[$key]) && $this->match($value,$params[$key]))
							{
							$match = true;
							break;
							}
						}
					}
				if ($match == true)
					{
					$this->triggered++;
					$class->$method($params);
					}
				}
			}
		s('timing')->pause('[event] '.$event);
		return $this;
		}
	private function match($a,$b)
		{
		if (is_string($a) && substr($a,0,6) == 'regex:')
			{
			if (preg_match('!^'.str_replace('!','\!',substr($a,6)).'$!',$b))
				{
				return true;
				}
			}
		elseif (is_string($b) && substr($b,0,6) == 'regex:')
			{
			if (preg_match('!^'.str_replace('!','\!',substr($b,6)).'$!',$a))
				{
				return true;
				}
			}
		elseif ($a == $b)
			{
			return true;
			}
		return false;
		}
}
