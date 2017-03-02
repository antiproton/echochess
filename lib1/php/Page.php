<?php
/*
Page - parses the url and provides paths to all the necessary files
to be loaded for the current page.

The current page is set by calling ->load, so the page displayed doesn't
have to depend on the url (useful for doing a soft redirect to the login
page for registered-only pages)

If a prefix is passed, or set at some point, subsequent loads will have
the prefix between the document root and the request path in the absolute
path to the page resources.

e.g.

docroot=/var/www/html
prefix=/p
request=/home/profile

absolute path to main page html file:

/var/www/html/p/home/profile/body.php

This is a stripped out version of the old Request or Navigation class,
which had stuff for parsing a menu definition file and setting the default
page for directory requests, and can probably be found in a previous backup
of /lib/php (before 2013.4.15)

FIXME the docroot isn't passed in any more so might be NULL, has to be
set from outside before load() will work properly.  this uses constants
anyway so could probably just set itself up with WWWROOT by default
*/

require_once "base.php";
require_once "string_utils.php";
require_once "Singleton.php";

class Page {
	use Singleton;

	public $url_request; //comes from url
	public $url_path; //comes from url
	public $docroot;
	public $index;
	public $path=""; //set by calling "load"
	public $prefix="";
	public $name;

	/*
	the different components each page can have:

	e.g. for a request to /site/dir/page, the body of the page will
	be in /var/www/html/site/dir/page/body.php
	*/

	public static $resources=[
		"head", //code to be executed before any output or headers are sent
		"body", //html and code for the page itself
		"css", //any css, or code to produce it, that the page requires
		"js" //as above, but js
	];

	public function __construct() {
		$this->url_request=REL_REQ;
		$this->url_path=parse_url($this->url_request, PHP_URL_PATH);
	}

	public function load($request) {
		$this->path=parse_url($request, PHP_URL_PATH);

		if(endswith($this->path, "/") && $this->index) {
			$this->path.=$this->index;
		}

		$parts=explode("/", $this->path);

		$this->name=end($parts);

		foreach(self::$resources as $type) {
			$this->$type="{$this->docroot}{$this->prefix}{$this->path}/$type.php";
		}
	}
}
?>