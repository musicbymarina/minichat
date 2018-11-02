<?php 

	$name = htmlspecialchars($_POST['pseudo']);
	$message = htmlspecialchars($_POST['message']);

	setcookie('pseudo', $name, time() + (86400*365), null, null, false, true);

	try{
		$connection = new PDO("mysql:host=localhost;dbname=minichat;charset=utf8", 'root', '');
	}catch(Exception $error){
		die($error->getMessage());
	}

	$sql_verifyUser = 'SELECT * from users WHERE name = :name';
	$verify_user = $connection->prepare($sql_verifyUser);
	$verify_user->execute(array('name'=>$name));
	while($verify_if_on_db = $verify_user->fetch()){
	 break;
	}
	if(!$verify_if_on_db){
		echo 'pas de pseudo à ce nom<br>';
		$verify_user->closeCursor();
		$sql_addUser = 'INSERT INTO users(name) VALUES(:name)';
		$add_user = $connection->prepare($sql_addUser);
		$add_user->execute(array('name'=>$name));
		echo 'pseudo crée<br>';
		$add_user->closeCursor();
	} 
		$verify_user->closeCursor();
		$sql_addMessage = 'INSERT INTO messages(message, user_id, date_creation) VALUES(:message, (SELECT id FROM users WHERE name = :name), NOW())';
	$add_message = $connection->prepare($sql_addMessage);
	$add_message->execute(array('message'=>$message, 'name'=>$name));
	$add_message->closeCursor();
	echo 'message crée<br>';
	
	
	header('Location:minichat.php');

?>