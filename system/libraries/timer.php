<?php
class timer {
private $name = null;
	function timer($name)
		{
		$this->name = $name;
		}
	function play()
		{
		s('timing')->play($this->name);
		return $this;
		}
	function pause()
		{
		s('timing')->pause($this->name);
		return $this;
		}
	function elapsed()
		{
		return s('timing')->elapsed($this->name);
		}
	function set($time)
		{
		s('timing')->set($this->name,$time);
		return $this;
		}
}
