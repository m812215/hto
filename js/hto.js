function hTOInit() {
	var u = hTORoot+"ajax/get_calendar";
	
	var e = $.url().param('e');
	if (typeof e != "undefined") {
		u += '_expanded';
	}

	$.get(u, function(data) {
		$('#hTOList').append('<div id="hTOCalendar"></div>');
		$('#hTOList').append('<div id="hTOListProper"></div>');
		$('#hTOCalendar').html(data);

		// Quick fix for Opera Mini, allows the viewing of HTO even if it doesn't work.
		// Allows opening the HTO with a URL parameter: ?date=2011-02-13
		$('.' + $.url().param('date') + '.date').click();			
	});


	// Handle clicks on the personType radio buttons
	$('.hTOPersonType').live('click', function() {
		$('.hTOAddFormType').hide();
	
		var type = $("input[name='hTOPersonType']:checked").val();
		
		switch(type) {
			case "hTOPersonLicenced":
				$('#hTOAddFormLicenced').show();
			break;
			
			case "hTOPersonStudent":
				$('#hTOAddFormStudent').show();
			break;
			
			case "hTOPersonTandemStudent":
				$('#hTOAddFormTandemStudent').show();
			break;
			
			case "hTOPersonCWWPilot":
				$('#hTOAddFormCWWPilot').show();			
			break;
			
			case "hTOPersonXDZPilot":
				$('#hTOAddFormXDZPilot').show();
			break;
		}
	});
	

	// ADD
	$('#hTOAddMe').live('click', function() {
		$('#hTOAddFormContainer').toggle();
		
		// reset form action in case form was used for editing
		$('#hTOAddForm').attr('action', hTORoot+'ajax/save');
		
		// reset submit button texts
		$('.hTOSubmit').attr('value', 'Tallenna');
		
		// Clear the form
		data = new Array;
		hTOPopulateForm(data);
	});


	// EDIT
	$('.hTOEdit').live('click', function() {
		// change the default action of "save" into "edit:"
		$('#hTOAddForm').attr('action', hTORoot+'ajax/edit');

		// reset submit button texts
		$('.hTOSubmit').attr('value', 'Tallenna muutokset');
		
		// Read entry id from title attribute
		var id = $(this).attr('title');
		id = id.substr(5);
		
		// Get the row details from DB based on id
		$.get(hTORoot+"ajax/get_row/"+id, function(data) {
			data = eval('('+data+')');

			// Populate form
			hTOPopulateForm(data);
			
			// Show form
			$('#hTOAddFormContainer').show();
		});

		return false;
	});
	

	// DELETE
	$('.hTODelete').live('click', function() {
		var personName = $(this).parent().parent().find('.hTOName').html();
	
		if(confirm('Oletko varma että haluat poistaa henkilön '+personName+' tältä päivältä?')) {
			// Read entry id from title attribute
			var id = $(this).attr('title');
			id = id.substr(7);

			$.post(hTORoot+"ajax/delete", {id: id}, function(data) {
				data = eval('('+data+')');
				
				if(data.status == 1) {
					// Update list:
					// Add throbber overlaid on list
					$('#hTOThrobber').show();
					
					// Refresh list:
					var date = $("input[name='hTODate']").val();
					$.get(hTORoot+"ajax/list_proper/"+date, function(data) {
						$('#hTOListProper').html(data);
						
						// Hide throbber
						$('#hTOThrobber').hide();
					});
					
					// Refresh calendar in the mode we had on:
					if($('#hTOCalendar').hasClass('expanded')) {
						var expanded = '_expanded';
					}
					else {
						var expanded = '';	
					}
					$.get(hTORoot+"ajax/get_calendar"+expanded+"/"+date, function(data) {
						$('#hTOCalendar').html(data);
						
						// Hide throbber
						$('#hTOThrobberCalendar').hide();
					});
				}
				else {
					var errors = 'Virhe henkilöä poistettaessa';
					hTOShowErrors(errors);
				}
			});
		}
		else {
			// Got canceled
		}

		return false;
	});
	

	// CANCEL adding a person
	$('.hTOCancel').live('click', function() {
		$('#hTOAddFormContainer').hide();
	});
	

	// SUBMIT ADD
	// See comment block of hTOAddSubmitHandler()
	$('#hTOAddForm').live('submit', function() { 
		hTOAddSubmitHandler($(this));
		return false;
	});
	$('.hTOSubmit').live('click', function() { 
		hTOAddSubmitHandler($(this).closest('form'));
		return false;
	});
	
	
	// SUBMIT DATE EDIT
	$('#hTOEditDateForm').live('submit', function() { 		
		hTODateEditSubmitHandler($(this))
		return false;
	});
	$('#hTOEditDateSubmit').live('click', function() { 
		hTODateEditSubmitHandler($(this).closest('form'));
		return false;
	});
	

	// Clicks on calendar dates
	$('#hTOCalendar ul li.date, #hTOCalendar td').live('click', function(){
		// Click on active date hides HTO
		if($(this).hasClass('hTOCurrentDate')) {
			$('li.date, td.date').removeClass('hTOCurrentDate');
			$('#hTOListProper').html('');
			$('#hTOAddFormContainer').hide();
			return false;
		}
	
	
		$('li.date, td.date').removeClass('hTOCurrentDate');
		$(this).addClass('hTOCurrentDate');

		var date = $(this).attr('class');
		date = date.split(' ');
		date = date[0];

		// Add throbber overlaid on list and form
		$('#hTOThrobberList').show();
		$('#hTOThrobberForm').show();
		
		// Update list		
		$.get(hTORoot+"ajax/list_proper/"+date, function(data) {
			$('#hTOListProper').html(data);
		});
	
		// Update form
		$.get(hTORoot+"ajax/form/"+date, function(data) {
			$('#hTOForm').html(data);
			
		});
	});
	
	// Opera Mini fix. 
	$('#hTOCalendar ul li.date a').live('click', function(){
		if(navigator.userAgent.indexOf('Opera Mini') == -1) {
			$(this).parent().click();
			return false;		
		}
	});	

	
	// EXPAND / COLLAPSE CALENDAR
	// TODO WE'RE LOSING THE CURRENT DATE
	$('#hTOExpandCalendar').live('click', function(){
		var date = $("input[name='hTODate']").val();
		$('#hTOThrobberCalendar').show();
		
		// Get latest list
		$.get(hTORoot+"ajax/get_calendar_expanded/"+date, function(data) {
			$('#hTOCalendar').html(data);
			$('#hTOCalendar').removeClass();
			
			// This is used to refresh the right calendar after data was changed
			$('#hTOCalendar').addClass('expanded');
		});
		
		return false;
	});
	
	$('#hTOCollapseCalendar').live('click', function(){
		var date = $("input[name='hTODate']").val();
		$('#hTOThrobberCalendar').show();
		
		// Get latest list
		$.get(hTORoot+"ajax/get_calendar/"+date, function(data) {
			$('#hTOCalendar').html(data);
			$('#hTOCalendar').removeClass();

			// This is used to refresh the right calendar after data was changed
			$('#hTOCalendar').addClass('collapsed');
		});
		
		return false;
	});
	
	
	// TOOLTIP
	/* CONFIG */		
		xOffset = 10;
		yOffset = 20;		
		// these 2 variable determine popup's distance from the cursor
		// you might want to adjust to get the right result		
	/* END CONFIG */		
	$(".tooltip").live('mouseenter', function(e){				  
		this.t = decodeURIComponent(this.title);
		this.title = "";									  
		$("body").append("<div id='tooltip'>"+ this.t +"</div>");
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");		
    });
    
	$(".tooltip").live('mouseleave', function(){
			this.title = encodeURIComponent(this.t);		
			$("#tooltip").remove();
    });	
    
	$(".tooltip").live('mousemove', function(e){
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});
	// end tooltip

	
	// EDITING DATE
	$('#hTOWrapper h2').live('mouseenter', function() {
		$('#hTOEditDateButton').fadeIn(100);
	});
	$('#hTOWrapper h2').live('mouseleave', function() {
		$('#hTOEditDateButton').fadeOut(100);
	});
	$('#hTOEditDateButton').live('click', function() {
		$('#hTOEditDateFormContainer').show();
	});
	$('#hTOEditDateCancel').live('click', function() {
		$('#hTOEditDateFormContainer').hide();
	});	

} // end init


function hTOShowResponse(response, statusText, xhr, $form) {
	var response = eval('('+response+')');
	var responseText = response.message;
	$('#hTOMessages').html(responseText);
	
	$('#hTOMessages').show();
	
	$('#hTOMessages').delay(1000);
	$('#hTOMessages').fadeOut(1500);
	$('#hTOAddFormContainer').delay(1500).fadeOut(500);
	
	// Add throbber overlaid on list and calendar
	$('#hTOThrobber').show();
	$('#hTOThrobberCalendar').show();
	
	// Update list and calendar
	var date = $("input[name='hTODate']").val();
	$.get(hTORoot+"ajax/list_proper/"+date, function(data) {
		$('#hTOListProper').html(data);
		
		// Hide throbber
		$('#hTOThrobber').hide();
	});
	
	// Refresh calendar in the mode we had on:
	if($('#hTOCalendar').hasClass('expanded')) {
		var expanded = '_expanded';
	}
	else {
		var expanded = '';	
	}
	$.get(hTORoot+"ajax/get_calendar"+expanded+"/"+date, function(data) {
		$('#hTOCalendar').html(data);
		
		// Hide throbber
		$('#hTOThrobberCalendar').hide();
	});
}

function hTOShowErrors(errors) {
	$('#hTOErrors').html(errors);
	$('#hTOErrors').show();
	$('#hTOErrors').delay(1000);
	$('#hTOErrors').fadeOut(1500);
}

function hTOPopulateForm(data) {
	// General data
	if(data.id) {
		$('#hTOId').val(data.id);	
	}
	else {
		$('#hTOId').val('');
	}
	
	if(data.name) {
		$('#hTOName').val(data.name);
	}
	else {
		$('#hTOName').val('');
	}
	
	if(data.notes) {
		$('#hTONotes').val(data.notes);
	}
	else {
		$('#hTONotes').val('');
	}
	
	if(data.from_time) {
		$('#hTOFromTime').val(data.from_time);	
	}
	else {
		$('#hTOFromTime').val('');
	}
		
	// Uncheck all radio buttons, and hide all fieldsets:
	$("input[name=hTOPersonType]").attr('checked', '');
	$("input[name=hTOStudentType]").attr("checked","");
	$('.hTOAddFormType').hide();
	
	// Check the right radio button based on the person type:
	if(data.f_licenced == "1") {
		$("input[name=hTOPersonType]").filter("[value=hTOPersonLicenced]").attr("checked","checked");
	}
	else if(data.f_s_aff == "1" || data.f_s_aff2 == "1" || data.f_s_sl == "1") {
		$("input[name=hTOPersonType]").filter("[value=hTOPersonStudent]").attr("checked","checked");
		
		// Set the student type
		if(data.f_s_aff == "1") {
			$("input[name=hTOStudentType]").filter("[value=hTO_f_s_aff]").attr("checked","checked");
		} 
		else if(data.f_s_aff2 == "1"){
			$("input[name=hTOStudentType]").filter("[value=hTO_f_s_aff2]").attr("checked","checked");		
		} 
		else if(data.f_s_sl == "1") {
			$("input[name=hTOStudentType]").filter("[value=hTO_f_s_sl]").attr("checked","checked");
		}
		
	}
	else if(data.f_s_tandem == "1") {
		$("input[name=hTOPersonType]").filter("[value=hTOPersonTandemStudent]").attr("checked","checked");
	}
	else if(data.f_cww_pilot == "1") {
		$("input[name=hTOPersonType]").filter("[value=hTOPersonCWWPilot]").attr("checked","checked");
	}
	else if(data.f_xdz_pilot == "1") {
		$("input[name=hTOPersonType]").filter("[value=hTOPersonXDZPilot]").attr("checked","checked");
	}
	// Then 'click' the selected radio button to show only the correct fieldset:
	$("input[name=hTOPersonType]:checked").click();
	
	// Flags that are only in one place:
	var miscFlags = new Array(
            'f_i_aff',
            'f_i_sl' ,
            'f_i_radio',
            'f_i_fs',
            'f_i_tandem',
            'f_s_radio',
            'f_s_fs'
	);
	
	for(var i=0;i<miscFlags.length;i++) {
		var iname = 'hTO_'+miscFlags[i];

		$('input[name='+iname+']').attr('checked', '');
		if(data[miscFlags[i]] == "1") {

			$('input[name='+iname+']').attr('checked', 'checked');
		}
	}
	
	// Duplicate flags that need to be checked in the right fieldset
	var miscFlags = new Array(
            'f_cww_only',
            'f_xdz_only',
            'f_unhappy'
	);
	
	for(var i=0;i<miscFlags.length;i++) {
		var iname = 'hTO_'+miscFlags[i];
		
		// Uncheck both
		$('input[name='+iname+'_l]').attr('checked', '');
		$('input[name='+iname+'_s]').attr('checked', '');
		
		// ..then append the right letter to iname:
		if(data.f_licenced == "1") {
			iname = iname + '_l';
		}
		else if(data.f_s_aff == "1" || data.f_s_aff2 == "1" || data.f_s_sl == "1") {
			iname = iname + '_s';		
		}

		// .. and then check the checkbox in the right fieldset
		if(data[miscFlags[i]] == "1") {
			$('input[name='+iname+']').attr('checked', 'checked');
		}
	}
	
}

function iedebug(string) {
	$('#iedebug').html($('#iedebug').html()+string+"<br/>");
	return false;
}


/**
 * This is a separate function, since in addition to binding the submit handler to the form's
 * submit event, we need to bind it to the submit buttons' click event as well just to keep
 * fucking IE happy.
 *
 * http://forum.jquery.com/topic/ie-specific-issues-with-live-submit
 *
 */
function hTOAddSubmitHandler(el) {
	// Validate input:
	var errors = '';
	
	// validate name
	var name = $("input[name='hTOName']").val();
	if(name == '') {
		errors += 'Nimi puuttuu. ';
	}
	
	// check that student selected instructor type
	var student = $("input[name='hTOPersonType']:checked").val();
	if(student == 'hTOPersonStudent') {
		var studentType = $("input[name='hTOStudentType']:checked").val();
		
		if(typeof(studentType) == 'undefined') {
			errors += 'Valitse tarvittava mesu. ';
		}
	}
	// done validating
	
	
	if(errors == '') {
		// submit the form 
		var options = { 
			success: hTOShowResponse
		};

		el.ajaxSubmit(options); 
		// return false to prevent normal browser submit and page navigation 
		return false;
	}
	else {
		hTOShowErrors(errors)
		return false;
	}
}
function hTODateEditSubmitHandler(el) {
	// submit the form 
	var options = { 
		success: hTOShowResponse
	}; 

	el.ajaxSubmit(options); 
	// return false to prevent normal browser submit and page navigation 
	return false;
}
