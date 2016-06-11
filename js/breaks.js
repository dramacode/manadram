(function($) {
    $(document).ready(function() {
          $(".tooltip").tipsy({
		html: true,
                gravity: 'w'
	});
        var scene = location.search;
        if (scene.indexOf("&scene=") > 0) {
            scene = scene.substring(scene.indexOf("&scene=") + 7);
            document.getElementById(scene).style = "background-color:yellow;"
        }

        var breaks = document.getElementById("confList").getElementsByClassName("break");
 
        for (i = 0; i < breaks.length; i++) {
            var td = document.getElementsByClassName(breaks[i].id);
            for (j = 0; j < td.length; j++) {
                td[j].className += " break";
            }
        }
    });
})(jQuery);