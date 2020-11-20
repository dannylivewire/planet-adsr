<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}

if(isset($_POST["selected_video"])) {
	$showObj->insertShow();
	header('Location: show_schedule.php');
}
$arrayChannels = $listObj->getSourcesList('channel');
$arrayPlaylists = $listObj->getSourcesList('playlist');
$arrayVideos = $listObj->getVideosList();
include 'include/header.php';
?>
<script language="javascript" type="text/javascript">
	function loadVideos(source) {
		
		$.ajax({
		  url: 'ajax/getSourceVideos.php?source_id='+source,
		  success: function(data) {
			$('#selected_videos').append(data);
		  }
		});
			
			
		
	}
</script>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        <?php
        include 'include/menu.php'; 
        ?>
        <div id="form_container">
        	<form name="new_show" action="" method="post" id="new_show" tmt:validate="true" enctype="multipart/form-data">    
            	
                <div id="label_input">
                    <label for="show_source">Choose source</label><br /><span id="span_description">(select as many sources as you want, using this drop multiple times)</span>
                </div>
                <div id="input_box">
                	<select name="show_source" onChange="javascript:loadVideos(this.options[this.selectedIndex].value);">
                    	<option value="0">Select a source</option>
                        <?php
						if(count($arrayChannels)>0) {
							?>
                            <optgroup label="Channels">
                            <?php
							foreach($arrayChannels as $channelId =>$channel) {
								?>
								<option value="<?php echo $channelId; ?>"><?php echo $channel["source_title"]; ?></option>
								<?php
							}
							?>
                            </optgroup>
                            <?php
						}
						if(count($arrayPlaylists)>0) {
							?>
                            <optgroup label="Playlists">
                            <?php
							foreach($arrayPlaylists as $playlistId => $playlist) {
								?>
								<option value="<?php echo $playlistId; ?>"><?php echo $playlist["source_title"]; ?></option>
								<?php
							}
							?>
                            </optgroup>
                            <?php
						}
						if(count($arrayVideos)>0) {
							?>
                            
                            <option style="font-weight:bold;font-style:italic" value="0">Other videos</option>
                            <?php
							foreach($arrayVideos as $videoId => $video) {
								?>
								<option disabled style="padding-left:15px"><?php echo $video["video_title"]; ?></option>
								<?php
							}
							?>
                            
                            <?php
						}
						?>
                    </select>
                    
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="show_list">Selected videos</label>
                </div>
                <div id="input_box">
                	<!--table for selected videos, loaded dinamically. columns: video, date, time, below there's recurrency and end date-->
                	<div id="selected_videos">
                    </div>                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                
                
                
                <!-- bridge buttons -->
                <div class="bridge_buttons_container">
                    <!-- cancel -->
                    <div class="admin_button cancel_button" ><a href="javascript:document.location.href='show_schedule.php';"></a></div>
                    
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