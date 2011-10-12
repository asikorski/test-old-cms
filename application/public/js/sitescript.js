$(document).ready(function(){
    
    //cufon
    ReplaceCufon();
    $("a.grouped_elements").fancybox();
    //scroll categories
    $(window).scroll(function(){			
        var bodyPos = $(window).scrollTop();

        var cat = $('#left_top');
        bottomPos = cat.height() + cat.position().top;
        if(bodyPos > bottomPos){
            $('#leftScrollDiv').css('position','fixed');
            $('#leftScrollDiv').css('top','0');
            $('#leftScrollDiv').css('width','250px');
        }else{
            $('#leftScrollDiv').css('position','relative');
            $('#leftScrollDiv').css('top','0');
        }
    });
    
    //short articles scroll
    $(".scrollable").scrollable({
        vertical: true,
        circular: true
    }).navigator().autoscroll({
        interval: 5000		
    });
    
    //dynamic load page
    scrollalert();
    
    
    //search form
    $('.input_search').click(function (){
        $(this).val(''); 
    });
    
});




//dynamic load page
function scrollalert(){
    var scrolltop=$('#content_left').height();
    var scrollheight=$('#content_left').attr('scrollHeight');
    var windowheight=$(window).scrollTop();
    var scrolloffset=$(window).height();
        
    //alert(200 + scrollheight-(windowheight+scrolloffset));
    if((scrollheight-(windowheight+scrolloffset)) < 0)
    {
        $('#statusAddArticle').show();
        //fetch new items
        $.get('/more.php', '', function(newitems){
            var item = $(newitems).css('display','none');
            $('#statusAddArticle').before(item);
            item.fadeIn(2000);

            
            ReplaceCufon();
            $('#statusAddArticle').hide();
        });
    }
    setTimeout('scrollalert();', 2500);
}

//cufon
function ReplaceCufon(){
    Cufon.replace('#info', {
        hover:true
    }) ;  
    Cufon.replace('#menu', {
        hover:true
    }) ;  
    Cufon.replace('h2', {
        hover:true
    }) ;  
    Cufon.replace('h3', {
        hover:true
    }) ;
    Cufon.replace('.news_item_category', {
        hover:true
    }) ;  
    Cufon.replace('.news_item_more', {
        hover:true
    }) ;
    Cufon.replace('.kontr_item_more', {
        hover:true
    }) ;
}