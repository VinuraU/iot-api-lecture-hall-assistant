<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require '../vendor/autoload.php';

    $app = new \Slim\App;
    
    // Audio
    require_once '../api/audio.php';
    require_once '../api/controllers.php';

    $app->run();

?>