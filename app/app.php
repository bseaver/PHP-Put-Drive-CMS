<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Data.php';

    $app = new Silex\Application();
    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    // Retrieve website data from JSON file
    $data = new Data('./../data.json');

    // Home route
    $app->get('/', function() use ($app, $data) {
        return $app['twig']->render('welcome.html.twig', ['data' => $data->data]);
    });

    $app->get('/breweries', function() use ($app, $data) {
        return $app['twig']->render('breweries.html.twig', ['data' => $data->data]);
    });

    $app->get('/brewery/{id}', function($id) use ($app, $data) {
        return $app['twig']->render('breweries.html.twig', ['data' => $data->data, 'id' => $id]);
    });

    $app->get('/brews', function() use ($app, $data) {
        return $app['twig']->render('brews.html.twig',
            ['data' => $data->data,
            'id' => $id,
            'missingBeerHeader' => $data->missingBeerHeader($id)]
        );
    });

    $app->get('/brew/{id}', function($id) use ($app, $data) {
        return $app['twig']->render('brews.html.twig',
            ['data' => $data->data,
            'id' => $id,
            'missingBeerHeader' => $data->missingBeerHeader($id)]
        );
    });

    // Posted data route
    // The following is not yet working
    // It has been tested with:
    // curl -H "Content-Type: application/json" -X POST -d '{"username":"xyz","password":"xyzabc"}' http://localhost:8000/post

    $app->post('/post', function() use($app) {
        $input = file_get_contents('php://input');

        // To Do:
        // verify proper function on deployed site
        // support authentication
        // remove diagnostics

        // echo '<pre>';
        // echo print_r($input);
        // echo '</pre>';

        $output = '<pre>' . print_r($input, true) . '</pre>';
        return $output;
    });


    return $app;
?>
