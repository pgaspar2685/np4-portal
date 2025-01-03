"use strict";

var App = {}; 

console.log("init APP");

$( document ).ready(function() {

    // bind de inputs ex: autocomplete etc
    App.resetPostFormControls();

    // reload on back
    window.onpopstate = function (event) {
        window.location = document.location;
    };
    
    // init
    App.afterInit();
    
    App.updateURL = true;
	
	onpopstate = function (oEvent) {
		if (oEvent.state != undefined && oEvent.state.url != undefined ){
			App.updateURL = false;
			App.loadContent( oEvent.state.url );
		}
	};

	$("#form-marcacoes").submit(function(){
		App.submitForm("#form-marcacoes", {});
	});

	App.autocomplete();
});

App.afterInit = function () {
	// Popover
	App.popoverInit();
};

App.popoverInit = function(){
	$('[data-toggle="tooltip"]').tooltip({
	    container: "body",
	    template:
	      '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
	  });
	  $('[data-toggle="popover"]').popover();
}

/**
 * Controla a animação de load da navegação
 * @memberOf App
 */
App.showLoading = function (bool) {
    if (bool == true) {
    	$("#content").fadeTo('fast', 0.6);
    } else {
    	$("#content").fadeTo('fast', 1);
    }
};
 
/**
 * Page load via AJAX
 * 
 * @param url 		-
 * @param postdata 	- dados a passar (muda o pedido para post)
 * @memberOf App
 */
App.loadContent = function(url, params) {
	
	$("#search-result").hide();
	
	App.showLoading(true);
	
	var url_params = url[0] == '/' ? url.substring(1) : url;
	url_params = url_params.split('/');
	
	if (url[0] != '/') url = '/' + url;
	
	var method = 'get';
	var payload = ''; //'&ajax=true'
	
	if (params != undefined && params.post != undefined) {
		method = 'post';
		payload += params.post;
	}
	
	$.ajax({
		url: url,
		dataType: 'json',
		type: method,
		data: payload,
		success: function (response) {
			if (response.success == true) {		
				
				if (response.redirect_url != undefined) {
					if (response.new_location != undefined && response.new_location == true) {
	                    // o login por exemplo quer q seja feito desta forma
	                    window.location = response.redirect_url;
	                    return true;
					} else if(response.login) {
						// reencaminha para a página pedida (antes de fazer login)
						App.showLoading(false);
						return App.loadContent(url);
					} else {
						App.showLoading(false);
						return App.loadContent(response.redirect_url);
					}
				} else {
					$("#content").html(response.html);
				}
			    
				
				if (response.js != undefined) {				    
					eval(response.js);
				}
				
				// rebind events
				App.resetPostFormControls();
				
				// metodo q podera ser costumizado
				App.afterSuccessLoadContent(response, url, url_params, window.location.href.toString().split(window.location.host)[1]);				
				
			} else { 
				if (response.callback != undefined) {
					eval(response.callback);
				} else
					App.handleResponseError( response );
			}				
		},
		complete: function () {			
			App.showLoading(false);
			
			if (params != undefined && params.callback != undefined) {
				params.callback();
			}
		},
		error: function (response) {
//			App.handleResponseError( response );
		}
	});
	return false;
};

/**
 * Semelhante ao loadContent mas em vez de fazer load do conteudo para o #content
 * faz para uma div (n muda nada do menu)
 * 
 * @memberOf App
 */
App.loadDiv = function (elem, div, url, params) {
  
	if (div instanceof $) {
	} else {
		// proteccao contra falta de #
	    if (div != undefined && div.charAt(0) != '.' && div.charAt(0) != '#') {
	        div = "#" + div;
	    }
	    
	    div = $(div);
	}

	if(elem)
		$(elem).html('<i class="fas fa-circle-notch fa-spin"></i>');
	
	if (params != undefined && params.remove != undefined) {
		$(params.remove).fadeTo('fast', 0.6);
	} else {
		div.fadeTo('fast', 0.6);
	}
	
    if (params != undefined && params.loading != undefined)
    	$(params.loading.element).html("<i class='fas fa-circle-notch fa-spin'></i>");
    
    $.ajax({
        url: url,
        dataType: 'json',
        type: params != undefined && params.method != undefined ? params.method : 'post', 
        data: params != undefined && params.post_data != undefined ? params.post_data : '',
        success: function (response) {
            
            if (response.success == true) {
            	
            	if (params != undefined && params.remove != undefined) {
                    if ($(params.remove).length > 0) {
                    	$(params.remove).remove();
                    }
                    return true;
                }
            	
                if (params != undefined && params.prepend != undefined && params.prepend == true) {
                	div.prepend(response.html);
                } else if (params != undefined && params.append != undefined && params.append == true) {
                	div.append(response.html);
                } else if (params != undefined && params.insertAfter != undefined && params.insertAfter == true) {
                	$( response.html ).insertAfter( div );
                } else if (params != undefined && params.insertBefore != undefined && params.insertBefore == true) {
                	$( response.html ).insertBefore( div );
                } else if (params != undefined && params.replace != undefined && params.replace == true) {
                	div.replaceWith(response.html);
                } else 
                	div.html(response.html);
                
                if (params != undefined && params.callback != undefined) {
                    params.callback();
                }
                
                if (response.js != undefined) {                    
                    eval(response.js);
                }
                
            } else { 
                App.handleResponseError( response );
            }  
            
            var icon = $(elem).data("icon");
            var text = $(elem).data("text");
            
            if (icon != undefined && text != undefined) {
            	$(elem).html('<span class="'+ $(elem).data("icon") +'"></span> '+ text);
            } else if ($(elem).data("icon") != undefined) {
            	$(elem).html('<span class="'+ $(elem).data("icon") +'"></span>');
            } else if ($(elem).data("text") != undefined) {
            	$(elem).html($(elem).data("text"));
            } 
            
            div.fadeTo('fast', 1);
        }
    });
    
    return false;
};

/**
 * Semelhante ao loadContent mas em vez de fazer load do conteudo para o #content
 * faz para um campo e campo relaccionado (n muda nada do menu)
 * 
 * @memberOf App
 */
App.loadField = function (field, value, params) {
	
	if ($("#" + field).length > 0) {
		$("#" + field).val( value );
		
		if (params.rel != undefined && params.value != undefined) {
			$("#" + params.rel).val( params.value );
		}
	}
	
}

/**
 * Semelhante ao loadField mas faz load de um URL e vai preencher os campos com os valores devolvidos pelo pedido
 * faz para um campo e campo relaccionado (n muda nada do menu)
 * 
 * @memberOf App
 */
App.loadFields = function (url, field, params) {
	
	if (params.loading != undefined) {
		$(params.loading).fadeTo('fast', 0.6);
	}

	if ($("#" + field).length > 0) {
		
		var field_value = $("#" + field).val();
		
		$.ajax({
			url: url,
			dataType: 'json',
			type: 'get',
			data: 'f=' + field +'&v=' + field_value,
			success: function (response) {
				if (response.success == true) {				
					if (response.data != undefined) {

						var fields_data = response.data;
						var count = Object.keys(fields_data).length;
						var obj = "";
	
						for (obj in fields_data) {
							$("#" + obj).val( fields_data[obj] );
						}
                    }
					
				} else { 
					if (response.callback != undefined) {
						eval(response.callback);
					} else
						App.handleResponseError( response );
				}				
			},
			complete: function () {			

				if (params != undefined && params.callback != undefined) {
					params.callback();
				}
				
				if (params.loading != undefined) {
					$(params.loading).fadeTo('fast', 1);
				}
				
			},
			error: function (response) {
				App.handleResponseError( response );
			}
		});
	}
	return false;

}

/**
 * Form submit
 * 
 * @params form object
 * @memberOf App
 */
App.submitForm = function (form, params) {    

	$(form).find(".is-invalid").removeClass("is-invalid");
	$(form).find(".invalid-feedback").remove();

	if ($(form).data("loading-placer") != undefined) {
	    $($(form).data("loading-placer")).addClass("loading");
	}
	
	$(form).fadeTo('fast', 0.6);
	
    //if (params != undefined && params.loading != undefined)
    //	$(params.loading.element).html(params.loading.content);
	
	if ($(form).find("input:file").length) {
        var formdata = new FormData(form);
        var process = false;
        var contenttype = false;
        
    } else {
         var formdata = $(form).serialize();
         var process = true;
         var contenttype = 'application/x-www-form-urlencoded';
    }
	
	var action = $(form).attr('action');
	if (params != undefined && params.action != undefined)
		action = params.action;

	$.ajax({
        url: action,
        type: $(form).attr('method'),
        data: formdata,
        processData: process,
        contentType: contenttype,
        dataType: 'json',
        success: function (response) {
   
            if (response.success == 'false') {

                App.handleResponseError( response, form);
                
                $(form).fadeTo('fast', 1);
                
    			if (params != undefined && params.callback != undefined && params.callback.notok != undefined) {
    				params.callback.notok(response);
    			}
                
            } else {
            	
    			if (params != undefined && params.callback != undefined && params.callback.beforeok != undefined) {
    				params.callback.beforeok();
    			}
   
                if (response.redirect_url != undefined) {
                    
                    if (response.new_location != undefined && response.new_location == true) {
                        // o login por exemplo quer q seja feito desta forma
                        window.location = response.redirect_url;
                        return true;
                    } else 
                        return App.loadContent(response.redirect_url);
                    
                } else if (response.html) {
                	
                	if (params != undefined && params.targetDiv != undefined) {
                		
                		if (params != undefined && params.targetType != undefined && params.targetType == 'append')
                			$(params.targetDiv).append(response.html);
                		else 
                			$(params.targetDiv).html(response.html);
                		
                	} else {
                	
                		var placer = $(form).data("targetdiv") != undefined ? $(form).data("targetdiv") : "#content";
                    	$(placer).find(".modal-body").html(response.html);
                	}
                
                	App.afterInit();
                	
                	// rebind events
    				App.resetPostFormControls();
    				
    				$(form).fadeTo('fast', 1);
    				
        			if (params != undefined && params.callback != undefined && params.callback.ok != undefined) {
        				params.callback.ok();
        			}
                }
                
                if (response.js != undefined) {                    
                    eval(response.js);
                }
                
            }
            /*if (response.success == true) {
                $("#post_" + post_id).fadeOut();
                
            } else { 
                App.handleResponseError( response );
            } */  
            
            if ($(form).data("loading-placer") != undefined) {
                $($(form).data("loading-placer")).removeClass("loading");
            }
        },
        error: function (what) {
			if (params != undefined && params.callback != undefined && params.callback.notok != undefined) {
				params.callback.notok(what);
			}
        }
    });
	
	return false;
	//return App.loadContent($(form).attr('action'), { post: formdata });
};



/**
 * Faz um request - on success faz reload duma url
 * @memberOf App
 */
App.loadAction = function(url, refresh_url, params) {
	
	if (refresh_url != undefined) {
		App.showLoading(true);
	}
	
	$.ajax({
		url: url,
		type: 'get',
		dataType: 'json',
		success: function (response) {		

			if (response.success == true) {
		
				if (refresh_url != undefined) {
					App.loadContent(refresh_url);
				} else if (response.redirect_url != undefined) {					
					App.loadContent(response.redirect_url);
				}
				
				if (params != undefined && params.callback != undefined) {
					
					if (typeof(params.callback) === "function") {
						params.callback( response );
					} else {
						eval( params.callback );
					}
				}
				
			} else { 
				App.handleResponseError( response );
			}			
		}, 
		error : function () {}
	});
	return false;
};

App.refreshDiv = function (div, url) {
    
    $.ajax({
        url: url,
        success: function (response) {
            $(div).html( response.html );
        }
    });  
    return false;
  };


/**
 * Tratamento do erro proveniente da resposta JSON
 * 
 */
App.handleResponseError = function (response, scope, params) {
	var error_message = "Ocorreu um problema indeterminado";
	
	if (response.code) {
	    switch (response.code) {
	    
	    	case 'swal':
                swal(
                        response.errors,
                        response.message,
                        'info' // integrar com todas as mensagem do https://sweetalert.js.org
                    );
                break;
	        case 'delete-errors':
	            // (!) delete errors implica fechar a modal e isso deve ser tratado antes
	            var mensagem = "";
                for (var field_name in response.errors) {
                    mensagem += response.errors[field_name] + "<br>";
                }
                swal('Erro', mensagem, 'error')
                break;
        
	        case 'save-errors':
            case 'field-errors':

                if ($(".modal").length > 0) {
                	
                	$(".modal .invalid-feedback").remove();
                	
                	var select_tab = false;
                    for (var field_name in response.errors) {
                    	
                    	var fields = [];
                    	fields.push(field_name);
                    	fields.push(field_name + '[]'); // Para verificar elementos que sejam array
                    	
                        for (var name in fields) {
                        	var field = $(".modal [name='" + fields[name] + "']");

                        	if(field.length){
		                        if (! select_tab) {
		                        	if (field.parents('.tab-pane').length) {
			                        	var tab = field.parents('.tab-pane');
			                        	$('#' + tab.attr('aria-labelledby')).tab('show');
			                        	select_tab = true;
		                        	}
		                        }
		                        
		                        if (field.parent(".input-group").length > 0) {
		                            field.addClass("is-invalid");
		                            field.parent(".input-group").addClass("is-invalid");
		                            field.parent(".input-group").after("<div class='invalid-feedback'>" + response.errors[field_name] + "</div>");
		                        } else {
		                            field.addClass("is-invalid");
		                            field.after("<div class='invalid-feedback'>" + response.errors[field_name] + "</div>");
		                        }
		                        
	                            if ( $('#' + field_name + '_chosen').length ) $('#' + field_name + '_chosen').addClass("is-invalid"); // invalid chosen-select
		                        
		                        if (field.attr('type') == 'hidden' && $(".modal [name='" + field_name + "_nome']").length) {
		                        	$(".modal [name='" + field_name + "_nome']").addClass("is-invalid");
		                        }
                        	}
                        }
                    }
                    select_tab = false;
                    // App.shakeModal();
                } else {

                	$(scope).find(".invalid-field").remove();

                    if ($(scope).find(".error-placer").length > 0) {
                    	
                        for (var field_name in response.errors) {
                        	
                            var field = $(scope).find("input[name='" + field_name + "']");
                            
                            $(field)
                                .addClass("is-invalid")
                                .focus()
                                .on('keyup change', function() {
                                    $(this).removeClass("is-invalid");
                                    $(scope).find(".error-placer").fadeOut('normal');
                                });
                            
                            if (field.attr("type") == "checkbox" || field.attr("type") == "radio") {
                            	field.parents(".form-group").append("<div class='invalid-field'><i class='fas fa-info-circle'></i> "+ response.errors[field_name] +"</div>");
                            } else {
                            	$("<div class='invalid-field'><i class='fas fa-info-circle'></i>"+ response.errors[field_name] +"</div>").insertAfter( $(field) );
                            }
                            
//                            $(scope).find(".error-placer")
//                                .html(response.errors[field_name])
//                                .fadeIn('normal');
                            
//                            break;
                        }
                    } /*else {
                    	
                        for (var field_name in response.errors) {
                            var field = $("#" + field_name);
                            
                            if (field.hasClass('text-html')) {
                            	field.siblings('iframe').after("<div class='invalid-feedback'>" + response.errors[field_name] + "</div>");
                            }
                        }
                    }*/
                }
                break;
            default:
            	if ($(scope).find(".error-placer").length > 0) {
            		$(scope).find(".error-placer")
                        .html(response.message)
                        .fadeIn('normal');
                } else {
                    swal(
                        response.code,
                        response.message,
                        'error'
                    );
                }
                break;
        }       
	} else {
    	if (response != undefined) {
    		if (response.message != undefined) {
    			error_message = response.message;
    			
    		} else if (response.responseText != undefined) {
    		    error_message = response.responseText;
    		
    		} else if (response.html != undefined) {
    			error_message = response.html;
    		}
    	} 
		
    	if (error_message.indexOf('html') != -1) {
    		return bootbox.dialog({
    			message: error_message			
    		});
    	} else if (response.error_code == '401') {
    		//window.location = '/session/index';
    		return swal("Erro", "Falha de permissões. Re-login?", "error");
    	} else if ($(scope).find(".error-placer").length > 0) {
    		$(scope).find(".error-placer")
                    .html(response.message)
                    .fadeIn('normal');
        } else {  
    		/*return swal({
    			title: (params != undefined &&  params.title != undefined) ? params.title : "Erro2", 
    			text: error_message, 
    			type: "error",
    			width: 600
    		});	*/	
    		
            if (error_message == undefined) {
                error_message = "Ocorreu um erro indeterminado"
            }
            
    		return bootbox.dialog({
                title: (params != undefined &&  params.title != undefined) ? params.title : "Erro",
                message: error_message,
                buttons: {
                    cancel: {
                        label: "Fechar",
                        className: "btn-secondary"
                    }                      
                }
           }); 
        }
	}
};

App.resetPostFormControls = function(form) {
	
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
    	$(form).find("button[type='submit']").attr("disabled","disabled");
    	$(form).find("button[type='submit']").append('<i class="ml-2 fas fa-circle-notch fa-spin"></i>');
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          $(form).find("button[type='submit']").removeAttr("disabled");
      	  $(form).find("button[type='submit'] .fas").remove();
        }
        form.classList.add('was-validated');
      }, false);
      
      form.addEventListener('keyup', function (event) {
          if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
              $(form).find("button[type='submit']").removeAttr("disabled");
          	  $(form).find("button[type='submit'] .fas").remove();
          }
          $(event.target).parent().addClass('was-validated');
      }, false);
      
    });
	
    /*
	$('.select2-minimal').select2({
        minimumResultsForSearch: Infinity
      });
	
	// Select2 by showing the search
   $('.select2-show-search').select2({
     minimumResultsForSearch: ''
   });
   
   $(".select2-option-add").select2({
//	   allowClear: true,
	   tags: true,
	   createTag: function (params) {
		    var term = $.trim(params.term);

		    if (term === '') {
		      return null;
		    }

		    return {
		      id: term,
		      text: term,
		      newTag: true // add additional parameters
		    }
		    
		    
		  },
		  insertTag: function (data, tag) {
		    // Insert the tag at the end of the results
		    data.push(tag);
		  }
   });
	*/
    
   if ($('.datepicker').length > 0) {
		var data_opts = {
			"autoclose" : true,
			"format" : "yyyy-mm-dd",
			"todayBtn" : true,
			"language" : "pt"
		};
			
		$('.datepicker').datepicker(data_opts);
	}
	
};

App.afterSuccessLoadContent = function(response, url, url_params) {
    
	var menu_element = ".horizontal-main .horizontalMenu-list > li.";
	$(menu_element +"active").removeClass("active");

    var title = "";
    if (response.controller != undefined) {
    	$(menu_element + response.controller).addClass('active');
        title = response.controller;
    } else {

    	title = url_params[1];
    	if (response.titulo != undefined) {
    		title = response.titulo;
    	}
    	
    }
    
    // history
    if (title != undefined) {
    	title = title.charAt(0).toUpperCase() + title.slice(1) + " | Pemami";
    } else {
    	title = "Pemami";
    }
    
    
    if (App.updateURL) {
	    var popstateObj = { 'url' : url, 'title' : title };
	    window.history.pushState(popstateObj, title, url);
    }
    
    App.updateURL = true;
    
    // mudar o titulo do browser
    document.title = title;
    
    if (url.indexOf('#') > 0) { // target.. lets check it
        var target = url.substr(url.indexOf('#'), url.length);
        
        if ($(target).length > 0) {

            $('html, body').animate({
                scrollTop: Math.round($(target).offset().top) - 140
            }, 500);
        } else {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        }
    } else {
    	$('html, body').animate({
            scrollTop: 0
        }, 500);
    }
   
    App.afterInit();
    
};

App.changeInput = function(item_nome){
	setTimeout(function(){
		$("#cliente_nome").val(item_nome).trigger('change');
	}, 100);
}

App.autocomplete = function(){

	var input = $('#cliente_nome');
	input.autocomplete({
		source: function (request, response) {
			var jsondata = {
				create: input.data('create'),
				term: request.term
			}; 
			const beforeLoadData = $.Event("beforeLoadData");
			input.trigger(beforeLoadData, [jsondata]);
			$.getJSON(input.data('remote'), {...jsondata, ...beforeLoadData.result} , response);
		},
		select: function (event, ui) {
			var createParams = ui.item.createParams || {};
			if (ui.item.action != undefined || (createParams.action != undefined)) {
				if($(".cliente-novo").length > 0) {
					$(".cliente-novo").removeClass("d-none");
					App.changeInput(input.val());
				}
			} else {
				$('#id_cliente').val(ui.item["id"]).trigger('change');
				$(".cliente-novo").addClass("d-none");
				App.changeInput(ui.item["nome"]);
			}
			// App.changeInput(ui.item["nome"]);
		}
	}).data("ui-autocomplete")._renderItem = function (ul, item) {
		return App.autocompleteOptions(ul, item);
	}
}

App.autocompleteOptions = function (ul, item) {
	var html;

	var item_nome = 'nome';

	if (item.html != undefined) {
		html = item.html;
		var element = $("<li>");
		if (item.action != undefined) {
			element.addClass("action");
		}
		return element.append(html).appendTo(ul);

	} else {
		html = "<a>";
		html += item[item_nome] + "</a>";
		return $("<li>").append(html).appendTo(ul);
	}
};