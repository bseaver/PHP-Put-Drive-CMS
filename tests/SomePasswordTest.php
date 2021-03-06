<?php
    require_once "src/SomePassword.php";

    class SomePasswordTest extends PHPUnit_Framework_TestCase
    {
        function test_password() {
            $resources[] = 'something';
            $users[] = 'somebody';
            $passwords[] = 'a-secret';

            $resources[] = 'data';
            $users[] = 'admin';
            $passwords[] = 'wrong-$password';

            $resources[] = 'data';
            $users[] = 'admin';
            $passwords[] = '123Go!';

            $resources[] = 'it';
            $users[] = 'works';
            $passwords[] = 'Abracadabra';

            $resources[] = 'it';
            $users[] = 'cousin';
            $passwords[] = 'abracadabra';

            $resources[] = 'it';
            $users[] = 'works';
            $passwords[] = 'abracadabra';

            $expected_results = array(
                [
                    'resource' => 'bony',
                    'user' => 'james',
                    'password' => 'asdf',
                    'result' => 400,
                    'reasoning' => 'Unknown resource - 400 Bad Request',
                ],
                [
                    'resource' => 'data',
                    'user' => 'admin',
                    'password' => '123Go!',
                    'result' => 200,
                    'reasoning' => 'Match! - 200 Good request',
                ],
                [
                    'resource' => 'data',
                    'user' => 'administrator',
                    'password' => '123Go!',
                    'result' => 401,
                    'reasoning' => 'Unknown user - 401 Unauthorized',
                ],
                [
                    'resource' => 'it',
                    'user' => 'works',
                    'password' => 'abra!',
                    'result' => 403,
                    'reasoning' => 'Bad password - 403 Forbidden',
                ],
            );

            foreach ($expected_results as $expected_result) {
                // Arrange
                $actual_result = $expected_result;

                // Act
                $actual_result['result'] = SomePassword::getStatusCode(
                        $resources, $users, $passwords,
                        $actual_result['resource'],
                        $actual_result['user'],
                        $actual_result['password']
                    );

                // Assert
                $this->assertEquals($expected_result, $actual_result);
            }
        }
    }
?>
