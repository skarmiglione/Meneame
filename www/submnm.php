<?php
$path = $globals['path'];
$globals['submnm'] = preg_replace('/[^\p{L}\d_]/u', ':', $path[1]);

include_once 'config.php';

$forbidden_routes = array('m', 'user', 'legal', 'notame', 'mobile', 'register', 'login', 'trends');

if (in_array($path[2], $forbidden_routes)) {
	// syslog(LOG_INFO, "Forbidden in subs: ".$path[2]);
	// Redirect to the root
	$uri = preg_split('/\/+/', $_SERVER['REQUEST_URI'], 10, PREG_SPLIT_NO_EMPTY);

	die(header('Location: /'.implode('/', array_slice($uri, 2))));
}

$globals['site_shortname'] = $globals['submnm'];

if (empty($globals['submnm']) || ! ($info = SitesMgr::get_info())) {
	not_found();
}

$globals['path'] = array_slice($path, 2);
$globals['base_url'] .= $path[0] . '/' . $path[1] . '/';

if (empty($routes[$path[2]])) {
	include './story.php';
	return;
}

$file = './'.$routes[$path[2]];

if (!is_file($file)) {
	not_found($path[1]);
}

$res = include $file;

if ($res === FALSE) {
	not_found($path[1]);
}
