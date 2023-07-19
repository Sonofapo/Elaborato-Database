<h1><?php echo $_VARS["mode"] == "login" ? "Login" : "Iscriviti" ?></h1>
<form action="index.php" method="post">
	<input type="hidden" name="action" value="user" />
	<input type="hidden" name="mode" value="<?php echo $_VARS["mode"] == "login" ? "login" : "subscribe" ?>" />

	<label for="usr">Username: </label>
	<input id="usr" name="usr" type="text" />
	<label for="psw">Password: </label>
	<input id="psw" name="psw" type="password" />

	<button type="submit">
		<?php echo $_VARS["mode"] == "login" ? "Login" : "Iscriviti"?>
	</button>
</form>

<div>
	Clicca 
	<a href="?action=user&mode=<?php echo $_VARS["mode"] == "login" ? "subscribe" : "login" ?>">qui</a>
	per <?php echo $_VARS["mode"] == "login" ? "iscriverti" : "accedere" ?>
</div>
