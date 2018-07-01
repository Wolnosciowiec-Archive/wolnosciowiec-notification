#!/usr/bin/env php
<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$twig = new \Twig_Environment(new \Twig_Loader_Filesystem([__DIR__ . '/config']));
$rendered = $twig->render('notification.yml.dist.twig', array_merge($_ENV, $_SERVER));

if (!is_file(__DIR__ . '/config/notification.yml')) {
    print(' >> Rendering notification.yml');
    file_put_contents(__DIR__ . '/config/notification.yml', $rendered);
    exit(0);
}

print(' >> Not rendering notification.yml as it already exists');
exit(0);
