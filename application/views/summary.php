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
							<th>CTQ</th>
							<td>
								<span class="happy">'.($summary['ctq']['l']['1'] ? $summary['ctq']['l']['1'] : '-').'</span>&nbsp;/&nbsp;
								<span class="unhappy">'.($summary['ctq']['l']['0'] ? $summary['ctq']['l']['0'] : '-').'</span>
							</td>
							<td>
								<span class="happy">'.($summary['ctq']['s']['1'] ? $summary['ctq']['s']['1'] : '-').'</span>&nbsp;/&nbsp;
								<span class="unhappy">'.($summary['ctq']['s']['0'] ? $summary['ctq']['s']['0'] : '-').'</span>
							</td>
				';

				$happyTotal = $summary['ctq']['l']['1']+$summary['ctq']['s']['1'];
				$unhappyTotal = $summary['ctq']['l']['0']+$summary['ctq']['s']['0'];
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
