<div id="patab">
    <div id="graphcontainer">
    <div id="graphdiv"></div>
    <br/>
    <p><i>Proportion du motif par lustre</i></p>
    </div>
    <script type="text/javascript">
         (function($) {
            $(document).ready(function() {
      google.charts.load('current', {'packages':['line', 'corechart']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var chartDiv = document.getElementById('graphdiv');

      var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', "Proportion");
      data.addColumn('number', "Nombre total de motifs dans le corpus");

      data.addRows([
        <?php echo $json; ?>
       
      ]);

      var materialOptions = {
        chart: {
          //title: 'Average Temperatures and Daylight in Iceland Throughout the Year'
        },
        series: {
          // Gives each series an axis name that matches the Y-axis below.
          0: {axis: 'Proportion'},
          1: {axis: 'Nombre'}
        },
        axes: {
          // Adds labels to each axis; they don't have to match the axis names.
          y: {
            Proportion: {label: 'Proportion (%)'},
            Nombre: {label: 'Nombre total'}
          }
        }
      };

      function drawMaterialChart() {
        var materialChart = new google.charts.Line(chartDiv);
        materialChart.draw(data, materialOptions);
      }


      drawMaterialChart();

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
