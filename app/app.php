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
        require_once __DIR__.'/../src/GoogleUserContentImage.php';
        require_once __DIR__.'/../src/ImageFile.php';

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
            $tempFile = $file . '.tmp';

            // Delete any "tmp" file
            if ( file_exists($tempFile) ) {
                unlink($tempFile);
            }
            if ( file_exists($tempFile) ) {
                $statusCode = 551;
            }

            // Save "tmp" file and verify valid json
            if ($statusCode === 200) {
                file_put_contents($tempFile, $input);
                $contents = file_get_contents($tempFile);
                $testData = json_decode($contents);
                if (!$testData) {
                    $statusCode = 552;
                }
            }

            // Move previous "new" file to "old" file
            if ($statusCode === 200 && file_exists($newFile)) {
                rename($newFile, $oldFile);
                if (file_exists($newFile) || !file_exists($oldFile)) {
                    $statusCode = 553;
                }
            }

            // Move "tmp" file to "new" file
            if ($statusCode === 200) {
                rename($tempFile, $newFile);
                if (file_exists($tempFile) || !file_exists($newFile)) {
                    $statusCode = 554;
                }
            }

            // Copy "new" file to "target" file
            if ($statusCode === 200) {
                copy($newFile, $targetFile);
                if (!file_exists($newFile) || !file_exists($targetFile)) {
                    $statusCode = 555;
                }
            }

            // Parse "new" file to separate out images
            // and download them for local hosting
            $imageFolder = __DIR__ . '/../web/cached_images/';
            $runtimeImagePath = '/cached_images/';
            $imageFilePrefix = $resource . '-img-';
            $imageFileSuffix = '.jpeg';
            $imageFileWildCard = $imageFolder . $imageFilePrefix . '*' . $imageFileSuffix;
            $imgNumber = 0;
            $processedInput = '';


            // First clean out old downloaded images
            if ($statusCode === 200) {
                foreach(glob($imageFileWildCard) as $oldImageFile) {
                    unlink($oldImageFile);
                }

                // Verify files are gone
                foreach(glob($imageFileWildCard) as $oldImageFile) {
                    $statusCode = 561;
                    break;
                }
            }
            
            // Download any Google User Content images
            if ($statusCode === 200) {
                $filePieces = ImageFile::parseImgSrc($input);

                // For each googleusercontent image
                foreach ($filePieces as $filePiece) {
                    if (GoogleUserContentImage::isGoogleUserContentImage($filePiece->imgURI)) {
                        $imgNumber++;
                        $newImageFile = $imageFilePrefix . $imgNumber . $imageFileSuffix;
                        $newImageTarget = $imageFolder . $newImageFile;
                        $newImagePath = $runtimeImagePath . $newImageFile;
                        if (GoogleUserContentImage::downloadImg($filePiece->imgURI, $newImageTarget)) {
                            $filePiece->imgURI = $newImagePath;
                        } else {
                            $statusCode = 562;
                            break;
                        }
                    }
                    $processedInput .= $filePiece->contents . $filePiece->imgURI;
                }
            }

            // Verify processed input is ok
            if ($statusCode === 200 && $imgNumber) {
                file_put_contents($tempFile, $processedInput);
                $contents = file_get_contents($tempFile);
                $testData = json_decode($contents);
                if (!$testData) {
                    $statusCode = 563;
                }
            }

            // Delete unprocessed "target"
            if ($statusCode === 200 && $imgNumber) {
                unlink($targetFile);
                if (file_exists($targetFile)) {
                    $statusCode = 564;
                }
            }

            // Move processed file to "target"
            if ($statusCode === 200 && $imgNumber) {
                rename($tempFile, $targetFile);
                if (file_exists($tempFile) || !file_exists($targetFile)) {
                    $statusCode = 565;
                    copy($newFile, $targetFile);
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
