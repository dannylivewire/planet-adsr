<?php
if(isset($_GET["date_from"]) && $_GET["date_from"]!='' && isset($_GET["date_to"]) && $_GET["date_to"]!='') {
	$arrayDates = $listObj->getShowDatesList($_GET["date_from"],$_GET["date_to"],'');
	$arrayMonths = $listObj->getShowMonthsList($_GET["date_from"],$_GET["date_to"],'');
} else if(isset($_GET["date_from"]) && $_GET["date_from"]!='') {
	$arrayDates = $listObj->getShowDatesList($_GET["date_from"],'','');
	$arrayMonths = $listObj->getShowMonthsList($_GET["date_from"],'','');
} else if(isset($_GET["drop_select"])) {
	$arrayDates = $listObj->getShowDatesList('','',$_GET["drop_select"]);
	$arrayMonths = $listObj->getShowMonthsList('','',$_GET["drop_select"]);
} else {
	$arrayDates = $listObj->getShowDatesList('','','today'); //default
	$arrayMonths = $listObj->getShowMonthsList('','','today');
}
?>
<div class="shows_title_col1">
    <div id="table_cell">#</div>
</div>
<div class="shows_title_col2">
    <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_shows','shows[]');" /></div>
</div>
<div class="shows_title_col3">
    <div id="table_cell">Title</div>
</div>
<div class="shows_title_col4">
    <div id="table_cell">Time</div>
</div>
<div class="shows_title_col5">
    <div id="table_cell">Source</div>
</div> 
<div class="shows_title_col6">
    <div id="table_cell"></div>
</div>

<div id="empty"></div>
<?php


for($z=0;$z<count($arrayMonths); $z++) {
	?>
    <div class="sources_divider" style="cursor:pointer" onclick="javascript:showDays('<?php echo $arrayMonths[$z]; ?>');">
        <div class="source_row">MONTH: <?php echo $arrayMonths[$z]; ?><span style="float:right;margin-right:10px"><?php echo $listObj->getTotShowsPerMonth($arrayMonths[$z]); ?> scheduled videos</span></div>
    </div>
    <div id="empty"></div>
    <div id="<?php echo $arrayMonths[$z]; ?>" style="display:none">
    
    <?php
	for($j=0;$j<count($arrayDates);$j++) {		
		
		$i = 1;
		$arrayShows = $listObj->getShowsList($arrayDates[$j],$arrayMonths[$z]);
		if(count($arrayShows)>0) {
			//format date
			?>
			<div class="sources_divider" style="cursor:pointer" onclick="javascript:loadShows('<?php echo $arrayDates[$j]; ?>','<?php echo $arrayMonths[$z]; ?>');">
				<div class="source_row">DATE: <?php echo $arrayDates[$j]; ?><span style="float:right;margin-right:10px"><?php echo $listObj->getTotShowsPerDay($arrayDates[$j]); ?> scheduled videos</span></div>
			</div>
			<div id="empty"></div>
			<div id="<?php echo $arrayDates[$j]; ?>" style="display:none">
			<?php
			
			/*foreach($arrayShows as $showId => $show) {												
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
			}*/
			?>
			</div>
			<?php
		}
	}
	?>
    </div>
    <?php
}


?>
<div id="empty"></div>