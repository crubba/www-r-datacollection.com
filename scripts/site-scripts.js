$(document).ready(function(){
	$(".comment-box").hide();
	$(".see-comment").click(function(){
    	$(this).hide();
    	$(this).next().slideToggle(200);
    });
})