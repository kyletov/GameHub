<?php
	$_REQUEST['fname']=isset($_REQUEST['fname']) ? $_REQUEST['fname'] : $_SESSION['user']->getFirstName();
	$_REQUEST['lname']=isset($_REQUEST['lname']) ? $_REQUEST['lname'] : $_SESSION['user']->getLastName();
	$_REQUEST['gender']=isset($_REQUEST['gender']) ? $_REQUEST['gender'] : $_SESSION['user']->getGender();
	$_REQUEST['age']=isset($_REQUEST['age']) ? $_REQUEST['age'] : $_SESSION['user']->getAge();
	$_REQUEST['bio']=isset($_REQUEST['bio']) ? $_REQUEST['bio'] : $_SESSION['user']->getBio();

	$userid=$_SESSION['user']->getUserid();

	$edit_profile_active=$_SESSION['state']=="edit_profile";
	$display_edit_profile='<td><form method="post"><button class="link" type="submit" name="submit" value="Edit Profile">Edit Profile</button></form></td>';
	if($edit_profile_active){
		$display_edit_profile='<td hidden><form method="post"><button class="link" type="submit" name="submit" value="Edit Profile">Edit Profile</button></form></td>';
	}

	$select_gender=array();
	$gender_list=array("Male", "Female", "Other");
	foreach($gender_list as $gender){
		if($_REQUEST['gender'] == $gender){
			$select_gender[]='<input type="radio" name="gender" value="'.$gender.'" checked >'.$gender;
		} else {
			$select_gender[]='<input type="radio" name="gender" value="'.$gender.'" >'.$gender;
		}
	}

	$max_age = 100;
	$select_age=array();
	$select_age[]='<option value=0 ></option>';
	for ($age=1; $age<=$max_age; $age++) {
		if($_REQUEST['age']==$age){
			$select_age[]='<option value='.$age.' selected >'.$age.'</option>';
		} else {
			$select_age[]='<option value='.$age.' >'.$age.'</option>';
		}
	}

	$saved_msg="";
	$isSaved=isset($_SESSION['saved_msg']);
	if($isSaved){
		$saved_msg=$_SESSION['saved_msg'];
		unset($_SESSION['saved_msg']);
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games</title>
	</head>
	<body>
		<header><img style="margin:0; padding:0" src="Title.jpg" /></header>
		<?php include_once("lib/navigation.php"); ?>
		<main>
			<?php if($isSaved)echo($saved_msg); ?>
			<table>
				<tr>
					<td><h1>User Profile</h1></td>
					<?php echo($display_edit_profile); ?>
				</tr>
			</table>
			<table>
				<tr>
					<td><h3>User: </h3></td>
					<td><?php echo($userid); ?></td>
				</tr>
				<?php
					if($edit_profile_active){
				?>
						<form method="post">
							<tr><td><label id="fname"><h3>First Name: </h3></label></td><td><input type="text" name="fname" value="<?php echo($_REQUEST['fname']) ?>"/></td></tr>
							<tr><td><label id="lname"><h3>Last Name: </h3></label></td><td><input type="text" name="lname" value="<?php echo($_REQUEST['lname']) ?>"/></td></tr>
							<tr><td><label id="gender"><h3>Gender: </h3></label></td><td>
								<?php foreach($select_gender as $radio_button)echo($radio_button);?>
							</td></tr>
							<tr><td><label id="age"><h3>Age: </h3></label></td>
								<td><select name="age">
									<?php foreach($select_age as $option)echo($option); ?>
								</select></td></tr>
							<tr><td><label id="bio"><h3>Bio: </h3></label></td><td><textarea name="bio" rows="5" cols="50" placeholder="Write something about yourself (max 250 characters)." /><?php echo($_REQUEST['bio']); ?></textarea></td></tr>
							<tr><td><label id="password">Enter password to confirm the above changes: </label></td><td><input type="password" name="password" /></td></tr>
							<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Save changes" />&nbsp;<input type="submit" name="submit" value="Cancel" /></td></tr>
							<tr><td>&nbsp;</td><td><?php echo(view_errors($errors)); ?></td></tr>
						</form>
				<?php
					} else {
				?>
						<tr><td><h3>First Name: </h3></td><td><?php echo($_SESSION['user']->getFirstName()) ?></td></tr>
						<tr><td><h3>Last Name: </h3></td><td><?php echo($_SESSION['user']->getLastName()) ?></td></tr>
						<tr><td><h3>Gender: </h3></td><td><?php echo($_SESSION['user']->getGender()) ?></td></tr>
						<tr><td><h3>Age: </h3></td><td><?php echo($_SESSION['user']->getAge()) ?></td></tr>
						<tr><td><h3>Bio: </h3></td><td><?php echo($_SESSION['user']->getBio()); ?></td></tr>
				<?php
					}
				?>
			</table>
		</main>
		<footer>
		</footer>
	</body>
</html>
