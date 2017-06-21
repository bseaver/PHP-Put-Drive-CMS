<?php
    require_once "src/ImageFile.php";

    class ImageFileParseTest extends PHPUnit_Framework_TestCase
    {
        function test_parseImgSrc()
        {
            $expected_results = array(
                [
                    'input' => 'Now listen to my story',
                    'results' =>['Now listen to my story'],
                    'reasoning' => 'No img tag',
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
