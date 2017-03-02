<?php
/*
NOTE some of the stuff in Request that was required by this has been taken out.
Some of it can be found in ~/dev/php/Request-old.php.

Request with functionality for parsing a menu definition file,
setting the default page for dir requests etc
*/

require_once "Request.php";

class RequestWithNav extends Request {
	//constants for parsing nav definition file:

	const SUBMENU_OPEN="{";
	const SUBMENU_CLOSE="}";
	const JOIN_LINE="\n";
	const JOIN_ENTRY=":";
	const JOIN_FLAG=" ";
	const FLAG_DEFAULT="d";

	public $menu;
	public $path;

	public function parse_menu(&$parent, &$list, $i=0, $path="") {
		while($i<count($list)) {
			$line=ltrim($list[$i]);

			$next=null;

			if(array_key_exists($i+1, $list)) {
				$next=ltrim($list[$i+1]);
			}

			if($line!=self::SUBMENU_OPEN && $line!=self::SUBMENU_CLOSE && $line!="") {
				$pair=explode(self::JOIN_ENTRY, $line);
				$title=$pair[0];
				$pair=explode(self::JOIN_FLAG, $pair[1]);
				$file=$pair[0];
				$flag=array_key_exists(1, $pair)?$pair[1]:null;

				if(!array_key_exists("child", $parent)) {
					$parent["child"]=[];
				}

				if($flag==self::FLAG_DEFAULT) {
					$parent["default"]=$file;
				}

				$parent["child"][$file]=[
					"title"=>$title,
					"path"=>"$path/$file",
					"file"=>$file,
					"flag"=>$flag,
					"child"=>[],
					"on"=>false
				];
			}

			if($next==self::SUBMENU_OPEN) {
				$i=$this->parse_menu($parent["child"][$file], $list, $i+2, "$path/$file");
			}

			if($next==self::SUBMENU_CLOSE) {
				return $i;
			}

			$i++;
		}

	}

	public function load_menu($fn) {
		if(file_exists($fn)) {
			$this->menu=[
				"child"=>[]
			];

			$list=explode(self::JOIN_LINE, file_get_contents($fn));
			$this->parse_menu($this->menu, $list);
			$this->parse_request($this->menu);
			//$this->set_filenames();
		}
	}

	public function load_default(&$parent) {
		if(array_key_exists("default", $parent)) {
			array_push($this->path, $parent["default"]);
			$this->load_default($parent["child"][$parent["default"]]);
		}
	}

	public function parse_request(&$parent, $pathindex=0) {
		if(count($this->path)==0) {
			$this->load_default($parent);
		}

		if(array_key_exists($this->path[$pathindex], $parent["child"])) {
			$item=&$parent["child"][$this->path[$pathindex]];
			$item["on"]=true;
			$this->title=$item["title"];

			if(count($item["child"])>0 && $pathindex==count($this->path)-1) {
				$this->load_default($item);
			}

			if($pathindex<(count($this->path)-1)) {
				$this->parse_request($item, $pathindex+1);
			}
		}
	}
}
?>