<?php

require_once __DIR__ . '/vendor/autoload.php';

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;
use Entrecore\GTMetrixClient\GTMetrixException;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/**
 * This worker will be ran on IronWorker to determine the status of the test
 */
class Worker {

    /**
     * @var Pagesoeed url to use
     */
    const PAGESPEED_URL = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed';

    /**
     * @var string Determines the current status of the test to avoid duplication of notification 
     */
    private $currentStatus;

    /**
     * @var ElephantIO\Client current socket server instance
     */ 
    private $socket;

    /**
     * @param Entrecore\GTMetrixClient\GTMetrixClient $client the current client used for the test
     * @param string $url
     * @param array $data
     * @param bool  $json
     *
     * @return array|string
     *
     * @throws GTMetrixConfigurationException
     * @throws GTMetrixException
     */
    protected function apiCall($client, $url, $data = array(), $json = true) {
        $username = $client->getUsername();
        $apiKey = $client->getApiKey();
        if (!$username || !$apiKey) {
            throw new GTMetrixConfigurationException('Username and API key must be set up before using API calls!' .
                'See setUsername() and setAPIKey() for details.');
        }

        $ch = curl_init($url);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/vendor/entrecore/gtmetrix/data/ca-bundle.crt');
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrNo = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close ($ch);

        if (!\preg_match('/^(2|3)/', $statusCode)) {
            if ($statusCode == 0) {
                throw new GTMetrixException('cURL error ' . $curlErrNo . ': ' . $curlError);
            }
            throw new GTMetrixException('API error ' . $statusCode . ': ' . $result);
        }

        if ($json) {
            $data = json_decode($result, true);
            if (json_last_error()) {
                throw new GTMetrixException('Invalid JSON received: ' . json_last_error_msg());
            }
        } else {
            $data = $result;
        }

        return $data;
    }

    /**
     * Send the socket emit event
     * @param Entrecore\GTMetrixClient\GTMetrixClient $client the current client used for the test
     * @param Entrecore\GTMetrixClient\GTMetrixTest $test the current client test status
     * @param string $testId ID of the current test for webscoket listener
     * @return boolean If success
     */
    private function emitTestStatus ($client, $test, $testId)
    {
        $state = $test->getState();
        if ($state != $this->currentStatus) {
            try {
                $testEvent = getenv('SOCKET_TEST_STATUS_EVENT');
                $resources = $test->getResources();
                $context = stream_context_create([
                    'http' => [
                        'header'  => "Authorization: Basic " . base64_encode(getenv('GTMETRIX_USERNAME') . ':' . getenv('GTMETRIX_APIKEY'))
                    ]
                ]);

                $data = [
                    'id'                => $test->getId(),
                    'state'             => $test->getState(),
                    'error'             => $test->getError(),
                    'reportUrl'         => $test->getReportUrl(),
                    'pagespeedScore'    => $test->getPagespeedScore(),
                    'yslowScore'        => $test->getYslowScore(),
                    'htmlBytes'         => $test->getHtmlBytes(),
                    'htmlLoadTime'      => $test->getHtmlLoadTime(),
                    'pageBytes'         => $test->getPageBytes(),
                    'pageLoadTime'      => $test->getPageLoadTime(),
                    'pageElements'      => $test->getPageElements(),
                    'resources'         => $resources,
                    'pollStateUrl'      => $test->getPollStateUrl(),
                    'type'              => 'desktop',
                    'screenshot'        => 'data:image/jpeg;base64,' . base64_encode(
                        file_get_contents($resources['screenshot'], false, $context)
                    )
                ];
                if ($state == GTMetrixTest::STATE_COMPLETED) {
                    $data['resources']['pagespeedData'] = $this->apiCall($client, $data['resources']['pagespeed']);
                    $data['resources']['gtmetrixData'] = $this->apiCall($client, $data['resources']['har']);
                }
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
     * Emits a custom test status for mobile
     * @param $status string The current rest status
     * @param string $testId ID of the current test for webscoket listener
     * @param $data array List of properties API returned
     */
    private function emitMobileTestStatus ($status, $testId, $data = [])
    {
        $testEvent = getenv('SOCKET_TEST_STATUS_EVENT');
        $data['state'] = $status;

        echo "Status update mobile: $status\n";

        $data['type'] = 'mobile';
        if ($status == 'error') {
            $data['error'] = $data;
        }

        $this->socket->emit($testEvent, [
            'data'  => $data,
            'id'    => $testId
        ]);
        echo "Emitted: $testId\n";
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
            echo "Status update desktop: $state\n";

            while (!$this->emitTestStatus($client, $client->getTestStatus($test), $payload->id)) {
                sleep(5);
            }
            sleep(getenv('SOCKET_TEST_STATUS_INTERVAL'));

        } while ($state != GTMetrixTest::STATE_COMPLETED && $state != GTMetrixTest::STATE_ERROR);

        while (!$this->emitTestStatus($client, $client->getTestStatus($test), $payload->id)) {
            sleep(5);
        }
        echo "Ending update: $state\n";

        /**
         * Google Pgespeed for mobile
         */
        echo "Starting update: started\n";
        $this->emitMobileTestStatus('started', $payload->id);

        $matches = [];
        preg_match('/((?:https?:\/\/)|(?:^))(.+)((?:\/$)|(?:(?<!\/)$))/', $payload->url, $matches);

        if (count($matches)) {
            $ch = curl_init(static::PAGESPEED_URL . '?' . http_build_query([
                'url'           => ($matches[1] ?: 'http://') . $matches[2] . ($matches[3] ?: '/'),
                'strategy'      => 'mobile',
                'screenshot'    => 'true'
            ]));
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_SSL_VERIFYPEER  => false
            ]);
            $content = curl_exec($ch);
            curl_close($ch);
            $content = json_decode($content, true);
            $content['screenshot']['data'] = str_replace(['_','-'], ['/','+'], $content['screenshot']['data']);
            
            if (isset($content['error']['errors'])) {
                $this->emitMobileTestStatus('error', $payload->id, $content['error']['errors'][0]['message']);
            }

            $this->emitMobileTestStatus('completed', $payload->id, $content);
        } else {
            $this->emitMobileTestStatus('error', $payload->id, 'Not a valid URL');
        }

        $this->socket->close();
    }
}

(new Worker())->run();
