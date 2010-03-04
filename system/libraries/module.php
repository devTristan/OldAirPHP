<?php
class module {
	protected function hook($event,$method,$params = array())
		{
		s('event')->hook($this,$event,$method,$params);
		return $this;
		}
	protected function unhook($event,$method,$params = array())
		{
		s('event')->unhook($this,$event,$method,$params);
		return $this;
		}
}
