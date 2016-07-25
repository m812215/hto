		<ul>
			<img src="<?php echo URL::base(); ?>img/ajax-loader.gif" alt="loading" height="16" width="16" id="hTOThrobberCalendar"/>
			
			<?php 
			foreach($dates as $calDate) {
				// Highlight active date
				if($date == $calDate['date']) {
					$liClass=" hTOCurrentDate";
				}
				else {
					$liClass="";
				}

				include('summary.php');
			
				echo '
				<li style="background-image:url('.
				URL::base().
				'ajax/image?c='.
				$calDate['cWWPilots'].
				'&x='.
				$calDate['xDZPilots'].
				'&h='.
				$calDate['happyPeople'].
				'&u='.
				$calDate['unhappyPeople'].
				'&n='.
				$calDate['canceled'].
				')" class="'.
				$calDate['date'].
				' date tooltip'.
				$liClass.
				'" title="'.$summaryHtml.'"><a href="?date='.$calDate['date'].'"><img src="'.URL::base().'img/rounded.png" class="hTORoundMask" alt="" /><span>'.
				$calDate['print'];
				
				// Add a bang if there is something in the notes for the day
				if($calDate['notes']) {
					if($calDate['canceled']) {
						print '<div class="hto-bang-bad">';
					}
					else {
						print '<div class="hto-bang-good">';				
					}
					print '&nbsp;!&nbsp;</div>';
				}
				
				echo "</span></a></li>\n";
			}
			?>
			
			
			
			<li class="hTOActions">
				<a href="?=e"><img src="<?php echo URL::base();?>img/calendar.png" alt="Lisää päiviä" title="Näytä lisää päiviä" id="hTOExpandCalendar"/></a>
				
				<img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Päivämäärien värien selitykset:<br/>
				- Yläreuna sininen: CTQ-pilotti paikalla.<br/>
				- Alareuna keltainen: XDZ-pilotti paikalla.<br/>
				- Vihreän ja punaisen palkin pituus: Ilmoittautuneiden hyppääjien määrä.
				<br/><br/>
				Valitse päivä klikkaamalla."/>
			</li>
		</ul>
