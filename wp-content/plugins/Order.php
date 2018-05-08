<?php
/*
Plugin Name: Order
Description: Manage Order
Plugin URI: 
Author URI: 
Author: Dhrup Developer
License: Public Domain
Version: 1.1
*/

/**
 * PART 1. Defining Custom Database Table
 * ============================================================================
 *
 * In this part you are going to define custom database table,
 * create it, update, and fill with some dummy data
 *
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 *
 * In case your are developing and want to check plugin use:
 *
 * DROP TABLE IF EXISTS wp_country;
 * DELETE FROM wp_options WHERE option_name = 'custom_table_example_install_data33';
 *
 * to drop table and option
 */

/**
 * $custom_table_example_db_version33 - holds current database version
 * and used on plugin update to sync database tables
 */
global $custom_table_example_db_version33;
$custom_table_example_db_version33 = '1.1'; // version changed from 1.0 to 1.1
global $wpdb;


/**
 * register_activation_hook implementation
 *
 * will be called when user activates plugin first time
 * must create needed database tables
 */

if(($_REQUEST['page']=='order_form')  || ($_REQUEST['page']=='order_detail_form')   )
{
 add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
      function load_admin_styles() {
        wp_enqueue_style( 'admin_css_foo', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' , false, '1.0.0' );
     
      } 

function pw_loading_scripts_wrong() {
  echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
}
add_action('admin_head', 'pw_loading_scripts_wrong');


}
 


function custom_table_example_install33()
{
    global $wpdb;
    global $custom_table_example_db_version33;

    $table_name = $wpdb->prefix . 'member_plans'; // do not forget about tables prefix

              $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      user_name VARCHAR(500) NOT NULL,
      user_email  VARCHAR(500) NOT NULL,
       website_url VARCHAR(500) NOT NULL,
        address text,
         zipcode  VARCHAR(200) NOT NULL,
          card_num  int(11) NOT NULL,
      card_cvc  int(11) NOT NULL,
      card_exp_month   int(11) NOT NULL,
      card_exp_year int(11) NOT NULL,
      item_name varchar(500) NOT NULL,
      item_price varchar(500) NOT NULL,
      item_price_currency varchar(500) NOT NULL,
      paid_amount int(11) NOT NULL,
      txn_id varchar(200) NOT NULL,
      status varchar(200) NOT NULL,
      date varchar(200) NOT NULL,
     
      PRIMARY KEY  (id)
    );";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('custom_table_example_db_version33', $custom_table_example_db_version33);

    /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $custom_table_example_db_version33 variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */
    $installed_ver = get_option('custom_table_example_db_version33');
    if ($installed_ver != $custom_table_example_db_version33) {
        $sql = "CREATE TABLE " . $table_name . " (
           id int(11) NOT NULL AUTO_INCREMENT,
      user_name VARCHAR(500) NOT NULL,
      user_email  VARCHAR(500) NOT NULL,
       website_url VARCHAR(500) NOT NULL,
        address text,
         zipcode  VARCHAR(200) NOT NULL,
          card_num  int(11) NOT NULL,
      card_cvc  int(11) NOT NULL,
      card_exp_month   int(11) NOT NULL,
      card_exp_year int(11) NOT NULL,
      item_name varchar(500) NOT NULL,
      item_price varchar(500) NOT NULL,
      item_price_currency varchar(500) NOT NULL,
      paid_amount int(11) NOT NULL,
      txn_id varchar(200) NOT NULL,
      status varchar(200) NOT NULL,
      date varchar(200) NOT NULL,
      PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('custom_table_example_db_version33', $custom_table_example_db_version33);
    }
}

register_activation_hook(__FILE__, 'custom_table_example_install33');

/**
 * register_activation_hook implementation
 *
 * [OPTIONAL]
 * additional implementation of register_activation_hook
 * to insert some dummy data
 */
function custom_table_example_install_data33()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'order'; // do not forget about tables prefix

   
}

register_activation_hook(__FILE__, 'custom_table_example_install_data33');

/**
 * Trick to update plugin database, see docs
 */
function custom_table_example_update_db_check33()
{
    global $custom_table_example_db_version33;
    if (get_site_option('custom_table_example_db_version33') != $custom_table_example_db_version33) {
        custom_table_example_install33();
    }
}

add_action('plugins_loaded', 'custom_table_example_update_db_check33');

/**
 * PART 2. Defining Custom Table List
 * ============================================================================
 *
 * In this part you are going to define custom table list class,
 * that will display your database records in nice looking table
 *
 * http://codex.wordpress.org/Class_Reference/WP_List_Table
 * http://wordpress.org/extend/plugins/custom-list-table-example/
 */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * custom_table_example_db_version33 class that will display our custom table
 * records in nice table
 */
class custom_table_example_db_version33 extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'order',
            'plural' => 'orders',
        ));
    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     * method name must be like this: "column_[column_name]"
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        return '<em>' . $item['name'] . '</em>';
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_user_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &country=2
        $actions = array(
          /*  'edit' => sprintf('<a href="?page=plans_form&plan_id=%s">%s</a>', $item['plan_id'], __('Edit', 'custom_table_example')),
            'delete' => sprintf('<a href="?page=%s&action=delete&plan_id=%s">%s</a>', $_REQUEST['page'], $item['plan_id'], __('Delete', 'custom_table_example')),*/
             'View' => sprintf('<a href="?page=order_detail_form&id=%s">%s</a>',$item['id'], __('View', 'custom_table_example')),
              
        );
 
        return sprintf('%s %s',
            $item['user_name'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['plan_id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'user_name' => __('User Name', 'custom_table_example'),
             'user_email' => __('User Email', 'custom_table_example'),
             'website_url'=> __('Website Url', 'custom_table_example'),
             'boost1'=> __('A', 'custom_table_example'),
             'boost2'=> __('B', 'custom_table_example'),
             'item_name' =>  __('Selected Package Name', 'custom_table_example'),
             'paid_amount'=>  __('Paid amount', 'custom_table_example'),
             'status'=>  __('Status', 'custom_table_example'),
             'date' => __('Date', 'custom_table_example'),
           
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'user_name' => array('plan_name', true),
             
            
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'order'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['plan_id']) ? $_REQUEST['plan_id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE plan_id IN($ids)");
            }
        }
    }

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'order'; // do not forget about tables prefix

        $per_page = 10; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
     
        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'user_name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        $se=isset($_REQUEST['s'])?$_REQUEST['s']:'';
 
        // $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name $search_val ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

$this->items = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name WHERE  user_name LIKE %s ORDER BY $orderby $order LIMIT %d OFFSET %d", '%' . $wpdb->esc_like($se) . '%', $per_page, $paged), ARRAY_A);

   $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name ");

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => count($this->items), // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}

/**
 * PART 3. Admin page
 * ============================================================================
 *
 * In this part you are going to add admin page for custom table
 *
 * http://codex.wordpress.org/Administration_Menus
 */

/**
 * admin_menu hook implementation, will add pages to list country and to add new one
 */
function custom_table_example_admin_menu33()
{
    add_menu_page(__('order', 'custom_table_example33'), __('Order Details', 'custom_table_example'), 'activate_plugins', 'order', 'custom_table_example_order_page_handler');
    add_submenu_page('order', __('Order List', 'custom_table_example33'), __('Order  List', 'custom_table_example'), 'activate_plugins', 'order', 'custom_table_example_order_page_handler');
   
    
    //add_submenu_page('member_plans', __('Add new Member Plan', 'custom_table_example33'), __('Add New Member Plan', 'custom_table_example'), 'activate_plugins', 'plans_form', 'custom_table_example_plans_form_page_handler');
    add_submenu_page('View Order', __('View Order', 'custom_table_example33'), __('View', 'custom_table_example'), 'activate_plugins', 'order_detail_form', 'custom_table_example_order_detail_form_page_handler');
}

add_action('admin_menu', 'custom_table_example_admin_menu33');

/**
 * List page handler
 *
 * This function renders our custom table
 * Notice how we display message about successfull deletion
 * Actualy this is very easy, and you can add as many features
 * as you want.
 *
 * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
 */
function custom_table_example_order_page_handler()
{
    global $wpdb;

    $table = new custom_table_example_db_version33();
    $table->prepare_items();

    
    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Plan deleted: %d', 'custom_table_example'), count($_REQUEST['plan_id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

   <!-- <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Member Plan List', 'custom_table_example')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=plans_form');?>"><?php _e('Add new', 'custom_table_example')?></a>
                                      <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=member_plans');?>"><?php _e('Reset search', 'custom_table_example')?>                             
                                 </a>
    </h2>-->
    <?php echo $message; ?>

<form id="order-search" method="GET" >

<p class="search-box">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
  <label class="screen-reader-text" for="post-search-input">Search:</label>
  <input type="search" id="post-search-input" name="s" value="">
  <input type="submit" id="search-submit" class="button" value="<?php _e('Search', 'custom_table_example')?>"></p>
</form>
    <form id="plans-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}

/**
 * PART 4. Form for adding andor editing row
 * ============================================================================
 *
 * In this part you are going to add admin page for adding andor editing items
 * You cant put all form into this function, but in this example form will
 * be placed into meta box, and if you want you can split your form into
 * as many meta boxes as you want
 *
 * http://codex.wordpress.org/Data_Validation
 * http://codex.wordpress.org/Function_Reference/selecountryd
 */

/**
 * Form page handler checks is there some data posted and tries to save it
 * Also it renders basic wrapper in which we are callin meta box render
 */
/*function custom_table_example_order_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'member_plans'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'plan_id' => 0,
        'plan_name' => '',
         'plan_detail_1' => '',
          'plan_detail_2' => '',
           'plan_detail_3' => '',
            'plan_detail_4' => '',
             'plan_detail_5' => '',
          'plan_price' => '',
           'added_date' => date('Y-m-d'),
        
    );

    // here we are verifying does this request is post back and have correct nonce
    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = custom_table_example_validate_plans($item);
        if ($item_valid === true) {
            if ($item['plan_id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
               
                $item['plan_id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Member Plan was successfully saved', 'custom_table_example');
                } else {
                    $notice = __('There was an error while saving member plan', 'custom_table_example');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('plan_id' => $item['plan_id']));
                
                if ($result) {
                    $message = __('Member Plan was successfully updated', 'custom_table_example');
                } else {
                    $notice = __('There was an error while updating member plan', 'custom_table_example');
                }
            }
        } else {
            // if $item_valid not true it contains error message(s)
            $notice = $item_valid;
        }
    }
    else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['plan_id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE plan_id = %d", $_REQUEST['plan_id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Member Plan not found', 'custom_table_example');
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('plans_form_meta_box', 'Member Plan data', 'custom_table_example_plans_form_meta_box_handler', 'plans', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Add member Plan', 'custom_table_example')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=member_plans');?>"><?php _e('back to list', 'custom_table_example')?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" name="form"  method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
       
        <input type="hidden" name="plan_id" value="<?php echo $item['plan_id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <?php do_meta_boxes('plans', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'custom_table_example')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}
*/
/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
/*function custom_table_example_plans_form_meta_box_handler($item)
{
    ?>

<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_name"><?php _e('Plan Name', 'custom_table_example')?></label>
        </th>
        <td>
            <input id="plan_name" name="plan_name" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_name'])?>"
                   size="50" class="code form_control" required="true" placeholder="<?php _e('Enter Plan name', 'custom_table_example')?>">
        </td>
    </tr>

      <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_detail_1"><?php _e('Plan Details 1', 'custom_table_example')?></label>
        </th>
        <td>
             <input id="plan_detail_1" name="plan_detail_1" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_detail_1'])?>"
                   size="50" class="code form_control" required="true" placeholder="<?php _e('Enter Plan Details', 'custom_table_example')?>">
            
           
        </td>
    </tr>

      <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_detail_2"><?php _e('Plan Details 2', 'custom_table_example')?></label>
        </th>
        <td>
             <input id="plan_detail_2" name="plan_detail_2" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_detail_2'])?>"
                   size="50" class="code form_control" required="true" placeholder="<?php _e('Enter Plan Details', 'custom_table_example')?>">
            
           
        </td>
    </tr>

      <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_detail_3"><?php _e('Plan Details 3', 'custom_table_example')?></label>
        </th>
        <td>
             <input id="plan_detail_3" name="plan_detail_3" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_detail_3'])?>"
                   size="50" class="code form_control"  placeholder="<?php _e('Enter Plan Details', 'custom_table_example')?>">
            
           
        </td>
    </tr>

      <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_detail_4"><?php _e('Plan Details 4', 'custom_table_example')?></label>
        </th>
        <td>
             <input id="plan_detail_4" name="plan_detail_4" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_detail_4'])?>"
                   size="50" class="code form_control"  placeholder="<?php _e('Enter Plan Details', 'custom_table_example')?>">
            
           
        </td>
    </tr>

      <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_detail_5"><?php _e('Plan Details 5', 'custom_table_example')?></label>
        </th>
        <td>
             <input id="plan_detail_5" name="plan_detail_5" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_detail_5'])?>"
                   size="50" class="code form_control"  placeholder="<?php _e('Enter Plan Details', 'custom_table_example')?>">
            
           
        </td>
    </tr>
     <tr class="form-field">
        <th valign="top" scope="row">
            <label for="plan_price"><?php _e('Plan Price', 'custom_table_example')?></label>
        </th>
        <td>
            <input id="plan_price" name="plan_price" type="text" style="width: 95%" value="<?php echo esc_attr($item['plan_price'])?>"
                   size="50" required="true"  class="code form_control" required="true" placeholder="<?php _e('Enter Plan price', 'custom_table_example')?>">
        </td>
    </tr>
    </tbody>
</table>
<?php
}*/
function custom_table_example_order_detail_form_page_handler($item)
{
   global $wpdb;
    $table_name = $wpdb->prefix . 'order'; // do not forget about tables prefix

    $message = '';
    $notice = '';
     $item = $default;
      if (isset($_REQUEST['id'])) {
    $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
  }
 ?>

  <div class="wrap">
   
   <div id="normal-sortables" class="meta-box-sortables">
    <div id="plans_form_meta_box" class="postbox ">
      <h2 class="hndle">Order Details
     <!--<a class="add-new-h2" align='right' href="http://imdhrup.com/wordpress/dhoct2017_280/wp-admin/admin.php?page=member_plans">All Member Plan</a> </h2>-->
 
      <div class="inside">
        <div class="row">
          <div class="col-md-12 col-xs-12">
      <div class="col-md-4 col-sm-4"></div>
    <!-- edit form column -->
    <div class="col-md-8 col-sm-6 col-xs-12 personal-info">
     
      <h3>Order information</h3>
     <?php
      if(!empty($item['user_name']))
      {
     ?>
        <div class="form-group">
          <label class="col-lg-3 control-label">User Name:</label>
          <div class="col-lg-8">
          
              
           <label for="user_name"> <?php echo esc_attr($item['user_name'])?></label>
           
          </div>
        </div>
<?php
}
 if(!empty($item['user_email']))
{
?>
         <div class="form-group">
          <label class="col-lg-3 control-label">User email:</label>
          <div class="col-lg-8">
          
              
           <label for="user_email"> <?php echo esc_attr($item['user_email'])?></label>
           
          </div>
        </div>
<?php
}
 if(!empty($item['website_url']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Website Url:</label>
          <div class="col-lg-8">
          
              
           <label for="website_url"> <?php echo esc_attr($item['website_url'])?></label>
           
          </div>
        </div>
<?php
}
if(!empty($item['boost1']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Boost A:</label>
          <div class="col-lg-8">
          
              
           <label for="boost1"> <?php echo esc_attr($item['boost1'])?></label>
           
          </div>
        </div>
<?php
}
if(!empty($item['boost2']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Boost B:</label>
          <div class="col-lg-8">
          
              
           <label for="boost2"> <?php echo esc_attr($item['boost2'])?></label>
           
          </div>
        </div>
<?php
}
 if(!empty($item['address']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Address:</label>
          <div class="col-lg-8">
            <label for="address"> <?php echo esc_attr($item['address'])?></label>
          </div>
        </div>
<?php
}
if(!empty($item['zipcode']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Zipcode:</label>
          <div class="col-lg-8">
            <label for="zipcode"> <?php echo esc_attr($item['zipcode'])?></label>
          </div>
        </div>
        <?php
    }
    if(!empty($item['card_num']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Card Number:</label>
          <div class="col-lg-8">
            <label for="card_num"> <?php echo esc_attr($item['card_num'])?></label>
          </div>
        </div>
        <?php
    }
      if(!empty($item['card_cvc']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Card CVC:</label>
          <div class="col-lg-8">
            <label for="card_cvc"> <?php echo esc_attr($item['card_cvc'])?></label>
          </div>
        </div>
        <?php
    }
      if(!empty($item['card_exp_month']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Card Expiry Month:</label>
          <div class="col-lg-8">
            <label for="card_exp_month"> <?php echo esc_attr($item['card_exp_month'])?></label>
          </div>
        </div>
        <?php
    }
    if(!empty($item['card_exp_year']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Card Expiry Year:</label>
          <div class="col-lg-8">
            <label for="card_exp_year"> <?php echo esc_attr($item['card_exp_year'])?></label>
          </div>
        </div>
        <?php
    }
    if(!empty($item['item_name']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Package Name:</label>
          <div class="col-lg-8">
            <label for="item_name"> <?php echo esc_attr($item['item_name'])?></label>
          </div>
        </div>
        <?php
    }
     if(!empty($item['item_price']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Package Price:</label>
          <div class="col-lg-8">
            <label for="item_price"> <?php echo esc_attr($item['item_price'])?></label>
          </div>
        </div>
        <?php
    }
     if(!empty($item['paid_amount']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Paid Amount:</label>
          <div class="col-lg-8">
            <label for="paid_amount"> <?php echo esc_attr($item['paid_amount'])?></label>
          </div>
        </div>
        <?php
    }
         if(!empty($item['txn_id']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Transaction ID:</label>
          <div class="col-lg-8">
            <label for="txn_id"> <?php echo esc_attr($item['txn_id'])?></label>
          </div>
        </div>
        <?php
    }
       if(!empty($item['status']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Status:</label>
          <div class="col-lg-8">
            <label for="status"> <?php echo esc_attr($item['status'])?></label>
          </div>
        </div>
        <?php
    }
       if(!empty($item['date']))
{
?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Date:</label>
          <div class="col-lg-8">
            <label for="date"> <?php echo esc_attr($item['date'])?></label>
          </div>
        </div>
        <?php
    }
?>
        
    </div>
          </div>
        </div>
      </div>
    </div>
   </div>
         
    </div>
     

  <?php

}

/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
/*function custom_table_example_validate_plans($item)
{
    $messages = array();

    if (empty($item['plan_name'])) $messages[] = __('Member Plan Name is required', 'custom_table_example');

    
     //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    if(!empty($item['plan_price']) && !preg_match('/[0-9]+/', $item['plan_price'])) $messages[] = __('Plan Price must be number');
    //...

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}*/

/**
 * Do not forget about translating your plugin, use __('english string', 'your_uniq_plugin_name') to retrieve translated string
 * and _e('english string', 'your_uniq_plugin_name') to echo it
 * in this example plugin your_uniq_plugin_name == custom_table_example
 *
 * to create translation file, use poedit FileNew catalog...
 * Fill name of project, add "." to path (ENSURE that it was added - must be in list)
 * and on last tab add "__" and "_e"
 *
 * Name your file like this: [my_plugin]-[ru_RU].po
 *
 * http://codex.wordpress.org/Writing_a_Plugin#Internationalizing_Your_Plugin
 * http://codex.wordpress.org/I18n_for_WordPress_Developers
 */
function custom_table_example_languages33()
{
    load_plugin_textdomain('custom_table_example', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'custom_table_example_languages33');
