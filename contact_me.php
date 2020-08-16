<?php
if($_POST)
{
	$to_Email   	= "jumperandtarry@gmail.com"; //Replace with recipient email address
	$subject        = 'Wedding RSVP'; //Subject line for emails
	
	
	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	
		//exit script outputting json data
		$output = json_encode(
		array(
			'type'=>'error', 
			'text' => 'Request must come from Ajax'
		));
		
		die($output);
    } 
	
	//check $_POST vars are set, exit if any missing
	if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userMessage"]) || !isset($_POST["userSong"]) || !isset($_POST["userMeal"]))
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
	$user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
	$user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
	$user_Song		  = filter_var($_POST["userSong"], FILTER_SANITIZE_STRING);
	$user_Meal        = filter_var($_POST["userMeal"], FILTER_SANITIZE_STRING);
	
	$user_Message = str_replace("\&#39;", "'", $user_Message);
	$user_Message = str_replace("&#39;", "'", $user_Message);

	$user_Song = str_replace("\&#39;", "'", $user_Song);
	$user_Song = str_replace("&#39;", "'", $user_Song);

	$user_Meal = str_replace("\&#39;", "'", $user_Meal);
	$user_Meal = str_replace("&#39;", "'", $user_Meal);
	
	//additional php validation
	if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
		die($output);
	}
	if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
		die($output);
	}
	if(strlen($user_Message)<1) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'You need to respond, will you be joining?'));
		die($output);
	}
	if(strlen($user_Song)<3) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'That cant be a valid song! Be a good sport..'));
		die($output);
	}
	if(strlen($user_Meal)<2) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Please give us you meal choice. If you are not coming, just type "NA"'));
		die($output);
	}
	
	//proceed with PHP email.
	$headers = 'From: '.$user_Email.'' . "\r\n" .
	'Reply-To: '.$user_Email.'' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$sentMail = @mail($to_Email, $subject, "RSVP: ".$user_Message."\r\n\n"."Song: ".$user_Song."\r\n\n".$user_Meal."\r\n\n".'Names: '.$user_Name."\r\n" .'Email: '.$user_Email, $headers);
	
	if(!$sentMail)
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Name .'! Thank you for your email'));
		die($output);
	}
}
?>