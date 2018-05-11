<?PHP
require 'vendor/autoload.php';

use nalletje\imuis_api\Models\Orders;

## Set Config
$_partnerkey = "";
$_environmentkey = "";
$debitor_nr = "";

## Create Session
$conn = new Orders($_partnerkey, $_environmentkey);
## Optional
$conn->setSelect('*'); // Select Statement
$conn->setMaxResults('10'); // The max results it will return
$conn->setSelectPage('1');  // in case of more results then 10
## Get all Data
$data = $conn->getOrderSorts();

echo "<pre>";
var_dump($data);
echo "</pre>";

## Notice, within $data->DATA you will find your results, within $data->METADATA you will find result count + retrievable pages
