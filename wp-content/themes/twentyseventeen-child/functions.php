<?php
require_once(__DIR__ . '/api.php');

add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles ()
{
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('wp_footer', 'add_socket_io');
function add_socket_io ()
{ ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.4/socket.io.min.js"></script>
    <script type="text/javascript">
        var socket = io('//<?= getenv('SOCKET_HOST') ?>:<?= getenv('SOCKET_PORT') ?>');
        socket.on('connect', function () {
            console.log('Connected socket');
        });
    </script>
<?php }

add_shortcode('gtmetrix_test_button', 'get_gtmetrix_test_button');
function get_gtmetrix_test_button ($atts)
{ ob_start(); ?>
    <button style="text-transform: uppercase;" onclick="sendTest()">Test</button>
    <script type="text/javascript">
        var urlSource = '<?= isset($atts['url-source']) ? $atts['url-source'] : '' ?>';
        var urlSourceValue = '';
        function sendTest () {
            if (jQuery(urlSource).length) {
                urlSourceValue = jQuery(urlSource).val();
                if (urlSourceValue) {
                    jQuery.post('//' + location.hostname + '/wp-json/api/v1/test',{
                        url: urlSourceValue,
                        id: socket.id
                    }).done(function (response) {
                        if (response.success) {
                            // Loading
                            startSocketIoListener();
                        } else {
                            alert(reponse.errors[0] + '. Please try again');
                        }
                    }).fail(function () {
                        alert('Failed to submit your request, please try again');
                    });
                } else {
                    alert('Please fill in the URL field');
                }
            } else {
                console.log(urlSource + ' doesn\'t exist');
            }
        }

        function startSocketIoListener () {
            socket.on('<?= getenv('SOCKET_TEST_STATUS_EVENT') ?>:' + socket.id, function (data) {
                alert(JSON.stringify(data));
            });
        }
    </script>
<?php return ob_get_clean(); }

