
(function($) {
        $(document).ready(function() {

        $( "#options" ).buttonset();


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


initListeners();
initListenersScene();
                
        });
})(jQuery);


function initListeners() {
    document.getElementById("add_row").addEventListener('click', function() {
        addRow();
    });
    document.getElementById("add_column").addEventListener('click', function() {
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
    });    }

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

function include(arr, obj) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] == obj) return true;
    }
}

function toggleScene(e) {
    var v = e.firstChild.value;
    switch (v) {
    case "A":
        e.firstChild.value = "P";
        e.setAttribute("style", "background:grey");
        break;
    case "P":
        e.firstChild.value = "A";/*M*/
        e.setAttribute("style", "background:white");/*white*/
        break;
    case "M":
        e.firstChild.value = "PM";
        e.setAttribute("style", "background-image: repeating-linear-gradient(-45deg, grey, grey 21px, black 21px, black 43px)");
        break;
    case "PM":
        e.firstChild.value = "APM";
        e.setAttribute("style", "background-image: repeating-linear-gradient(-45deg, white, white 10px, grey 10px, grey 30px, black 30px, black 45px)");
        break;
    case "APM":
        e.firstChild.value = "A";
        e.setAttribute("style", "background:none");
        break;
    }
    //change value
    //change background-color
}

function addRow() {
    
    var lastTr = document.getElementsByClassName("character");
    var length = lastTr.length - 1;
    lastTr = lastTr[length];
    var patternTable = document.querySelector("#pattern tbody");
    var newTr = lastTr.cloneNode(true);
    //set rowId	
    var rowId = nextChar(lastTr.id);
    // 	tr
    newTr.id = rowId;
    // first td (header)
    newTr.firstElementChild.textContent = rowId;
    //second td (optional)
    var opt = newTr.getElementsByTagName("td")[1];
    opt.firstElementChild.name = "optional" + rowId;
    //third td (indifferent bound together)
    var opt = newTr.getElementsByTagName("td")[2];
    opt.firstElementChild.name = "indifferent_same_value_" + rowId;
    // other tds	
    var tds = newTr.getElementsByClassName("configuration");
    for (var i = 0; i < tds.length; i++) {
        var n = i + 1;
        tds[i].className = "configuration r" + rowId + " c" + (i + 1);
        tds[i].firstElementChild.id = rowId + (i + 1);
        tds[i].firstElementChild.name = "pattern["+rowId+"][" + i-2 +"]";
        tds[i].setAttribute("style", "");
        tds[i].firstElementChild.setAttribute("value", "A");
        // 		tds[i].lastElementChild.setAttribute("for", rowId + (i + 1));
    }
    //append	
    patternTable.appendChild(newTr);
    initListenersScene();
}

function addColumn() {
            if (document.getElementById("A").getElementsByClassName("configuration").length<10) {
//get last column id
    var th = document.getElementById("link")
    var tds = th.getElementsByTagName("td");
    var l = tds.length;
    // td link
    var lastTd = th.lastElementChild;
    var newTd = lastTd.cloneNode(true);
    newTd.firstElementChild.firstElementChild.name = "link" + (l - 1) + "-" + l;
    th.appendChild(newTd);
    //td optional
    var tr = document.getElementById("optional");
    var lastTd = tr.lastElementChild;
    var newTd = lastTd.cloneNode(true);
    newTd.firstChild.name = "optional" + (l - 1);
    tr.appendChild(newTd);
    //other tds
    var tr = document.getElementsByClassName("character");
    for (var i = 0; i < tr.length; i++) {
        var rowId = tr[i].id;
        var lastTd = tr[i].lastElementChild;
        var newTd = lastTd.cloneNode(true);
        newTd.className = "configuration r" + rowId + " c" + (l - 1);
        newTd.firstElementChild.id = rowId + (l - 1);
        newTd.firstElementChild.name = "pattern["+rowId+"]["+ (l-3) +"]";
        newTd.setAttribute("style", "");
        newTd.firstElementChild.setAttribute("value", "A");
        // 		newTd.lastElementChild.setAttribute("for", rowId + (l-1));
        tr[i].appendChild(newTd);
    }
    initListenersScene();
    }
}

function delRow() {
    var lastTr = document.getElementsByClassName("character");
    var length = lastTr.length - 1;
    lastTr = lastTr[length];
    var rowId = lastTr.id;
    var patternTable = document.querySelector("#pattern tbody");
    if (length > 0) {
        patternTable.removeChild(lastTr);
        //deleteFields(rowId);
    }
}

function delCol() {
    var patternTable = document.querySelector("#pattern tbody");
    var tr = patternTable.getElementsByTagName("tr");
    for (var i = 0; i < tr.length; i++) {
        var l = tr[i].getElementsByTagName("td");
        l = l.length;
        var lastTd = tr[i].lastElementChild;
        if (l > 2) {
            tr[i].removeChild(lastTd);
        }
    }
}





function addFieldConfiguration() {
    var fields = document.getElementsByClassName("xpath-field-configuration");
    var newField = fields[0].cloneNode(true);
        newField.setAttribute("name","xpath-"+fields.length);

    document.getElementById("xpath-fields-configuration").appendChild(newField);
}
function delFieldConfiguration() {
    if(document.getElementsByClassName("xpath-field-configuration").length > 1){
    var fields = document.getElementById("xpath-fields-configuration");
    
    fields.removeChild(fields.lastChild);
    
    }
}