<div id="patab">
    <div id="graphcontainer">
    <div id="graphdiv"></div>
    <br/>
    <p><i>Proportion du motif par lustre</i></p>
    </div>
    <script type="text/javascript">
         (function($) {
            $(document).ready(function() {
                g = new Dygraph(
                document.getElementById("graphdiv"),
                <?php echo $csv; ?>,
                {ylabel: 'Proportion (%)',
                drawPoints:true,
                      color: '#ac1a2f'
                    }
                );
            });
        })(jQuery);
    </script>
    <div id="div-patab-info">
        <div id="patab-table">
        <?php echo $patab;?><?php if(isset($_POST["group"])){?>
        </div>
        <div id="patab-options">
        <p>Grouper les personnages <i class="fa fa-check"></i></p><?php }?><?php if(isset($_POST["ignore_confident"])){?>
    
        <p>Ignorer les confidents <i class="fa fa-check"></i></p><?php }?>
        </div>
    </div>
    <div id="patab-results">
    <p><i class="fa fa-caret-right"></i> <?php echo $occurrences["n"]; ?> occurence<?php if($occurrences["n"]>1){echo "s";}?> sur <?php echo $occurrences["total"]; ?> (<?php echo $occurrences["percentage"]; ?>%)</p>
    </div>
</div>
