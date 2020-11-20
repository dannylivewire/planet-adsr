<?php
include '../common.php';
$video_id = $_GET["video_id"];
$videoObj->setVideo($video_id);
?>
<script>
	//set datepicker and timepicker here
	$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
	$( "#datepicker_date<?php echo $video_id?>_1").datepicker({
		altField: "#show_date<?php echo $video_id; ?>_1",
		altFormat: "yy,mm,dd",
		minDate: new Date(),
		 onClose: function(selectedDate) { 
			
			  
			  $( "#recurrency_end_date<?php echo $video_id; ?>").datepicker( "option", "minDate", selectedDate );
			 
					 
		}
		
		

	});
	
	$( "#recurrency_end_date<?php echo $video_id?>").datepicker({
		altField: "#end_date<?php echo $video_id; ?>",
		altFormat: "yy,mm,dd",
		minDate: new Date(),
		 onClose: function(selectedDate) { 
			
			  
			  $( "#datepicker_date<?php echo $video_id; ?>_1").datepicker( "option", "maxDate", selectedDate );
			 
					 
		}
		
		

	});
	
	$('#show_time<?php echo $video_id; ?>_1').timepicker('destroy');
	$('#show_time<?php echo $video_id; ?>_1').timepicker({
		
	});
	
	function showRecurrency(video_id) {
		if($('#video_recurrency_'+video_id).css("display") == "none") {
			$('#video_recurrency_'+video_id).fadeIn();
		} else {
			$('#video_recurrency_'+video_id).fadeOut();
		}
	}
	function addDateTime(video_id,num) {
		$('#datetime'+video_id).append('<br>Date:&nbsp;<input type="text" id="datepicker_date'+video_id+'_'+num+'" /><input type="hidden" name="show_date'+video_id+'[]" id="show_date'+video_id+'_'+num+'" />&nbsp;&nbsp;Time:&nbsp;<input type="text" name="show_time'+video_id+'[]" id="show_time'+video_id+'_'+num+'" />');
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
	function resetDateTime(video_id) {
		$('#datetime'+video_id).html('Date:&nbsp;<input type="text" id="datepicker_date'+video_id+'_1" /><input type="hidden" name="show_date'+video_id+'[]" id="show_date'+video_id+'_1" /> &nbsp;Time:&nbsp;<input type="text" name="show_time'+video_id+'[]" id="show_time'+video_id+'_1" />&nbsp;<input type="button" value="+" onclick="javascript:addDateTime('+video_id+',2);" style="width:auto"/>&nbsp;<input type="button" value="reset" onclick="javascript:resetDateTime('+video_id+');" style="width:auto"/>');
		$('#recurrency'+video_id).fadeIn();
	}

</script>
<div class="selected_video_row">
    <div class="selected_videos_col1">
        <input type="checkbox" name="selected_video[]" value="<?php echo $video_id; ?>" checked />
    </div>
    <div class="selected_videos_col2">
        <?php echo $videoObj->getVideoTitle(); ?> - <?php echo $videoObj->getVideoDuration(); ?> seconds
    </div>
    <div class="selected_videos_col3">
        <div id="datetime<?php echo $video_id; ?>">
            Date:&nbsp;<input type="text" id="datepicker_date<?php echo $video_id;?>_1" />
            <input type="hidden" name="show_date<?php echo $video_id;?>[]" id="show_date<?php echo $video_id; ?>_1" />
            &nbsp;Time:&nbsp;<input type="text" name="show_time<?php echo $video_id;?>[]" id="show_time<?php echo $video_id;?>_1" />&nbsp;<input type="button" id="plus_button" value="+" onclick="javascript:addDateTime(<?php echo $video_id; ?>,2);" style="width:auto"/>&nbsp;<input type="button" value="reset" onclick="javascript:resetDateTime(<?php echo $video_id; ?>);" style="width:auto"/>
        </div>
    </div>
    <div id="empty"></div>
    <div class="selected_video_recurrency" id="recurrency<?php echo $video_id; ?>">
        Recurrent:&nbsp;<input type="checkbox" name="recurrency<?php echo $video_id;?>" value="1" onclick="javascript:showRecurrency(<?php echo $video_id; ?>);" />
        <div id="video_recurrency_<?php echo $video_id;?>" style="display:none" class="recurrency">
            
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="1" checked="checked">&nbsp;Mondays&nbsp;&nbsp;
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="2" checked="checked">&nbsp;Tuesdays&nbsp;&nbsp;
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="3" checked="checked">&nbsp;Wednesdays&nbsp;&nbsp;
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="4" checked="checked">&nbsp;Thursdays&nbsp;&nbsp;
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="5" checked="checked">&nbsp;Fridays&nbsp;&nbsp;
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="6" checked="checked">&nbsp;Saturdays&nbsp;&nbsp;
            <input type="checkbox" name="show_weekday<?php echo $video_id; ?>[]" id="show_weekday_<?php echo $video_id; ?>" value="7" checked="checked">&nbsp;Sundays&nbsp;&nbsp;
            End recurrency:&nbsp;<input type="text" id="recurrency_end_date<?php echo $video_id; ?>" style="width:130px" />
            <input type="hidden" name="end_date<?php echo $video_id;?>" id="end_date<?php echo $video_id; ?>" />
            <div id="empty"></div>
        </div>
    </div>
    <div id="rowline"></div>
</div>
    
   