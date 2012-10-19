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
			url : '/ajax/delete',
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

function jqueryAssociate(params) {
	var agree=confirm("Are you sure ?");
	if (agree)
	{	
		$.ajax({
			async : true,
			type: 'POST',
			dataType : 'jsonp',
			url : '/ajax/associate',
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
function submitenter(myfield,e)
{
//	console.log('submitenter');
//	console.log(myfield);
//	console.log(e);
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


function toggle_animate(tag_id, tag_focus, margintop) {

	$("#" + tag_id ).toggle();
	$("#" + tag_id ).animate({
		width: "100%",
		//opacity: 0.4,
		marginTop: margintop + "px",
		//marginLeft: "3.6in",
		fontSize: "3em",
		borderWidth: "10px"
	}, 0 );
	$("#" + tag_focus).focus();
}

function search(params){

//	console.log('search');
//	console.log(params);
	
	if(typeof params.search_tag_id == "undefined" || params.search_tag_id == ''){
		//the default tag id for the input box is 'input_search'
		search_tag_id = 'input_search';
		//searched_value = $('#input_search').val();
	} else {
		search_tag_id = params.search_tag_id;
	}
	
//	console.log(search_tag_id);
	
	if(typeof params.searched_value == "undefined" || params.searched_value == '') {
		//gets the value from the input box
		searched_value = urlencode($('#' + search_tag_id).val());
	} else {
		searched_value = urlencode(params.searched_value);
	}
	
//	console.log(searched_value);
	
	if(typeof searched_value !== "undefined" && searched_value){

		params.searched_value = searched_value;
		
		jqueryForm(params, function(response){
			//console.log('search function is closing');
			$('#' + params.form_name).toggle();
		    //alert('Done');
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
			url : '/ajax/set_as_my_tj_organization',
			data : {
				params: params,
			},
			success : function(json){
				get_my_tj_organization(params.oid);
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
		url : '/ajax/get_my_tj_organization',
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
		url : '/ajax/getForm',
		data : {
			params: params,
		},
		success : openJqueryForm, //this handles only jquery requests thrown errors 
		error: errorCallback,
	})
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
//			console.log('jqueryForm has an error');
			alert(urldecode(json.error));
		}
	});
}


function openJqueryForm(json){
//	console.log('openJqueryForm');
//	console.log(json);
	
	var tag = $("<div></div>");
	var procedure = '';
	selected_radio = ''; //global
	
	if(typeof json == "object" && json.html) {
		
		var html_form = urldecode(json.html);
		
		tag.html(html_form).dialog({
		
			autoOpen: false,
			height: 'auto',
			width: 'auto',
			modal: true,
			position: ['center',30],
			resizable: false,
			buttons: {
				"Ok": function() {
					postFormToAjax(json.url,'jsonp','POST',json.form_name,json.object_name,json.related_object_name,json.related_object_id,selected_radio,json.procedure);
				},
//				"Reset": function(){
//					var form = document.forms[json.form_name];
//					form.reset();					
//				},
			},

			close: function() {
			} 
		}
		).dialog('open');	
	}	
}

function postFormToAjax(url,dataType,type,form_name,object_name,related_object_name,related_object_id,selected_radio,procedure){
	//console.log('postFormToAjax');
	
	var form = document.forms[form_name];
	var formObj = retrieveForm(form);

	jQuery.ajax({
    	url		: '/ajax/validateForm',
    	dataType: dataType,
    	type	: type,
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
    	
    	url = urldecode(url);
    	
    	//let's see if the final url is an ajax request
    	if(!url.match(/^\/ajax/)) {
        	//if it's a page so I just submit the form to it
        	$('#'+form_name).submit();
        	return true;
    	}    	
    	
    	jQuery.ajax({
        	url		: url,
        	dataType: dataType,
        	type	: type,
            data    : {
            		procedure: procedure,
                	form: formObj,
                	
                	object_name: object_name,
                	selected_radio: selected_radio,
            
                	related_object_name: related_object_name,
                	related_object_id: related_object_id,                	
            },
            error	: errorCallback,
        })
		.done(function(json){
			//console.log('last ajax query has been completed');
			if(typeof json.error !== "undefined" && json.error){
				//console.log('postFormToAjax has an error at POST stage.');
				//console.log(json);
				alert(urldecode(json.error));
				//return false;
			}
		})        
        .success(function(json) {
        	//console.log('last ajax query has been successfull');
        	if(typeof json.message !== "undefined" && json.message){
        		alert(urldecode(json.message));
            	window.location.hash = json.focus_tab;
            	window.location.reload(true);
        	} 
        });
    });	
}

/* TODO this needs refactoring: postForm should be used instead of this one */
function sendForm(url,dataType,type,action,dataObj) {
    jQuery.ajax({
        //url      : "/index.php/contact/update_settings/",
        //dataType : "html",    	
		//type : 'POST',
    	url		: url,
    	dataType: dataType,
    	type	: type,
        data    : {
            		//action: 'person_aliases',
        			action: action,
            		save: true,
            		form: dataObj,
        			},
    })
    .success(function(){
    	alert('Aliases configuration saved');
    	//close the accordion
    	jQuery('#contact_accordion').accordion("activate",false);
    	jQuery('#contact_accordion').accordion("activate",2);
    })
    
	//TODO modify update_settings so that it returns a json array and send it to the DOM updating the form fields
	//and showing a proper confirmation message
    
/*    
    .success(function(){
    	window.location.reload();
    })
*/
/*  
    .success(function(responseObj) { sendFormSuccess(responseObj); } )
    .error  (function(jqXHR, status, errorThrown) {
                alert("Error submitting form: "
                    + status + " : " + errorThrown);
             } )
*/         
	;
}
