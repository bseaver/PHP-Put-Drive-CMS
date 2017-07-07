<?php
class ImportJson {
    public $data;

    public function __construct($file, $type = 'json') {
        if ($type === 'json') {
            $contents = file_get_contents($file);
            $this->data = json_decode($contents);
        }
    }
}

?>
