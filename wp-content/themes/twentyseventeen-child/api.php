<?php

add_action('rest_api_init', function () {
    register_rest_route( 'api/v1', '/test', array(
        'methods' => 'POST',
        'callback' => 'queue_test',
    ));
});
function queue_test ()
{
    $data = [
        'success' => false
    ];

    if ($_POST['url'] && $_POST['id']) {
        try {
            $worker = new \IronWorker\IronWorker(array(
                'token' => getenv('IRON_TOKEN'),
                'project_id' => getenv('IRON_PROJECTID')
            ));

            $taskId = $worker->postTask(getenv('IRON_STATUSWORKER_NAME'), [
                'url'   => $_POST['url'],
                'id'   => $_POST['id']
            ]);
            $data['success'] = true;
            $data['data'] = $taskId;
        } catch (\Exception $e) {
            $data['errors'] = [$e->getMessage()];
        }
    } else {
        $data['errors'] = ['Missing url or id parameter'];
    }

    return $response = new WP_REST_Response($data);
}