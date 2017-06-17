<?php
// Adapted From: https://stackoverflow.com/questions/804045/preferred-method-to-store-php-arrays-json-encode-vs-serialize
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo '<pre>';

// Make a big, honkin test array
// You may need to adjust this depth to avoid memory limit errors
$mem_used = memory_get_usage();
$testArray = fillArray(0, 5);
$mem_used = memory_get_usage() - $mem_used;
echo "Memory used by array = " . $mem_used . "\n";

// Time json encoding
$start = microtime(true);
$str = json_encode($testArray);
$jsonTime = microtime(true) - $start;
echo "JSON encoded in $jsonTime seconds\n";
file_put_contents('speedtest_data.json', $str);

// Time json decoding
$start = microtime(true);
$testArrayJD = json_decode(file_get_contents('speedtest_data.json'), TRUE);
$jsonTime = microtime(true) - $start;
echo "JSON decoded in $jsonTime seconds\n";

// Time serialization
$start = microtime(true);
$str = serialize($testArray);
$serializeTime = microtime(true) - $start;
echo "PHP serialized in $serializeTime seconds\n";
file_put_contents('speedtest_data.ser', $str);

// Time unserialization
$start = microtime(true);
$testArrayUn = unserialize(file_get_contents('speedtest_data.ser'));
$serializeTime = microtime(true) - $start;
echo "PHP unserialized in $serializeTime seconds\n";

// // https://stackoverflow.com/questions/5678959/php-check-if-two-arrays-are-equal
// if ($testArrayJD == $testArrayUn) {

// if (serialize($testArrayJD) === serialize($testArrayUn)) {

if (count($testArrayJD) == count($testArrayUn)) {
    echo "Results from json_decode() and unserialize() return identical results as expected.\n";
} else {
    echo "Results from json_decode() and unserialize() return unexpected results!!\n";
    echo "Count from unserialize = " . count($testArrayUn) . " count from json_decode = " . count($testArrayJD) . "\n";
    echo "Count 'cat' = " . count('cat') . "\n";
}

// Compare them
if ($jsonTime < $serializeTime) {
    printf("json_decode() was roughly %01.2f%% faster than unserialize()\n", ($serializeTime / $jsonTime - 1) * 100);
}
else if ($serializeTime < $jsonTime ) {
    printf("serialize() was roughly %01.2f%% faster than json_encode()\n", ($jsonTime / $serializeTime - 1) * 100);
} else {
    echo "Impossible!\n";
}
echo '</pre>';

function fillArray( $depth, $max ) {
    static $seed;
    if (is_null($seed)) {
        $seed = array('a', 2, 'c', 4, 'e', 6, 'g', 8, 'i', 10);
    }
    if ($depth < $max) {
        $node = array();
        foreach ($seed as $key) {
            $node[$key] = fillArray($depth + 1, $max);
        }
        return $node;
    }
    return 'empty';
}
