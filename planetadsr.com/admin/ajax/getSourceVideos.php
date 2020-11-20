<?php
include '../common.php';
$source_id = $_GET["source_id"];
$arrayVideos = $listObj->getVideosList($source_id);
?>
<script>
	function showRecurrency(video_id) {
		if($('#video_recurrency_'+video_id).css("display") == "none") {
			$('#video_recurrency_'+video_id).fadeIn();
		} else {
			$('#video_recurrency_'+video_id).fadeOut();
		}
	}
	function addDateTime(video_id,num) {
		$('#datetime'+video_id).append('<div id="'+video_id+'_'+num+'">Date:&nbsp;<input type="text" id="datepicker_date'+video_id+'_'+num+'" /><input type="hidden" name="show_date'+video_id+'[]" id="show_date'+video_id+'_'+num+'" />&nbsp;&nbsp;Time:&nbsp;<input type="text" name="show_time'+video_id+'[]" id="show_time'+video_id+'_'+num+'" />&nbsp;<a href="javascript:resetTime('+video_id+','+num+');"><img src="images/icons/trash.png" border=0 alt="reset time" title="reset time" /></a>&nbsp;<input type="button" value="-" onclick="javascript:removeDateTime('+video_id+','+num+');"  style="width:auto"></div>');
		$('#recurrency'+video_id).fadeOut();
		$('#plus_button').attr("onclick","javascript:addDateTime("+video_id+","+(num+1)+");");
		$( "#datepicker_date"+video_id+"_"+num).datepicker({
			altField: "#show_date"+video_id+"_"+num,
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
				
				 
						 
			}
			
			

		});
		
		
		
		$('#show_time'+video_id+'_'+num).timepicker('destroy');
		$('#show_time'+video_id+'_'+num).timepicker({
			
		});
	}
	function removeDateTime(video_id,num) {
		$('#'+video_id+"_"+num).remove();
		if($('#datepicker_date'+video_id+'_1').val()!='' && document.getElementsByName('show_date'+video_id+'[]').length==1) {
			$('#recurrency'+video_id).fadeIn();
		} else {
			$('#recurrency'+video_id).fadeOut();
		}
		
	}
	
	function resetDateTime(video_id) {
		$('#datetime'+video_id).html('Date:&nbsp;<input type="text" id="datepicker_date'+video_id+'_1" />&nbsp;<a href="javascript:resetDate('+video_id+',1);"><img src="images/icons/trash.png" border=0 alt="reset date" title="reset date" /></a><input type="hidden" name="show_date'+video_id+'[]" id="show_date'+video_id+'_1" /> &nbsp;Time:&nbsp;<input type="text" name="show_time'+video_id+'[]" id="show_time'+video_id+'_1" />&nbsp;<a href="javascript:resetTime('+video_id+',1);"><img src="images/icons/trash.png" border=0 alt="reset time" title="reset time" /></a>&nbsp;<input type="button" value="+" onclick="javascript:addDateTime('+video_id+',2);" style="width:auto"/>&nbsp;<input type="button" value="reset" onclick="javascript:resetDateTime('+video_id+');" style="width:auto"/>');
		$('#recurrency'+video_id).fadeOut();
		
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		$( "#datepicker_date"+video_id+"_1").datepicker({
			altField: "#show_date"+video_id+"_1",
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
			 	
			 	  
			 	  $( "#recurrency_end_date"+video_id).datepicker( "option", "minDate", selectedDate );
				  if(selectedDate!='' && document.getElementsByName('show_date'+video_id+'[]').length==1) {
				  	$('#recurrency'+video_id).fadeIn();
				  }
				 		 
			}
			
			

		});
		
		$( "#recurrency_end_date"+video_id).datepicker({
			altField: "#end_date"+video_id,
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
			 	
			 	  
			 	  $( "#datepicker_date"+video_id+"_1").datepicker( "option", "maxDate", selectedDate );
				 
				 		 
			}
			
			

		});
		
		$('#show_time'+video_id+'_1').timepicker('destroy');
		$('#show_time'+video_id+'_1').timepicker({
			
		});
	}
	
	function resetTime(video_id,num) {
		$('#show_time'+video_id+'_'+num).val('');
	}
	
	function resetDate(video_id,num) {
		$('#datepicker_date'+video_id+'_'+num).val('');
		$('#recurrency'+video_id).fadeOut();
	}
</script>
<?php

foreach($arrayVideos as $videoId => $video) {
	?>
    <script>
		//set datepicker and timepicker here
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		$( "#datepicker_date<?php echo $videoId?>_1").datepicker({
			altField: "#show_date<?php echo $videoId; ?>_1",
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
			 	
			 	  
			 	  $( "#recurrency_end_date<?php echo $videoId; ?>").datepicker( "option", "minDate", selectedDate );
				  if(selectedDate!='' && document.getElementsByName('show_date<?php echo $videoId; ?>[]').length==1) {
				  	$('#recurrency<?php echo $videoId; ?>').fadeIn();
				  }
				 		 
			}
			
			

		});
		
		$( "#recurrency_end_date<?php echo $videoId?>").datepicker({
			altField: "#end_date<?php echo $videoId; ?>",
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
			 	
			 	  
			 	  $( "#datepicker_date<?php echo $videoId; ?>_1").datepicker( "option", "maxDate", selectedDate );
				 
				 		 
			}
			
			

		});
		
		$('#show_time<?php echo $videoId; ?>_1').timepicker('destroy');
		$('#show_time<?php echo $videoId; ?>_1').timepicker({
			
		});
		
	</script>
    <div class="selected_video_row">
        <div class="selected_videos_col1">
            <input type="hidden" name="selected_video[]" value="<?php echo $videoId; ?>" checked />
        </div>
        <div class="selected_videos_col2">
            <?php echo $video["video_title"]; ?> - <?php echo $listObj->milliToTimeString($video["video_duration"]*1000); ?> seconds
        </div>
        <div class="selected_videos_col3">
        	<div id="datetime<?php echo $videoId; ?>">
                Date:&nbsp;<input type="text" id="datepicker_date<?php echo $videoId;?>_1" />&nbsp;<a href="javascript:resetDate(<?php echo $videoId; ?>,1);"><img src="images/icons/trash.png" border=0 alt="reset date" title="reset date" /></a>
                <input type="hidden" name="show_date<?php echo $videoId;?>[]" id="show_date<?php echo $videoId; ?>_1" />
                &nbsp;Time:&nbsp;<input type="text" name="show_time<?php echo $videoId;?>[]" id="show_time<?php echo $videoId;?>_1" />&nbsp;<a href="javascript:resetTime(<?php echo $videoId; ?>,1);"><img src="images/icons/trash.png" border=0 alt="reset time" title="reset time" /></a>&nbsp;<input type="button" id="plus_button" value="+" onclick="javascript:addDateTime(<?php echo $videoId; ?>,2);" style="width:auto"/>&nbsp;<input type="button" value="reset" onclick="javascript:resetDateTime(<?php echo $videoId; ?>);" style="width:auto"/>
            </div>
        </div>
        <div id="empty"></div>
        
        <div class="selected_video_recurrency" id="recurrency<?php echo $videoId; ?>" style="display:none; background-color: #ccc; margin: 10px 0">
        	<div style="float:right;">Recurrent:&nbsp;<input type="checkbox" name="recurrency<?php echo $videoId;?>" value="1" onclick="javascript:showRecurrency(<?php echo $videoId; ?>);" /></div>
            <div id="empty"></div>
            <div id="video_recurrency_<?php echo $videoId;?>" style="display:none; margin: 10px 0; padding-bottom: 10px;" class="recurrency">
            	
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="1" checked="checked">&nbsp;Mondays&nbsp;&nbsp;
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="2" checked="checked">&nbsp;Tuesdays&nbsp;&nbsp;
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="3" checked="checked">&nbsp;Wednesdays&nbsp;&nbsp;
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="4" checked="checked">&nbsp;Thursdays&nbsp;&nbsp;
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="5" checked="checked">&nbsp;Fridays&nbsp;&nbsp;
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="6" checked="checked">&nbsp;Saturdays&nbsp;&nbsp;
                <input type="checkbox" name="show_weekday<?php echo $videoId; ?>[]" id="show_weekday_<?php echo $videoId; ?>" value="7" checked="checked">&nbsp;Sundays&nbsp;&nbsp;
                End recurrency:&nbsp;<input type="text" id="recurrency_end_date<?php echo $videoId; ?>" style="width:130px" />
                <input type="hidden" name="end_date<?php echo $videoId;?>" id="end_date<?php echo $videoId; ?>" />
                <div id="empty"></div>
            </div>
        </div>
        <div id="rowline"></div>
    </div>
    <?php
}
?>