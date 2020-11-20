<?php
include '../common.php';
$video_id = $_GET["video_id"];
$videoObj->setVideo($video_id);
if(isset($_POST["video_id"])) {
	if(isset($_POST["video_duration"])) {
		$_POST["video_duration"] = $_POST["video_duration"];
	}
	
	$videoObj->updateVideo();
	?>
    <script>
		window.parent.document.location.reload();
		window.close();
		
	</script>
    <?php
}

?>
<script language="javascript" src="js/tmt_libs/tmt_core.js"></script>    
<script language="javascript" src="js/tmt_libs/tmt_css.js"></script>
<script language="javascript" src="js/tmt_libs/tmt_tabs.js"></script>
<script language="javascript" src="js/tmt_libs/tmt_form.js"></script>
<script language="javascript" src="js/tmt_libs/tmt_validator.js"></script>
<script>
	function validateForm() {
		if(tmt.validator.validateForm('edit_video')) {
			formObj = document.forms["edit_video"];
			formObj.submit();
		}
	}
</script>
<div class="manage_slot_box_container" style="margin:0px">
                            
    <div class="manage_slot_box_container_inside" id="playlist_div">
        <div id="label_input">
            <div class="label_subtitle">
                Edit video
                
            </div>
        </div>
        <form id="edit_video" name="edit_video" tmt:validate="true" method="post" target="iframe_edit_submit" action="ajax/editVideo.php" enctype="multipart/form-data">
        	<input type="hidden" name="video_id" value="<?php echo $videoObj->getVIdeoid(); ?>" />
            <div class="select_container" style="margin:0px">
        
                <div class="addlocalvideo_title margin_t">Video title:</div>
                <div class="addlocalvideo_input margin_t" style="width:600px"><input type="text" name="video_title" class="short_input_box" id="title_local_video1" value="<?php echo $videoObj->getVideoTitle(); ?>" tmt:required="true" tmt:message="Insert a title for the video" /></div>
                <div class="cleardiv"></div>
                
                <?php
                if($videoObj->getVideoType() == 3 || $videoObj->getVideoType() == 4) {
                    ?>
                    <div class="addlocalvideo_title margin_t">Video thumb:</div>
                    <div class="addlocalvideo_input margin_t" style="width:600px"><input type="file" name="video_thumb" class="short_input_box" id="thumb_local_video1" value="" /></div>
                    <div class="cleardiv"></div>
                    <?php
                }
                if($videoObj->getVIdeoType() == 3) {
                    ?>
                    <div class="addlocalvideo_title margin_t">Video duration (in seconds):</div>
                    <div class="addlocalvideo_input margin_t" style="width:600px"><input type="text" name="video_duration" class="short_input_box" id="duration_local_video1" value="<?php echo $videoObj->getVideoDuration(); ?>" tmt:required="true" tmt:pattern="positiveinteger" tmt:filters="numbersonly" tmt:message="Insert video duration" /></div>
                    <div class="cleardiv"></div>
                    <?php
                }
                ?>
                
                <div class="addlocalvideo_title margin_t">Video author:</div>
                <div class="addlocalvideo_input margin_t" style="width:600px"><input type="text" name="video_author" class="short_input_box" id="author_local_video1" value="<?php echo $videoObj->getVideoAuthor(); ?>"  /></div>
                <div class="cleardiv"></div>
                
                
                <div class="addlocalvideo_title margin_t">Video description:</div>
                <div class="addlocalvideo_input margin_t" style="width:600px"><textarea name="video_description" class="short_input_box" id="description_local_video1" style="height:50px;line-height:15px;"><?php echo $videoObj->getVideoDescription(); ?></textarea></div>
                <div class="cleardiv"></div>
                
                
            
            </div>
            <div class="admin_button"><input type="button" id="apply_button" style="background-color:#fff;" name="saveunpublish" value="" onclick="javascript:validateForm();"></div>
        </form>
        <div id="rowspace"></div>
        
    </div>
    <div id="empty"></div>
</div>
<iframe id="iframe_edit_submit" name="iframe_edit_submit" style="border:0;display:none;width:0px;height:0px"></iframe>