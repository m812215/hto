		<h2><?php echo $fDate;?> <img id="hTOEditDateButton" class="tooltip" src="<?php echo URL::base();?>img/pencil.png" title="Tästä voi muokata koko päivän tietoja."/><img src="<?php echo URL::base();?>img/blank.gif" alt="" height="16"/></h2>

		<div id="hTOEditDateFormContainer">
			<form method="post" action="<?php echo URL::base();?>ajax/save_date" id="hTOEditDateForm">
				<input type="hidden" name="hTODate" value="<?php echo $date;
				?>"/>

				<label>Huom: <input type="text" name="hTOEditDateNotes" id="hTOEditDateNotes" size="30" value="<?php
				echo $data['day']['notes'];
				?>" maxlength="100"/></label>

				<label><input type="checkbox" name="hTOEditDateCanceled"<?php
				if($data['day']['canceled']) {
					echo ' checked="checked"';
				}
				?> /> Ei hyppytoimintaa </label>

				<input type="submit" value="Tallenna" id="hTOEditDateSubmit" />&nbsp;<input type="button" value="Peruuta" id="hTOEditDateCancel"/>
			</form>
		</div>

		<?php

		if($data['day']['canceled']) {
			echo '<p class="dayError">'.$data['day']['notes'].'</p>';
		}
		else {

			if($data['day']['pilotMissing']) {
				echo '<p class="dayError">Lentäjä puuttuu.</p>';
			}

			if($data['day']['notes']) {
				echo '<p class="dayMessage">'.$data['day']['notes'].'</p>';
			}
		?>

		<h3>Ilmoittautuneet: <img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Kannattaa muistaa, että kesällä viikonloppuisin on kyllä toimintaa vaikkei se täällä näkyisikään."/></h3>

		<div id="hTOTableContainer">
			<img src="<?php echo URL::base(); ?>img/ajax-loader.gif" alt="loading" height="16" width="16" id="hTOThrobberList"/>

			<table>
				<thead>
					<tr>
						<th class="hTOThrobber">&nbsp;</th>

						<th>Nimi</th>

						<th>Paikalla klo</th>

						<th>&nbsp;</th>

						<th>Tila</th>

						<th>&nbsp;</th>

						<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>

				<tbody>
					<?php
					if(count($data['people'])) {
						foreach($data['people'] as $row) {
						?>
						<tr>
							<td<?php
							if($row['role'] == 'Oppilas') {
								echo ' class="student"';
							}
							else if($row['role'] == 'Tandem') {
								echo ' class="tandemStudent"';
							}
							else if($row['role'] == 'XDZ-pilotti') {
								echo ' class="xdzPilot"';
							}
							else if($row['role'] == 'CWW-pilotti') {
								echo ' class="cwwPilot"';
							}
							?>><?php echo $row['role']; ?></td>

							<td class="hTOName"><?php echo $row['name']; ?></td>

							<td><?php echo $row['from_time']; ?></td>

							<td><?php echo $row['notes']; ?></td>

							<td><?php
							if($row['happiness']) {
								echo'<img src="'.URL::base().'img/cool.gif" alt="OK" width="15" height="15" />';
							}
							else {
								echo'<img src="'.URL::base().'img/frown.gif" alt="Ei OK" width="15" height="15" />';
							}
							?></td>

							<td>
								<span class="ok"><?php echo $row['happyNotes']; ?></span>

								<?php

								?>

								<span class="notOk"><?php echo $row['unhappyNotes']; ?></span>
							</td>

							<td class="actions">
								<?php if($row['id']) { ?>
								<a href="#" title="edit <?php echo $row['id']?>" class="hTOEdit"><img  class="tooltip" src="<?php echo URL::base();?>img/pencil.png" title="Muokkaa"/></a>&nbsp;<a href="#" title="delete <?php echo $row['id']?>" class="hTODelete"><img  class="tooltip" src="<?php echo URL::base();?>img/cross.png" title="Poista"/></a>
								<?php } ?>
							</td>
						</tr>
						<?php
						}// end foreach
					} // end if
					else {
						echo '<tr><td colspan="7">Ei ilmoittautuneita.</td></tr>';
					}
					?>

				</tbody>
			</table>
		</div>

		<input id="hTOAddMe" type="button" value="Lisää minut" />
		<?php
			} // end if dayCanceled
		?>
