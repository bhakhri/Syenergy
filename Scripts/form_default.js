	
	// This is a function used to update values in database for Form_default values 
	
	var regex = /^(.*)(\d)+$/i;
		var newIndex = $(".tab").length;
		var final_arr = {};	
		var path_p;
		var l = window.location;
		var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1]+"/";
		
		// This function is on Hyper link click(Set default values for Report Parameters
		
		$(document).on("click", "a#lk1", function(){
			var c_arr = {};								// To store check boxes ID's in array for DB storage.
			$(this).parents("table")					
					.find("*").each(function() {
					
						var id = this.id+newIndex || "";
						var node_name = $( this ).get( 0 ).nodeName;
						var node_type = $('#'+this.id).attr('type');					
						switch(node_name){
							case 'INPUT':
								switch(node_type){
									case 'checkbox':
									case 'radio':
										var id = this.id.substr(0,this.id.length);
										var w_chkd;
										$('#'+this.id).is(":checked") ? w_chkd=true : w_chkd=false;
										c_arr[id] = w_chkd;
										break;
									case 'text':
										var item = this.id;
										var item_set = item.substr(0,item.length);
										var val_txt = $('#'+item).val();
										
										final_arr[item_set] = val_txt;
										
										break;
								}
								break;
						}
			});		
			
			// This array is created for input types in the form and update them in database if exists otherwise insert
			
			final_arr["checked"] = c_arr;
			final_arr = escape(JSON.stringify(final_arr,true));
			// alert(final_arr);
			$.ajax({
				type: "POST",
				url: base_url+"Library/save_formdefault.php",
				data : "data="+final_arr+"&type=update&path="+path_p
				
				}).done(function( msg )
					{		
						
						msg=$.trim(msg);
						if(msg!=false){
							var check=JSON.parse(msg,true);							
							parser(check);
							//$('#body').empty();
							//hiddenFloatingDiv('testTypeDiv');
							newIndex=0;
							final_arr = {};
							c_arr = {};		
							alert("Data Saved Successfully");
						}else{
							alert("Some error occured. Please retry");												
						}
					});
		});
		
		
		$( document ).click(function(event) {
			var target = $(event.target);
			if(target.is( "img" )){
				$('#body').empty();newIndex=0;
			}			  
		});
		
	function parser(obj){		
		$.each( obj, function( i, val ) {
			if(typeof(val) == 'object'){
				parser(val);					// recursive call of Parser function
			}
			if($.type(val)==='boolean'){
				$('#'+i).prop('checked', val);
			}else{
				$('#'+i).prop('value', val);
			}
		});
	}	
	
	/*
		This is called on the page load and Fetch's record from the database and populates the form accordingly
	*/
	$(function() {
		path_p = window.location.pathname;
		console.log(window.location.pathname);
	  select_form_default();
	});
	function select_form_default(){
		
		$.ajax({
				type: "POST",
				url: base_url+"Library/save_formdefault.php",
				data : "type=select&path="+path_p
				}).done(function( msg )
					{	
						
						msg=$.trim(msg);
						if(msg!="false"){
							var check=JSON.parse(msg,true);							
							parser(check);
						//	$('#body').empty();
							//hiddenFloatingDiv('testTypeDiv');
							newIndex=0;
							final_arr = {};
							c_arr = {};
						}else{
							
						}
					});
	}