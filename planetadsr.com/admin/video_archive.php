<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}
if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["sources"])) {
	$arrSources=$_POST["sources"];
	$qryString = "0";
	for($i=0;$i<count($arrSources); $i++) {
		$qryString .= ",".$arrSources[$i];
	}
		
	switch($_POST["operation"]) {
		case "delSources":
			$sourceObj->delSources($qryString);
			break;
			
		case "refreshSources":
			$sourceObj->refreshSources($qryString,$settingObj);
			break;
			
		case "addmoreSources":
			$sourceObj->addmoreSources($qryString,$settingObj);
			break;
			
			break;
	}                
	header('Location: video_archive.php?tab=1');
}

if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["videos"])) {
	$arrVideos=$_POST["videos"];
	$qryString = "0";
	for($i=0;$i<count($arrVideos); $i++) {
		$qryString .= ",".$arrVideos[$i];
	}
		
	switch($_POST["operation"]) {
		case "delVideos":
			$videoObj->delVideos($qryString);
			break;
	}                
	header('Location: video_archive.php?tab=2');
}
$tab_position = 0;
if(isset($_GET["tab"])) {
	$tab_position = $_GET["tab"];
}
include 'include/header.php';
?>
 <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script language="javascript" type="text/javascript">
	
	$(function() {
		tmt.validator.patterns.youtubevideo = new RegExp("^http://youtu.be/[a-zA-Z0-9._]{11}$");
		//tmt.validator.patterns.youtubeplaylist = new RegExp("^http://www.youtube.com/playlist\?list=PL[A-Z0-9]{16}$");
		tmt.validator.patterns.httpstart = new RegExp("^http://");
		
	});
	function goToByScroll(id){
	      $('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');
	}
	
	
	function addVideo(num) {
		$('#add_video').remove();
		$('#add_video_'+(num-1)).append('&nbsp;<input type="button" value="remove" onClick="javascript:removeVideo('+(num-1)+');" />');
		//$('#custom_videos').append('<div id="add_video_'+num+'" style="margin-top:5px"><input type="text" name="video_link[]" class="short_input_box" id="link_video'+num+'" tmt:required="true" value="" tmt:message="Insert a valid video link" tmt:pattern="youtubevideo" />&nbsp;<input type="button" id="add_video" value="add more" onClick="javascript:addVideo('+(num+1)+');"></div>');
		//$('#add_video').attr("onClick","javascript:addVideo("+(num+1)+");");
		$('#custom_videos').append('<div id="add_video_'+num+'" style="margin-top:5px">Type:&nbsp;&nbsp;<select name="video_type[]" id="type_video" tmt:required="true" tmt:invalidvalue="0" tmt:message="Select the type of the video"><option value="0"></option><option value="1">YOUTUBE</option><option value="2">VIMEO</option></select>&nbsp;&nbsp;&nbsp;Link:&nbsp;<input type="text" name="video_link[]" class="short_input_box" id="link_video'+num+'" value="" tmt:required="true" tmt:message="Insert at least a video link" />&nbsp;&nbsp;<input type="button" id="add_video" value="add more" onClick="javascript:addVideo('+(num+1)+');"></div>');
		
		
	}
	
	function removeVideo(num) {
		$('#add_video_'+num).remove();
	}
	
	function loadChannel() {
		if($('#type_channel').val() == 1) {
			if($('#id_channel').val() == '' && $('#name_channel').val() == '') {
				alert("Insert a valid channel");
			} else {
				$('#result_channel').html('<img src="images/loading.gif" border=0>');
				
				$.ajax({
				  url: 'ajax/loadSource.php?type=channel&source='+$('#type_channel').val()+'&name='+$('#name_channel').val()+'&id='+$('#id_channel').val(),
				  success: function(data) {
					 
					  $('#result_channel').html('');
					  if(data == 0) {
						  alert("Channel not valid or empty, no video added");
					  } else {
						  alert(data+" videos added");
						  
					  }
					  document.location.reload();
				  }
				});
			}
		} else if($('#type_channel').val() == 2) {
			if(Trim($('#link_channel').val()) == '') {
				alert("Insert a valid channel");
			} else {
				$('#result_channel').html('<img src="images/loading.gif" border=0>');
				
				$.ajax({
				  url: 'ajax/loadSource.php?type=channel&source='+$('#type_channel').val()+'&link='+$('#link_channel').val(),
				  success: function(data) {
					 
					  $('#result_channel').html('');
					  if(data == 0) {
						  alert("Channel not valid or empty, no video added");
					  } else {
						  alert(data+" videos added");
						  
					  }
					  document.location.reload();
				  }
				});
			}
		} else {
			alert("Select the channel type");
		}
		
	}
	function loadPlaylist() {
		if(Trim($('#link_playlist').val()) == '') {
			alert("Insert a valid playlist");
		} else if($('#type_playlist').val() == 0) {
			alert("Select the playlist type");
		} else {
			$('#result_playlist').html('<img src="images/loading.gif" border=0>');
			
			$.ajax({
			  url: 'ajax/loadSource.php?type=playlist&source='+$('#type_playlist').val()+'&link='+$('#link_playlist').val(),
			  success: function(data) {
				  $('#result_playlist').html('');
				  if(data == 0) {
					  alert("Playlist not valid or empty, no video added");
				  } else {
					  alert(data+" videos added");
				  }
				document.location.reload();
			  }
			});
		}
		
	}
	function loadVideos() {
		$('#result_video').html('<img src="images/loading.gif" border=0>');
		var youtubevideo = new RegExp("^http://youtu.be/[a-zA-Z0-9._-]{11}$");
		var len = document.getElementsByName("video_link[]").length;
		var error = 0;
		var empty = 0;
		for(var i = 0;i<len;i++) {
			if(document.getElementsByName("video_type[]").item(i).options[document.getElementsByName("video_type[]").item(i).selectedIndex].value == 0) {
				document.getElementsByName("video_type[]").item(i).style.border="1px solid #FF0000";
		   		error = 1;			
			} else if(Trim(document.getElementsByName("video_link[]").item(i).value) == '') {
				empty++; 
			} else {
				document.getElementsByName("video_link[]").item(i).style.border="1px solid #CCCCCC";
			}
		}
		if(error==1) {
			$('#result_video').html('');
			alert("You haven't selected one or more video type. Check inserted data");
		} else if(empty == len) {
			$('#result_video').html('');
			alert("Insert at least a video");
		} else {
			
			//ajax call
			$.ajax({
			  url: 'ajax/loadVideos.php',
			  type: 'POST',
			  data: $('#videos').serialize(),
			  success: function(data) {
				  $('#result_video').html('');
				  if(data == 0) {
					  alert("Videos already exist, no video added");
				  } else {
					  alert(data+" videos added");
				  }
				document.location.reload();
			  }
			});
		}
		
	}
	
	function refreshSource(source_id) {
		//loader
		$('body').prepend('<div id="div_back"></div>');
		$('#modal_loading').fadeIn();
		$.ajax({
		  url: 'ajax/refreshSource.php?source_id='+source_id,
		  success: function(data) {
			document.location.href="video_archive.php?tab=1";
		  }
		});
	}
	
	function refreshVideo(video_id) {
		$('body').prepend('<div id="div_back"></div>');
		$('#modal_loading').fadeIn();
		$.ajax({
		  url: 'ajax/refreshVideo.php?video_id='+video_id,
		  success: function(data) {
			document.location.href="video_archive.php?tab=2";
		  }
		});
	}

	function addmoreSource(source_id) {
		$('body').prepend('<div id="div_back"></div>');
		$('#modal_loading').fadeIn();
		$.ajax({
		  url: 'ajax/addmoreSource.php?source_id='+source_id,
		  success: function(data) {
			document.location.href="video_archive.php?tab=1";
		  }
		});
	}
	
	function delSource(source_id) {
		if(confirm("Are you sure you want to delete this source? All related video will be deleted and not restorable")) {
			$.ajax({
			  url: 'ajax/delSource.php?source_id='+source_id,
			  success: function(data) {
				document.location.href="video_archive.php?tab=1";
			  }
			});
		}
	}
	
	
	function delVideo(video_id) {
		if(confirm("Are you sure you want to delete this video?")) {
			$.ajax({
			  url: 'ajax/delVideos.php?video_id='+video_id,
			  success: function(data) {
				document.location.href="video_archive.php?tab=2";
			  }
			});
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
				$('#modal_tutorial').css({'height':'600px','margin-top':'-300px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go on the <strong>Video page</strong><br/>2) Click on share<br/>3) Copy the link</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/video.jpg">');
				break;
			case 4:
				$('#modal_tutorial').css({'height':'150px','margin-top':'-75px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go to a <strong>channel page</strong><br/>2) copy the numbers after \"channels\" word in the url</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/vimeo_channel.jpg">');
				break;
			case 5:
				$('#modal_tutorial').css({'height':'150px','margin-top':'-75px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go to an <strong>album page</strong><br/>2) copy the numbers after \"album\" word in the url</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/vimeo_album.jpg">');
				break;
			case 6:
				$('#modal_tutorial').css({'height':'600px','margin-top':'-300px'});
				$('#modal_tutorial_explanation').html("<div style=\"padding: 10px; margin-bottom: 10px; font-size: 16px;\">1) Go on the <strong>Video page</strong><br/>2) Click on share<br/>3) Copy the link</div>");
				$('#modal_tutorial_image').html('<img src="images/tutorial/vimeo_video.jpg">');
				break;
			
		}
		$('#modal_tutorial').fadeIn();
	}
	function closeTutorial() {
		$('#modal_tutorial').fadeOut();
		$('#div_back').remove();
	}
	
	function showVideos(divid,opener) {
		if($('#'+divid).css("display") == "none") {
			$('#'+divid).fadeIn();
			$('#'+opener+divid).find('img').first().attr("src","images/icons/minus_btn.png");
		} else {
			$('#'+divid).fadeOut();
			$('#'+opener+divid).find('img').first().attr("src","images/icons/plus_btn.png");
		}
	}
	function showSource(divid,opener) {
		if($('#'+divid).css("display") == "none") {
			$('#'+divid).fadeIn();
			$('#'+opener).find('img').first().attr("src","images/icons/minus_btn.png");
		} else {
			$('#'+divid).fadeOut();
			$('#'+opener).find('img').first().attr("src","images/icons/plus_btn.png");
		}
	}
	
	function showDiv(type) {
		switch(parseInt(type)) {
			case 1:
				$('#youtube_channel').fadeIn();
				$('#vimeo_channel').fadeOut();
				break;
			case 2:
				$('#youtube_channel').fadeOut();
				$('#vimeo_channel').fadeIn();
				break;
			default:
				$('#youtube_channel').fadeOut();
				$('#vimeo_channel').fadeOut();
				break;
		}
	}
	function showPlTutorial(type) {
		if(type=='1') {
			$('#tutorial_pl_youtube').fadeIn();
			$('#tutorial_pl_vimeo').fadeOut(0);
		} else if(type=='2') {
			$('#tutorial_pl_vimeo').fadeIn();
			$('#tutorial_pl_youtube').fadeOut(0);
		}
	}
	function showVideoTutorial(type) {
		if(type=='1') {
			$('#tutorial_v_youtube').fadeIn();
			$('#tutorial_v_vimeo').fadeOut(0);
		} else if(type=='2') {
			$('#tutorial_v_vimeo').fadeIn();
			$('#tutorial_v_youtube').fadeOut(0);
		}
	}
</script>
<div id="modal_loading" style="display:none"><img src="images/loading.png" border=0></div>
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
        	<div class="tmtTabGroup" id="mainTab" tmt:tabgroup="true">            
                <div class="tmtTabs">                
                    <a href="javascript:;" class="tmtTabSelected tmtTab">Add channel</a>                    
                    <a href="javascript:;" class="tmtTab">Add playlist</a>                    
                    <a href="javascript:;" class="tmtTab">Add videos</a>                
                </div>            
                <div class="tmtPanelGroup">
                
                    <div class="tmtPanel" tmt:tabpanel="true" style="display:block">
                   
                        <!-- 
                        =======================
                        === add channel ==
                        =======================
                        -->
                        <div class="manage_slot_box_container">                        
                            <div class="manage_slot_box_container_inside" id="channel_div">
                                <div id="label_input">Add a <strong>Youtube</strong>/<strong>Vimeo</strong> channel link to add videos to the archive</div>
                                <div class="margin_t">
                                    
                                    <div class="float_left margin_t">N.B. Youtube videos will be retrieved in the same order they're displayed on Youtube</div>
                                    <div class="cleardiv"></div>
                                </div>
                                <form tmt:validate="true">
                                <div class="select_container margin_t">
                                
                                    <div class="float_left">
                                        <div class="input_title float_left">Type</div>
                                        <div class="input_input float_left margin_l">
                                            <select name="channel_type" id="type_channel" tmt:required="true" tmt:invalidvalue="0" tmt:message="Select the type of the channel" onchange="javascript:showDiv(this.options[this.selectedIndex].value);">
                                                <option value="0"></option>
                                                <option value="1">YOUTUBE</option>
                                                <option value="2">VIMEO</option>
                                            </select>
                                        </div>
                                        <div class="cleardiv"></div>
                                    </div>
                                        
                                        
                                    <div class="float_left margin_l">
                                        <div class="input_title"></div>
                                        <div class="input_input">
                                        	<div id="youtube_channel" style="display:none">
                                            	<div class="float_left">(insert channel's name or channel's id)</div>
                                                <div class="float_left margin_l"><a href="javascript:showTutorial(1);"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a></div>
                                                <div class="cleardiv"></div>
                                            	<div class="float_left margin_t" style="width:55px">
                                            	Name:
                                                </div>
                                                <div class="float_left"><input type="text" name="channel_name" class="short_input_box" id="name_channel" value="" tmt:required="true" tmt:message="Insert a valid channel user" tmt:pattern="alphanumeric"></div>
                                                <div class="cleardiv"></div>
                                                <div class="margin_t">or</div>
                                                <div class="float_left margin_t" style="width:55px">ID:</div>
                                                <div class="float_left margin_t"><input type="text" name="channel_id" class="short_input_box" id="id_channel" value="" tmt:required="true" tmt:message="Insert a valid channel user" tmt:pattern="alphanumeric"></div>
                                                <div class="cleardiv"></div>
                                                
                                            </div>
                                            <div id="vimeo_channel" style="display:none">
                                            	<div class="float_left">(insert channel's id)</div>
                                                <div class="float_left margin_l"><a href="javascript:showTutorial(4);"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a></div>
                                                <div class="cleardiv"></div>
                                            	<input type="text" name="channel_link" class="short_input_box margin_t" id="link_channel" value="" tmt:required="true" tmt:message="Insert a valid channel user" tmt:pattern="alphanumeric"><br /><br />
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cleardiv"></div>
                                    
                                </div>
                                <div class="admin_button"><input type="button" id="apply_button" style="background-color:#fff;" name="saveunpublish" onclick="javascript:loadChannel();" value=""><div id="result_channel" style="float:left;margin-top:35px"></div></div>
                                </form>
                                <div id="rowspace"></div>
                            </div>
                        </div>
                        <div id="empty"></div>
                    
                    </div>
                
                    <div class="tmtPanel" tmt:tabpanel="true">
                    
                    	<!-- 
                        =======================
                        === add playlist ==
                        =======================
                        -->
                       <div class="manage_slot_box_container">
                            
                            <div class="manage_slot_box_container_inside" id="playlist_div">
                                <div id="label_input">Add a <strong>Youtube</strong> playlist link or <strong>Vimeo</strong> album id to add videos to the archive</div>
                                <div class="margin_t">
                                    
                                    <div class="float_left margin_t">N.B. Youtube videos will be retrieved in the same order they're displayed on Youtube</div>
                                    <div class="cleardiv"></div>
                                </div>
                                
                                <form tmt:validate="true">
                                <div class="select_container">
                                
                                	<div class="float_left">
                                    	<div class="input_title float_left">Type</div>
                                        <div class="input_input float_left margin_l">
                                            <select name="playlist_type" id="type_playlist" tmt:required="true" tmt:invalidvalue="0" tmt:message="Select the type of the playlist/album" onchange="javascript:showPlTutorial(this.options[this.selectedIndex].value);">
                                                <option value="0"></option>
                                                <option value="1">YOUTUBE</option>
                                                <option value="2">VIMEO</option>
                                            </select>
                                        </div>
                                        <div class="cleardiv"></div>
                                    </div>
                                    
                                    
                                    <div class="float_left margin_l">
                                        <div class="input_title"></div>
                                        <div class="input_input">
                                        	<div id="tutorial_pl_youtube" style="display:none">
                                                <div class="float_left">(a valid Youtube playlist link is like "http://www.youtube.com/playlist?list=XXXXXXXXXXXXX")</div>
                                                <div class="float_left margin_l"><a href="javascript:showTutorial(2);"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a></div>
                                                <div class="cleardiv"></div>
                                            </div>
                                            <div id="tutorial_pl_vimeo" style="display:none">
                                                <div class="float_left">(insert album's ID)</div>
                                                <div class="float_left margin_l"><a href="javascript:showTutorial(5);"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a></div>
                                                <div class="cleardiv"></div>
                                            </div>
                                            <input type="text" name="playlist_link" class="short_input_box margin_t" id="link_playlist" value="" tmt:message="Insert a valid playlist link" tmt:pattern="youtubeplaylist"><br /><br />
                                            
                                        </div>
                                    </div>
                                    <div class="cleardiv"></div>
                                    
                                </div>
                                <div class="admin_button"><input type="button" id="apply_button" style="background-color:#fff;" name="saveunpublish" onclick="javascript:loadPlaylist();" value=""><div id="result_playlist" style="float:left;margin-top:35px"></div></div>
                                </form>
                                <div id="rowspace"></div>
                                
                            </div>
                            <div id="empty"></div>
                        </div>
                    
                    </div>
                    
                    <div class="tmtPanel" tmt:tabpanel="true">
                    
                    	<!-- 
                        =======================
                        === add videos ==
                        =======================
                        -->
                       <div class="manage_slot_box_container">
                            
                            <div class="manage_slot_box_container_inside" id="playlist_div">
                                <div id="label_input">
                                    <div class="label_subtitle">
                                    	Add <strong>Youtube</strong>/<strong>Vimeo</strong> video links
                                        <div id="tutorial_v_youtube" style="display:none">
                                            <br /><span id="span_description">(a valid video link is like "http://youtu.be/XXXXXXXXXXX")</span>
                                            &nbsp;<a href="javascript:showTutorial(3);"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a>
                                        </div>
                                        <div id="tutorial_v_vimeo" style="display:none">
                                            <br /><span id="span_description">(a valid video link is like "https://vimeo.com/XXXXX")</span>
                                            &nbsp;<a href="javascript:showTutorial(6);"><img src="images/icons/help.png" border = 0 alt="help" title="help" /></a>
                                        </div>
                                    </div>
                                </div>
                                <form id="videos" name="videos" tmt:validate="true">
                                <div class="select_container">
                                    <div class="input_title"></div>
                                    <div class="input_input" id="custom_videos">
                                    	<div id="add_video_1">
                                        	Type:&nbsp;
                                            <select name="video_type[]" id="type_video" tmt:required="true" tmt:invalidvalue="0" tmt:message="Select the type of the video" onchange="javascript:showVideoTutorial(this.options[this.selectedIndex].value);">
                                                <option value="0"></option>
                                                <option value="1">YOUTUBE</option>
                                                <option value="2">VIMEO</option>
                                            </select>&nbsp;&nbsp;
                                            Link:&nbsp;<input type="text" name="video_link[]" class="short_input_box" id="link_video1" value="" tmt:required="true" tmt:message="Insert at least a video link" />&nbsp;
                                           &nbsp;<input type="button" id="add_video" value="add more" onClick="javascript:addVideo(2);" />
                                           
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="admin_button"><input type="button" id="apply_button" style="background-color:#fff;" name="saveunpublish" onclick="javascript:loadVideos();" value=""><div id="result_video" style="float:left;margin-top:35px"></div></div>
                                </form>
                                <div id="rowspace"></div>
                                
                            </div>
                            <div id="empty"></div>
                        </div>
                    
                    </div>
                
                </div>
            
            </div>
        	
            
            
            
            <!-- 
            ============================================================================================
            ===  ==
            ============================================================================================
            -->
            
            
            <div id="rowspace"></div>
            <div id="rowline"></div>
            <div id="rowspace"></div>
            <a name="results" id="results"></a>
            <div id="secondTab" tmt:tabgroup="true" class="tmtTabs">            
                <div class="tmtTabs">    
                	<?php
					switch($tab_position) {
						case 1:
							$style1="display:block";
							$style2="display:none";
							?>
                            <a href="javascript:;" class="tmtTabSelected tmtTab">Sources</a>                    
                    		<a href="javascript:;" class="tmtTab">Videos</a>  
                            <?php
							break;
						case 2:
							$style1="display:none";
							$style2="display:block";
							?>
                            <a href="javascript:;" class="tmtTab">Sources</a>                    
                    		<a href="javascript:;" class="tmtTab tmtTabSelected">Videos</a>  
                            <?php
							break;
						default:
							$style1="display:block";
							$style2="display:none";
							?>
                            <a href="javascript:;" class="tmtTabSelected tmtTab">Sources</a>                    
                    		<a href="javascript:;" class="tmtTab">Videos</a>  
                            <?php
							break;
					}
					?>            
                                  
                </div>                
                <div class="tmtPanelGroup">                
                    <div class="tmtPanel" tmt:tabpanel="true" style="<?php echo $style1; ?>" id="source_list"> 
                    	<?php
						include "sources.php"; 
						?>                   
                    	<div id="empty"></div>
                    </div>                    
                    <div class="tmtPanel" tmt:tabpanel="true" id="video_list" style="<?php echo $style2; ?>">                    	
                    	<?php
						include "videos.php"; 
						?>  
                        <div id="empty"></div>                   
                    </div>               
                </div>            
            </div>
            
            
            
            
            <div id="cleardiv"></div>
            <div id="rowspace"></div>
        </div>
    </div>
</div>
<?php 
include 'include/footer.php';
?>
<iframe id="iframe_submit" name="iframe_submit" style="width:0px;height:0px;border:none;display:none"></iframe>
