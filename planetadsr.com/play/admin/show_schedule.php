<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}


if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["shows"])) {
	$arrShows=$_POST["shows"];
	$qryString = "0";
	for($i=0;$i<count($arrShows); $i++) {
		$qryString .= ",".$arrShows[$i];
	}
		
	switch($_POST["operation"]) {
		
			
		case "delShows":
			$showObj->delShows($qryString);
			header('Location: show_schedule.php');
			break;
		
		
	}                
	
}

include 'include/header.php';
?>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script language="javascript" type="text/javascript">
	$(function() {
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		$( "#search_date_from").datepicker({
			altField: "#date_from",
			altFormat: "yy,mm,dd",
			
			 onClose: function(selectedDate) { 
				
				  
				  $( "#search_date_to").datepicker( "option", "minDate", selectedDate );
				 
						 
			}
			
			
	
		});
		
		$( "#search_date_to").datepicker({
			altField: "#date_to",
			altFormat: "yy,mm,dd",
			
			 onClose: function(selectedDate) { 
				
				  
				  $( "#search_date_from").datepicker( "option", "maxDate", selectedDate );
				 
						 
			}
			
			
	
		});
	});
	
	function goToByScroll(id){
	      $('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');
	}
	
	
	
	
	function delShows(show_id) {
		$.ajax({
		  url: 'ajax/delShows.php?show_id='+show_id,
		  success: function(data) {
			document.location.reload();
		  }
		});
	}
	
	function showDays(date) {
		
		if($('#'+date).css("display") == "none") {
			$('#'+date).fadeIn();
		} else {
			$('#'+date).fadeOut();
		}
	}
	
	function loadShows(date,month) {
		if($('#'+date).css("display") == "none") {
			//ajax call to render days in month
			$.ajax({
			  url: 'ajax/getShowMonthDays.php?date='+date+'&month='+month,
			  success: function(data) {
				$('#'+date).html(data);
				$('#'+date).fadeIn();
			  }
			});
		} else {
			$('#'+date).fadeOut();
		}
		/*if($('#'+date).css("display") == "none") {
			$('#'+date).fadeIn();
		} else {
			$('#'+date).fadeOut();
		}*/
	}
	//do search with ajax in some way
	function doSearch() {
		if(Trim($('#date_from').val()) == '') {
			alert("Insert at least a date");
		} else {
			$.ajax({
			  url: 'ajax/searchSchedule.php?date_from='+$('#date_from').val()+"&date_to="+$('#date_to').val(),
			  success: function(data) {
				$('#table').hide().html(data).show("slide",{"direction":"up"},1000);
				goToByScroll('results');
			  }
			});
		}
	}
	
	function doSearchDrop() {
		
		$.ajax({
		  url: 'ajax/searchSchedule.php?date_from=&date_to=&drop_select='+$('#drop_select').val(),
		  success: function(data) {
			$('#table').hide().html(data).show("slide",{"direction":"up"},1000);
			goToByScroll('results');
		  }
		});
		
	}
	
	function resetSearch() {
		$('#date_from').val('');
		$('#search_date_from').val('');
		$('#date_to').val('');
		$('#search_date_to').val('');
		$( "#search_date_from").datepicker( "option", "maxDate", null);
		
		$.ajax({
		  url: 'ajax/searchSchedule.php',
		  success: function(data) {
			$('#table').hide().html(data).show("slide",{"direction":"up"},1000);
			goToByScroll('results');
			$('#drop_select').val('today');
		  }
		});
	}
	
	function editShow(show_id) {
		$('#old_time_'+show_id).css("display","none");
		$('#edit_button_'+show_id).css("display","none");
		$('#edit_'+show_id).css("display","inline");		
		$('#save_button_'+show_id).css("display","inline");
		$('#new_time_'+show_id).val($('#old_time_'+show_id).html());	
		$('#new_time_'+show_id).timepicker('destroy');
		arrTime = $('#old_time_'+show_id).html().split(":");
		$('#new_time_'+show_id).timepicker({
			hour:parseInt(arrTime[0]),
			minute:parseInt(arrTime[1]),
			second:0
		});
	}
	
	function saveShow(show_id) {
		$.ajax({
		  url: 'ajax/saveShow.php?show_id='+show_id+'&new_time='+$('#new_time_'+show_id).val(),
		  success: function(data) {
			$('#old_time_'+show_id).html($('#new_time_'+show_id).val()+":00");
			$('#old_time_'+show_id).css("display","inline");
			$('#edit_button_'+show_id).css("display","inline");
			$('#edit_'+show_id).css("display","none");		
			$('#save_button_'+show_id).css("display","none");
			$('#new_time_'+show_id).timepicker('destroy');
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
        <!-- action button -->
        <div id="action_bar">
        	<div><a href="new_show.php" class="float_left bg_green mark_white add_button font_bold">ADD VIDEOS</a></div>
            <div id="action" class="font_bold font_333"><a onclick="javascript:delItems('manage_shows','shows[]','delShows','delete')">Remove selected videos</a></div>
        </div> 
        
        <!-- filters -->
        <div class="margin_tb_big">
        	<!-- filter select -->
        	<div class="float_left">
            	<div class="float_left filter_text margin_r">Filter using selected time range:</div>
                <div class="float_left filter_select margin_t">
                	<select id="drop_select">
                    	<option value="today" selected>Today</option>
                        <option value="tomorrow">Tomorrow</option>
                        <option value="next7">Next 7 Days</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="float_left"><a href="javascript:doSearchDrop();" class="bg_333 font_bold mark_white filter_button margin_l">Go</a></div>
            </div>
            
            <div class="float_left filter_text" style="margin-left: 100px;"><strong>OR</strong></div>
            
            <!-- filter period of time -->
            <div class="float_right">
            	<div class="float_left filter_text margin_r">From:</div>
                <div class="float_left margin_r"><input type="text" name="search_date_from" id="search_date_from" class="filter_input"/></div>
                <div class="float_left filter_text margin_r">To:</div>
                <div class="float_left"><input type="text" name="search_date_to" id="search_date_to" class="filter_input" />
                						<input type="hidden" name="date_from" id="date_from" /><input type="hidden" name="date_to" id="date_to" />
                </div>
                <div class="float_left"><a href="javascript:doSearch();" class="bg_333 font_bold mark_white filter_button margin_l">Search</a></div>
                <div class="float_left"><a href="javascript:resetSearch();" class="bg_333 font_bold mark_white filter_button margin_l">Reset</a></div>
            </div>
            
            <div class="cleardiv"></div>
           
        </div>
        
        
        <!-- schedule -->     
        <a id="results" name="results"></a>
        <form name="manage_shows" action="" method="post">
            <input type="hidden" name="operation" />
            <input type="hidden" name="shows[]" value=0 />
            <div id="table_container">
                <div id="table">
                	<?php
					include 'schedule.php';
					?>
                    
                    
                </div>
                <div id="rowspace"></div>
            </div>
        </form>      
     </div>
   </div>
</div>

<?php 
include 'include/footer.php';
?>