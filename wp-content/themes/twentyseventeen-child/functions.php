<?php
require_once(__DIR__ . '/api.php');


add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');
function enqueue_child_theme_styles ()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    wp_enqueue_script('socketio', get_stylesheet_directory_uri() . '/bower_components/socket.io/lib/client.js', [], false, true);
    wp_register_script('angular', get_stylesheet_directory_uri() . '/bower_components/angular/angular' . (WP_DEBUG ? '' : '.min') . '.js', [], false, true);
    wp_register_script('angular', get_stylesheet_directory_uri() . '/bower_components/angular-img-http-src/index.js', [], false, true);
    
    wp_enqueue_script('angularapp', get_stylesheet_directory_uri() . '/app.js', ['angular'], false, true);
}

add_action('wp_footer', 'add_socket_io', PHP_INT_MAX);
function add_socket_io ()
{ ?>
    <script type="text/javascript">
        var socketTestStatusEvent = '<?= getenv('SOCKET_TEST_STATUS_EVENT') ?>';

        var socket = io('//<?= getenv('SOCKET_HOST') ?>:<?= getenv('SOCKET_PORT') ?>');
        socket.on('connect', function () {
            console.log('Connected socket');
        });
    </script>
<?php }
