<?php
namespace Worldcup\App;

require_once __DIR__ .'/Psr4Autoloader.php';

// instantiate the loader
$loader = new Psr4Autoloader;

// register the autoloader
$loader->register();

// register the base directories for the namespace prefix
$loader->addNamespace('Worldcup\App', __DIR__);