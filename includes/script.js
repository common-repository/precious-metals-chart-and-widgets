function changeOptionDrop(opt ,key){
  if(opt == 'no'){  
	jQuery('#to-fixed-width-'+key).show();
   }else{
	 jQuery('#to-fixed-width-'+key).hide(); 
   }		  
}

function changeOption(opt){
	if(opt == 'no'){
	  document.getElementById("option-fixed-width").style.display = "block";
	}else{
	 document.getElementById("option-fixed-width").style.display = "none";
	}
}
  
jQuery(document).on('widget-added', function(){
   jQuery('.widget-control-save').click();
}); 