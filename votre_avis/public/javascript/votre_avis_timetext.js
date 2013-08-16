	$(document).ready(function(){

	  var limitnum = 100; // set your int limit for max number of characters
	  
	  function limiting(obj, limit) {
	    var cnt = $("#numfield > span");
	    var txt = $(obj).val(); 
	    var len = txt.length;
	    
	    // check if the current length is over the limit
	    if(len > limit){
	       $(obj).val(txt.substr(0,limit));
	       $(cnt).html(len-1);
	     } 
	     else { 
	       $(cnt).html(len);
	     }
	     
	     // check if user has less than 20 chars left
	     if(limit-len <= 20) {
	     	$(cnt).addClass("erreur_message");
	     }
	  }


	  $('textarea').keyup(function(){
	    limiting($(this), limitnum);
	  });
	});