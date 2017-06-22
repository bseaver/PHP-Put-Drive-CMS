<?php
    require_once "src/ImageFile.php";

    class ImageFileParseTest extends PHPUnit_Framework_TestCase
    {
        function test_Parse() {
            $expected_results = array(
                [
                    'input' => 'ABC,123',
                    'onWhat' => ';',
                    'result' =>'',
                    'reasoning' => 'Empty result if the string to parse on is not found.',
                ],
            );

            foreach ($expected_results as $expected_result) {
                // Arrange
                $actual_result = $expected_result;
                // Act
                $actual_result['result'] = ImageFile::parse($actual_result['input'], $actual_result['onWhat']);
                // Assert
                $this->assertEquals($expected_result, $actual_result);
            }
        }



        function test_parseImgSrc()
        {
            $expected_results = array(
                [
                    'input' => 'Now listen to my story',
                    'results' =>['Now listen to my story'],
                    'reasoning' => 'No img tag',
                ],
                [
                    'input' => '<div><p ><span ></span></p></div><h2  ><span ></span></h2><h2  ><span ><img alt=\"\" src=\"https://lh6.googleusercontent.com/fqlXoiCjU_uICFFJ1\"  title=\"\"></span><span >Kevin',
                    'results' =>[
                        '<div><p ><span ></span></p></div><h2  ><span ></span></h2><h2  ><span ><img alt=\"\" src=\"',
                        'https://lh6.googleusercontent.com/fqlXoiCjU_uICFFJ1',
                        '\"  title=\"\"></span><span >Kevin',
                    ],
                    'reasoning' => 'One image tag in middle',
                ],
            );

            foreach ($expected_results as $expected_result) {
                // Arrange
                $actual_result = $expected_result;
                // Act
                $actual_result['results'] = ImageFile::parseImgSrc($actual_result['input']);
                // Assert
                $this->assertEquals($expected_result, $actual_result);
            }
        }
    }
?>
