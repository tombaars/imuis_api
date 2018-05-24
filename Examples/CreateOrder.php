<?PHP
require 'vendor/autoload.php';
use nalletje\imuis_api\Model\CreateOrder;

## Set Config
$_partnerkey = "";
$_environmentkey = "";

// Tries to create a connection to your iMuis Application
$conn = new CreateOrder($_partnerkey, $_environmentkey);
// Set Order Kop Details
$conn->setDebitorNr('100000');
//$conn->setDateTime(date("Y-m-d H:i:s")); // In case not Set, it takes the date now.
$conn->setOrderSort('ORDERSORT'); // This has to be the same as in iMuis!
$conn->setReference('Order Description');  // Will be visible on the invoice
$conn->setConfirmation(TRUE);  // TRUE / FALSE -OR- J / N
// Now we set some Products to the order :).
// Example all Fields :: $conn->addOrderArticles($ART, $AANT, $OMSCHR = false, $OPMINT = false, $OPMEXT = false, $DAT = false, $BLOK = false, $OMSCHR = false, $PERCKORT = false, $MAG = false, $DATLEV = false);
$conn->addOrderArticles('ARTNR', 'QUANTITY');
$conn->addOrderArticles('ARTNR2', 'QUANTITY');
// Create the Order :).
$conn->createOrder();


?>
