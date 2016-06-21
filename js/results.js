(function($) {
$(document).ready(function() {

    $( "#tables" ).accordion({
      collapsible: true,
      active: false
    });
    
    var tfConfig = {
    base_path: 'TableFilter/dist/tablefilter/',
     auto_filter: true,
     rows_counter: true,
        watermark: "Filtrer",
        //col_widths: ['25px', '25px', '200px', '200px', '200px', '200px', '200px', '200px' ],

    // Sort extension: in this example the column data types are provided by the
    // 'col_number_format' and 'col_date_type' properties. The sort extension
    // also has a 'types' property defining the columns data type. If the
    // 'types' property is not defined, the sorting extension will check for
    // 'col_number_format' and 'col_date_type' properties.
    extensions: [{ name: 'sort' }]
    };
    var tables = document.getElementsByClassName("tableFilter");
    for (var i = 0; i < tables.length; i++) {
      var tf = new TableFilter(tables[i].id, tfConfig);
        tf.init();
    }
	
        

                  var tables = document.getElementsByClassName("tableExport");
    for (var i = 0; i < tables.length; i++) {
        tables[i].getElementsByClassName("rdiv")[0].innerHTML = '<a class="tooltip-e" title="Télécharger les résultats au format CSV" id="export-'+tables[i].id+'"><i class="fa fa-download"></i></a>';
    }  
        
        
        

        
        
        
        
        
        
        
        });
})(jQuery);