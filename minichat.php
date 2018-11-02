<?php 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Mini-chat</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
	<h1>Mini-chat</h1>
</header>
<main>
	<article>
		<?php 
		if(isset($_COOKIE['pseudo'])){
			echo '<p class="info">Dernier message envoyé par '.ucfirst($_COOKIE['pseudo']).'</p>';
		}else{
			echo '<p class=warning>Pas encore de conversation</p>';
		}
		?>
	</article>
	<article>
		<form action="minichat_post.php" method="POST">
			<label for="pseudo">Pseudo: <input type="text" name="pseudo" id="pseudo" value='<?php echo ucfirst($_COOKIE['pseudo']); ?>' required></label><br>
			<label for="message">Message: <input type="text" name="message" id="message" required></label><br>
			<input type="submit" name="submit" value="Envoyer">
		</form>
	</article>
	<article class="conversation">
		<h3>Conversation:</h3>
		<?php 
			try{
				$connection = new PDO("mysql:host=localhost;dbname=minichat;charset=utf8", 'root', '');
			}catch(Exception $error){
				die($error->getMessage());
			}

			$sql_message = 'SELECT users.name AS name, messages.message AS message, messages.user_id AS user_id, date_format(messages.date_creation, "%d/%m/%Y à %H:%i:%s") AS date_creation FROM messages INNER JOIN users ON user_id = users.id ORDER BY date_creation DESC';

			$getMessages = $connection->query($sql_message);
			while($message = $getMessages->fetch()){
				echo '<p>[Le '.$message['date_creation'].'] <b>'.ucfirst($message['name']).'</b>: '.$message['message'].'</p>';
			}

			$getMessages->closeCursor();
		?>
	</article>
</main>
</body>
</html>