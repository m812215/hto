<?php defined('SYSPATH') OR die('No Direct Script Access');

setlocale(LC_TIME, 'fi_FI');

Class Controller_Ajax extends Controller
{
    function action_index() {
		$this->action_list(date('Y-m-d'));
    }
    
    
    /**
     * Prints the full data list for a given date.
     * @param $date String an ISO-formatted date stamp. Defaults to today.
     * 
     * This function and the associated template should only be called when data has changed and
     * we need to update the calendar as well as the list. In other cases update hTOListProper
     * only.
     *
     */
	function action_list($date = false) {
		noCacheHeaders();
		$view = View::factory('list');

		// Select date to use
		if(!$date) {
			$date = date('Y-m-d');
		}

		// Generate readable date strings
		$fDate = date('j.n.Y', strtotime($date));
		$fDate .= strtolower(strftime(' %A', strtotime($date)));
		$view->fDate = $fDate;

		// Get people who signed up for today
		$hto = new Model_HTO();
		$people = $hto->getPeopleForDate($date);
		
		// Get calendar contents
		$view->dates = $hto->getDates();
		
		// Assign variables to template
		$view->data = $people;
		$view->date = $date;
		
		$this->request->response = $view->render();
	}    
    
    
    /**
     * Prints the actual list only for a given date, without the calendar.
     * @param $date String an ISO-formatted date stamp. Defaults to today.
     *
     */
	function action_list_proper($date = false) {
		noCacheHeaders();
		$view = View::factory('list_proper');

		// Select date to use
		if(!$date) {
			$date = date('Y-m-d');
		}

		// Generate readable date strings
		$fDate = date('j.n.Y', strtotime($date));
		$fDate .= strtolower(strftime(' %A', strtotime($date)));
		$view->fDate = $fDate;

		// Get people who signed up for today
		$hto = new Model_HTO();
		$people = $hto->getPeopleForDate($date);
		
		// Assign variables to template
		$view->data = $people;
		$view->date = $date;
		
		$this->request->response = $view->render();
	}    
	
	
	/**
     * Gets the small calendar. Used when collapsing the expanded calendar.
     *
     */
	function action_get_calendar($date = false) {
		noCacheHeaders();
		$view = View::factory('calendar');

		// Select date to use, used for highlighting active date
		// Don't select any date on initial load. Should be the only time this is called 
		// without parameter.
		//if(!$date) {
		//	$date = date('Y-m-d');
		//}
		$view->date = $date;

		// Get calendar contents
		$hto = new Model_HTO();
		$view->dates = $hto->getDates();
		
		$this->request->response = $view->render();
	}  
	
	
	/**
     * Gets the expanded calendar.
     *
     */
	function action_get_calendar_expanded($date = false) {
		noCacheHeaders();
		$view = View::factory('calendar_expanded');
		
		// Select date to use, used for highlighting active date
		if(!$date) {
			$date = date('Y-m-d');
		}
		$view->date = $date;

		// Get calendar contents
		$hto = new Model_HTO();
		$view->dates = $hto->getDates(60);
		
		$this->request->response = $view->render();
	}
    
    
    /**
     * Prints the form
     *
     */
	function action_form($date = false) {
		noCacheHeaders();
		$view = View::factory('form');
		
		if(!$date) {
			$date = date('Y-m-d');
		}
		
		$view->date = $date;
		
		$this->request->response = $view->render();
	}
	
	
	/**
	 * AJAX form submit handler
	 *
	 */
	function action_save() {
		noCacheHeaders();
		$hto = new Model_HTO();
		
		$hto->addPerson($_POST);
		
		$return = array(
			'status' => 1,
			'message' => 'Ilmoittautuminen vastaanotettu.',
		);
		
		die(json_encode($return));
	}
		
		
	/**
	 * Day edit AJAX form submit handler
	 *
	 */
	function action_save_date() {
		noCacheHeaders();
		$hto = new Model_HTO();
		
		$hto->updateDate($_POST);
		
		$return = array(
			'status' => 1,
			'message' => 'Muutokset tallennettu.',
		);
		
		die(json_encode($return));
	}
	
	
	/**
	 * Delete person from a date
	 *
	 */ 
	function action_delete() {
		noCacheHeaders();
		$hto = new Model_HTO();
		
		$id = (int)$_POST['id'];
		
		$hto->deletePerson($id);
		
		$return = array(
			'status' => 1,
			'message' => 'Henkilö poistettu.',
		);
		
		die(json_encode($return));
	} 
	
	
	/**
	 * Gets the data for a single row for editing the row
	 * 
	 */
	function action_get_row($id) {
		noCacheHeaders();
		$hto = new Model_HTO();
		$row = $hto->getRow($id);
		die(json_encode($row));
	}
	
	
	/**
	 * Saves changes after editing
	 *
	 */
	function action_edit() {
		noCacheHeaders();
		$hto = new Model_HTO();
		
		$hto->editPerson($_POST);
		
		$return = array(
			'status' => 1,
			'message' => 'Muutokset tallennettu.',
		);
		
		die(json_encode($return));
	}
	
	
	/**
	 * Returns HTML for embedding HTO on another page
	 *
	 */
	function action_get_fragment() {
	logg('get frag');
		noCacheHeaders();
		// Get front page view, and add rendered calendar to it
		$view = View::factory('fragment');
		
		$this->request->response = $view->render();
	}
	
	
	/**
	 * Generates background images for the calendar dates
	 *
	 */
	function action_image() {
		noCacheHeaders();
		$cww = (int)$_GET['c'];
		$xdz = (int)$_GET['x'];
		$happy = (int)$_GET['h'];
		$unhappy = (int)$_GET['u'];
		$canceled = (int)$_GET['n'];
		
		$width = 66;
		$personBlockWidth = ceil($width/10); // Make 9 people "fill up" the day
		
		header("Content-type: image/png");
		
		$img = imagecreatetruecolor($width, 26);
		
		$white = imagecolorallocate($img, 255, 255, 255);
		$black = imagecolorallocate($img, 0,0,0);
		$gray = imagecolorallocate($img, 192, 192, 192);
		$lightGray = imagecolorallocate($img, 220, 220, 220);
		$veryLightGray = imagecolorallocate($img, 235, 235, 235);
		$blue = imagecolorallocate($img, 192, 192, 255);
		$green = imagecolorallocate($img, 120, 255, 120);
		$red = imagecolorallocate($img, 255, 160, 160);
		$yellow = imagecolorallocate($img, 255, 255, 120);
		
		if($canceled) {
			imagefill($img, 0,0, $gray);		
		}
		else {
			// bg
			imagefill($img, 0,0,$white);
			
			// CWW Pilot slot
			$points = array(
				0,0,
				$width, 0,
				$width, 4,
				0,4,
			);
			if($cww) {
				imagefilledpolygon ( $img , $points , 4 , $blue );		
			}
	
			// XDZ Pilot slot
			$points = array(
				0,21,
				$width, 21,
				$width, 26,
				0,26
			);
			if($xdz) {
				imagefilledpolygon ( $img , $points , 4 , $yellow );
			}
			
			// Happy people slot
			$happyX = $happy*$personBlockWidth;
			if($happyX > $width) {
				$happyX = $width;
			}
			$points = array(
				0,5,
				0+$happyX,5,
				0+$happyX,12,
				0,12
			);
			if($happy) {
				imagefilledpolygon ( $img , $points , 4 , $green );
			}
			
			// Unhappy people slot
			$unhappyX = $unhappy*$personBlockWidth;
			if($unhappyX > $width) {
				$unhappyX = $width;
			}

			$points = array(
				0,13,
				0+$unhappyX,13,
				0+$unhappyX,20,
				0,20
			);
			if($unhappy) {
				imagefilledpolygon ( $img , $points , 4 , $red);		
			}		
		}
		
		imagepng($img);
		die();
	}
	
	function action_test() {
		die('*'.URL::base().'*');
	}
}

function logg($thing) {
	$fp = fopen('application/logs/debug.log', 'a');
	
	if(is_array($thing) || is_object($thing)) {
		fputs($fp, print_r($thing, true));
	}
	else {
		fputs($fp, $thing);	
	}
	
	fputs($fp, "\n");
	
	fclose($fp);
}

function noCacheHeaders() {
	header('Pragma: no-cache');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
	header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
}