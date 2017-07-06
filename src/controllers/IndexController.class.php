<?php 
/**
 * INDEX CONTROLLER CLASS
 */

class IndexController extends Controller {

	public function Index () {
		$UsersHistory = new UsersHistory();
		echo "UsersHistory create: ";
		var_dump($UsersHistory->migrate());
		
		//$this->user->setup();
		//$this->user->addUser('Pavel Leonovich', 'pavel', '12345', 'my@mail.com');
		//$this->content.= "user auth: ".$this->user->auth('pavel','12345');
		$this->content.= "user is auth: ".$this->user->isAuth();
		$Pages = new Pages();
		//echo "migrate: ";
		//var_dump($Pages->migrate());
		//echo "insert: ";
		//var_dump($Pages->insertData());
		$ID = Args::getOne($_GET,"id",0);
		var_dump($_GET);
		$this->content.= "<br>url: ".$this->url->get(array('sid'=>9),'id');
		$this->content.= "<br>sef url: ".$this->sefurl->get(array('sid'=>9),'id');

		if(count($_POST)>0) {
			$res = $Pages::save();
			var_dump($res);
		}
		
		$DATA = $Pages::getById($ID);
		var_dump($DATA);
		$ARGS = Args::factory($DATA)
		->set("id",0)
		->set("title")
		->set("text")
		->set("description");
		echo "<br/>ID: ".$ARGS->id;

		// RENDER PAGE
		$this->content.= View::factory()
		->bind("DATA",$ARGS)
		->render('form', true);

		// MAIN RENDER
		$this->render($this->content);
	}

}