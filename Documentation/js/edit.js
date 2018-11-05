
$('.container').attr('contentEditable','true');

$(document).on('keypress',function(e){
	if ( e.ctrlKey && (e.which == 86 || e.which==118) ) {
	  setTimeout(function(){
	  $('pre code:not(.hljs)').each(function(i, block) {
		  hljs.highlightBlock(block);
		});
	},100);
   }
});

/*Save on Ctrl + Enter*/

$(document).on('keypress',function(e){
	if ( e.ctrlKey && e.which == 13) {
	
		/*Code for save*/
		
		$('[contentEditable]').removeAttr('contentEditable');
		//$('.affix, .affix-top, .affix-bottom').removeClass('affix affix-top affix-bottom');
		//$('#sidenav li').removeClass('active');
	
	
	}
});