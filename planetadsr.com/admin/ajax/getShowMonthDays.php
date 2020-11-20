<?php
include '../common.php';
$month = $_GET["month"];
$date = $_GET["date"];
$i = 1;
$arrayShows = $listObj->getShowsList($date,$month);
if(count($arrayShows)>0) {
	foreach($arrayShows as $showId => $show) {												
		if($i % 2) {
			$class="alternate_table_row_white";
		} else {
			$class="alternate_table_row_grey";
		}
		
		?>
		
		
	
		<div class="shows_row_col1" class="<?php echo $class; ?>">
			<div id="table_cell"><?php echo $i; ?></div>
		</div>
		<div class="shows_row_col2" class="<?php echo $class; ?>">
			<div id="table_cell"><input type="checkbox" name="shows[]" value="<?php echo $showId; ?>" onclick="javascript:disableSelectAll('manage_shows',this.checked);" /></div>
		</div>
		<div class="shows_row_col3" class="<?php echo $class; ?>">
			<div id="table_cell"><?php echo $show["video_title"];?></div>
		</div>
		<div class="shows_row_col4" class="<?php echo $class; ?>">
			<div id="table_cell">
				<span id="old_time_<?php echo $showId; ?>"><?php echo $show["show_time"]; ?></span>
				<span style="display:none" id="edit_<?php echo $showId; ?>"><input type="text" style="width:100px" id="new_time_<?php echo $showId; ?>" /></span>
				<span id="edit_button_<?php echo $showId; ?>">&nbsp;<input type="button" value="edit" onclick="javascript:editShow(<?php echo $showId; ?>);" /></span>
				<span id="save_button_<?php echo $showId; ?>" style="display:none">&nbsp;<input type="button" value="save" onclick="javascript:saveShow(<?php echo $showId; ?>);" /></span>
			</div>
		</div>
		<div class="shows_row_col5" class="<?php echo $class; ?>">
			<div id="table_cell"><?php echo $show["source"]; ?></div>
		</div> 
	   
		<div class="shows_row_col6" class="<?php echo $class; ?>">
			<div id="table_cell"><a href="javascript:delShows(<?php echo $showId; ?>);"><img src="images/icons/delete.png" border=0  alt="delete" title="delete"/></a></div>
		</div>
		<div id="empty"></div>
		<?php
		$i++;
	}
}
?>