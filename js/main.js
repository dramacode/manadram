(function($) {
    $(document).ready(function() {
        
        $("#options").buttonset();


        $(".tooltip").tipsy({
            html: true,
            gravity: 'w'
        });
        $(".tooltip-e").tipsy({
            html: true,
            gravity: 'e'
        });
         $(".fancybox").fancybox({
            'titlePosition': 'inside',
            'transitionIn': 'none',
            'transitionOut': 'none'
        });
        var thisHash = window.location.hash;
        if(window.location.hash) {
            $(thisHash).fancybox().trigger('click');
        }
        initListeners();
        initListenersScene();

    });
})(jQuery);


function initListeners() {
    $("#add_row").click(function() {
        addRow();
    });
    $("#add_column").click(function() {
        addColumn();
    });

    document.getElementById("delete_row").addEventListener('click', function() {
        delRow();
    });
    document.getElementById("delete_column").addEventListener('click', function() {
        delCol();
    });

    document.getElementById("add_field_configuration").addEventListener('click', function() {
        addFieldConfiguration();
    });
    document.getElementById("delete_field_configuration").addEventListener('click', function() {
        delFieldConfiguration();
    });
}

function initListenersScene() {
    var scene = document.getElementsByClassName("configuration");
    for (var i = 0; i < scene.length; i++) {
        scene[i].addEventListener('click', function(e) {

            toggleScene(this);

        });
    }
}

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}


function toggleScene(e) {
    var v = e.firstChild.value;
    switch (v) {
    case "0":
        e.firstChild.value = "1";
        e.setAttribute("style", "background:grey");
        break;
    case "1":
        e.firstChild.value = "0";
        e.setAttribute("style", "background:white");
        break;
    }

}

function addRow() {

    //clone
    var lastTr = document.getElementsByClassName("character");
    var length = lastTr.length - 1;
    lastTr = lastTr[length];
    var newTr = lastTr.cloneNode(true);
    var rowId = nextChar(lastTr.id);
    //modify
    //tr
    newTr.id = rowId;
    var tds = newTr.getElementsByClassName("configuration");
    for (var i = 0; i < tds.length; i++) {
        var n = i + 1;
        //td
        tds[i].setAttribute("style", "");
        //input
        tds[i].firstElementChild.name = "pattern[" + rowId + "][" + i + "]";
        tds[i].firstElementChild.setAttribute("value", "0");
        tds[i].addEventListener('click', function(e) {
            toggleScene(this);
        });
    }
    //append	
    var patternTable = document.querySelector("#pattern tbody");
    patternTable.appendChild(newTr);
}

function addColumn() {
    var td = document.getElementById("A").getElementsByClassName("configuration");
    var l = td.length;
    if (l < 10) {
        var tr = document.getElementsByClassName("character");
        for (var i = 0; i < tr.length; i++) {
            var rowId = tr[i].id;
            //clone
            var lastTd = tr[i].lastElementChild;
            var newTd = lastTd.cloneNode(true);
            //modify
            //td
            newTd.setAttribute("style", "");
            //input
            newTd.firstElementChild.name = "pattern[" + rowId + "][" + l + "]";
            newTd.firstElementChild.setAttribute("value", "0");
            newTd.addEventListener('click', function(e) {
                toggleScene(this);
            });
            //append
            tr[i].appendChild(newTd);
        }
    }
}

function delRow() {
    var lastTr = document.getElementsByClassName("character");
    var length = lastTr.length - 1;
    lastTr = lastTr[length];
    var patternTable = document.querySelector("#pattern tbody");
    if (length > 0) {
        patternTable.removeChild(lastTr);
    }
}

function delCol() {
    var patternTable = document.querySelector("#pattern tbody");
    var tr = patternTable.getElementsByTagName("tr");
    for (var i = 0; i < tr.length; i++) {
        var l = tr[i].getElementsByTagName("td");
        l = l.length;
        var lastTd = tr[i].lastElementChild;
        if (l > 1) {
            tr[i].removeChild(lastTd);
        }
    }
}





function addFieldConfiguration() {
    var fields = document.getElementsByClassName("xpath-field-configuration");
    var newField = fields[0].cloneNode(true);
    newField.setAttribute("name", "xpath[xpath-" + fields.length+"]");

    document.getElementById("xpath-fields-configuration").appendChild(newField);
}

function delFieldConfiguration() {
    if (document.getElementsByClassName("xpath-field-configuration").length > 1) {
        var fields = document.getElementById("xpath-fields-configuration");

        fields.removeChild(fields.lastChild);

    }
}