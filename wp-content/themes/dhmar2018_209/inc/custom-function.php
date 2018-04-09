<?php
/**
 * Implement a custom header for Twenty Thirteen 
 *
 * @link http://codex.wordpress.org/Custom_Headers 
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/**
 * Set up the WordPress core custom header arguments and settings.
 *
 * @uses add_theme_support() to register support for 3.4 and up.
 * @uses theme_header_style() to style front-end.
 * @uses theme_admin_header_style() to style wp-admin form.
 * @uses theme_admin_header_image() to add custom markup to wp-admin form.
 * @uses register_default_headers() to set up the bundled header images.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
 
function include_core_jqueryfile()
{
?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
	<script type="text/javascript">
	
		$(document).ready( function($){
			
			 // postbox click start
			 $(".menudiv").click(function(){  
				 
				var rowid = $(this).parent('.postbox').attr("rowid");
				if($(this).parent('.postbox').hasClass("closed"))
				{
					$(this).parent('.postbox').removeClass("closed");
					$("#theme_admin_last_open_tab_no").val(rowid);
				}
				else
				{
					$(this).parent('.postbox').addClass("closed");
					$("#theme_admin_last_open_tab_no").val("");
				}				
				
			 });

		}); 
	
    </script>
    
<?php
}

function setup_theme_admin_menus() {  
    add_submenu_page('themes.php', 'Front Page Elements', 'Theme Settings', 'manage_options',  'theme-settings', 'theme_home_page_settings');   
}  
add_action("admin_menu", "setup_theme_admin_menus");


function theme_home_page_settings() {
	include_core_jqueryfile();
	
	
	
	if(isset($_POST["update_settings"])) {

	    // Website Settings
		$theme_photoone_end_image = esc_attr($_POST['theme_photoone_hidden']);
		if($_FILES['theme_photoone_end_image']['error'] <= 0)
		{
			$types = array("image/png","image/gif","image/jpeg","image/jpg","image/pjpeg","image/x-png","image/png","image/xbm","image/jp2","image/tiff","image/bmp");
			if(in_array($_FILES["theme_photoone_end_image"]["type"],$types))
			{
				$tmp_name = $_FILES["theme_photoone_end_image"]["tmp_name"];
				$imagename = rand(1,9999999999).str_replace(' ','_',$_FILES["theme_photoone_end_image"]["name"]);
				$upload_dir = wp_upload_dir();
				$uplodepath = $upload_dir['path']."/".$imagename;
				move_uploaded_file($tmp_name, $uplodepath);
				$theme_photoone_end_image = $upload_dir['url']."/".$imagename;
			}
		}  
		update_option("theme_photoone_about", $theme_photoone_end_image);
		
		$theme_phototwo_end_image = esc_attr($_POST['theme_phototwo_hidden']);
		if($_FILES['theme_phototwo_end_image']['error'] <= 0)
		{
			$types = array("image/png","image/gif","image/jpeg","image/jpg","image/pjpeg","image/x-png","image/png","image/xbm","image/jp2","image/tiff","image/bmp");
			if(in_array($_FILES["theme_phototwo_end_image"]["type"],$types))
			{
				$tmp_name = $_FILES["theme_phototwo_end_image"]["tmp_name"];
				$imagename = rand(1,9999999999).str_replace(' ','_',$_FILES["theme_phototwo_end_image"]["name"]);
				$upload_dir = wp_upload_dir();
				$uplodepath = $upload_dir['path']."/".$imagename;
				move_uploaded_file($tmp_name, $uplodepath);
				$theme_phototwo_end_image = $upload_dir['url']."/".$imagename;
			}
		}  
		update_option("theme_phototwo_about", $theme_phototwo_end_image);
		
		$theme_photothree_end_image = esc_attr($_POST['theme_photothree_hidden']);
		if($_FILES['theme_photothree_end_image']['error'] <= 0)
		{
			$types = array("image/png","image/gif","image/jpeg","image/jpg","image/pjpeg","image/x-png","image/png","image/xbm","image/jp2","image/tiff","image/bmp");
			if(in_array($_FILES["theme_photothree_end_image"]["type"],$types))
			{
				$tmp_name = $_FILES["theme_photothree_end_image"]["tmp_name"];
				$imagename = rand(1,9999999999).str_replace(' ','_',$_FILES["theme_photothree_end_image"]["name"]);
				$upload_dir = wp_upload_dir();
				$uplodepath = $upload_dir['path']."/".$imagename;
				move_uploaded_file($tmp_name, $uplodepath);
				$theme_photothree_end_image = $upload_dir['url']."/".$imagename;
			}
		}  
		update_option("theme_photothree_about", $theme_photothree_end_image);

        
        $theme_photothree_end_image = esc_attr($_POST['theme_photofour_hidden']);
		if($_FILES['theme_photofour_end_image']['error'] <= 0)
		{
			$types = array("image/png","image/gif","image/jpeg","image/jpg","image/pjpeg","image/x-png","image/png","image/xbm","image/jp2","image/tiff","image/bmp");
			if(in_array($_FILES["theme_photofour_end_image"]["type"],$types))
			{
				$tmp_name = $_FILES["theme_photofour_end_image"]["tmp_name"];
				$imagename = rand(1,9999999999).str_replace(' ','_',$_FILES["theme_photofour_end_image"]["name"]);
				$upload_dir = wp_upload_dir();
				$uplodepath = $upload_dir['path']."/".$imagename;
				move_uploaded_file($tmp_name, $uplodepath);
				$theme_photofour_end_image = $upload_dir['url']."/".$imagename;
			}
		}  
		update_option("theme_photofour_about", $theme_photofour_end_image);


		
        // Footer and Social Media Settings -->

	    $theme_cpy_content = esc_attr($_POST["theme_cpy_content"]);   
		update_option("theme_cpy_content", $theme_cpy_content);

        $msg = '<div class="updated below-h2" id="message"><p>Theme Settings updated.</p></div>';
	} 
?>
<style>
	
	.form-table img{
		background:#333333;
		border: 1px solid #BDBDBD;
		border-radius: 3px;
		padding: 10px;
		width: 50%;
        height: 20px;
        margin-top:5px;
	}
    #dashboard-widgets h3{
        margin: 7px 12px 8px !important;
        font-weight:bold;
    }
    .postbox .hndle{
        border:none !important;
    }
    .submit {
        padding:0 !important;
        margin-top:0px !important;
    }
    .wrap {
        width:60%;
    }
</style>
  <link href="<?php echo esc_url(home_url('/'));?>wp-admin/load-styles.php?c=1&dir=ltr&load=dashicons,admin-bar,buttons,media-views,wp-admin,wp-auth-check&ver=3.8.3" rel="stylesheet">
  <link href="<?php echo esc_url(home_url('/'));?>wp-admin/css/colors.min.css?ver=3.8.3" rel="stylesheet">

<?php

include('insertimages.php');
$categories = get_categories();
?>

    
    <div class="wrap">
        <?php screen_icon('themes'); ?> 
 		<h2>Theme Setting</h2>
        <?php echo $msg;?>
        <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="update_settings" value="Y" />
        <input type="hidden" name="theme_admin_last_open_tab_no" id="theme_admin_last_open_tab_no" value="<?php echo get_option('theme_admin_last_open_tab_no'); ?>" />
        
        
        <div id="dashboard-widgets-wrap">
        	<div id="dashboard-widgets" class="metabox-holder">
                <div id="postbox-container-1" class="postbox-container" style="width: 95%;">
                	<div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    
            <!-- Theme Setting Start -->
                    
            <div id="dashboard_right_now" rowid="row11" class="postbox  <?php if(get_option('theme_admin_last_open_tab_no') != "row11") { echo 'closed'; } ?>"> <!-- closed -->
            <div class="handlediv menudiv" title="Click to toggle"><br></div>
            <h3 class="hndle menudiv " style="cursor: pointer !important;">
            <span>Website Setting</span>&nbsp;
            </h3>
            <div class="inside">
            <div class="main">
            
                <table class="form-table">
                    <tbody>
                    
                    <tr class="form-field form-required">
                        <th scope="row"><h4>Website Header Logo :</h4></th>
                        <td>
                            <input type="hidden" name="theme_photoone_hidden" value="<?php echo get_option("theme_photoone_about");?>" />
                            <input type="file" name="theme_photoone_end_image"  />
                            <div <?php if(get_option("theme_photoone_about") == "") { echo 'style="display:none";';}?>>
                                <a href="<?php echo get_option("theme_photoone_about");?>" target="_blank">
                                    <img src="<?php echo get_option("theme_photoone_about");?>" height="50" width="70">
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="form-field form-required">
                        <th scope="row"><h4>Website Footer Logo :</h4></th>
                        <td>
                            <input type="hidden" name="theme_phototwo_hidden" value="<?php echo get_option("theme_phototwo_about");?>" />
                            <input type="file" name="theme_phototwo_end_image"  />
                            <div <?php if(get_option("theme_phototwo_about") == "") { echo 'style="display:none";';}?>>
                                <a href="<?php echo get_option("theme_phototwo_about");?>" target="_blank">
                                    <img src="<?php echo get_option("theme_phototwo_about");?>" height="50" width="70">
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="form-field form-required">
                        <th scope="row"><h4>Checkout Page Logo :</h4></th>
                        <td>
                            <input type="hidden" name="theme_photothree_hidden" value="<?php echo get_option("theme_photothree_about");?>" />
                            <input type="file" name="theme_photothree_end_image"  />
                            <div <?php if(get_option("theme_photothree_about") == "") { echo 'style="display:none";';}?>>
                                <a href="<?php echo get_option("theme_photothree_about");?>" target="_blank">
                                    <img src="<?php echo get_option("theme_photothree_about");?>" height="50" width="70">
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="form-field form-required">
                        <th scope="row"><h4>Conversion Page Logo :</h4></th>
                        <td>
                            <input type="hidden" name="theme_photofour_hidden" value="<?php echo get_option("theme_photofour_about");?>" />
                            <input type="file" name="theme_photofour_end_image"  />
                            <div <?php if(get_option("theme_photofour_about") == "") { echo 'style="display:none";';}?>>
                                <a href="<?php echo get_option("theme_photofour_about");?>" target="_blank">
                                    <img src="<?php echo get_option("theme_photofour_about");?>" height="50" width="70">
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
            </div>
            </div>
            
            
            <div id="dashboard_right_now" rowid="row14" class="postbox <?php if(get_option('theme_admin_last_open_tab_no') != "row14") { echo 'closed'; } ?>"> <!-- closed -->
            <div class="handlediv menudiv" title="Click to toggle"><br></div>
            <h3 class="hndle menudiv " style="cursor: pointer !important;">
            <span>Footer Setting </span>&nbsp;
            </h3>
            <div class="inside">
            <div class="main">
            <span style="font-size: 12px;color: #cc181e;">Note : Please, Leave blank for hide.</span><p class="description">Use your full social url.</p>
                <table class="form-table">
                    <tbody>
                   
                    <tr class="form-field">
                        <th scope="row"><h4> CopyRight :</h4></th>
                        <td><input type="text" name="theme_cpy_content" value="<?php echo get_option("theme_cpy_content");?>" placeholder="Enter CopyRight Content"/></td>
                    </tr>
                   
                    </tbody>
                </table>
            </div>
            </div>
            </div> <!-- Social -->
            
                    </div>
                </div>	
            </div>
        </div>
		<p class="submit"><input type="submit" value="Update Theme Settings" class="button button-primary" id="createusersub" name="createuser"></p>
		</form>
    </div>
    <?php 
}
?>