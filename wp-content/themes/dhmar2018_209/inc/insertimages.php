

<script type="text/javascript">

$(document).ready( function(){
	
	$("#insert-media-button").click(function(){ $("#wp-uploader-id-2-hetal").show();
		$(".librarylistiamges").each(function(){
			var imgurl =$(this).attr('imgurl');
			alert(imgurl);
			
			var alliamges = $("#hetal_site_background_images").val();
			if(alliamges.indexOf(imgurl) != -1)
			{
				$(this).addClass(' selected details ');
				var oldval = parseInt($("#selected_count").html().replace(" selected",""))+1; 
				$("#selected_count").html(oldval+" selected"); 
			}
		});
	});
	
	
	$(".media-modal-close").click(function(){ $("#wp-uploader-id-2-hetal").hide(); });


	$(".attachment").click(function(){
			var oldval = $("#hetal_site_background_images").val();
			
		//alert(oldval);
		
			var thisimgurl = $(this).attr("imgurl");
			if($(this).hasClass("selected"))
			{
				$(this).removeClass("selected");
				$(this).removeClass("details");
				var oldval = oldval.replace($(this).attr("imgurl")+",","");
			}
			else
			{
				$(this).addClass("selected");
				$(this).addClass("details");
				var oldval = oldval+$(this).attr("imgurl")+","; 
			}
		
		
		//alert(oldval);
		
		var oldvalarray = oldval.split(","); 
		
		
		var count = oldvalarray.length;
		$("#hetal_site_background_images").val(oldval);
		$("#selected_count").html((count-1)+" selected"); 
	});
	
	
	
	
	$(".media-button-insert").click(function(){
		
		var alliamges = $("#hetal_site_background_images").val();
		//alert(alliamges);
		
		var alliamgesarray = alliamges.split(","); 
		var count = alliamgesarray.length;
		//alert(count);

		$("#attachmentslistview").html("");
		$.each(alliamgesarray , function(i, val) { 
			//alert(alliamgesarray[i]);
			if(alliamgesarray[i] != "")
			{
				$("#attachmentslistview").append('<li class=" selection save-ready" style="width: 24.3% !important;margin-top: 10px;padding: 2px;"><div class="attachment-preview type-image subtype-jpeg landscape"><div class="thumbnail"><div class="centered"><img draggable="false" <img draggable="false"  height="180" width="180" src="'+alliamgesarray[i]+'"></div></div></div></li>');
			}
		});
		$("#wp-uploader-id-2-hetal").hide();
	});





});

</script>



<?php
/*
$images = get_children( 'post_type=attachment&post_mime_type=image' );
if ( empty($images) ) {
	// no attachments here
} else {
	foreach ( $images as $image ) {
		echo wp_get_attachment_image( $image->ID, $size='thumbnail' );
		echo 'post date' . $image->post_date;

	}
}
*/
?>


<div tabindex="0" id="wp-uploader-id-2-hetal" class="supports-drag-drop" style="display: none;">
  <div class="media-modal wp-core-ui">
    <a title="Close" href="#" class="media-modal-close"><span class="media-modal-icon"></span></a>
    <div class="media-modal-content">
      <div class="media-frame wp-core-ui" id="__wp-uploader-id-0">
      
        <div class="media-frame-menu">
          <div class="media-menu">
          	<a href="#" class="media-menu-item active">Select Images</a>
          	<a href="media-new.php" class="media-menu-item">Insert Media</a>.
<!--      	<a href="#" class="media-menu-item">Set Featured Image</a>
            <div class="separator"></div>
            <a href="#" class="media-menu-item">Insert from URL</a>
-->          </div>
        </div>


        <div class="media-frame-title">
          <h1>Select Images</h1>
        </div>
        <div class="media-frame-router">
          <div class="media-router"><!--<a href="#" class="media-menu-item">Upload Files</a>--><a href="#" class="media-menu-item active">Media Library</a>
          </div>
        </div>
        <div class="media-frame-content">
          <div class="attachments-browser">
            <div class="media-toolbar">
              <!--<div class="media-toolbar-secondary">
                <select class="attachment-filters">
                  <option value="all">All media items</option>
                  <option value="uploaded">Uploaded to this post</option>
                  <option value="image">Images</option>
                  <option value="audio">Audio</option>
                  <option value="video">Video</option>
                </select>
              </div>
              <div class="media-toolbar-primary">
                <input type="search" placeholder="Search" class="search">
              </div>-->
            </div>
            
            <ul class="attachments ui-sortable ui-sortable-disabled" style="right: 0 !important;" id="__attachments-view-54">
              
              <?php
				$images = get_children( 'post_type=attachment&post_mime_type=image' );
				if(empty($images)){
					// no attachments here
				} else {
					foreach ( $images as $image ) {
						//echo wp_get_attachment_image( $image->ID, $size='thumbnail' );
						//echo 'post date' . $image->post_date;
						?>
                            <li class="attachment save-ready librarylistiamges" imgurl="<?php echo $image->guid;?>">
                                <div class="attachment-preview type-image subtype-jpeg landscape">
                                  <div class="thumbnail">
                                    <div class="centered">
                                     <?php echo wp_get_attachment_image( $image->ID, $size='thumbnail' );?>
                                    </div>
                                  </div>
                                  <a title="Deselect" href="#" class="check">
                                    <div class="media-modal-icon"></div>
                                  </a>
                                </div>
                            </li>
                        <?php
					}
				}
			  ?>
            </ul>
            
       <!-- <div class="media-sidebar">
              <div class="media-uploader-status" style="display: none;">
                <h3>Uploading</h3>
                <a href="#" class="upload-dismiss-errors">Dismiss Errors</a>

                <div class="media-progress-bar">
                  <div></div>
                </div>
                <div class="upload-details">
                  <span class="upload-count">
				<span class="upload-index"></span> / <span class="upload-total"></span>
                  </span>
                  <span class="upload-detail-separator">&ndash;</span>
                  <span class="upload-filename"></span>
                </div>
                <div class="upload-errors"></div>
              </div>
              <div class="attachment-details save-ready">
                <h3>
			Attachment Details
			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved">Saved.</span>
			</span>
		</h3>
                <div class="attachment-info">
                  <div class="thumbnail">

                    <img draggable="false" src="http://localhost/one_page1/wp-content/uploads/2014/04/portrait4-300x200.jpg">

                  </div>
                  <div class="details">
                    <div class="filename">portrait4.jpg</div>
                    <div class="uploaded">April 15, 2014</div>



                    <div class="dimensions">1500 × 1000</div>



                    <a target="_blank" href="http://localhost/one_page1/wp-admin/post.php?post=13&amp;action=edit&amp;image-editor" class="edit-attachment">Edit Image</a>
                    <a href="#" class="refresh-attachment">Refresh</a>





                    <a href="#" class="delete-attachment">Delete Permanently</a>


                    <div class="compat-meta">

                    </div>
                  </div>
                </div>


                <label data-setting="title" class="setting">
                  <span>Title</span>
                  <input type="text" value="portrait4">
                </label>
                <label data-setting="caption" class="setting">
                  <span>Caption</span>
                  <textarea></textarea>
                </label>

                <label data-setting="alt" class="setting">
                  <span>Alt Text</span>
                  <input type="text" value="">
                </label>

                <label data-setting="description" class="setting">
                  <span>Description</span>
                  <textarea></textarea>
                </label>
              </div>
              <form class="compat-item"></form>
              <div class="attachment-display-settings">
                <h3>Attachment Display Settings</h3>


                <label class="setting">
                  <span>Alignment</span>
                  <select data-user-setting="align" data-setting="align" class="alignment">

                    <option value="left">
                      Left</option>
                    <option value="center">
                      Center</option>
                    <option value="right">
                      Right</option>
                    <option selected="" value="none">
                      None</option>
                  </select>
                </label>


                <div class="setting">
                  <label>

                    <span>Link To</span>


                    <select data-user-setting="urlbutton" data-setting="link" class="link-to">


                      <option selected="" value="file">


                        Media File
                      </option>
                      <option value="post">

                        Attachment Page
                      </option>

                      <option value="custom">
                        Custom URL</option>
                      <option value="none">
                        None</option>

                    </select>
                  </label>
                  <input type="text" data-setting="linkUrl" class="link-to-custom" readonly="" style="display: inline;">
                </div>




                <label class="setting">
                  <span>Size</span>
                  <select data-user-setting="imgsize" data-setting="size" name="size" class="size">

                    <option value="thumbnail">
                      Thumbnail &ndash; 150 × 150
                    </option>


                    <option value="medium">
                      Medium &ndash; 300 × 200
                    </option>


                    <option value="large">
                      Large &ndash; 625 × 416
                    </option>


                    <option selected="selected" value="full">
                      Full Size &ndash; 1500 × 1000
                    </option>

                  </select>
                </label>

              </div>
            </div> -->
            
          </div>
        </div>
        <div class="media-frame-toolbar">
          <div class="media-toolbar">
            <div class="media-toolbar-secondary">
              <div class="media-selection one">
                <div class="selection-info">
                  <span class="count" id="selected_count">0 selected</span>
<!--                  <a href="#" class="edit-selection">Edit</a>
                  <a href="#" class="clear-selection">Clear</a>
-->                </div>


<!--
                <div class="selection-view">
                  <ul class="attachments ui-sortable" id="__attachments-view-44">
                    <li class="attachment selection details selected save-ready">
                      <div class="attachment-preview type-image subtype-jpeg landscape">
                        <div class="thumbnail">
                          <div class="centered">
                            <img draggable="false" src="http://localhost/one_page1/wp-content/uploads/2014/04/portrait4-300x200.jpg">
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
-->                
              </div>
            </div>
            <div class="media-toolbar-primary"><a href="#" class="button media-button button-primary button-large media-button-insert">Insert Images</a>
            </div>
          </div>
        </div>
        
        
        
      </div>
    </div>
  </div>
  <div class="media-modal-backdrop"></div>
</div>