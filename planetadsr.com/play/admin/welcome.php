<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}
include 'include/header.php';
?>

<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        	<?php
            include 'include/menu.php';
			?>        
           <!-- welcome -->
            <div id="welcome_container">
                <div class="logo_admin"><img src="images/logo_admin.gif"  /></div>
                <div class="welcome_text"><p>Welcome to YouTube Video Gallery Scheduling - Admin Panel<br>Use the menu above to manage all configurations and contents</p></div>
                <?php
				if($settingObj->getMeasureId() == '0') {
					?>
                    <div class="welcome_text" style="padding: 15px; background-color: #ccc; color: #000;"><p>Hey Admin,<br />
                    it seems like you did not adjust the settings.<br />
                    Remember, <strong>if you skip this step, the YouTube Video Gallery Scheduling cannot work.</strong><br />
                    Let's go to start now!</p></div>
                    <?php
				}
				?>
            </div>
        
        
        </div>
    </div>
</div>
<?php 
include 'include/footer.php';
?>