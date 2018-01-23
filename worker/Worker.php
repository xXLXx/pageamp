<?php

require_once __DIR__ . '/vendor/autoload.php';

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/**
 * This worker will be ran on IronWorker to determine the status of the test
 */
class Worker {

    /**
     * @var string Determines the current status of the test to avoid duplication of notification 
     */
    private $currentStatus;

    /**
     * @var ElephantIO\Client current socket server instance
     */ 
    private $socket;

    /**
     * Send the socket emit event
     * @param string the current GTMetrixTest::state
     * @param array data to be sent to event ENV{SOCKET_TEST_STATUS_EVENT}
     * @param string test status ID
     * @return boolean If success
     */
    private function emitTestStatus ($state, $data, $testId)
    {
        if ($state != $this->currentStatus) {
            try {
                $testEvent = getenv('SOCKET_TEST_STATUS_EVENT');
                $this->socket->emit($testEvent, [
                    'data'  => $data,
                    'id'    => $testId
                ]); 

                $this->currentStatus = $state;

                echo "Emitted: $testId\n";
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                return false;
            }
        }

        return true;
    }

    /**
     * Used to run worker
     * @return void
     */
    public function run ()
    {
        $worker = new \IronWorker\IronWorker([
            'token' => getenv('IRONWORKER_TOKEN'),
            'project_id' => getenv('IRONWORKER_PROJECTID')
        ]);

        $payload = json_decode(file_get_contents(getenv('PAYLOAD_FILE')));

        $client = new GTMetrixClient();
        $client->setUsername(getenv('GTMETRIX_USERNAME'));
        $client->setAPIKey(getenv('GTMETRIX_APIKEY'));

        $client->getLocations();
        $client->getBrowsers();
        $test = $client->startTest($payload->url);

        // Initialize our socket
        $this->socket = new Client(new Version1X(getenv('SOCKET_URL')));
        $this->socket->initialize();
         
        // Wait for result
        do {
            $state = $test->getState();
            echo "Status update: $state\n";

            while (!$this->emitTestStatus($state, (array) $client->getTestStatus($test), $payload->id)) {
                sleep(5);
            }
            sleep(getenv('SOCKET_TEST_STATUS_INTERVAL'));

        } while ($state != GTMetrixTest::STATE_COMPLETED && $state != GTMetrixTest::STATE_ERROR);

        while (!$this->emitTestStatus($state, (array) $client->getTestStatus($test), $payload->id)) {
            sleep(5);
        }
        echo "Ending update: $state\n";
        $this->socket->close();
    }
}

(new Worker())->run();
