 (function($) {
        $(document).ready(function() {

          $(".tooltip").tipsy({
		html: true,
                gravity: 'w'
	});
                      $(".tooltip-e").tipsy({
		html: true,
                gravity: 'e'
	});
        
          
        
    function exportTableToCSV($table, filename) {
        var $rows = $table.find('tr:not(.fltrow):has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character

            // actual delimiter characters for CSV format
            colDelim = '	',
            rowDelim = '\n',

            // Grab text from table into CSV formatted string
            csv = $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td:not(.view-table, .view-text, .view-source)');

                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();

                    return text.replace(/"/g, '""'); // escape double quotes

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim),

            // Data URI
            csvData = 'data:application/octet-stream;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
            .attr({
            'download': filename,
                'href': csvData,
                'target': '_blank'
        });
    }

    // This must be a hyperlink

        
        

        
        
         var tables = document.getElementsByClassName("tableExport");
    for (var i = 0; i < tables.length; i++) {
    $("#export-"+tables[i].id).on('click', function (event) {
        // CSV
        //alert('#'+this.getAttribute("id").replace("export-",""));
        exportTableToCSV.apply(this, [$('#'+this.getAttribute("id").replace("export-","")), 'export.csv']);
        
        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });
    }   
        
        

        
        
        
        });
})(jQuery);