<script type="text/javascript">
	$(function(){
		$("#show-adv-info").click(function(){
			var display = $("#show-info").css("display");	
			if(display == "block"){
				$("#show-info").css("display", "none");
				$(this).attr('value', 'Show Advanced Info');
			}else{
				$("#show-info").css("display", "block");
				$(this).attr('value', 'Hide Advanced Info');
			}
		});
	});
</script>