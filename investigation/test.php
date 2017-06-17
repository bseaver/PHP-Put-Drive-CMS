<?php
    class Data {
        public $data;

        public function __construct($file) {
            $this->data = json_decode(file_get_contents($file));
            // print_r($this->data['Breweries'][..]); // Array
            //print_r($this->data->Breweries[0]->City); // Object
        }

        public function getBreweries()
        {
            return $this->data->Breweries;
        }
    }

    $test = new Data('../data.json');
    // foreach($data->getBreweries() as $brewery) {
    //     echo '<h2>'.$brewery->Name.'</h2>';
    // }
    echo '<pre>';
    print_r($test->data->Breweries);
    echo '</pre>';

?>
