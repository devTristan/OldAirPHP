<?php
abstract class controller {
	protected function bind($event,$method,$params = array())
		{
		s('event')->bind($this,$event,$method,$params);
		return $this;
		}
	protected function unbind($event,$method,$params = array())
		{
		s('event')->unbind($this,$event,$method,$params);
		return $this;
		}
}
