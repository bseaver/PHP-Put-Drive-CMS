<?php
    require_once "src/GoogleUserContentImage.php";

    class GoogleUserContentImageTest extends PHPUnit_Framework_TestCase
    {
        function test_isGoogleUserContentImage() {
            $expected_results = array(
                [
                    'input' => 'This isnt even a URI',
                    'output' => FALSE,
                    'reasoning' => 'Check some random string',
                ],
                [
                    'input' => '',
                    'output' => FALSE,
                    'reasoning' => 'Check an empty string',
                ],
                [
                    'input' => NULL,
                    'output' => FALSE,
                    'reasoning' => 'Check a NULL',
                ],
                [
                    'input' => 'https://lh4.googleusercontent.com/LUd3Xc7QPiAIEh1PZfFkwJ-LrEnZK9_0m1bCgXil1eEyE',
                    'output' => TRUE,
                    'reasoning' => 'Check looks real',
                ],
                [
                    'input' => 'https://lh4.googleusecontent.com/LUd3Xc7QPiJ-LrEnZK9_0m1bCgXil1eEyE',
                    'output' => FALSE,
                    'reasoning' => 'Check not googleusercontent',
                ],
                [
                    'input' => 'https://lh4.googleusecontent.com/LUd3Xc7QPiJ-LrEnZK9_0m1bCgXil1eEyE',
                    'output' => FALSE,
                    'reasoning' => 'Check not https',
                ],
            );

            foreach ($expected_results as $expected_result) {
                // Arrange
                $actual_result = $expected_result;

                // Act
                $actual_result['output'] = GoogleUserContentImage::isGoogleUserContentImage($actual_result['input']);

                // Assert
                $this->assertEquals($expected_result, $actual_result);
            }
        }
    }


?>
