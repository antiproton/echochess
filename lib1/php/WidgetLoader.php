<?php
class WidgetLoader {
	private $dir;
	private $paths=[];

	private $extensions=[
		".template.php",
		".js",
		".css"
	];

	public function __construct($dir) {
		$this->dir=$dir;
	}

	public function load($file) {
		if(is_array($file)) {
			foreach($file as $fn) {
				$this->load($fn);
			}
		}

		else {
			$path=$this->dir."/".$file;

			if(is_dir($path)) {
				$dir=scandir($path);

				foreach($dir as $node) {
					if($node!=="." && $node!=="..") {
						$this->load("$file/$node");
					}
				}
			}

			else {
				$ext=substr($path, strpos($path, "."));

				if(array_search($ext, $this->extensions)!==false) {
					$this->addPath($ext, $path);
				}
			}
		}
	}

	private function addPath($ext, $path) {
		if(!array_key_exists($ext, $this->paths)) {
			$this->paths[$ext]=[];
		}

		$this->paths[$ext][]=$path;
	}

	private function includeFiles($ext) {
		foreach($this->paths[$ext] as $fn) {
			include $fn;
			echo "\n";
		}
	}

	public function outputJs() {
		$this->includeFiles(".js");
	}

	public function outputCss() {
		$this->includeFiles(".css");
	}

	public function outputTemplates() {
		$this->includeFiles(".template.php");
	}
}
?>