<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/ImportJson.php';
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    $app = new Silex\Application();
    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../web/views'
    ));

    // Retrieve website data from JSON file
    $data = new ImportJson(__DIR__.'/../data/data.json');

    // Home route
    $app->get('/', function() use ($app, $data) {
        return $app['twig']->render('welcome.html.twig', ['data' => $data->data]);
    });

    $app->get('/generations', function() use ($app, $data) {
        return $app['twig']->render('generations.html.twig', ['data' => $data->data]);
    });

    $app->get('/generation/{id}', function($id) use ($app, $data) {
        return $app['twig']->render('generations.html.twig', ['data' => $data->data, 'id' => $id]);
    });

    $app->post('/data', function(Request $request) use($app) {
    })->before(function (Request $request) {
        require_once __DIR__.'/../src/SomePassword.php';

        // Test (assuming resource=data, user=admin, password=password and sendfile.json exists at root)
        // curl -H "Authorization: Basic YWRtaW46cGFzc3dvcmQ=" -H "Content-Type: application/json" -X POST -d @sendfile.json localhost:8888/data
        $resource = 'data';
        $user = $request->getUser();
        $password = $request->getPassword();
        $contentType = $request->getContentType();

        // Check resource, user and pw
        // If match, expect statusCode of 200
        $pws = parse_ini_file(__DIR__ . '/../passwords.ini');

        $statusCode = SomePassword::getStatusCode(
            $pws['resources'], $pws['users'], $pws['passwords'],
            $resource, $user, $password
        );

        // Only json supported at this point
        if ($statusCode === 200 && $contentType !== 'json') {
            $statusCode = 501;
        }

        if ($statusCode === 200 && $contentType === 'json') {
            $input = file_get_contents('php://input');
            $file = __DIR__ . '/../data/' . $resource;
            $newFile = $file . '.new';
            $targetFile = $file . '.json';
            $oldFile = $file . '.old';

            // Delete any "new" file
            if ( file_exists($newFile) ) {
                unlink($newFile);
            }
            if ( file_exists($newFile) ) {
                $statusCode = 551;
            }

            // Save "new" file and verify valid json
            if ($statusCode === 200) {
                file_put_contents($newFile, $input);
                $contents = file_get_contents($newFile);
                $testData = json_decode($contents);
                if (!$testData) {
                    $statusCode = 552;
                }
            }

            // Move previous "target" file to "old" file
            if ($statusCode === 200 && file_exists($targetFile)) {
                rename($targetFile, $oldFile);
                if (file_exists($targetFile) || !file_exists($oldFile)) {
                    $statusCode = 553;
                }
            }

            // Copy "new" file to "target" file
            if ($statusCode === 200) {
                copy($newFile, $targetFile);
                if (!file_exists($newFile) || !file_exists($targetFile)) {
                    $statusCode = 554;
                }
            }

            // End of processing
            if ($statusCode === 200) {
                $statusCode = 201;
            }
        }

        $statusMessage = $statusCode . ': ';
        switch ($statusCode) {
            case 201:
                $statusMessage .= ' Upload processed';
                break;

            case 400:
                $statusMessage .= ' Unknown resource';
                break;

            case 401:
                $statusMessage .= ' Unknown user';
                break;

            case 403:
                $statusMessage .= ' Invalid user or password';
                break;

            case 500:
                $statusMessage .= ' Unspecified server error';
                break;

            case 501:
                $statusMessage .= ' Unsupported content type';
                break;

            default:
                $statusMessage .= ' Processing error!';
                break;
        }

        return new Response($statusMessage, $statusCode);
    });

    return $app;
?>
