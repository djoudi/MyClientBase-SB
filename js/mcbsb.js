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
	    	}
	    	
	    	dataObj[i] = { field: field, value: value, type: type };
	    }
	}
	return dataObj;
}

function jqueryDelete(params) {
	var agree=confirm("Are you sure ?");
	if (agree)
	{	
		$.ajax({
			async : true,
			type: 'POST',
			dataType : 'jsonp',
			url : '/contact/ajax/delete',
			data : {
				params: params,
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
	    	if(typeof json.message !== "undefined" && json.message){
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
				alert(urldecode(json.error));
			}
		})
	    .success(function(json){
	    	if(typeof json.message !== "undefined" && json.message){
	    		alert(urldecode(json.message));
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

		params.searched_value = searched_value;
		
		jqueryForm(params, function(response){
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

function jqueryForm(params) {
//	console.log('jqueryForm');
//	console.log(params);
	$.ajax({
		async: false,
		type: 'POST',
		dataType : 'jsonp',
		url : '/contact/ajax/getForm',
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


function openJqueryForm(json){
	//console.log('openJqueryForm');
	//console.log(json);
	
	var tag = $('<div id="mydialog"></div>');
	var procedure = '';
	selected_radio = ''; //global
	
	if(typeof json == "object") {
		
		if(json.html){
			
			var html_form = urldecode(json.html);
			
			tag.html(html_form).dialog({
			
				autoOpen: false,
				closeOnEscape: true,
				height: 'auto',
				width: 'auto',
				modal: true,
				position: ['center',30],
				resizable: false,
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
					//add something to do when the dialog opens
					//console.log('open dialog');
				},
				close: function(event, ui) {
					postFormToAjax(json.url,'jsonp','POST',json.form_name,json.object_name,json.related_object_name,json.related_object_id,selected_radio,json.procedure,null);	
				} 
			}
			).dialog('open');
		} 
	}
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

function postFormToAjax(url, dataType, type, form_name, object_name, related_object_name, related_object_id, selected_radio, procedure, input_params){
	//console.log('postFormToAjax');
	//console.log(input_params);
	
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

