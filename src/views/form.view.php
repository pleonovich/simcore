<h2>Form</h2>
<form method="POST" action="">
	id:<br/>
	<input type="text" name="id" value="<?=$DATA->id?>" ></input><br/>
	title:<br/>
	<input type="text" name="title" value="<?=$DATA->title?>" ></input><br/>
	text:<br/>
	<input type="text" name="text" value="<?=$DATA->text?>" ></input><br/>
	description:<br/>
	<input type="text" name="description" value="<?=$DATA->description?>" ></input><br/>
	<br/>
	<input type="submit" value="save" ></input>
</form>