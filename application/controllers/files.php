<?php
class controller_files extends controller {
	public function index()
		{
		s('views')->show_view('files/index',array(
			'folder' => 'root/',
			'files' => $this->listfiles()
			));
		}
	public function ls()
		{
		$folder = (isset($_GET['location'])) ? $_GET['location'] : '';
		echo json_encode($this->listfiles($folder));
		die();
		}
	private function listfiles($folder = '')
		{
		$ls = files::ls(DIR_PUBLIC.'root/'.$folder);
		if ($folder) {$folder = $folder.'/';}
		$files = array();
		foreach ($ls as $filename)
			{
			$file = new iconfile(DIR_PUBLIC.'root/'.$folder.$filename);
			if (substr($filename,0,1) != '.')
				{
				$files[] = array(
					'icon' => $file->icon,
					'name' => $file->name,
					'heading' => $file->heading,
					'is_dir' => is_dir($file->location)
					);
				}
			}
		return $files;
		}
}
class thumbnails {
private $cache;
	public function __construct()
		{
		$this->cache = s('cache','thumbnails','array');
		}
	public function __call($method,$args)
		{
		$var = sha1(serialize(array($method,$args)));
		if (isset($this->cache->$var))
			{
			return $this->cache->$var;
			}
		if (method_exists($this,$method))
			{
			$output = $this->$method(new file($args[0]));
			}
		else
			{
			$file = str_replace('/','-',$this->mimetype).'.png';
			$output = (file_exists(DIR_PUBLIC.'icon/'.$file)) ? $file : 'unknown.png';
			}
		return $this->cache->$var = URL_BASE.'icon/'.$output;
		}
	private function folder($file)
		{
		switch ($file->location)
			{
			case DIR_PUBLIC.'root/Documents': return 'folder-documents.png';
			case DIR_PUBLIC.'root/Music': return 'folder-music.png';
			case DIR_PUBLIC.'root/Pictures': return 'folder-pictures.png';
			case DIR_PUBLIC.'root/Videos': return 'folder-videos.png';
			default: return 'folder.png';
			}
		}
}
class iconfile extends file {
private $icon;
private $heading;
	public function get_icon()
		{
		if (is_dir($this->location()))
			{
			return $this->icon = s('thumbnails')->folder($this->location());
			}
		else
			{
			$file = 'icon/'.str_replace('/','-',$this->mimetype).'.png';
			if (!file_exists(DIR_PUBLIC.$file))
				{
				return $this->icon = URL_BASE.'icon/unknown.png';
				}
			return $this->icon = URL_BASE.$file;
			}
		}
	public function get_heading()
		{
		if (is_dir($this->location()))
			{
			$count = count(files::ls($this->location()));
			return $this->heading = $count.' File'.(($count == 1) ? '' : 's');
			}
		else
			{
			return $this->heading = files::readable_bytes($this->size());
			}
		}
}
