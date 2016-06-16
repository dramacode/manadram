<div id="patab">
    <div id="graphcontainer">
    <div id="graphdiv"></div>
    <br/>
    <p><i>Proportion du motif par lustre</i></p>
    </div>
    <script type="text/javascript">
         (function($) {
            $(document).ready(function() {
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
            ['Année', 'Proportion', 'Nombre total'],
<?php echo $json; ?>
      ]);
        var formatter = new google.visualization.NumberFormat({ 
  pattern: '#%', 
  fractionDigits: 1
});
formatter.format(data, 1);

    var options = {
        height: 300,
        fontName: 'Verdana',
        fontSize:12,
        chartArea:{
            top:20,
            height:220
        },

      hAxis: {title: ''},
      seriesType: 'bars',
      bar: {groupWidth: "95%"},
      series: {
        0: {
            targetAxisIndex:0,
            color:'#ac1a2f',
            
            visibleInLegend:false
            },
        1: {
            type: 'line',
            targetAxisIndex:1,
            color:'lightgrey',
            visibleInLegend:false
            }
        },
        vAxes: {
          // Adds titles to each axis.
          0: {title: 'Proportion', format:'percent'},
          1: {title: 'Nombre total de motifs dans le corpus', format:'# ###'}
        }

    };

    var chart = new google.visualization.ComboChart(document.getElementById('graphdiv'));
    chart.draw(data, options);
  }

            });
        })(jQuery);
    </script>
    <div id="div-patab-info">
        <div id="patab-table">
        <?php echo $patab;?>
        </div>
        <div id="patab-options"><?php if(isset($_POST["group"])){?>
        <p>Grouper les personnages <i class="fa fa-check"></i></p><?php }?><?php if(isset($_POST["ignore_confident"])){?>
    
        <p>Ignorer les confidents <i class="fa fa-check"></i></p><?php }?>
        </div>
        <div id="patab-results">
        <p><i class="fa fa-caret-right"></i> <?php echo $occurrences["n"]; ?> occurence<?php if($occurrences["n"]>1){echo "s";}?> trouvée<?php if($occurrences["n"]>1){echo "s";}?> sur <?php echo $occurrences["total"]; ?> (<?php echo $occurrences["percentage"]; ?>%) en <?php echo $time; ?> secondes.</p>
        </div>
    </div>

</div>
