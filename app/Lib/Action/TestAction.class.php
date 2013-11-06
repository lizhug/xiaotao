<?php

class TestAction extends Action {

    public function index(){
		$sq = new PDO("sqlite:Data/weibo/timing.db");
	
		$result = $sq->query("SELECT * FROM weibo");
		
		foreach($result as $each) {
			print_r($each);
		}
    }
}


?>
