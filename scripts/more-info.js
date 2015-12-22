$(document).ready(function(){
    $(".bio-list li ul").hide();
    $(".read-more").click(function(){
    	$(this).hide();
    	$(this).next().slideToggle(200);
    });
})