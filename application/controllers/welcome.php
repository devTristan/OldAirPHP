<?php
class controller_welcome extends controller {
	function index()
		{
		s('views')->show_view('welcome_message');
		}
	function css()
		{
		header('Content-Type: text/css');
		s('views')->show_view('welcome');
		}
}
