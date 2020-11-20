<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}
if(isset($_POST["password_confirm"])) {	
	$adminObj->updatePassword();
	?>
    <script>
		alert("Password changed successfully");
		document.location.href="welcome.php";
	</script>
    <?php	
}

include 'include/header.php';
?>
<script language="javascript" type="text/javascript">
	function checkData(frm) {
		with(frm) {
			if(Trim(old_password.value)== '') {
				alert("Insert your old password");
				return false;
			} else if(Trim(password.value) == '' || Trim(password_confirm.value) == '' || Trim(password.value) != Trim(password_confirm.value)) {
				alert("New password and password confirm fields must have the same value");
				return false;
			} else {
				$.ajax({
				  url: 'ajax/checkOldPassword.php?old='+old_password.value,
				  success: function(data) {
					if(data == 1) {
						frm.submit();
					} else {
						alert("Old password is not correct");
					}
				  }
				});
				return false;
			}
		}
	}
</script>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        <?php
        include 'include/menu.php'; 
        ?>
        <div id="form_container">
        	<form name="editsettings" action="" method="post" onsubmit="return checkData(this);">           
                
                <div id="label_input">
                    <label for="old_password">1. Old password</label><br /><span id="span_description">(your current password)</span>
                </div>
                <div id="input_box">
                    <input type="password" class="long_input_box" id="old_password" name="old_password" value="">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="old_password">2. New password</label>
                </div>
                <div id="input_box">
                    <input type="password" class="long_input_box" id="password" name="password" value="">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                <div id="label_input">
                    <label for="old_password">3. Confirm password</label><br /><span id="span_description">(retype your new password)</span>
                </div>
                <div id="input_box">
                    <input type="password" class="long_input_box" id="password_confirm" name="password_confirm" value="">
                   
                </div>
                <div id="rowspace"></div>
                <div id="rowline"></div>
                <div id="rowspace"></div>
                
                
                <!-- bridge buttons -->
                <div class="bridge_buttons_container">
                    <!-- cancel -->
                    <div class="admin_button cancel_button" ><a href="javascript:document.location.href='';"></a></div>
                    
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