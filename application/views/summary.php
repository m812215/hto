<?php
				// Generate summary table
				$summary = $calDate['summary'];
				$summaryHtml = '
					<table>
						<tr>
							<th></th>
							<th>Kelpparit</th>
							<th>Oppilaat</th>
							<th><b>Kaikki</b></th>
						</tr>
						<tr>
							<th>CWW</th>
							<td>
								<span class="happy">'.($summary['cww']['l']['1'] ? $summary['cww']['l']['1'] : '-').'</span>&nbsp;/&nbsp;
								<span class="unhappy">'.($summary['cww']['l']['0'] ? $summary['cww']['l']['0'] : '-').'</span>
							</td>
							<td>
								<span class="happy">'.($summary['cww']['s']['1'] ? $summary['cww']['s']['1'] : '-').'</span>&nbsp;/&nbsp;
								<span class="unhappy">'.($summary['cww']['s']['0'] ? $summary['cww']['s']['0'] : '-').'</span>
							</td>
				';

				$happyTotal = $summary['cww']['l']['1']+$summary['cww']['s']['1'];
				$unhappyTotal = $summary['cww']['l']['0']+$summary['cww']['s']['0'];
                /*
				$summaryHtml .= '
							<td>
								<b>
									<span class="happy">'.($happyTotal ? $happyTotal : '-').'</span>&nbsp;/&nbsp;
									<span class="unhappy">'.($unhappyTotal ? $unhappyTotal : '-').'</span>
								</b>
							</td>
						</tr>
						<tr>
							<th>XDZ</th>
							<td>
								<span class="happy">'.($summary['xdz']['l']['1'] ? $summary['xdz']['l']['1'] : '-').'</span>&nbsp;/&nbsp;
								<span class="unhappy">'.($summary['xdz']['l']['0'] ? $summary['xdz']['l']['0'] : '-').'</span>
							</td>
							<td>
								<span class="happy">'.($summary['xdz']['s']['1'] ? $summary['xdz']['s']['1'] : '-').'</span>&nbsp;/&nbsp;
								<span class="unhappy">'.($summary['xdz']['s']['0'] ? $summary['xdz']['s']['0'] : '-').'</span>
							</td>
				';
				$happyTotal = $summary['xdz']['l']['1']+$summary['xdz']['s']['1'];
				$unhappyTotal = $summary['xdz']['l']['0']+$summary['xdz']['s']['0'];
                 */
                $summaryHtml .= '
							<td>
								<b>
									<span class="happy">'.($happyTotal ? $happyTotal : '-').'</span>&nbsp;/&nbsp;
									<span class="unhappy">'.($unhappyTotal ? $unhappyTotal : '-').'</span>
								</b>
							</td>
						</tr>
					</table>
				';
				$summaryHtml = urlencode($summaryHtml);
				$summaryHtml = str_replace('+', ' ', $summaryHtml);
