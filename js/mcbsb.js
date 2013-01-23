/**
 * MCB-SB js library
 */

/* this should prevent IE to give error if console is not defined. TODO test it 
 * http://stackoverflow.com/questions/7585351/testing-for-console-log-statements-in-ie
 */
if (!window.console) {
    (function() {
      var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml",
      "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];
      window.console = {};
      for (var i = 0; i < names.length; ++i) {
        window.console[names[i]] = function() {};
      }
    }());
}

function errorCallback(jqXHR, textStatus, errorThrown)
{
	//console.log('errorCallback has been called.');
    //console.log(jqXHR);
	//alert(textStatus +": "+ errorThrown);
    if(jqXHR.responseText != '') {
    	//sends back the server html error (ex. 404 message)
    	html = jqXHR.responseText;
    } else {
    	//sends back the jquery error
    	html =  textStatus +": "+ errorThrown;
    }
    var tag = $("<div></div>");
    tag.html(html).dialog({
		
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		position: 'center',
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function(){
				$(this).dialog('close');
			},
		},

		close: function() {
		} 
	}
	).dialog('open');	
    
}


function submit_person(){
	first_name = $('#first_name').val();
	last_name = $('#last_name').val();

	if(first_name == '' || last_name == '') return true;
	
	searched_value = first_name + ' ' + last_name;

	//console.log('searched_value ' + searched_value); 
	search({ 
			'searched_value': searched_value,
			'first_name': first_name,
			'last_name': last_name,
			'procedure':'searchPersonToAdd',
			'form_name':'add_person_form',
			'form_type':'search',
			'object_name':'person',
			'url':'/contact/ajax/create_person/',
			'hash':'set_here_the_hash' 
	});				
    return false;
}

function submit_organization(){
	
	organization_name = $('#organization_name').val();

	if(organization_name == '') return true;
	
	searched_value = organization_name;
	
	search({ 
			'searched_value': searched_value,
			'procedure':'searchOrganizationToAdd',
			'form_name':'add_organization_form',
			'form_type':'search',
			'object_name':'organization',
			'url':'/contact/ajax/create_organization/',
			'hash':'set_here_the_hash' 
			});				
    return false;
}


function search_organization(){
//	console.log('obj_type' + object_type);
//	console.log('con_id' + contact_id);
	search({ 
		'procedure':'personToOrganizationMembership', 
		'form_name': 'search_organization_form', 
		'form_type':'search',
		'object_name':'organization',
		'related_object_name': object_type,
		'related_object_id': contact_id,
		'url':'/contact/ajax/associate/',
		'hash':'set_here_the_hash'
	});
	return false;
}

function check_password(password,confirm_password,email){
	
	if(password != confirm_password) {
		show_message('Passwords do not match','error');
		return false;
	}	
	 
	if(password == email) {
		show_message('Your email can not be your password','error');
		return false;		
	}
	
	var option = {}; 
	strength = $.fn.teststrength(password,email,option);
	
	if(strength != "Good" && strength != "Strong") {
		show_message('Password is not strong enough','error');
		return false;
	}
	
	return true;
}

function submit_password(){

	var email = $('#contact_email').val();
	if(!email) {
		show_message('A password can be set only if the contact has an email set.','error');
		return false;
	}
	
	var uid = $('#contact_uid').val();
	var password = $('#password').val();
	var confirm_password = $('#confirm_password').val();
	
	if(!check_password(password,confirm_password,email)) return false;
	
	$.ajax({
		async: true,
		type: 'POST',
		dataType : 'jsonp',
		url : '/contact/ajax/update_password',
		data: {
			uid: uid,
			password: password,
			confirm_password: confirm_password,
			},
		success : function(json){
			if(json.status) {
				toggle_animate('set_password','password');
				show_message(json.message,'success');
				return false;
			} else {
				show_message(json.message,'error');
			}
		}, 
		error: errorCallback,		
	});
}

function toggle_enable(){
	var agree=confirm("Are you sure ?");
	if (agree)
	{			
		$.ajax({
			async: true,
			type: 'POST',
			dataType : 'jsonp',
			url : '/contact/ajax/toggle_enable',
			data: {
				contact_id: contact_id,
				object_type: object_type,
				},
			success : function(json){
				if(json.status) {
					show_message(json.message,'success');
					window.setTimeout(function(){location.reload()},1000);
					return false;
				} else {
					show_message(json.message,'error');
				}
			}, 
			error: errorCallback,		
		});
	}
}

function retrieveForm(form) {

	var dataObj = {};
	
	var elemArray = form.elements;

	if(typeof elemArray.length !== "undefined" && elemArray.length){
	    for (var i = 0; i < elemArray.length; i++) {
	
	    	var element = elemArray[i];
	    	var field = element.name;
	    	var value = element.value;
	    	if(typeof element.type !== "undefined" && element.type){
	    		
	    		var type = element.type.toUpperCase();
	    		
	    		//console.log(element);
	    		
	    		if(type == 'CHECKBOX' || type == 'RADIO'){
	    			
	    			//add checkboxes only if they are checked
	    			if(checked = $('#' + element.id + ':checked').val()){
	    				
	    				dataObj[i] = { field: field, value: value, type: type };
	    			} 
	    		} else {
	    			dataObj[i] = { field: field, value: value, type: type };
	    		}
	    	}
	    	
	    	
	    }
	}
	return dataObj;
}

function jqueryDelete(params, url) {
	var agree=confirm("Are you sure ?");
	if (agree)
	{	
		if(!url) url = '/contact/ajax/delete';
		
		//console.log(params);
		
		$.ajax({
			async : true,
			type: 'POST',
			dataType : 'jsonp',
			url : url,
			data : {
				params: params,
			}, 
			error: errorCallback,
		})
		.done(function(json){
			if(typeof json.error !== "undefined" && json.error){
				//console.log('jqueryDelete has an error');
				//alert(urldecode(json.error));
			}
		})
	    .success(function(json){
	    	if((typeof json.message !== "undefined" && json.message) || json.status){
	    		//alert(urldecode(json.message));
	    		window.location.hash = json.focus_tab;
	    		window.location.reload(true);
	        	//console.log('json' + json);
	    	}
	    });
	}
}

function jqueryAssociate(params) {
	var agree=confirm("Are you sure ?");
	if (agree)
	{	
		$.ajax({
			async : true,
			type: 'POST',
			dataType : 'jsonp',
			url : '/contact/ajax/associate',
			data : {
				params: params,
			}, 
			error: errorCallback,
		})
		.done(function(json){
			if(typeof json.error !== "undefined" && json.error){
				//console.log('jqueryDelete has an error');
				//alert(urldecode(json.error));
			}
		})
	    .success(function(json){
	    	if(typeof json.message !== "undefined" && json.message){
	    		//alert(urldecode(json.message));
	        	window.location.hash = json.focus_tab;
	        	window.location.reload(true);
	    	}
	    });
	}
}

//this intercepts the "enter" keystroke
/*
function submitenter(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	if (keycode == 13)
	{
		myfield.form.submit();
	    return false;
	}
	else
	   return true;
}
*/

function toggle_animate(tag_id, tag_focus) {

	$("#" + tag_id ).toggle();
	$("#" + tag_id ).animate({
		width: "100%",
		//opacity: 0.4,
		//marginTop: margintop + "px",
		//marginLeft: "3.6in",
		//fontSize: "3em",
		//borderWidth: "10px"
	}, 0 );
	$("#" + tag_focus).focus();
}

function search(params){
	//console.log('search');
	
	if(typeof params.search_tag_id == "undefined" || params.search_tag_id == ''){
		//the default tag id for the input box is 'input_search'
		search_tag_id = 'input_search';
	} else {
		search_tag_id = params.search_tag_id;
	}
	
	if(typeof params.searched_value == "undefined" || params.searched_value == '') {
		//gets the value from the input box
		searched_value = urlencode($('#' + search_tag_id).val());
	} else {
		searched_value = urlencode(params.searched_value);
	}
	
	if(typeof searched_value !== "undefined" && searched_value){

		//console.log(params);
		
		params.searched_value = searched_value;
		
		jqueryForm(params, null, function(response){
			//console.log('search function is closing');
			$('#' + params.form_name).toggle();
		});	
	} else {
		return false;
	}
};	

function set_as_my_tj_organization(params){
	var agree=confirm("Are you sure ?");
	if (agree)
	{	
		$.ajax({
			async: true,
			type: 'POST',
			dataType : 'jsonp',
			url : '/contact/ajax/set_as_my_tj_organization',
			data : {
				params: params,
			},
			success : function(json){
				alert(json.message);
				if(json.status){
					get_my_tj_organization(params.oid);					
				}
			}, 
			error: errorCallback,
		})
		.done(function(json){
			if(typeof json.error !== "undefined" && json.error){
				alert(urldecode(json.error));
			}
		});
	}
}

function get_my_tj_organization(current_oid){
	
	$.ajax({
		async: true,
		type: 'POST',
		dataType : 'jsonp',
		url : '/contact/ajax/get_my_tj_organization',
		success : function(json){
			if(json.status){
				if(current_oid == json.oid){
					$('#tj_organization').show();
				}
			}
		}, 
		error: errorCallback,
	})
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			alert(urldecode(json.error));
		}
	});
}

function jqueryForm(params,url) {
//	console.log('jqueryForm');
//	console.log(params);
	
	//default value TODO the ajax controller in contact module needs refactoring
	if(!url) url = '/contact/ajax/getForm';
	
	$.ajax({
		async: false,
		type: 'POST',
		dataType : 'jsonp',
		url : url,
		data : {
			params: params,
		},
		success : function(json){
			if(json.html){
				openJqueryForm(json);
			} else {
				switch(json.procedure){
					case 'searchPersonToAdd':
						if(json.uid){
							window.location = '/contact/form/uid/' + json.uid;
						}
					break;
					
					case 'searchOrganizationToAdd':
						if(json.oid){
							window.location = '/contact/form/oid/' + json.oid;
						}						
					break;
					
					default:
						postFormToAjax(json.url,'jsonp','POST',null,json.object_name,json.related_object_name,json.related_object_id,null,json.procedure,json.input_params);
					break;
				}			
			}
		},
		error: errorCallback,
	})
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			alert(urldecode(json.error));
		}
	});
}

function retrieve_validate_form(form_name){
	
	var form = document.forms[form_name];
	var formObj = retrieveForm(form);

	jQuery.ajax({
    	url		: '/contact/ajax/validateForm',
    	dataType: 'jsonp',
    	type	: 'post',
        data    : {
            	form: formObj
        },
        error	: errorCallback,
    })
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			//console.log('postFormToAjax has an error at validation stage.');
			alert(urldecode(json.error));
		}
	})
	.success(function(json) {
		//console.log('form validation has been successfull');
		return json;
	});
}

function openJqueryForm(json){
//	console.log('openJqueryForm');
//	console.log(json);
	
	var tag = $('<div id="mydialog"></div>');
	var procedure = '';
	selected_radio = ''; //global
	
	if(typeof json == "object") {
		
		if(json.html){
				
			var html_form = urldecode(json.html);
			
			var dialog_title = '';
			
			//TODO uncomment once I can translate js messages
			//if(json.form_title) dialog_title = json.form_title;
			tag.html(html_form).dialog({
			
				autoOpen: false,
				closeOnEscape: true,
				height: 'auto',
				width: 'auto',
				modal: true,
				position: ['center',30],
				resizable: true,
				title: dialog_title,
				//zIndex: 900,
				buttons: {
					"Ok": function() {
						$(this).dialog("close");
					},
					"Cancel": function(){
						$(this).dialog("destroy");
					},
	//				"Reset": function(){
	//					var form = document.forms[json.form_name];
	//					form.reset();					
	//				},
				},
				open: function(){

					//this remove the X button but causes the dialog to open twice when all the text in the page is selected (ctr+A on the browser)
					//$('#mydialog').dialog({ dialogClass: 'no-close' });
					
					//enables datetimepicker for all the form fields with class datetimepicker
					$(".datetimepicker").datetimepicker({ 
						dateFormat: 'yy-mm-dd',
						hour: 7,
						hourMin: 7,
						hourMax: 20,
						stepMinute: 15,
//						minDateTime: 0,
//						maxDateTime: null
					});
					
					//enables datepicker for all the form fields with class datepicker
					$(".datepicker").datepicker({ 
						dateFormat: 'yy-mm-dd',
//						minDate: 0,
//						maxDate: null
					});
				},
				close: function(event, ui) {
					
					switch(json.procedure){
						
						case 'automated_form':
							var form = document.forms[json.form_name];
							var formObj = retrieveForm(form);
							$('#'+json.form_name).submit();
						break;
						
						case 'create_otr':
						case 'create_appointment':
						case 'create_appointment_for_task':
						case 'close_task':
							console.log('post to ajax');
							console.log(json);
							postToAjax(json);
						break;
						
						default:
							console.log(json);
							postFormToAjax(json.url,'jsonp','POST',json.form_name,json.object_name,json.related_object_name,json.related_object_id,selected_radio,json.procedure,null);
						break;
					}
				} 
			}
			).dialog('open');
		} 
	}
}

//TODO refactoring: this function replaces postFormToAjax
function postToAjax(json, dataType, type){
	console.log('postToAjax');
	console.log(json);
	
	var params = json;
	if(!dataType) var dataType = 'jsonp';
	if(!type) var type = 'POST';
	var url = urldecode(json.url);
	var formObj = '';
	
	if(json.form_name) {
		
		var form = document.forms[json.form_name];
		formObj = retrieveForm(form);
		
		console.log('formObj');
		console.log(formObj);
		
	} 
	
	$.ajax({
    	url		: url,
    	dataType: dataType,
    	type	: type,
        data    : { 
        			form: formObj,
        			json: json,
        		  },
        error	: errorCallback,
    })
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			console.log('error ' + json.error);
			return false;
		}
	})        
    .success(function(json) {

    	if(json.status){
			switch(urldecode(json.procedure)){
				case 'replace_html':
					
					$.each(json.replace, function(index, item) {
						console.log(item)
						$('#' + item.id).html(item.html);
					});
					return true;
					
				break;
				
				default:
		    		//alert(urldecode(json.message));
					var focus_tab = json.focus_tab;
					if(!focus_tab){
						if(params.procedure == 'create_otr') focus_tab = 'tab_Tasks';
						if(params.procedure == 'create_appointment') focus_tab = 'tab_Tasks';
						if(params.procedure == 'create_appointment_for_task') focus_tab = 'tab_Tasks';
						if(params.procedure ==  'close_task') focus_tab = 'tab_Tasks';
						if(params.procedure == 'create_appointment_for_task') focus_tab = 'tab_Tasks';
					}
					//console.log('focus tab ' + focus_tab);
		        	window.location.hash = focus_tab;
		        	window.location.reload(true);					
				break;
			}	    		
    	} 
    });	
	
}

function postFormToAjax(url, dataType, type, form_name, object_name, related_object_name, related_object_id, selected_radio, procedure, input_params){
//	console.log('postFormToAjax');
//	console.log(input_params);
	
	url = urldecode(url);
	
	if(form_name) {
		
		var form = document.forms[form_name];
		var formObj = retrieveForm(form);
		
		json = retrieve_validate_form(form_name);	
		
		//let's see if the final url is an ajax request
		if(!url.match(/^\/contact\/ajax/)) {
			//console.log('submitting to page ' + url + ' and leaving');
			
	    	$('#'+form_name).submit();
	    	return true;
		}    
		
	} else {
		var formObj = '';
	}
	
	
	
	jQuery.ajax({
    	url		: url,
    	dataType: dataType,
    	type	: type,
        data    : {
        		procedure: procedure,
            	form: formObj,
            	
            	input_params: input_params,
            	
            	object_name: object_name,
            	selected_radio: selected_radio,
        
            	related_object_name: related_object_name,
            	related_object_id: related_object_id,                	
        },
        error	: errorCallback,
    })
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			alert(urldecode(json.error));
			return false;
		}
	})        
    .success(function(json) {
    	if(typeof json.message !== "undefined" && json.message){
			switch(json.procedure){
				case 'create_person':
					if(json.uid){
						window.location = '/contact/form/uid/' + json.uid;
					}
				break;

				case 'create_organization':
					if(json.oid){
						window.location = '/contact/form/oid/' + json.oid;
					}
				break;
				
				case 'close_task':
					console.log('after submit');
					console.log(json);
					if(json.status && json.html && json.html_id){
						console.log('replacing');
						$('#' + json.html_id).html(json.html);
					} else {
						console.log('wrong');
						//window.location.reload(true);
					}					
				break;
				
				default:
		    		//alert(urldecode(json.message));
		        	window.location.hash = json.focus_tab;
		        	window.location.reload(true);					
				break;
			}	    		
    	} 
    });
	
}

function addAutoComplete(input){

	$(input)
	// do not navigate away from the field on tab when selecting an item
	.bind( "keydown", function( event ) {
    	if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {
                event.preventDefault();
    	}
	})
	.autocomplete({
		source: function(request,response) {
				$.ajax({
					async : true,
					type: 'POST',
					dataType : 'jsonp',
					url : '/contact/ajax/fill_autocomplete',
					data : {
						searched_object: 'person',
						attribute: $(input).attr('id'),
					}, 
					error: errorCallback,
				})
				.done(function(json){
					if(typeof json.error !== "undefined" && json.error){
						//console.log('jqueryDelete has an error');
						alert(urldecode(json.error));
					}
				})
			    .success(function(json){
				    response( $.map(json.values, function(item){
					    return item;
				    }));
			    });
			},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},				
		select: function( event, ui ) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push( "" );
			this.value = terms.join( ", " );
			return false;
		}				
	});			
		
}

function addZero(i)
{
	if (i<10){
		i="0" + i;
	}
	return i;
}

function show_message(message,type){
	
	var now = new Date();
	var current_time = addZero(now.getHours())+':'+addZero(now.getMinutes())+':'+addZero(now.getSeconds());
	var css_class = '';
	
	if(type=='error') css_class = 'dark_red';
	if(type=='success') css_class = 'dark_green';
	
	message = '<li class="system_message ' + css_class + '">' + current_time + ' - '+ message + '</li>';
	
	$('#notification_area_messages').prepend(message);
	
}
