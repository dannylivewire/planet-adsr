<script>
	$(function() {
		// hide all submenus
		$("#s1").hide();
		$("#s2").hide();
		
		// show submenu 1
		$("#p1").mouseenter(
		  function() {
			if ($("#s1").is(":hidden")) $("#s1").slideDown(); else $("#s1").slideUp();
		  }
		);
		
		$("#p2").mouseenter(
		  function() {
			if ($("#s2").is(":hidden")) $("#s2").slideDown(); else $("#s2").slideUp();
		  }
		);	
		
		
		//show submenu 1
		$("#p1").mouseleave(
		  function() {
			$("#s1").slideUp();
		  }
		);	
		
		$("#p2").mouseleave(
		  function() {
			$("#s2").slideUp();
		  }
		);	
		
				
	  }
	);
	
	function alertConfigure() {
		alert("You must adjust settings before you can have full menu working");
	}
	
	function alertManagement() {
		alert("As you've chosen auto video management, this menu is not available");
	}
	
	function alertSchedule() {
		alert("As you've chosen the schedule management, this menu is not available");
	}
	
	function alertNoSchedule() {
		alert("As you've chosen to not have the schedule management, this menu is not available");
	}
</script>

<!-- header -->
<div id="header_container">    
	<?php
    if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != 0) { 
	?>    
        <div class="header_left">
            <div class="header_title"><h1>CONTROL PANEL</h1></div>
            <div class="link_website"><a href="../" target="_blank">go to site</a></div>
        </div>
        <div class="header_identity_container">
            <div class="header_identity">Logged as: <strong><?php echo $_SESSION["admin_name"]?></strong></div>
            <div class="header_logout"><strong><a href="logout.php">LOGOUT</a></strong></div>
        </div>        
        <div id="cleardiv"></div>        
        <div class="line_dotted"></div>
    
    <?php
	} else { 
	?>
        <div class="header_left">
            <div class="header_title"><h1>CONTROL PANEL</h1></div>
        </div>        
        <div id="cleardiv"></div>        
        <div class="line_dotted"></div>    
    <?php 
	} 
	?>    
</div>
<div id="cleardiv"></div>    
<!-- menu -->
<div id="menu_container">
    <div id="menu">
    <ul>
        <?php
        if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != 0) { 
		?>
        	<li><a href="welcome.php" class="home_button"></a></li>
            <li><a href="configuration.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"configuration.php")) { echo "style='background-color: #666;'"; }?>>SETTINGS</a></li>
            
            <?php
			if($settingObj->getMeasureId() == '0') {
				?>
                <li><a href="javascript:alertConfigure();" style='color: #666;'>VIDEO ARCHIVE</a></li>  
                <li><a href="javascript:alertConfigure();" style='color: #666;'>ON AIR MANAGEMENT</a></li>
                <li><a href="javascript:alertConfigure();" style='color: #666;'>SCHEDULE</a></li>
				<li><a href="javascript:alertConfigure();" style='color: #666;'>CHANGE ADMIN PASSWORD</a></li>
                <?php
			} else {
				if($settingObj->getManagement() == 0) {
					?>
					<li id="p2"><a href="#" <?php if(stristr($_SERVER["SCRIPT_NAME"],"video_archive.php") || stristr($_SERVER["SCRIPT_NAME"],"deleted_videos.php")) { echo "style='background-color: #666;'"; }?>>VIDEO ARCHIVE</a>
                    	<ul id="s2">
                            <li><a href="video_archive.php">MANAGE</a></li>
                            <li><a href="deleted_videos.php">DELETED VIDEOS</a></li>              
                        </ul>
                    </li>
                    	
					<!--<li><a href="deleted_videos.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"deleted_videos.php")) { echo "style='background-color: #666;'"; }?>>DELETED VIDEOS</a></li>-->
					<?php
					if($settingObj->getScheduleVideo() == 1) {
						?>
						<li><a href="javascript:alertSchedule();" style='color: #666;'>ON AIR MANAGEMENT</a></li>
						<li><a href="show_schedule.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"show_schedule.php") || stristr($_SERVER["SCRIPT_NAME"],"new_show.php")) { echo "style='background-color: #666;'"; }?>>SCHEDULE</a></li>
						
						<?php
					} else {
						?>
						<li><a href="on_air.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"on_air.php")) { echo "style='background-color: #666;'"; }?>>ON AIR MANAGEMENT</a></li>
						<li><a href="javascript:alertNoSchedule();" style='color: #666;'>SCHEDULE</a></li>
						<?php
					}
				} else {
					?>
                    <li><a href="javascript:alertManagement();" style='color: #666;'>VIDEO ARCHIVE</a></li>                
                    <li><a href="javascript:alertManagement();" style='color: #666;'>ON AIR MANAGEMENT</a></li>
                    <li><a href="javascript:alertManagement();" style='color: #666;'>SCHEDULE</a></li>
                    <?php
				}
				?>
				
				<li><a href="password.php" <?php if(stristr($_SERVER["SCRIPT_NAME"],"password.php")) { echo "style='background-color: #666;'"; }?>>CHANGE ADMIN PASSWORD</a></li>
				<?php
			}
			?>
        <?php
		}
		?>
    </ul>
   </div>
</div>