<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}

if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["onair_videos"])) {
	$arrVideos=$_POST["onair_videos"];
	$qryString = "0";
	for($i=0;$i<count($arrVideos); $i++) {
		$qryString .= ",".$arrVideos[$i];
	}
		
	switch($_POST["operation"]) {
		
		case "unpublishVideos":
			$videoObj->unpublishVideos($qryString);
			header('Location: on_air.php?tab=1');
			break;
		case "orderVideos":
			$videoObj->orderVideos($qryString,$_POST["orderby_total"]);
			header('Location: on_air.php?tab=2');
			break;
		
	}                
	
}

if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["all_videos"])) {
	$arrVideos=$_POST["all_videos"];
	$qryString = "0";
	for($i=0;$i<count($arrVideos); $i++) {
		$qryString .= ",".$arrVideos[$i];
	}
		
	switch($_POST["operation"]) {
		
		case "publishVideos":
			$videoObj->publishVideos($qryString);
			header('Location: on_air.php?tab=2');
			break;
		case "unpublishVideos":
			$videoObj->unpublishVideos($qryString);
			header('Location: on_air.php?tab=1');
			break;
		
	}                
	
}


$filterCondition="";
if(isset($_POST["video_upload_date"]) && $_POST["video_upload_date"] != '') {
	$filterCondition .= " AND DATE_FORMAT(video_upload_date,'%Y-%m-%d')='".$_POST["video_upload_date"]."'";
}




//order management
if(isset($_REQUEST["orderby"]) && $_REQUEST["orderby"] != '' && isset($_REQUEST["type"]) && $_REQUEST["type"] != '') {
	
	switch($_REQUEST["orderby"]) {
		case "title":
			if($_REQUEST["type"] == 'asc') {
				$_SESSION["allVideosOrder"] = "ORDER BY video_title asc";
				$_SESSION["allVideosTitleOrder"] = "desc";
			} else {
				$_SESSION["allVideosOrder"] = "ORDER BY video_title desc";
				$_SESSION["allVideosTitleOrder"] = "asc";
			}
			break;
		case "date":
			if($_REQUEST["type"] == 'asc') {
				$_SESSION["allVideosOrder"] = "ORDER BY video_upload_date asc";
				$_SESSION["allVideosUpdatedOrder"] = "desc";
			} else {
				$_SESSION["allVideosOrder"] = "ORDER BY video_upload_date desc";
				$_SESSION["allVideosUpdatedOrder"] = "asc";
			}
			break;
		case "onair":
			if($_REQUEST["type"] == 'asc') {
				$_SESSION["allVideosOrder"] = "ORDER BY video_active asc";
				$_SESSION["allVideosOnairOrder"] = "desc";
			} else {
				$_SESSION["allVideosOrder"] = "ORDER BY video_active desc";
				$_SESSION["allVideosOnairOrder"] = "asc";
			}
			break;
		
	}
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
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		$( "#date_to_select").datepicker({
			altField: "#video_upload_date",
			altFormat: "yy-mm-dd",
			
			 onClose: function(selectedDate) { 
					 
			}
			
			
	
		});
		
	});
	
	
	function publishVideo(video_id) {
		$.ajax({
		  url: 'ajax/publishVideos.php?video_id='+video_id,
		  success: function(data) {
			document.location.href="on_air.php?tab=2";
		  }
		});
	}
	
	function unpublishVideo(video_id) {
		$.ajax({
		  url: 'ajax/unpublishVideos.php?video_id='+video_id,
		  success: function(data) {
			document.location.href="on_air.php?tab=1";
		  }
		});
	}
	
	function changeOrder(video_id) {
		value = $('#order_'+video_id+' option:selected').val();
		$.ajax({
		  url: "ajax/changeVideoOrder.php?id="+video_id+"&value="+value,
		  success: function(data) {
			document.location.reload();
		  }

		});
	}
	
	function delVideo(video_id) {
		if(confirm("Are you sure you want to delete this video?")) {
			$.ajax({
			  url: 'ajax/delVideos.php?video_id='+video_id,
			  success: function(data) {
				document.location.href="on_air.php?tab=1";
			  }
			});
		}
	}
	
	function showVideos(divid,opener) {
		if($('#'+divid).css("display") == "none") {
			$('#'+divid).fadeIn();
			$('#'+opener+divid).find('img').first().attr("src","images/icons/minus.png");
		} else {
			$('#'+divid).fadeOut();
			$('#'+opener+divid).find('img').first().attr("src","images/icons/plus.png");
		}
	}
</script>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        <?php
        include 'include/menu.php'; 
        ?>
        	
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
                            <a href="javascript:;" class="tmtTabSelected tmtTab">All videos</a>                    
                    		<a href="javascript:;" class="tmtTab">On air</a>  
                            <?php
							break;
						case 2:
							$style1="display:none";
							$style2="display:block";
							?>
                            <a href="javascript:;" class="tmtTab">All videos</a>                    
                    		<a href="javascript:;" class="tmtTab tmtTabSelected">On air</a>  
                            <?php
							break;
						default:
							$style1="display:block";
							$style2="display:none";
							?>
                            <a href="javascript:;" class="tmtTabSelected tmtTab">All videos</a>                    
                    		<a href="javascript:;" class="tmtTab">On air</a>  
                            <?php
							break;
					}
					?>            
                                  
                </div>                
                <div class="tmtPanelGroup">                
                    <div class="tmtPanel" tmt:tabpanel="true" style="<?php echo $style1; ?>" id="allvideos_list"> 
                    	<?php
						include "all_videos.php"; 
						?>                   
                    	<div id="empty"></div>
                    </div>                    
                    <div class="tmtPanel" tmt:tabpanel="true" id="onairvideos_list" style="<?php echo $style2; ?>">                    	
                    	<?php
						include "on_air_videos.php"; 
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