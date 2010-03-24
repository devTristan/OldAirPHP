<?php
class library {
	protected function hook($event,$method,$params = array())
		{
		return s('event')->bind($this,$event,$method,$params);
		}
	protected function unhook($event,$method = '*',$params = array())
		{
		s('event')->unbind($this,$event,$method,$params);
		return $this;
		}
}
