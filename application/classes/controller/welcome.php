<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{	
		$view = View::factory('front_page');

		$this->request->response = $view->render();
	}
	
} // End Welcome
