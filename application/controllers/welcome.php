<?php
class controller_welcome extends controller {
	function index()
		{
		s('views')->show_view('welcome_message');
		}
}
