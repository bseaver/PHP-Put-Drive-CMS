<?php
    require_once "src/ImageFile.php";

    class ImageFileParseTest extends PHPUnit_Framework_TestCase
    {
        function test_parse() {
            $expected_results = array(
                [
                    'delimiter' => ';',
                    'returnDelimiter' => FALSE,
                    'input' => 'ABC,123',
                    'input_after_parse' => '',
                    'result' =>'ABC,123',
                    'reasoning' => 'Result gets all if delimiter not found.',
                ],
                [
                    'delimiter' => ';',
                    'returnDelimiter' => FALSE,
                    'input' => ';123',
                    'input_after_parse' => ';123',
                    'result' =>'',
                    'reasoning' => 'Result gets nothing if input starts with delimiter.',
                ],
                [
                    'delimiter' => ';',
                    'returnDelimiter' => FALSE,
                    'input' => 'ABC;123',
                    'input_after_parse' => ';123',
                    'result' =>'ABC',
                    'reasoning' => 'Result gets up to delimiter.',
                ],
                [
                    'delimiter' => ';',
                    'returnDelimiter' => TRUE,
                    'input' => 'ABC,123',
                    'input_after_parse' => '',
                    'result' =>'ABC,123',
                    'reasoning' => 'Result gets all if delimiter not found despite "return delimiter".',
                ],
                [
                    'delimiter' => ';',
                    'returnDelimiter' => TRUE,
                    'input' => ';123',
                    'input_after_parse' => '123',
                    'result' =>';',
                    'reasoning' => 'Result gets delimiter if input starts with delimiter and "return delimiter".',
                ],
                [
                    'delimiter' => ';',
                    'returnDelimiter' => TRUE,
                    'input' => 'ABC;123',
                    'input_after_parse' => '123',
                    'result' =>'ABC;',
                    'reasoning' => 'Result gets up through delimiter with "return delimiter".',
                ],
            );

            foreach ($expected_results as $expected_result) {
                // Arrange
                $actual_result = $expected_result;
                $actual_result['input_after_parse'] = $actual_result['input'];

                // Act
                $actual_result['result'] = ImageFile::parse(
                        $actual_result['input_after_parse'],
                        $actual_result['delimiter'],
                        $actual_result['returnDelimiter']
                    );

                // Assert
                $this->assertEquals($expected_result, $actual_result);
            }
        }



        function test_parseImgSrc()
        {
            $expected_results = array(
                [
                    'input' => 'Now listen to my story',
                    'results' =>[
                            (object) [
                                'contents' => 'Now listen to my story',
                                'imgURI' => ''

                            ]
                        ],
                    'reasoning' => 'No img tag',
                ],
                [
                    'input' => '<div><p ><span ></span></p></div><h2  ><span ></span></h2><h2  ><span ><img alt=\"\" src=\"https://lh6.googleusercontent.com/fqlXoiCjU_uICFFJ1\"  title=\"\"></span><span >Kevin',
                    'results' =>[
                        (object) [
                            'contents' => '<div><p ><span ></span></p></div><h2  ><span ></span></h2><h2  ><span ><img alt=\"\" src=\"',
                            'imgURI' => '',
                        ],
                        (object) [
                            'contents' => '',
                            'imgURI' => 'https://lh6.googleusercontent.com/fqlXoiCjU_uICFFJ1',
                        ],
                        (object) [
                            'contents' => '\"  title=\"\"></span><span >Kevin',
                            'imgURI' => '',
                        ],
                    ],
                    'reasoning' => 'One image tag in middle',
                ],
                [
                    'input' => '<img alt=\"Picture of my dog Jaxon\" src=\"https://img.URI\"  title=\"Hi Mom!\">',
                    'results' =>[
                        (object) [
                            'contents' => '<img alt=\"Picture of my dog Jaxon\" src=\"',
                            'imgURI' => '',
                        ],
                        (object) [
                            'contents' => '',
                            'imgURI' => 'https://img.URI',
                        ],
                        (object) [
                            'contents' => '\"  title=\"Hi Mom!\">',
                            'imgURI' => '',
                        ],
                    ],
                    'reasoning' => 'Input begins and ends with a single image tag.',
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
