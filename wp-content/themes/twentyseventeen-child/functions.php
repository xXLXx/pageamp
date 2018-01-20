<?php
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles ()
{
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('do_footer', 'add_socket_io');
function add_socket_io ()
{ ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
    <script type="text/javascript">
        var socket = io();
    </script>
<?php }

add_shortcode('gtmetrix_test_button', 'get_gtmetrix_test_button');
function get_gtmetrix_test_button ($atts)
{ ob_start(); ?>
    <button style="text-transform: uppercase;" onclick="sendTest()">Test</button>
    <script type="text/javascript">
        var urlSource = '<?= isset($atts['url-source']) ? $atts['url-source'] : '' ?>';
        function sendTest () {
            if (jQuery(urlSource).length) {
                var urlSourceValue = jQuery(urlSource).val();
                if (urlSourceValue) {
                    jQuery.post({
                        url: 'https://hud-e.iron.io/api/<?= getenv('IRON_PROJECTID') ?>/tasks',
                        headers: {
                            Authorization: 'OAuth <?= getenv('IRON_TOKEN') ?>'
                        },
                        data: {
                            code_name: '<?= getenv('IRON_STATUSWORKER_NAME') ?>',
                            payload: {
                                url: urlSourceValue
                            }
                        }
                    }).done(function () {
                        startSocketIoListener();
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
            socket.on('<?= getenv('SOCKET_TEST_STATUS_EVENT') ?>', function (data) {
                alert(JSON.stringify(data));
            });
        }
    </script>
<?php return ob_get_clean(); }