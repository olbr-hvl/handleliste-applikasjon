<?php

// Support relative paths by changing directory, otherwise php's built-in web server will only use the document root as the current directory

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // strip query & fragment (dirname will get confused by potentional extra slashes)
$dir = dirname($path); // includes leading slash
chdir(".$dir");