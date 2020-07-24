<?php

define("DOMAIN_WEB", "https://www.srcf.net");
define("DOMAIN_CONTROL", "https://control.srcf.net");

function title() {
	$BASETITLE = "Student-Run Computing Facility (SRCF)";
	if (PHP_SAPI == "cli" && isset($_SERVER['argv'][1])) {
		# CGI handler uses this
		return $_SERVER['argv'][1];
	} elseif ($title = getenv('title')) {
		# PHP pages and stuff I haven't thought of use this
		return $title . " - " . $BASETITLE;
	} else {
		# Oops, no title
		return $BASETITLE;
	}
}

function extrahead() {
	if (isset($_ENV['extrahead'])) {
		# PHP pages and stuff I haven't thought of use this
		return $_ENV['extrahead'];
	} else {
		return "";
	}
}

function output($name) {
	$file = file_get_contents(dirname(__FILE__) . '/' . $name . '.html');
	$file = str_replace("{{ DOMAIN_WEB }}", DOMAIN_WEB, $file);
	$file = str_replace("{{ DOMAIN_CONTROL }}", DOMAIN_CONTROL, $file);
	$file = str_replace("{{ TITLE }}", htmlspecialchars(title()), $file);
	$file = str_replace("{{ EXTRAHEAD }}", extrahead(), $file);
	echo $file;
}
