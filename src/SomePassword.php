<?php
/**
* TO DO: Correct format of this comment
*
* Support basic password checking:
*   Match resource, user and password.
*   Return:
*     200 if a match
*     400 if resource doesn't match
*     401 if resource matches, but not user
*     403 if resource and user matches, but not password
*
**/
class SomePassword {
    public static function getStatusCode($resources, $users, $passwords, $resource, $user, $password) {
        // Start with no match
        $result = 400;

        $length = min(count($resources), count($users), count($passwords));

        for ($i=0; $i < $length; $i++) {
            if ($resources[$i] === $resource) {
                $result = max($result, 401);
                if ($users[$i] === $user) {
                    $result = max($result, 403);
                    if ($passwords[$i] === $password) {
                        $result = 200;
                        break;
                    }
                }
            }
        }

        return $result;
    }
}

?>
