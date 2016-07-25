		<div id="hTOCalendarExpanded">
			<table cellspacing="0">
				<img src="<?php echo URL::base(); ?>img/ajax-loader.gif" alt="loading" height="16" width="16" id="hTOThrobberCalendar"/>
				
				<?php /*
				<tr>
					<th>Maanantai</th>
					<th>Tiistai</th>
					<th>Keskiviikko</th>
					<th>Torstai</th>
					<th>Perjantai</th>
					<th>Lauantai</th>
					<th>Sunnuntai</th>
				</tr>
				*/
				?>
	
				<tr>
					<th>Ma</th>
					<th>Ti</th>
					<th>Ke</th>
					<th>To</th>
					<th>Pe</th>
					<th>La</th>
					<th>Su</th>
				</tr>
				
				<tr>
				<?php
				// Pad the beginning of the week with empty cells
				$firstDay = $dates[0]['N'];
				for($i = 0;$i < $firstDay-1 ;$i++) {
					echo "\t\t\t\t<td>&nbsp;</td>\n";
				}
				
				foreach($dates as $calDate) {
					// Highlight active date
					//.. but not when we just loaded and nothing is seen
					//.. and it's this check that fixes the dropping of current date when 
					// collapsing...
					if($date == $calDate['date']) {
						$liClass=" hTOCurrentDate";
					}
					else {
						$liClass="";
					}
					
					include('summary.php');
					
					echo '				<td style="background-image:url('.
					URL::base().
					'ajax/image?c='.
					$calDate['cWWPilots'].
					'&x='.
					$calDate['xDZPilots'].
					'&h='.$calDate['happyPeople'].
					'&u='.
					$calDate['unhappyPeople'].
					'&n='.
					$calDate['canceled'].
					')" class="'.
					$calDate['date'].
					' date tooltip'.
					$liClass.
					'" title="'.$summaryHtml.'"><span>'.
					$calDate['print_extended'];
					
					// Add a bang if there is something in the notes for the day
					if($calDate['notes']) {
						if($calDate['canceled']) {
							print '&nbsp;<span class="hto-bang-bad">';
						}
						else {
							print '&nbsp;<span class="hto-bang-good">';				
						}
						print '&nbsp;!&nbsp;</span>';
					}
					
					echo "</span></td>\n";
					
					if($calDate['N'] == 7) {
						echo "			</tr>\n";
					}
				}
				
				
				// Pad the end of the calendar if we didn't finish with a Sunday
				$lastDay = $calDate['N'];
				
				if($lastDay != 7) {
					while($lastDay != 7) {
						echo '<td>&nbsp;</td>';
						$lastDay++;
					}
					
					echo'			
					</tr>
					';
				}
				?>
			</table>
			
			<p class="hTOActions">
				<img src="<?php echo URL::base();?>img/bullet_toggle_minus.png" alt="Vähemmän päiviä" title="Näytä vähemmän päiviä" id="hTOCollapseCalendar"/>
				
				<img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Päivämäärien värien selitykset:<br/>
				- Yläreuna sininen: CWW-pilotti paikalla.<br/>
				- Alareuna keltainen: XDZ-pilotti paikalla.<br/>
				- Vihreän ja punaisen palkin pituus: Ilmoittautuneiden hyppääjien määrä.
				<br/><br/>
				Valitse päivä klikkaamalla."/>
			</p>
			
		</div>