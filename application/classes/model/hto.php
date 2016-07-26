<?php
defined('SYSPATH') or die('No direct script access.');

class Model_HTO extends Kohana_Model {
    /**
     * Get the last 10 posts
     * @return ARRAY
     */
    public function getTest()
     {
        $sql = '
        	SELECT * FROM test
        ';

		$data = $this->_db->query(Database::SELECT, $sql, FALSE)->as_array();
        return $data;
     }

    /**
     * Returns a list of people signed up for the given date.
     *
     * Note that this also returns the date-specific fields, such as canceled
     * and notes.
     *
     * @param $date String Optional ISO date string (YYYY-MM-DD)
     *
     */
	public function getPeopleForDate($date = false) {
		if(!$date) {
			$date = date('Y-m-d');
		}

		$query = DB::query(Database::SELECT, '
			SELECT * FROM date_person
			WHERE date = :date
			ORDER BY
				f_xdz_pilot DESC,
				f_ctq_pilot DESC,
				f_licenced DESC,
				f_s_aff DESC,
				f_s_aff2 DESC,
				f_s_sl DESC,
				f_s_tandem DESC,
				name
		');

		$query->param(':date', $date);

		$data = $query->execute()->as_array();

		// Go through the rows, and set attributes for the day
		$dayCTQPilot = false;
		$dayXDZPilot = false;

		$dayNoAFFInstructors = 0;
		$dayAFFInstructors = '';

		$dayNoSLInstructors = 0;
		$daySLInstructors = '';

		$dayNoRadioInstructors = 0;
		$dayRadioInstructors = '';

		$dayFSInstructor = false;
		$dayTandemInstructor = false;

		foreach($data as $row) {
			if($row['f_ctq_pilot']) {
				$dayCTQPilot = $row['name'];
			}
			if($row['f_xdz_pilot']) {
				$dayXDZPilot = $row['name'];
			}
			if($row['f_i_aff']) {
				$dayNoAFFInstructors++;
				$dayAFFInstructors .= ' / '.$row['name'];
			}
			if($row['f_i_sl']) {
				$dayNoSLInstructors++;
				$daySLInstructors .= ' / '.$row['name'];
			}
			if($row['f_i_radio']) {
				$dayNoRadioInstructors ++;
				$dayRadioInstructors .= ' / '.$row['name'];
			}
			if($row['f_i_fs']) {
				$dayFSInstructor = $row['name'];
			}
			if($row['f_i_tandem']) {
				$dayTandemInstructor = $row['name'];
			}
		}

		// Clean up extra commas from instructor lists
		$dayAFFInstructors = trim($dayAFFInstructors, ' /');
		$daySLInstructors = trim($daySLInstructors, ' /');
		$dayRadioInstructors = trim($dayRadioInstructors, ' /');

		// Go through the data and set all necessary attributes in the rows
		$newData = array();
		foreach($data as $row) {
			// Set role
			if(isset($row['f_ctq_pilot']) && $row['f_ctq_pilot']) {
				$row['role'] = 'CTQ-pilotti';
			}
			else if(isset($row['f_xdz_pilot']) && $row['f_xdz_pilot']) {
				$row['role'] = 'XDZ-pilotti';
			}
			else if(isset($row['f_licenced']) && $row['f_licenced']) {
				$row['role'] = 'Kelppari';
			}
			else if(
				(isset($row['f_s_aff']) && $row['f_s_aff']) ||
				(isset($row['f_s_aff2']) && $row['f_s_aff2']) ||
				(isset($row['f_s_sl']) && $row['f_s_sl'])
			){
				$row['role'] = 'Oppilas';
			}
			else if(isset($row['f_s_tandem']) && $row['f_s_tandem']){
				$row['role'] = 'Tandem';
			}
			else {
				$row['role'] = '?';
			}
			// done with role

			// Set happiness and notes based on available and needed resources
			$happyNotes = '';
			$unhappyNotes = '';
			$row['happiness'] = 1;

			// Planes:
			if($row['f_ctq_only'] && !$dayCTQPilot) {
				$row['happiness'] = 0;
			}
			else if($row['f_xdz_only'] && !$dayXDZPilot) {
				$row['happiness'] = 0;
			}
			else if(!$dayXDZPilot && !$dayCTQPilot) {
				$row['happiness'] = 0;
			}

			if($row['f_unhappy']) {
				$row['happiness'] = 0;
			}

			// maybe add later: (- FS S with I-B and no I-FS)
			if($row['f_s_sl']) {
				if($dayNoSLInstructors) {
					$happyNotes .= ' Mesu: '.$daySLInstructors.', ';
				}
				else {
					$unhappyNotes .= ' Tarvitsee PL-mesun, ';
					$row['happiness'] = 0;
				}
			}

			if($row['f_s_aff']) {
				if($dayAFFInstructors) {
					$happyNotes .= ' Mesu: '.$dayAFFInstructors.', ';
				}
				else {
					$unhappyNotes .= ' Tarvitsee NOVA-mesun, ';
					$row['happiness'] = 0;
				}
			}
			if($row['f_s_aff2']) {
				if($dayNoAFFInstructors >= 2) {
					$happyNotes .= ' Mesut: '.$dayAFFInstructors.', ';
				}
				else if($dayNoAFFInstructors == 1) {
					$happyNotes .= ' Mesut: '.$dayAFFInstructors.', ?';
					$unhappyNotes .= ' Tarvitsee toisen NOVA-mesun, ';
					$row['happiness'] = 0;
				}
				else {
					$unhappyNotes .= ' Tarvitsee 2 NOVA-mesua, ';
					$row['happiness'] = 0;
				}
			}

			if($row['f_s_fs']) {
				if($dayFSInstructor) {
					$happyNotes .= 'VPK: '.$dayFSInstructor.', ';
				}
				else {
                    $unhappyNotes .= ' Tarvitsee VPK:n';
					$row['happiness'] = 0;
				}
			}

			if($row['f_s_radio']) {
				if($dayNoRadioInstructors) {
					// An SL-student's instructor can't be on the radio for him
					if(
						$row['f_s_sl'] &&
						$dayNoRadioInstructors == 1 &&
						$dayNoSLInstructors == 1 &&
						$daySLInstructors == $dayRadioInstructors
					) {
						$unhappyNotes .= ' Tarvitsee radiokouluttajan, ';
						$row['happiness'] = 0;
					}
					// An AFF-student's instructor can't be on the radio for him
					else if(
						$row['f_s_aff'] &&
						$dayNoRadioInstructors == 1 &&
						$dayNoAFFInstructors == 1 &&
						$dayAFFInstructors == $dayRadioInstructors
					) {
						$unhappyNotes .= ' Tarvitsee radiokouluttajan, ';
						$row['happiness'] = 0;
					}
					// An AFF-student's instructors can't be on the radio for him
					else if(
						$row['f_s_aff2'] &&
						$dayNoRadioInstructors == 2 &&
						$dayNoAFFInstructors == 2 &&
						$dayAFFInstructors == $dayRadioInstructors
					) {
						$unhappyNotes .= ' Tarvitsee radiokouluttajan, ';
						$row['happiness'] = 0;
					}
					else if(
						$row['f_s_aff2'] &&
						$dayNoRadioInstructors == 1 &&
						$dayNoAFFInstructors == 1 &&
						$dayAFFInstructors == $dayRadioInstructors
					) {
						$unhappyNotes .= ' Tarvitsee radiokouluttajan, ';
						$row['happiness'] = 0;
					}
					else {
						$happyNotes .= ' Radisti: '.$dayRadioInstructors.', ';
					}

				}
				else {
					$unhappyNotes .= ' Tarvitsee radiokouluttajan, ';
					$row['happiness'] = 0;
				}
			}

			if($row['f_s_tandem']) {
				if($dayTandemInstructor) {
					$happyNotes .= ' Tandem-mesu: '.$dayTandemInstructor.', ';
				}
				else {
					$unhappyNotes .= ' Tarvitsee tandem-mesun, ';
					$row['happiness'] = 0;
				}
			}

			// INSTRUCTOR flags
			if($row['f_i_aff']) {
				$happyNotes .= 'NOVA-mesu, ';
			}
			if($row['f_i_sl']) {
				$happyNotes .= 'PL-mesu, ';
			}
			if($row['f_i_radio']) {
				$happyNotes .= 'radiokouluttaja, ';
			}
			if($row['f_i_fs']) {
				$happyNotes .= 'VPK';
			}
			if($row['f_i_tandem']) {
				$happyNotes .= 'tandem-mesu, ';
			}

			// Plane flags
			if($row['f_ctq_only']) {
				if($dayCTQPilot) {
					$happyNotes .= ' Hyppää vain CTQ:stä, ';
				}
				else {
					$unhappyNotes .= ' Tarvitsee CTQ:n, ';
				}
			}
			else if($row['f_xdz_only']) {
				if($dayXDZPilot) {
					$happyNotes .= ' Hyppää vain XDZ:sta, ';
				}
				else {
					$unhappyNotes .= ' Tarvitsee XDZ:n, ';
				}
			}

			$row['happyNotes'] = trim($happyNotes, ' ,');
			$row['unhappyNotes'] = trim($unhappyNotes, ' ,');

			if($row['happyNotes'] && $row['unhappyNotes']) {
				$row['happyNotes'] .= ", ";
			}

			// Format the seconds off the timestamp, and don't show anything if there's no info
			if($row['from_time'] == '00:00:00') {
				$row['from_time'] = '';
			}
			else {
				$row['from_time'] = substr($row['from_time'], 0, 5);
			}

			$newData[] = $row;
		}

		// Day-wide flags
		$dayInfo = array(
			'pilotMissing' => 0,
			'canceled' => 0,
			'notes' => '',
		);

		// Missing pilot?
		if(!$dayCTQPilot && !$dayXDZPilot) {
			$dayInfo['pilotMissing'] = 1;
		}

		// Get info for day
		$query = DB::query(Database::SELECT, 'SELECT * FROM date WHERE date = :date');
		$query->param(':date', $date);
		$data = $query->execute()->as_array();

		if(isset($data[0])) {
			// Canceled?
			if($data[0]['canceled']) {
				$dayInfo['canceled'] = 1;
			}

			// Other info?
			$dayInfo['notes'] = $data[0]['notes'];
		}
		// done with day-wide flags

		$finalData = array(
			'people' => $newData,
			'day' => $dayInfo,
		);

		return $finalData;
	}

	/**
	 * @param $data Array The _unfiltered_ POST array from the AJAX call.
	 *
	 */
	public function addPerson($data) {
		$query = DB::query(Database::INSERT, '
			INSERT INTO date_person(
				name,
				date,
				from_time,
				notes,
				f_ctq_pilot,
				f_xdz_pilot,
				f_ctq_only,
				f_xdz_only,
				f_licenced,
				f_unhappy,
				f_i_aff,
				f_i_sl,
				f_i_radio,
				f_i_fs,
				f_i_tandem,
				f_s_aff,
				f_s_aff2,
				f_s_sl,
				f_s_radio,
				f_s_fs,
				f_s_tandem
			)
			values(
				:name,
				:date,
				:from_time,
				:notes,
				:f_ctq_pilot,
				:f_xdz_pilot,
				:f_ctq_only,
				:f_xdz_only,
				:f_licenced,
				:f_unhappy,
				:f_i_aff,
				:f_i_sl,
				:f_i_radio,
				:f_i_fs,
				:f_i_tandem,
				:f_s_aff,
				:f_s_aff2,
				:f_s_sl,
				:f_s_radio,
				:f_s_fs,
				:f_s_tandem
			)
		');

		$this->_generateQuery($query, $data);

		if($query->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * @param $data Array The _unfiltered_ POST array from the AJAX call.
	 *
	 */
	public function editPerson($data) {
		$query = DB::query(Database::UPDATE, '
			UPDATE date_person SET
				name = :name,
				date = :date,
				from_time = :from_time,
				notes = :notes,
				f_ctq_pilot = :f_ctq_pilot,
				f_xdz_pilot = :f_xdz_pilot,
				f_ctq_only = :f_ctq_only,
				f_xdz_only = :f_xdz_only,
				f_licenced = :f_licenced,
				f_unhappy = :f_unhappy,
				f_i_aff = :f_i_aff,
				f_i_sl = :f_i_sl,
				f_i_radio = :f_i_radio,
				f_i_fs = :f_i_fs,
				f_i_tandem = :f_i_tandem,
				f_s_aff = :f_s_aff,
				f_s_aff2 = :f_s_aff2,
				f_s_sl = :f_s_sl,
				f_s_radio = :f_s_radio,
				f_s_fs = :f_s_fs,
				f_s_tandem = :f_s_tandem
			WHERE id = :id
		');

		$this->_generateQuery($query, $data);

		$query->param(':id', $data['hTOId']);

		if($query->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * helper function that sets parameters for Add and Edit queries
	 *
	 */
	private function _generateQuery(&$query, $data) {
		// Make sure all keys are set
		$fields = array(
			'hTOPersonType',
			'hTOStudentType',
		);

		foreach($fields as $field) {
			if(!isset($data[$field])) {
				$data[$field] = false;
			}
		}

		// For checkbox fields the check is a bit more complex:
		$checkBoxFields = array(
			'hTO_f_i_aff',
			'hTO_f_i_sl',
			'hTO_f_i_radio',
			'hTO_f_i_fs',
			'hTO_f_i_tandem',

			'hTO_f_s_radio',
			'hTO_f_s_fs',
		);

		foreach($checkBoxFields as $field) {
			if(isset($data[$field]) && $data[$field] == 'on'){
				$data[$field] = 1;
			}
			else {
				$data[$field] = 0;
			}
		}

		// And for the duplicate checkbox fields the check is even more complex.
		// In addition to the check, we set key 'hTO_f_unhappy', which isn't set in the POST
		// array.
		$checkBoxFields = array(
			'hTO_f_ctq_only',
			'hTO_f_xdz_only',
			'hTO_f_unhappy',
		);

		foreach($checkBoxFields as $field) {
			if(isset($data[$field.'_l']) && $data[$field.'_l'] == 'on'){
				$data[$field] = 1;
			}
			else if(isset($data[$field.'_s']) && $data[$field.'_s'] == 'on') {
				$data[$field] = 1;
			}
			else {
				$data[$field] = 0;
			}
		}

		// The name just gets chopped to 30 chars
		$name = substr(htmlspecialchars($data['hTOName']), 0, 30);
		if(!$name) {
			$name = '';
		}

		// Some heuristics to try and format the time given by the user so that
		// MySQL understands it too:
		$fromTime = substr(htmlspecialchars($data['hTOFromTime']), 0, 5);
		if(!$fromTime) {
			$fromTime = ''; // This goes in the db as "00:00:00", but we handle that when printing.
		}
		else {
			// Try to make sure the string is interpreted correctly
			if(strlen($fromTime) == 5) {
				// Let's try fixing the timestamp. For example "12.00"
				// doesn't work, but "12:00" does.
				$fromTime = substr_replace($fromTime, ':', 2, 1);
			}
			else if(strlen($fromTime) == 4) {
				$fromTime = substr_replace($fromTime, ':', 1, 1);
			}
			else if(strlen($fromTime) == 3) {
				// can't think of any sensible time format with three chars. Except "3pm", but
				// we're just not that american.
				$fromTime = '';
			}
			else if(strlen($fromTime) == 2 || strlen($fromTime) == 1) {
				// Must be '21' or '9' or something like that.
				$fromTime = $fromTime.':00:00';
			}
			else {
				$fromTime = '';
			}
		}
		logg('final fromTime: '.$fromTime);

		$notes = substr(htmlspecialchars($data['hTONotes']), 0, 100);
		if(!$notes) {
			$notes = '';
		}

		$query->param(':name', $name);
		$query->param(':date' , $data['hTODate']);
		$query->param(':from_time', $fromTime);
		$query->param(':notes', $notes);

		switch($data['hTOPersonType']) {
			case 'hTOPersonLicenced':
				$query->param(':f_ctq_pilot', 0);
				$query->param(':f_xdz_pilot', 0);

				$query->param(':f_ctq_only', $data['hTO_f_ctq_only']);
				$query->param(':f_xdz_only', $data['hTO_f_xdz_only']);

				$query->param(':f_licenced', 1);
				$query->param(':f_unhappy', $data['hTO_f_unhappy']);

				$query->param(':f_i_aff', $data['hTO_f_i_aff']);
				$query->param(':f_i_sl', $data['hTO_f_i_sl']);
				$query->param(':f_i_radio', $data['hTO_f_i_radio']);
				$query->param(':f_i_fs', $data['hTO_f_i_fs']);
				$query->param(':f_i_tandem', $data['hTO_f_i_tandem']);

				$query->param(':f_s_aff', 0);
				$query->param(':f_s_aff2', 0);
				$query->param(':f_s_sl', 0);
				$query->param(':f_s_radio', 0);
				$query->param(':f_s_fs', 0);
				$query->param(':f_s_tandem', 0);
			break;

			case 'hTOPersonStudent':

				$query->param(':f_ctq_pilot', 0);
				$query->param(':f_xdz_pilot', 0);

				$query->param(':f_ctq_only', $data['hTO_f_ctq_only']);
				$query->param(':f_xdz_only', $data['hTO_f_xdz_only']);

				$query->param(':f_licenced', 0);
				$query->param(':f_unhappy', $data['hTO_f_unhappy']);

				$query->param(':f_i_aff', 0);
				$query->param(':f_i_sl', 0);
				$query->param(':f_i_radio', 0);
				$query->param(':f_i_fs', 0);
				$query->param(':f_i_tandem', 0);

				switch($data['hTOStudentType']) {
					case 'hTO_f_s_sl':
						$query->param(':f_s_aff', 0);
						$query->param(':f_s_aff2', 0);
						$query->param(':f_s_sl', 1);
					break;

					case 'hTO_f_s_aff':
						$query->param(':f_s_aff', 1);
						$query->param(':f_s_aff2', 0);
						$query->param(':f_s_sl', 0);
					break;

					case 'hTO_f_s_aff2':
						$query->param(':f_s_aff', 0);
						$query->param(':f_s_aff2', 1);
						$query->param(':f_s_sl', 0);
					break;
				}

				$query->param(':f_s_radio', $data['hTO_f_s_radio']);
				$query->param(':f_s_fs', $data['hTO_f_s_fs']);
				$query->param(':f_s_tandem', 0);

			break;

			case 'hTOPersonTandemStudent':
				$query->param(':f_ctq_pilot', 0);
				$query->param(':f_xdz_pilot', 0);

				$query->param(':f_ctq_only', $data['hTO_f_ctq_only']);
				$query->param(':f_xdz_only', $data['hTO_f_xdz_only']);

				$query->param(':f_licenced', 0);
				$query->param(':f_unhappy', 0);
				$query->param(':f_i_aff', 0);
				$query->param(':f_i_sl', 0);
				$query->param(':f_i_radio', 0);
				$query->param(':f_i_fs', 0);
				$query->param(':f_i_tandem', 0);
				$query->param(':f_s_aff', 0);
				$query->param(':f_s_aff2', 0);
				$query->param(':f_s_sl', 0);
				$query->param(':f_s_radio', 0);
				$query->param(':f_s_fs', 0);
				$query->param(':f_s_tandem', 1);
			break;

			case 'hTOPersonCTQPilot':
				$query->param(':f_ctq_pilot', 1);
				$query->param(':f_xdz_pilot', 0);
				$query->param(':f_ctq_only', 0);
				$query->param(':f_xdz_only', 0);
				$query->param(':f_licenced', 0);
				$query->param(':f_unhappy', 0);
				$query->param(':f_i_aff', 0);
				$query->param(':f_i_sl', 0);
				$query->param(':f_i_radio', 0);
				$query->param(':f_i_fs', 0);
				$query->param(':f_i_tandem', 0);
				$query->param(':f_s_aff', 0);
				$query->param(':f_s_aff2', 0);
				$query->param(':f_s_sl', 0);
				$query->param(':f_s_radio', 0);
				$query->param(':f_s_fs', 0);
				$query->param(':f_s_tandem', 0);
			break;

			case 'hTOPersonXDZPilot':
				$query->param(':f_ctq_pilot', 0);
				$query->param(':f_xdz_pilot', 1);
				$query->param(':f_ctq_only', 0);
				$query->param(':f_xdz_only', 0);
				$query->param(':f_licenced', 0);
				$query->param(':f_unhappy', 0);
				$query->param(':f_i_aff', 0);
				$query->param(':f_i_sl', 0);
				$query->param(':f_i_radio', 0);
				$query->param(':f_i_fs', 0);
				$query->param(':f_i_tandem', 0);
				$query->param(':f_s_aff', 0);
				$query->param(':f_s_aff2', 0);
				$query->param(':f_s_sl', 0);
				$query->param(':f_s_radio', 0);
				$query->param(':f_s_fs', 0);
				$query->param(':f_s_tandem', 0);
			break;
		}

	}

	/**
	 * @param Integer $numberOfDates How many dates do we want to show? Default value is
	 * used, except when getting the expanded calendar, in which case the number is selected
	 * by the calling function.
	 *
	 */
	public function getDates($numberOfDates = 8) {
		$time = time();
		$dates = array();
		for($i=0 ; $i < $numberOfDates ; $i++) {
			$date['date'] = date('Y-m-d', $time);

			$fDate = date('j.n.', $time);
			$weekday = substr(strftime('%A', $time), 0, 2);
			$date['print'] = $weekday.'&nbsp;'.$fDate;
			$date['print_extended'] = $fDate;

			// Get summary for participation information
			$dateData = $this->getPeopleForDate($date['date']);

			$people = $dateData['people'];

			$xDZPilots = 0;
			$cWWPilots = 0;
			$happyPeople = 0;
			$unhappyPeople = 0;

			$summary = array(
				'xdz' => array(
					's' => array(
						'0' => 0,
						'1' => 0,
					),
					'l' => array(
						'0' => 0,
						'1' => 0,
					),
					'pilot' => array(
						'0' => 0,
						'1' => 0,
					),
				),
				'ctq' => array(
					's' => array(
						'0' => 0,
						'1' => 0,
					),
					'l' => array(
						'0' => 0,
						'1' => 0,
					),
					'pilot' => array(
						'0' => 0,
						'1' => 0,
					),
				),
			);

			foreach($people as $person) {
				// This data is for generating the date background image:
				if($person['role'] == 'CTQ-pilotti') {
					$cWWPilots++;
				}
				else if($person['role'] == 'XDZ-pilotti') {
					$xDZPilots++;
				}
				else if($person['happiness'] == 1) {
					$happyPeople++;
				}
				else if($person['happiness'] == 0) {
					$unhappyPeople++;
				}

				// This data is for the summary:

				if($person['f_licenced']) {
					$key2 = 'l';
				}
				else {
					if($person['role'] != 'CTQ-pilotti' && $person['role'] != 'XDZ-pilotti') {
						$key2 = 's';
					}
					else {
						// Just to get the pilots to not show up in totals
						$key2 = 'pilot';
					}

				}
				/*
				if($person['f_licenced']) {
					$key2 = 'l';
				}
				else {
					$key2 = 's';
				}
				*/
				if(!$person['f_ctq_only']) {
					logg('adding to xdz');
					// Add to XDZ array
					$summary['xdz'][$key2][$person['happiness']] = $summary['xdz'][$key2][$person['happiness']] + 1;
				}

				if(!$person['f_xdz_only']) {
					logg('adding to ctq');
					// Add to CTQ array
					$summary['ctq'][$key2][$person['happiness']] = $summary['ctq'][$key2][$person['happiness']] + 1;
				}

			}

			$date['summary'] = $summary;

			$date['xDZPilots'] = $xDZPilots;
			$date['cWWPilots'] = $cWWPilots;
			$date['happyPeople'] = $happyPeople;
			$date['unhappyPeople'] = $unhappyPeople;

			$date['canceled'] = $dateData['day']['canceled'];
			$date['notes'] = $dateData['day']['notes'];

			$date['N'] = date('N', $time);

			$dates[] = $date;
			$time = strtotime('+1 day', $time);
		}

		return $dates;
	}

	/**
	 * @param $id Row id
	 *
	 */
	public function deletePerson($id) {
		$query = DB::query(Database::DELETE, '
			DELETE FROM date_person WHERE id = :id
		');

		$query->param(':id', $id);

		if($query->execute()) {
			return true;
		}
		else {
			return false;
		}

	}

	/**
	 * Gets a single row from the main table, used for populating the edit form.
	 *
	 */
	public function getRow($id) {
		$query = DB::query(Database::SELECT, 'SELECT * FROM date_person WHERE id = :id');

		$query->param(':id', $id);

		$data = $query->execute()->as_array();

		// Drop the seconds, and the whole element if it's not set
		if($data[0]['from_time'] == '00:00:00') {
			$data[0]['from_time'] = '';
		}
		else {
			$data[0]['from_time'] = substr($data[0]['from_time'], 0, 5);
		}

		return $data[0];
	}

	/**
	 * @param $data Array The _unfiltered_ POST array from the AJAX call.
	 *
	 */
	public function updateDate($data) {
		$query = DB::query(Database::INSERT, '
			INSERT INTO date(
				date,
				canceled,
				notes
			)
			values(
				:date,
				:canceled,
				:notes
			)
			ON DUPLICATE KEY UPDATE
				canceled = :canceled,
				notes = :notes
		');

		// Handle the checkbox:
		if(isset($data['hTOEditDateCanceled']) && $data['hTOEditDateCanceled'] == 'on') {
			$query->param(':canceled', 1);
		}
		else {
			$query->param(':canceled', 0);
		}

		// Handle notes:
		$notes = substr(htmlspecialchars($data['hTOEditDateNotes']), 0, 100);
		// Kohana will cast false into 0, but we want an empty string:
		if(!$notes) {
			$notes = '';
		}
		$query->param(':notes', $notes);

		// No need to do anything about the date:
		$query->param(':date', $data['hTODate']);

		if($query->execute()) {
			return true;
		}
		else {
			return false;
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
}
