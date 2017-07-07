<?php
    require_once "src/ImportJson.php";

    class ImportJsonTest extends PHPUnit_Framework_TestCase
    {
        function __construct() {
            parent::__construct();
            $this->tempFileName = __DIR__ . __CLASS__ . '.tmp';
        }

        protected function tearDown()
        {
            unlink($this->tempFileName);
        }

        function test_import() {
            $expected_results = array(
                [
                    'fileContents' => '{"purposeOfLife":42}',
                    'isValid' => 'yes',
                    'reasoning' => 'Is a valid json object',
                ],
                [
                    'fileContents' => 'The purpose of life cannot be calculated',
                    'isValid' => 'no',
                    'reasoning' => 'Is NOT a valid json object',
                ],
            );

            foreach ($expected_results as $expected_result) {
                // Arrange
                $actual_result = $expected_result;
                file_put_contents($this->tempFileName, $actual_result['fileContents']);

                // Act
                $testObject = new ImportJson($this->tempFileName, 'json');
                $testData = $testObject->data;

                if ($testData) {
                    $actual_result['isValid'] = 'yes';
                } else {
                    $actual_result['isValid'] = 'no';
                }

                // Assert
                $this->assertEquals($expected_result, $actual_result);
            }
        }
    }
?>
