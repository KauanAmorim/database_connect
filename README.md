# database_connect
This is a system to connect many local databases for study

require_once "vendor/autoload.php";

echo "<pre>";
$connect = new \connection\connectdb("buy_register");
$connection = $connect->getConnection();

$query = "SELECT * FROM cliente";

var_dump($connect->execute($query, 'fetch')); -> result

var_dump($connect->setConnection($connection)); -> true

$object = new stdClass();
var_dump($connect->setConnection($object)); -> false