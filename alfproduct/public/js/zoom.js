/**
 * Simple Rollover[single]
 * @author Tenderfeel(tenderfeel@gmail.com)
 * @ver 1.0
 * @HOME http://tenderfeel.xsrv.jp/javascript/271/
 * @license The MIT License
 */
window.onload =function(){
    var myImg = document.getElementById("thumb").getElementsByTagName("img");
    var regrep = "_thumb";
    var newimg = new Array();
    for (var i = 0; i <myImg.length; i++) {
        newimg[i] = new Image();
        newimg[i].src = myImg[i].src;
        myImg[i].onmouseover =function() {
            var href = this.src.replace(regrep,"");
                document.getElementById('rollover_view').src=href;
        }
    }
}

