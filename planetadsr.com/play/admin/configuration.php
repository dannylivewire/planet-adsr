<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}
if(isset($_POST["timezone"])) {	
	$result=$settingObj->updateSettings();	
	if($result == 0) {
		?>
        <script>
			alert("Link for <?php echo $_POST["source_type"]; ?> is not valid, no videos found");
		</script>
        <?php
	} else {
		header('Location: welcome.php');
	}
}
$arrayMeasures = $listObj->getMeasuresList();
$arrayTimezones = $listObj->getTimezonesList();
include 'include/header.php';
?>
<script language="javascript" type="text/javascript">
	tmt.validator.patterns.youtubevideo = new RegExp("^http://youtu.be/[a-zA-Z0-9]{11}$");
	tmt.validator.patterns.youtubeplaylist = new RegExp("^http://www.youtube.com/playlist?list=PL[A-Z0-9]{16}$");
	tmt.validator.patterns.httpstart = new RegExp("^http://");
	$(function() {
		<?php
		if($settingObj->getDisplayTitle() == '1') {
			?>
			$('#title_options').css({"display":"block"});
			<?php
		}
		if($settingObj->getDisplayDescription() == '1') {
			?>
			$('#description_options').css({"display":"block"});
			<?php
		}
		if($settingObj->getDisplayThumb() == '1') {
			?>
			$('#thumb_options').css({"display":"block"});
			<?php
		}
		if($settingObj->getMeasureId() == '') {
			?>
			$('#custom_dimensions').css({"display":"block"});
			$('#custom_width').attr("tmt:required","true");
			$('#custom_height').attr("tmt:required","true");
			<?php
		}
		if($settingObj->getDisplayVideolist() == 1) {
			?>
			$('#video_layout_options').fadeIn();
			<?php
		}
		if($settingObj->getManagement() == 1) {
			?>
			showManagement(1);
			<?php
			if($settingObj->getSourceType() == 'channel') {
				?>
				enableChannel();
				<?php
			} else {
				?>
				enablePlaylist();
				<?php
			}
		} else {
			?>
			showManagement(0);
			<?php
		}
		
		
		
		?>
		showDisplayVideos(<?php echo $settingObj->getScheduleVideo(); ?>);
		showLayoutDiv(<?php echo $settingObj->getLayout(); ?>);
		showMarginBetween('<?php echo $settingObj->getVideoNavigation(); ?>');
		showScheduleOptions('<?php echo $settingObj->getShowScheduleList(); ?>');
		showVideoinformationOptions('<?php echo $settingObj->getShowVideoinfo(); ?>');
	});
	
	function showVideoLayoutOptions() {
		if($('input[name=display_videolist]:checked', '#editsettings').val() == 1) {
			$('#video_layout_options').fadeIn();
		} else {
			$('#video_layout_options').fadeOut();
		}
	}
	
	function showCustom(val) {
		if(val=='') {
			$('#custom_dimensions').fadeIn();
			$('#custom_width').attr("tmt:required","true");
			$('#custom_height').attr("tmt:required","true");
		} else {
			$('#custom_dimensions').fadeOut();
			$('#custom_width').val("");
			$('#custom_height').val("");
			$('#custom_width').attr("tmt:required","false");
			$('#custom_height').attr("tmt:required","false");
		}
	}
	function showOptions(element,type) {
		if(element.checked) {
			$('#'+type+'_options').fadeIn();
		} else {
			$('#'+type+'_options').fadeOut();
		}
	}
	
	function enableChannel() {
		$('#link_channel').removeAttr("disabled");
		$('#link_playlist').attr("disabled","disabled");
		//$('#link_playlist').val('');
		$('#link_channel').attr("tmt:required", "true");
		$('#link_playlist').attr("tmt:required", "false");
		$('#link_playlist').prop("name", "");
		$('#link_channel').prop("name", "source_link");
		$('#channel_source').removeAttr("disabled");
		$('#playlist_source').attr("disabled","disabled");
	}
	
	function enablePlaylist() {
		$('#link_playlist').removeAttr("disabled");
		$('#link_channel').attr("disabled","disabled");
		//$('#link_channel').val('');
		$('#link_playlist').attr("tmt:required", "true");
		$('#link_channel').attr("tmt:required", "false");
		$('#link_channel').prop("name", "");
		$('#link_playlist').prop("name", "source_link");
		$('#playlist_source').removeAttr("disabled");
		$('#channel_source').attr("disabled","disabled");
		
	}
	function showManagement(num) {
		if(num == 1) {
			$('#auto_management').fadeIn();
			$('#source_num_videos').attr("tmt:pattern","positiveinteger" );
			$('#source_num_videos').attr("tmt:message","Insert a valid value for num videos");
			$('#source_num_videos').attr("tmt:maxnumber","50");
			$('#source_num_videos').attr("tmt:minnumber","1");			  
			$('#manual_management').fadeOut();
			<?php
			if($settingObj->getSourceType() == 'channel') {
				?>
				enableChannel();
				<?php
			} else {
				?>
				enablePlaylist();
				<?php
			}
			?>
		} else {
			$('#auto_management').fadeOut();
			$('#source_num_videos').removeAttr("tmt:pattern");
			$('#source_num_videos').removeAttr("tmt:message");
			$('#source_num_videos').removeAttr("tmt:maxnumber");
			$('#source_num_videos').removeAttr("tmt:minnumber");
			$('#link_channel').removeAttr("tmt:required");	
			$('#link_playlist').removeAttr("tmt:required");
			//$('#link_channel').attr("tmt:required", "false");	
			//$('#link_playlist').attr("tmt:required", "false");	
			$('#manual_management').fadeIn();
		}
	}
	function showTutorial(num) {
		$('body').prepend('<div id="div_back" onclick="javascript:closeTutorial();"></div>');
		switch(num) {
			case 1:
				$('#modal_tutorial').css({'height':'600px','margin-top':'-300px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go on the <strong>Channel page</strong><br/>2) Copy the username. It is the string after word \"By\"</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/channel.jpg">');
				break;
			case 2:
				$('#modal_tutorial').css({'height':'600px','margin-top':'-300px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go on the <strong>Playlist page</strong><br/>2) click on share<br/>3)  copy the code</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/playlist.jpg">');
				break;
			case 3:
				$('#modal_tutorial').css({'height':'150px','margin-top':'-75px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go to a <strong>channel page</strong><br/>2) copy the numbers after \"channels\" word in the url</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/vimeo_channel.jpg">');
				break;
			case 4:
				$('#modal_tutorial').css({'height':'150px','margin-top':'-75px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go to an <strong>album page</strong><br/>2) copy the numbers after \"album\" word in the url</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/vimeo_album.jpg">');
				break;
		}
		$('#modal_tutorial').fadeIn();
	}
	function changeTutorial(source,type) {
		switch(source) {
			case 'channel':
				switch(type) {
					case '1':
						$('#'+source+'_tutorial').attr('href','javascript:showTutorial(1);');
						break;
					case '2':
						$('#'+source+'_tutorial').attr('href','javascript:showTutorial(3);');
						break;
				}
				break;
			case 'playlist':
				switch(type) {
					case '1':
						$('#'+source+'_tutorial').attr('href','javascript:showTutorial(2);');
						break;
					case '2':
						$('#'+source+'_tutorial').attr('href','javascript:showTutorial(4);');
						break;
				}
				break;
		}
		
	}
	function closeTutorial() {
		$('#modal_tutorial').fadeOut();
		$('#div_back').remove();
	}
	function showDisplayVideos(num) {
		if(num == 1) {
			$('#schedule_display_videos').fadeIn();
		} else {
			$('#schedule_display_videos').fadeOut();
		}
	}
	function showLayoutDiv(radio_value) {
		
		if(radio_value=='1') {
			$('#vertical_layout').fadeIn();
			$('#horizontal_layout').fadeOut();
			$('#vertical_buttons').fadeIn();
			$('#horizontal_buttons').fadeOut();
			$('#video_navigation_horizontal').attr("name","");
			$('#video_navigation_vertical').attr("name","video_navigation");
			$('.videolist_position_horizontal').each(function() {
				$(this).prop('checked',false);
			});
		} else {
			$('#vertical_layout').fadeOut();
			$('#horizontal_layout').fadeIn();
			$('#vertical_buttons').fadeOut();
			$('#horizontal_buttons').fadeIn();
			$('#video_navigation_vertical').attr("name","");
			$('#video_navigation_horizontal').attr("name","video_navigation");
			$('.videolist_position_vertical').each(function() {
				$(this).prop('checked',false);
			});
		}
	}
	
	function showMarginBetween(val) {
		if(val !='separate') {
			$('#margin_between').fadeIn();
		} else {
			$('#margin_between').fadeOut();
		}
	}
	
	function showScheduleOptions(val) {
		if(val == 1) {
			$('#schedule_options').fadeIn();
		} else {
			$('#schedule_options').fadeOut();
		}
	}
	
	function showVideoinformationOptions(val) {
		if(val == 1) {
			$('#videoinformation_options').fadeIn();
		} else {
			$('#videoinformation_options').fadeOut();
		}
	}
</script>
<div id="modal_tutorial" style="display:none">
	<div id="modal_tutorial_explanation"></div>
    <div id="modal_tutorial_image"></div>
</div>


<div id="top_bg_container_all">


    <div id="container_all">
        <div id="container_content">
        
        <?php
        include 'include/menu.php'; 
        ?>
        
        <div id="form_container">
        
        	<form name="editsettings" action="" method="post" id="editsettings" tmt:validate="true" enctype="multipart/form-data"> 
            
               <!-- ================ SITE CONFIGURATION ====================== -->
               
            	<div class="config_title">SITE CONFIGURATION</div>  
                
                
                <div id="label_input">
                    <label for="timezone">Timezone</label><br /><span id="span_description">To play videos according to your timezone</span>
                </div>
                
                <div id="input_box">
                	<select name="timezone" id="timezone">
                    	<?php
						foreach($arrayTimezones as $timezoneid => $timezone) {
						?>
                        	<option value="<?php echo $timezone["timezone_value"]; ?>" <?php if(trim($settingObj->getTimezone()) == trim($timezone["timezone_value"])) { echo 'selected="selected"'; }?>><?php echo $timezone["timezone_name"]; ?></option>
						<?php
						}
						?>
                    </select>
                  
                </div>
		<div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="yt_api_key">Youtube API Key</label><br /><span id="span_description">(insert here the generated API Key in the <a href="https://console.developers.google.com">Google Developers Console</a>)</span>
                </div>
                <div id="input_box">
                    <input type="text" class="inputtext" name="yt_api_key" value="<?php echo $settingObj->getYtApiKey(); ?>" />
                </div>
                
                <div id="rowspace"></div>
                
                
                <!-- ================ PLAYER CONFIGURATION ====================== -->
                  
                <div class="config_title">VIDEO PLAYER LAYOUT</div>
                
                <div id="label_input">
                    <label for="measure_id">Player dimensions</label><br /><span id="span_description">(choose your player dimensions)</span>
                </div>
                
                <div id="input_box">
                	<select name="measure_id" id="measure_id" onchange="javascript:showCustom(this.options[this.selectedIndex].value);" tmt:required="true" tmt:invalidvalue="0" tmt:message="Select player dimensions">
                    	<option value="0">Select a dimension</option>
                        <?php
						foreach($arrayMeasures as $measureId => $measure) {
							?>
                            <option value="<?php echo $measureId; ?>" <?php if($settingObj->getMeasureId() == $measureId) { echo 'selected="selected"'; }?>><?php echo $measure["measure_width"]." x ".$measure["measure_height"]." px"; ?></option>
                            <?php
						}
						?>
                        <option value="" <?php if($settingObj->getMeasureId() == '') { echo 'selected="selected"'; }?>>Custom...</option>
                    </select>
                    <div id="custom_dimensions" style="display:none">
                    	<label for"custom_width">Width</label>
                        <input type="text" name="custom_width" id="custom_width" style="width:100px" tmt:message="Insert player width" tmt:pattern="positiveinteger" value="<?php echo $settingObj->getCustomWidth(); ?>" />&nbsp;px&nbsp;&nbsp;
                        <label for"custom_height">Height</label>
              			<input type="text" name="custom_height" id="custom_height" style="width:100px" tmt:message="Insert player height" tmt:pattern="positiveinteger" value="<?php echo $settingObj->getCustomHeight(); ?>" />&nbsp;px                        
                    </div>
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="autoplay">Autoplay</label><br /><span id="span_description">(choose if your videos will automatically start when a user open the page of your website)</span>
                </div>
                
                <div id="input_box">
                	<input type="radio" name="autoplay" id="autoplay" value="1" <?php if($settingObj->getAutoplay() == '1') { echo 'checked="checked"'; }?>/>&nbsp;YES&nbsp;&nbsp;<input type="radio" name="autoplay" id="autoplay" value="0"  <?php if($settingObj->getAutoplay() == '0' || $settingObj->getAutoplay() == '') { echo 'checked="checked"'; }?>/>&nbsp;NO
                </div>
                
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="show_video_info">Show video information</label><br /><span id="span_description">(choose if you want to show video information above the player)</span>
                </div>
                
                <div id="input_box">
                	<input type="radio" name="show_video_info" id="show_video_info" value="1" <?php if($settingObj->getShowVideoinfo() == '1') { echo 'checked="checked"'; }?> onclick="javascript:showVideoinformationOptions(1);"/>&nbsp;YES&nbsp;&nbsp;<input type="radio" name="show_video_info" id="show_video_info" value="0"  <?php if($settingObj->getShowVideoinfo() == '0' || $settingObj->getShowVideoinfo() == '') { echo 'checked="checked"'; }?> onclick="javascript:showVideoinformationOptions(0);"/>&nbsp;NO
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <div id="videoinformation_options">
                    <div id="label_input">
                        <label for="video_info_font_size">Video information text size</label><br /><span id="span_description">(if you chose to show video info, you can customize it choosing text size in pixels)</span>
                    </div>
                    
                    <div id="input_box">
                        <input type="text" class="number_input_box" name="video_info_font_size" value="<?php echo $settingObj->getVideoInfoFontSize(); ?>" tmt:pattern="positiveinteger" />&nbsp;px
                    </div>
                    
                    <div id="rowspace"></div>
                </div>
                
                
                
                <!-- ================ VIDEO LIST CONFIGURATION ====================== -->
                
                
                <div class="config_title">VIDEOLIST LAYOUT</div>
                <!-- display video list -->
                <div id="label_input">
                    <label for="display_videolist">Display videolist</label><br /><span id="span_description">(choose if you want to show your videos list)</span>
                </div>
                
                <div id="input_box">
                   YES&nbsp;<input type="radio" name="display_videolist" value="1" <?php if($settingObj->getDisplayVideolist() == "1") { echo "checked"; }?> onclick="javascript:showVideoLayoutOptions();"/>&nbsp;&nbsp;
                   NO&nbsp;<input type="radio" name="display_videolist" value="0" <?php if($settingObj->getDisplayVideolist() == "0") { echo "checked"; }?>  onclick="javascript:showVideoLayoutOptions();"/>
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="video_layout_options" style="display:none">
                	<!-- video list layout  -->
                    <div id="label_input">
                        <label for="layout">Videolist layout</label>
                    </div>
                    
                    <div id="input_box">
                        <input type="radio" name="layout" id="layout" value="0" <?php if($settingObj->getLayout() == '0') { echo 'checked="checked"'; }?> onclick="javascript:showLayoutDiv(this.value);"/>&nbsp;Horizontal&nbsp;&nbsp;<input type="radio" name="layout" id="layout" value="1" <?php if($settingObj->getLayout() == '1' || $settingObj->getLayout() == '') { echo 'checked="checked"'; }?>  onclick="javascript:showLayoutDiv(this.value);"/>&nbsp;Vertical
                                         
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <!-- video list position -->
                    <div id="vertical_layout" style="display:none">
                    	<div id="label_input">
                            <label for="layout">Videolist position</label>
                        </div>
                        
                        <div id="input_box">
                            <input type="radio" name="videolist_position" class="videolist_position_vertical" tmt:required="true" tmt:message="Please select a position for the videolist" value="left" <?php if($settingObj->getVideolistPosition() == 'left') { echo 'checked="checked"'; }?> />&nbsp;on the left of the player&nbsp;&nbsp;<input type="radio" class="videolist_position_vertical"  name="videolist_position" value="right" <?php if($settingObj->getVideolistPosition() == 'right') { echo 'checked="checked"'; }?> />&nbsp;on the right of the player
                                             
                        </div>
                        <div id="rowspace"></div>
                        <div id="rowline"></div>
                        <div id="rowspace"></div>
                    </div>
                    <div id="horizontal_layout" style="display:none">
                    	<div id="label_input">
                            <label for="layout">Videolist position</label>
                        </div>
                        
                        <div id="input_box">
                            <input type="radio" name="videolist_position" class="videolist_position_horizontal"  value="top" <?php if($settingObj->getVideolistPosition() == 'top') { echo 'checked="checked"'; }?> />&nbsp;above the player&nbsp;&nbsp;<input type="radio" name="videolist_position"  class="videolist_position_horizontal" value="bottom" <?php if($settingObj->getVideolistPosition() == 'bottom') { echo 'checked="checked"'; }?> />&nbsp;under the player
                                             
                        </div>
                        <div id="rowspace"></div>
                        <div id="rowline"></div>
                        <div id="rowspace"></div>
                    </div>
                    
                    <!-- video list element measures -->
                    <div id="label_input">
                        <label for="videolist_width">Videolist element measures</label><br /><span id="span_description">(choose the measures of your videolist elements)</span>
                    </div>
                    
                    <div id="input_box" style="float: left; width: 300px;">
                    	<div class="input_text float_left">Width:</div>
                       <div class="input_input float_left"><input type="text" class="number_input_box" name="videolist_width" value="<?php echo $settingObj->getVideolistWidth(); ?>" tmt:pattern="positiveinteger" tmt:required="true" tmt:message="Insert a valide number for the videolist element width" />&nbsp;px</div>
                       
                       <div class="cleardiv"></div>
                       
                       <div class="input_text float_left margin_t">Height:</div>
                       <div class="input_input float_left margin_t"><input type="text" class="number_input_box" name="videolist_height" value="<?php echo $settingObj->getVideolistHeight(); ?>" tmt:pattern="positiveinteger" tmt:required="true" tmt:message="Insert a valide number for the videolist element height" />&nbsp;px</div>
                       
                    </div>
                    
                    <div style="float: right"><img src="images/tutorial/measures.jpg"  /></div>
                    <div class="cleardiv"></div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <div id="label_input">
                        <label for="video_padding">Videolist elements padding</label><br /><span id="span_description">(choose padding of the videolist elements)</span>
                    </div>
                    <div id="input_box">
                        <input type="text" class="number_input_box" name="video_padding" value="<?php echo $settingObj->getVideoPadding(); ?>" />&nbsp;px
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <!-- video list margin -->
                    <div id="label_input">
                        <label for="videolist_margin">Videolist margin</label><br /><span id="span_description">(choose the distance between the player and the videolist)</span>
                    </div>
                    
                    <div id="input_box">
                       <input type="text" class="number_input_box" name="videolist_margin" value="<?php echo $settingObj->getVideolistMargin(); ?>" tmt:pattern="positiveinteger" tmt:required="true" tmt:message="Insert a valide number for the videolist margin" />&nbsp;px
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    
                    <!-- display options -->
                    <div id="label_input">
                        <label for="display_options">Display options</label><br /><span id="span_description">(select the elements you want to show for each video in the list)</span>
                    </div>
                    <div id="input_box">
                    	<!-- title -->
                        <div class="float_left input_check"><input type="checkbox" name="display_options[]" id="display_options" value="title" <?php if($settingObj->getDisplayTitle() == '1') { echo 'checked="checked"'; }?> onclick="javascript:showOptions(this,'title');" tmt:minchecked="1" tmt:message="Select at least one display option"/></div>
                        <div class="float_left input_text">Title</div>
                        <div class="cleardiv"></div>
                        
                        <div id="title_options" style="display:none">
                            <div id="label_input" style="font-size: 16px;">
                                <label for="title_chars">Number of chars for the title of the video</label><br /><span id="span_description">(if the title is longer than this value it will be trimmed and ended with "...". Leave this field empty to display the full title)</span>
                            </div>
                            <div id="input_box">
                                <input type="text"  class="number_input_box" name="title_chars" id="title_chars" value="<?php echo $settingObj->getTitleChars(); ?>" tmt:pattern="positiveinteger" tmt:message="Insert a valid number of chars for title"  />
                            </div>
                            
                        </div>
                        
                        <div class="divide_small"></div>
                        <div class="margin_t"></div>
                        
                        <!-- description -->
                        <div class="float_left input_check"><input type="checkbox" name="display_options[]" id="display_options" value="description" <?php if($settingObj->getDisplayDescription() == '1') { echo 'checked="checked"'; }?> onclick="javascript:showOptions(this,'description');" /></div>
                        <div class="float_left input_text">Description</div>
                        <div class="cleardiv"></div>
                        
                        <div id="description_options" style="display:none">
                            <div id="label_input" style="font-size: 16px;">
                                <label for="description_chars">Number of chars for the description of the video</label><br /><span id="span_description">(if the description is longer than this value it will be trimmed and ended with "...". Leave this field empty to display the full description)</span>
                            </div>
                            <div id="input_box">
                                <input type="text"  class="number_input_box" name="description_chars" id="description_chars" value="<?php echo $settingObj->getDescriptionChars(); ?>" tmt:pattern="positiveinteger" tmt:message="Insert a valid number of chars for description" />
                            </div>
                            
                        </div>
                        
                        <div class="divide_small"></div>
                        <div class="margin_t"></div>
                        
                        <!-- thumbnail image -->
                        <div class="float_left input_check"><input type="checkbox" name="display_options[]" id="display_options" value="thumb"  <?php if($settingObj->getDisplayThumb() == '1') { echo 'checked="checked"'; }?> onclick="javascript:showOptions(this,'thumb');"/></div>
                        <div class="float_left input_text_long">Thumbnail image</div>
                        <div class="cleardiv"></div>
                        
                        <div id="thumb_options" style="display:none">
                            <div id="label_input" style=" font-size: 16px;">
                                <label for="thumb_width">Thumbnail image width</label><br /><span id="span_description">(the thumbnail image will be resized according to this width and with a proportional height)</span>
                            </div>
                            <div id="input_box">
                                <input type="text" class="number_input_box" name="thumb_width" id="thumb_width" value="<?php echo $settingObj->getThumbWidth(); ?>"  tmt:pattern="positiveinteger" tmt:message="Insert a valid thumbnail image width" />&nbsp;px
                            </div>
                            
                            
                        </div>
                        
                        <div class="divide_small"></div>
                        <div class="margin_t"></div>
                               
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <div id="label_input">
                        <label for="videolist_font_size">Text size</label><br /><span id="span_description">(insert text size for videolist in pixels)</span>
                    </div>
                    
                    <div id="input_box">
                        <input type="text" class="number_input_box" name="videolist_font_size" value="<?php echo $settingObj->getVideolistFontSize(); ?>" tmt:pattern="positiveinteger" />&nbsp;px
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <!-- Number video to display -->
                    <div id="label_input">
                        <label for="video_num">Number of videos to display</label><br /><span id="span_description">(number of videos displayed in the videolist without scrolling)</span>
                    </div>
                    <div id="input_box">
                        <input type="text" class="number_input_box" id="video_num" name="video_num" value="<?php echo $settingObj->getVideoNum(); ?>" tmt:required="true" tmt:pattern="positiveinteger" tmt:message="Insert a valid number of videos to display">                   
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    
                    
                    <!-- Video list elements background colors -->
                    <div id="label_input">
                        <label for="thumb_bg">Videolist elements background colors</label><br /><span id="span_description">(choose background colors for the differents states of the videolist elements. Use Hexadecimal colors (i.e. #FFFFFF)</span>
                    </div>
                    <div id="input_box">
                    	<div class="input_text_long float_left">Default background:</div>
                        <div class="input_input float_left"><input type="text" class="number_input_box" name="thumb_bg" value="<?php echo $settingObj->getThumbBg(); ?>" maxlength=7/></div>
                        <div class="cleardiv"></div>
                        
                        <div class="margin_t"></div>
                        <div class="input_text_long float_left">Mouse-over background:</div>
                        <div class="input_input float_left"><input type="text" class="number_input_box" name="thumb_bg_hover" value="<?php echo $settingObj->getThumbBgHover(); ?>" maxlength=7/></div>
                        <div class="cleardiv"></div>
                        
                        <div class="margin_t"></div>
                        <div class="input_text_long float_left">Current video background:</div>
                        <div class="input_input float_left"><input type="text" class="number_input_box" name="thumb_bg_sel" value="<?php echo $settingObj->getThumbBgSel(); ?>" maxlength=7 />  </div>   
                        
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    
                    
                </div>
                
                
                <div class="config_title">VIDEOLIST BUTTONS (prev-next)</div>
                 <!-- video navigation -->
                <div id="label_input">
                    <label for="video_navigation">Video navigation</label><br /><span id="span_description">(choose where to put video navigation buttons)</span>
                </div>
                <div id="input_box">
                    <div id="vertical_buttons" style="display:none">
                        <select name="video_navigation" id="video_navigation_vertical" onchange="javascript:showMarginBetween(this.value);">
                            <option value="separate" <?php if($settingObj->getVideoNavigation() == 'separate' && $settingObj->getLayout() == '1') { echo "selected"; } ?>>One at the top and one at the bottom</option>
                            <option value="top" <?php if($settingObj->getVideoNavigation() == 'top' && $settingObj->getLayout() == '1') { echo "selected"; } ?>>Both at the top</option>
                            <option value="bottom" <?php if($settingObj->getVideoNavigation() == 'bottom' && $settingObj->getLayout() == '1') { echo "selected"; } ?>>Both at the bottom</option>
                        </select>
                    </div>
                    <div id="horizontal_buttons" style="display:none">
                        <select name="video_navigation" id="video_navigation_horizontal" onchange="javascript:showMarginBetween(this.value);">
                            <option value="separate" <?php if($settingObj->getVideoNavigation() == 'separate' && $settingObj->getLayout() == '0') { echo "selected"; } ?>>One on the left and one on the right</option>
                            <option value="left" <?php if($settingObj->getVideoNavigation() == 'left' && $settingObj->getLayout() == '0') { echo "selected"; } ?>>Both on the left</option>
                            <option value="right" <?php if($settingObj->getVideoNavigation() == 'right' && $settingObj->getLayout() == '0') { echo "selected"; } ?>>Both on the right</option>
                        </select>
                    </div>                
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <div id="label_input">
                    <label for="button_back_color">Background colors</label><br /><span id="span_description">(choose background colors for the different states of the videos in the carousel. Use Hexadecimal colors (i.e. #FFFFFF)</span>
                </div>
                <div id="input_box">
                    <div class="input_text_long float_left">Default background:</div>
                    <div class="input_input float_left"><input type="text" class="number_input_box" name="button_back_color" value="<?php echo $settingObj->getButtonBackColor(); ?>" maxlength=7/></div>
                    <div class="cleardiv"></div>
                    
                    <div class="margin_t"></div>
                    <div class="input_text_long float_left">Mouse-over background:</div>
                    <div class="input_input float_left"><input type="text" class="number_input_box" name="button_back_color_hover" value="<?php echo $settingObj->getButtonBackColorHover(); ?>" maxlength=7/></div>
                                       
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <div id="label_input">
                    <label for="button_font_size">Text size</label><br /><span id="span_description">(insert text size for buttons in pixels)</span>
                </div>
                
                <div id="input_box">
                    <input type="text" class="number_input_box" name="button_font_size" value="<?php echo $settingObj->getButtonFontSize(); ?>" tmt:pattern="positiveinteger" />&nbsp;px
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <div id="label_input">
                    <label for="button_color">Text colors</label><br /><span id="span_description">(choose text colors for the different states of the videolist buttons. Use Hexadecimal colors (i.e. #FFFFFF)</span>
                </div>
                <div id="input_box">
                    <div class="input_text_long float_left">Default color:</div>
                    <div class="input_input float_left"><input type="text" class="number_input_box" name="button_color" value="<?php echo $settingObj->getButtonColor(); ?>" maxlength=7/></div>
                    <div class="cleardiv"></div>
                    
                    <div class="margin_t"></div>
                    <div class="input_text_long float_left">Mouse-over color:</div>
                    <div class="input_input float_left"><input type="text" class="number_input_box" name="button_color_hover" value="<?php echo $settingObj->getButtonColorHover(); ?>" maxlength=7/></div>
                                       
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="button_padding">Padding</label><br /><span id="span_description">(choose padding for the videolist buttons. If videolist layout is vertical, padding will be applied to top and bottom of the buttons, if videolist layout is horizontal it will be applied to left and right of the buttons)</span>
                </div>
                <div id="input_box">
                	<input type="text" class="number_input_box" name="button_padding" value="<?php echo $settingObj->getButtonPadding(); ?>" />&nbsp;px
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="button_margin_videolist">Margin from videolist</label><br /><span id="span_description">(choose the distance between videolist and buttons. If videolist layout is vertical, margin will be applied to to and bottom of the videolist, , if videolist layout is horizontal  it will be applied to left and right of the videolist)</span>
                </div>
                <div id="input_box">
                	<input type="text" class="number_input_box" name="button_margin_videolist" value="<?php echo $settingObj->getButtonMarginVideolist(); ?>" />&nbsp;px
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="margin_between" style="display:none">
                    <div id="label_input">
                        <label for="button_margin_between">Margin between buttons</label><br /><span id="span_description">(choose the distance between the buttons)</span>
                    </div>
                    <div id="input_box">
                        <input type="text" class="number_input_box" name="button_margin_between" value="<?php echo $settingObj->getButtonMarginBetween(); ?>" />&nbsp;px
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                </div>
                <div class="config_title">VIDEO MANAGEMENT</div>
                <!-- Choose preferred management -->
                <div id="label_input">
                    <label for="management">Choose preferred management</label><br /><span id="span_description">(choose if you want to personally manage your videos or if you prefer the app to do it for you))</span>
                </div>
                <div id="input_box">
                   AUTO&nbsp;<input type="radio" name="management" value="1" <?php if($settingObj->getManagement() == "1") { echo "checked"; }?> onclick="javascript:showManagement(1);"/>&nbsp;&nbsp;
                   MANUAL&nbsp;<input type="radio" name="management" value="0" <?php if($settingObj->getManagement() == "0") { echo "checked"; }?> onclick="javascript:showManagement(0);"/>
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                <!-- Choose the source -->
                <div id="auto_management">
                    <div id="label_input">
                        <label for="source_type">Choose the source</label><br /><span id="span_description">(all videos from a channel or a playlist)</span>
                    </div>
                    <div id="input_box">
                    	<div class="input_check float_left"><input type="radio" name="source_type" id="source" value="channel" <?php if($settingObj->getSourceType() == 'channel') { echo 'checked="checked"'; }?> onclick="javascript:enableChannel();"/></div>
                        <div class="input_text float_left">Channel</div>
                        <div class="margin_t margin_r float_left">
                        	<select name="source_source" id="channel_source" disabled onchange="javascript:changeTutorial('channel',this.options[this.selectedIndex].value);">
                            	<option value="1" <?php if($settingObj->getSourceSource() == "1") { echo "selected"; }?>>YOUTUBE</option>
                                <option value="2" <?php if($settingObj->getSourceSource() == "2") { echo "selected"; }?>>VIMEO</option>
                            </select>
                        </div>
                        <div class="input_radio_input float_left"><input type="text" name="source_link" class="short_input_box" id="link_channel" tmt:required="true" value="<?php if($settingObj->getSourceType() == "channel") { echo $settingObj->getSourceLink(); }?>"  tmt:message="Insert channel link" ></div>
                        <div class="float_left help_icon"><a href="javascript:showTutorial(1);" id="channel_tutorial"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a></div>
                        <div class="cleardiv"></div>
                        
                        <div class="margin_t"></div>
                        
                       <div class="input_check float_left"><input type="radio" name="source_type" id="source" value="playlist" <?php if($settingObj->getSourceType() == 'playlist') { echo 'checked="checked"'; }?> onclick="javascript:enablePlaylist();" /></div>
                       <div class="input_text float_left">Playlist</div>
                       <div class="margin_t margin_r float_left">
                        	<select name="source_source" id="playlist_source" disabled onchange="javascript:changeTutorial('playlist',this.options[this.selectedIndex].value);">
                            	<option value="1" <?php if($settingObj->getSourceSource() == "1") { echo "selected"; }?>>YOUTUBE</option>
                                <option value="2" <?php if($settingObj->getSourceSource() == "2") { echo "selected"; }?>>VIMEO</option>
                            </select>
                        </div>
                       <div class="input_radio_input float_left"><input type="text" name="source_link" class="short_input_box" id="link_playlist" value="<?php if($settingObj->getSourceType() == "playlist") { echo $settingObj->getSourceLink(); }?>" disabled  tmt:required="true" tmt:message="Insert playlist link"></div>
                        <div class="float_left help_icon"><a href="javascript:showTutorial(2);" id="playlist_tutorial"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a></div>
                        <div class="cleardiv"></div>
                       
                        
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <input type="hidden" name="source_num_videos" value="25" />
                    <!--
                    <div id="label_input">
                        <label for="source_num_videos">Choose the number of videos from the source</label><br /><span id="span_description">(maximum number of videos is 50)</span>
                    </div>
                    <div id="input_box">
                        <input type="text" name="source_num_videos" id="source_num_videos" class="number_input_box" value="<?php echo $settingObj->getSourceNumVideos(); ?>"  />
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    -->
                </div>
                <!-- Schedule video -->
                <div id="manual_management">
                    <div id="label_input">
                        <label for="schedule_video">Schedule video</label><br /><span id="span_description">(choose if you want to set a show schedule for your videos)</span>
                    </div>
                    <div id="input_box">
                       YES&nbsp;<input type="radio" name="schedule_video" value="1" <?php if($settingObj->getScheduleVideo() == "1") { echo "checked"; }?> onclick="javascript:showDisplayVideos(this.value);"/>&nbsp;&nbsp;
                       NO&nbsp;<input type="radio" name="schedule_video" value="0" <?php if($settingObj->getScheduleVideo() == "0") { echo "checked"; }?>  onclick="javascript:showDisplayVideos(this.value);"/>
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                    <div id="schedule_display_videos" style="display:none">
                        <div id="label_input">
                            <label for="playlist_start">Videos to display</label>
                        </div>
                        <div id="input_box">
                            <select name="playlist_start">
                                <option value="0" <?php if($settingObj->getPlaylistStart() == '0') { echo "selected"; } ?>>Only today's videos</option>
                                <option value="-1" <?php if($settingObj->getPlaylistStart() == '-1') { echo "selected"; } ?>>Yesterday and today</option>
                                <option value="-2" <?php if($settingObj->getPlaylistStart() == '-2') { echo "selected"; } ?>>Two days ago and today</option>
                                <option value="-3" <?php if($settingObj->getPlaylistStart() == '-3') { echo "selected"; } ?>>Three days ago and today</option>
                                <option value="-4" <?php if($settingObj->getPlaylistStart() == '-4') { echo "selected"; } ?>>Four days ago and today</option>
                                <option value="-5" <?php if($settingObj->getPlaylistStart() == '-5') { echo "selected"; } ?>>Five days ago and today</option>
                                <option value="-100" <?php if($settingObj->getPlaylistStart() == '-100') { echo "selected"; } ?>>All videos</option>
                            </select>            
                        </div>
                        <div id="rowspace"></div>
                        <div id="rowline"></div>
                        <div id="rowspace"></div>
                        <div id="label_input">
                            <label for="schedule_video">Show your schedule</label><br /><span id="span_description">(choose if you want to show the schedule below the player)</span>
                        </div>
                        <div id="input_box">
                           YES&nbsp;<input type="radio" name="show_schedule_list" value="1" <?php if($settingObj->getShowScheduleList() == "1") { echo "checked"; }?> onclick="javascript:showScheduleOptions(1);"/>&nbsp;&nbsp;
                           NO&nbsp;<input type="radio" name="show_schedule_list" value="0" <?php if($settingObj->getShowScheduleList() == "0") { echo "checked"; }?> onclick="javascript:showScheduleOptions(0);"  />
                        </div>
                        <div id="rowspace"></div>
                        <div id="rowline"></div>
                        <div id="rowspace"></div>
                        <div id="schedule_options">
                            <div id="label_input">
                                <label for="schedule_list_font_size">Schedule text size</label><br /><span id="span_description">(choose text size in pixels)</span>
                            </div>
                            
                            <div id="input_box">
                                <input type="text" class="number_input_box" name="schedule_list_font_size" value="<?php echo $settingObj->getScheduleListFontSize(); ?>" tmt:pattern="positiveinteger" />&nbsp;px
                            </div>
                            <div id="rowspace"></div>
                            <div id="rowline"></div>
                            <div id="rowspace"></div>
                            
                            <div id="label_input">
                                <label for="schedule_list_height">Schedule height</label><br /><span id="span_description">(choose the box height in pixels)</span>
                            </div>
                            
                            <div id="input_box">
                                <input type="text" class="number_input_box" name="schedule_list_height" value="<?php echo $settingObj->getScheduleListHeight(); ?>" tmt:pattern="positiveinteger" />&nbsp;px
                            </div>
                            <div id="rowspace"></div>
                            <div id="rowline"></div>
                            <div id="rowspace"></div>
                            
                            <div id="label_input">
                                <label for="schedule_list_margin_top">Schedule distance from player</label><br /><span id="span_description">(choose distance from player and schedule in pixels)</span>
                            </div>
                            
                            <div id="input_box">
                                <input type="text" class="number_input_box" name="schedule_list_margin_top" value="<?php echo $settingObj->getScheduleListMarginTop(); ?>" tmt:pattern="positiveinteger" />&nbsp;px
                            </div>
                            <div id="rowspace"></div>
                            <div id="rowline"></div>
                            <div id="rowspace"></div>
                        </div>
                    </div>
                    <!-- Loop Videos -->
                    <div id="label_input">
                        <label for="loop_videos">Loop videos</label><br /><span id="span_description">(choose if you want to make your playlist restart when it ends)</span>
                    </div>
                    <div id="input_box">
                       YES&nbsp;<input type="radio" name="loop_videos" value="1" <?php if($settingObj->getLoopVideos() == "1") { echo "checked"; }?> />&nbsp;&nbsp;
                       NO&nbsp;<input type="radio" name="loop_videos" value="0" <?php if($settingObj->getLoopVideos() == "0") { echo "checked"; }?> />
                    </div>
                    <div id="rowspace"></div>
                    <div id="rowline"></div>
                    <div id="rowspace"></div>
                </div>
                <!-- BUTTONS -->
                <!-- ========================================================== -->
                <div class="bridge_buttons_container">
                    <!-- cancel -->
                    <div class="admin_button cancel_button" ><a href="javascript:document.location.href='welcome.php';"></a></div>
                    
                    <!-- save -->
                    <div class="admin_button" style="margin-left:750px"><input type="submit" id="apply_button" name="saveunpublish" value=""></div>
                    
                </div>
                <div id="rowspace"></div>
             </form>
            
         </div>
        
        
        </div>
    </div>
</div>
<?php 
include 'include/footer.php';
?>
