(function($) {
$(document).ready(function() {
    $(".fancybox").fancybox({
            'titlePosition': 'inside',
            'transitionIn': 'none',
            'transitionOut': 'none'
        });
    $( "#tables" ).accordion({
      collapsible: true,
      active: false
    });
    
    var tfConfigCorpus = {
    base_path: 'TableFilter/dist/tablefilter/',
     auto_filter: true,
     loader:true,
     rows_counter: true,
        watermark: "Filtrer",
        col_types: ["String","String","String","String","String","String","Number"] ,
    extensions: [{ name: 'sort' }]
    };
    var tfConfigTable = {
    base_path: 'TableFilter/dist/tablefilter/',
     auto_filter: true,
     loader:true,
     rows_counter: true,
        watermark: "Filtrer",
        col_types: ["String","Number","Number"] ,
    extensions: [{ name: 'sort' }]
    };
    var tfConfigOccurrences = {
    base_path: 'TableFilter/dist/tablefilter/',
     auto_filter: true,
     loader:true,
     rows_counter: true,
        watermark: "Filtrer",
        col_types: ["String","String","String","String","String","String","Number","String", "Number", "Number", "Number"] ,
    extensions: [{ name: 'sort' }]
    };    
    var tfConfigCode = {
    base_path: 'TableFilter/dist/tablefilter/',
     auto_filter: true,
     loader:true,
     rows_counter: true,
        watermark: "Filtrer",
        col_types: ["String","String","Number", "Number"] ,
    extensions: [{ name: 'sort' }]
    };
    
    var tfConfigXPath = {
    base_path: 'TableFilter/dist/tablefilter/',
     auto_filter: true,
     loader:true,
     rows_counter: true,
        watermark: "Filtrer",
        col_types: ["Number", "Number"] ,
    extensions: [{ name: 'sort' }]
    };    
    var tables = document.getElementsByClassName("tableFilterCorpus");
    for (var i = 0; i < tables.length; i++) {
      var tf = new TableFilter(tables[i].id, tfConfigCorpus);
        tf.init();
    }    var tables = document.getElementsByClassName("tableFilterTable");
    for (var i = 0; i < tables.length; i++) {
      var tf = new TableFilter(tables[i].id, tfConfigTable);
        tf.init();
    }
    var tables = document.getElementsByClassName("tableFilterOccurrences");
    for (var i = 0; i < tables.length; i++) {
      var tf = new TableFilter(tables[i].id, tfConfigOccurrences);
        tf.init();
    }    var tables = document.getElementsByClassName("tableFilterCode");
    for (var i = 0; i < tables.length; i++) {
      var tf = new TableFilter(tables[i].id, tfConfigCode);
        tf.init();
    }
    var tables = document.getElementsByClassName("tableFilterXPath");
    for (var i = 0; i < tables.length; i++) {
      var tf = new TableFilter(tables[i].id, tfConfigXPath);
        tf.init();
    }	
        

    var tables = document.getElementsByClassName("tableExport");
    for (var i = 0; i < tables.length; i++) {
        tables[i].getElementsByClassName("rdiv")[0].innerHTML = '<a class="tooltip-e" title="Télécharger les résultats au format CSV" id="export-'+tables[i].id+'"><i class="fa fa-download"></i></a>';
    }  
        
        
        

        
        
        
        
        
        
        
        });
})(jQuery);

