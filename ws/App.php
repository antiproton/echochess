<?php
/*
websocket application
*/

require_once "Data.php";
require_once "/var/www/chess/php/stockfish.php";
require_once "vendor/autoload.php";

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class App implements MessageComponentInterface {
	/*
	users - connections indexed by username
	*/

    private $users=[];

    public function __construct() {

    }

    public function onOpen(ConnectionInterface $conn) {
		$user=$conn->Session->get("user");

		if($user) {
			$conn->username=$user;
			$this->users[$user]=$conn;
		}

		else {
			$conn->close();
		}
    }

    public function onMessage(ConnectionInterface $conn, $msg) {
		/*
		NOTE this is a temporary thing for having the analysis
		on the big server until it runs out, after which it will move
		to somewhere else
		*/

		$data=Data::unserialise($msg);

		switch($data["type"]) {
			case "analyse": {
				$conn->send(stockfish($data["fen"], $data["analysis_time"]));

				break;
			}
		}
    }

    public function onClose(ConnectionInterface $conn) {
        unset($this->users[$conn->username]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
?>