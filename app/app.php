<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Patron.php";
    //require_once __DIR__."/../src/.php";


    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    // HOME PAGE - DISPLAYS LINK TO LIST OF CUISINES OR RESTAURANTS
    $app->get('/', function() use($app){
        return $app['twig']->render('index.html.twig');

    return $app;
    
    });
