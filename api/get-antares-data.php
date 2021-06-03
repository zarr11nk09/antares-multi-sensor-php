<?php

use function PHPSTORM_META\type;

include('antares-php.php');

$antares = new antares_php();
$antares->set_key('93025066f9605fe1:d2b4a06f345d1717');

$data = $antares->get('dht11', 'MonitoringSuhu09');

echo json_encode($data);
