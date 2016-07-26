<?php // this goes in div#hTOForm ?>

	<div id="hTOAddFormContainer">
		<img src="<?php echo URL::base(); ?>img/ajax-loader.gif" alt="loading" height="16" width="16" id="hTOThrobberForm"/>
		<?php
		/* And what do you know? IE is fucking broken for a change. Trapping the submit event with jQUery's
		live() just plain and simple doesn't work. The workaround is to prevent form submissions in the
		HTML, and add the submit handler to the submit buttons. */
		?>
		<form method="post" action="<?php echo URL::base();?>ajax/save" id="hTOAddForm" onsubmit="return false;">
			<input type="hidden" name="hTODate" value="<?php echo $date;
			// Note that the id field below is only filled when an edit button is clicked
			?>"/>

			<input type="hidden" name="hTOId" id="hTOId" value=""/>

			<table id="hTOBasicInfo">
				<tr>
					<th><label for="hTOName">Nimi</label></th>
					<th><label for="hTOFromTime">Paikalla klo</label></th>
					<th><label for="hTONotes">Huom</label></th>
				</tr>

				<tr>
					<td><input type="text" name="hTOName" id="hTOName" size="20"  maxlength="30"/></td>

					<td><input type="text" name="hTOFromTime" id="hTOFromTime" size="5" maxlength="5"/>&nbsp;<img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Saapumisaika kentälle muodossa '17:00'."/></td>

					<td><input type="text" name="hTONotes" id="hTONotes" size="30" maxlength="100"/>&nbsp;<img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Vapaatekstikenttä johon voi kirjoitella esim. tarvitsemistaan tai tarjoamistaan kyydeistä."/></td>
				</tr>
			</table>

			<label><input type="radio" name="hTOPersonType" class="hTOPersonType" value="hTOPersonLicenced"/> Kelppari</label><br/>

			<fieldset id="hTOAddFormLicenced" class="hTOAddFormType">
				<fieldset>
					<legend>Kouluttaja</legend>

					<label><input type="checkbox" name="hTO_f_i_aff" /> Pudotan NOVAa</label><br/>

					<label><input type="checkbox" name="hTO_f_i_sl" /> Pudotan PLää</label><br/>

					<label><input type="checkbox" name="hTO_f_i_radio" /> Toimin radiokouluttajana</label><br/>

                    <label><input type="checkbox" name="hTO_f_i_fs" /> Toimin VPK:na</label><br/>

					<label><input type="checkbox" name="hTO_f_i_tandem" /> Pudotan tandemeita</label><br/>
				</fieldset>
			<!--
				<label><input type="checkbox" name="hTO_f_xdz_only_l" />Hyppään vain XDZ:sta <img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Vierailijoiden kannattaa ruksata tämä, niin tiedetään ettei kyseinen hyppääjä ole täyttämässä CTQ-pokia. Tai muutenkin jos matalat ei nappaa."/></label><br/>

				<label><input type="checkbox" name="hTO_f_ctq_only_l" />Hyppään vain CTQ:stä</label><br/>
-->
				<label title=":("><input type="checkbox" name="hTO_f_unhappy_l" /><img src="<?php echo URL::base();?>img/frown.gif" alt="Ei OK" width="15" height="15" /> <img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Jos olisit tulossa, mutta jostain vielä kiikastaa, ruksaa tämä ja kirjoita 'Huom'-kohtaan miksi suupielet ovat alaspäin, esim. 'Jos saan kyydin'. Kun kyyti sitten löytyy, ota tästä ruksi pois."/></label><br/>

				<input type="submit" value="Tallenna" class="hTOSubmit" />&nbsp;<input type="button" value="Peruuta" class="hTOCancel"/>
			</fieldset>

			<label><input type="radio" name="hTOPersonType" class="hTOPersonType" value="hTOPersonStudent"/> Oppilas</label><br/>

			<fieldset id="hTOAddFormStudent" class="hTOAddFormType">
				<label><input type="radio" name="hTOStudentType" value="hTO_f_s_sl" />Tarvitsen PL-mesun</label><br/>

				<label><input type="radio" name="hTOStudentType" value="hTO_f_s_aff" />Tarvitsen NOVA-mesun</label><br/>

				<label><input type="radio" name="hTOStudentType" value="hTO_f_s_aff2" />Tarvitsen 2 NOVA-mesua</label><br/>

				<label><input type="checkbox" name="hTO_f_s_radio" />Tarvitsen radiokouluttajan</label><br/>

                <label><input type="checkbox" name="hTO_f_s_fs" />Tarvitsen Mesun/VPK:n</label><br/>
<!--
				<label><input type="checkbox" name="hTO_f_xdz_only_s" />Hyppään vain XDZ:sta</label><br/>

				<label><input type="checkbox" name="hTO_f_ctq_only_s" />Hyppään vain CTQ:stä <img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="PL-oppilaan kannattaa ruksata tämä, ettei päädy kerholle ihmettelemään kuinka Apinahissistä ei voikaan hypätä pakkolaukaisuhyppyjä."/></label><br/>
-->
				<label title="foo"><input type="checkbox" name="hTO_f_unhappy_s" /><img src="<?php echo URL::base();?>img/frown.gif" alt="Ei OK" width="15" height="15" /> <img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Jos olisit tulossa, mutta jostain vielä kiikastaa, ruksaa tämä ja kirjoita 'Huom'-kohtaan miksi suupielet ovat alaspäin, esim. 'Jos saan kyydin'. Kun kyyti sitten löytyy, ota tästä ruksi pois."/></label><br/>

				<input type="submit" value="Tallenna" class="hTOSubmit" />&nbsp;<input type="button" value="Peruuta" class="hTOCancel"/>
			</fieldset>

			<label><input type="radio" name="hTOPersonType" class="hTOPersonType" value="hTOPersonTandemStudent"/> Tandem-oppilas <img src="<?php echo URL::base();?>img/help.png" class="tooltip" title="Tätä käyttää useimmiten tandem-puhelinpäivystäjä tai tandem-mesu."/></label>

			<br/>

			<fieldset id="hTOAddFormTandemStudent" class="hTOAddFormType">
            <!--
				<label><input type="checkbox" name="hTO_f_xdz_only_s" />Hyppään vain XDZ:sta</label><br/>

				<label><input type="checkbox" name="hTO_f_ctq_only_s" />Hyppään vain CTQ:stä</label><br/>
-->
				<input type="submit" value="Tallenna" class="hTOSubmit" />&nbsp;<input type="button" value="Peruuta" class="hTOCancel"/>
			</fieldset>

<!--
			<label><input type="radio" name="hTOPersonType" class="hTOPersonType" value="hTOPersonXDZPilot"/> XDZ-pilotti</label><br/>
			<fieldset id="hTOAddFormXDZPilot" class="hTOAddFormType">
				<input type="submit" value="Tallenna" class="hTOSubmit" />&nbsp;<input type="button" value="Peruuta" class="hTOCancel"/>
			</fieldset>
-->
			<label><input type="radio" name="hTOPersonType" class="hTOPersonType" value="hTOPersonCTQPilot"/> CTQ-pilotti</label><br/>

			<fieldset id="hTOAddFormCTQPilot" class="hTOAddFormType">
				<input type="submit" value="Tallenna" class="hTOSubmit" />&nbsp;<input type="button" value="Peruuta" class="hTOCancel"/>
			</fieldset>

		</form>
	</div>
