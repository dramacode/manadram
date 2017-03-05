<div class="patab">
    <div class="graphcontainer">
        <div class="graphdiv" id="graphdiv<?php echo $needletr;?>"></div>
        <p><i>Proportion du motif par lustre</i></p>
    </div>
    <script type="text/javascript">
         (function($) {
            $(document).ready(function() {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawVisualization);
                function drawVisualization() {
                    var data = google.visualization.arrayToDataTable([
                        ['Année', 'Proportion', 'Nombre total'],
                        <?php echo $json; ?>
                        ]);
                    var formatter = new google.visualization.NumberFormat({ 
                        pattern: '#.#%', 
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
                            0: {title: 'Proportion', format:'#.#%'},
                            1: {title: 'Nombre total de motifs dans le corpus', format:'# ###'}
                            }

                        };                    
                    var chart = new google.visualization.ComboChart(document.getElementById('graphdiv<?php echo $needletr;?>'));
                    chart.draw(data, options);
                    }
                });
            })(jQuery);
    </script>
</div>
