/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('.wrapper').find('a[id="a1"]').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).text(this.expand?"Zwiń":"Personalizuj");
    $(this).closest('.wrapper').find('.small, .big').toggleClass('small big');
    });
    
    $(document).ready(function(){
    $("a#img_help1").click(function(){
        $("img#img_help1").toggle();
    });
    $("a#img_help2").click(function(){
        $("img#img_help2").toggle();
});
});
    
    
    function showMe (box) {

        var chboxs = document.getElementsByName("domyslny");
        var vis = "none";
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){
             vis = "block";
                break;
            }
        }
        document.getElementById(box).style.display = vis;


    }