<?php
require __DIR__ . '/vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/views');
$twig = new Twig_Environment($loader, array(
    // 'cache' => __DIR__ . '/templates/cache',
));

?>
