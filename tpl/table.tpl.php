<!DOCTYPE html>
<html>
    <head>
          <meta charset="UTF-8"/>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
          <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
          <script type="text/javascript" src="js/jquery.hoverIntent.js"></script>
          <script type="text/javascript" src="js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
          <script type="text/javascript" src="js/tipsy/src/javascripts/jquery.tipsy.js"></script>
          <script type="text/javascript" src="js/fancybox/source/jquery.fancybox.js"></script>
          <script type="text/javascript" src="TableFilter/dist/tablefilter/tablefilter.js"></script>
          <script src="js/table.js" type="text/javascript"> </script>
          <link rel="stylesheet" type="text/css" href="css/form.css"></link>
          <link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css" type="text/css" ></link>
          <link rel="stylesheet" href="js/tipsy/src/stylesheets/tipsy.css" type="text/css" ></link>
          <link rel="stylesheet" href="css/font-awesome/css/font-awesome.css" type="text/css" ></link>
          <link rel="stylesheet" type="text/css" href="css/style.css"></link>
          <link rel="stylesheet" type="text/css" href="css/table.css"></link>
          <link rel="icon" type="image/x-icon" href="img/favicon.ico"></link>            
          <title><?php echo $html["author"].", ".$html["title"]." (".$html["created"].")";?></title>
    </head>
    <body>
      <h3><?php echo $html["author"].", <i>".$html["title"]."</i> (".$html["created"].")";?></h3>
      <?php echo $html["html"];?>
      <table class="legend result pattern">
          <tr>
              <td class="absent configuration">0</td>
              <td class="caption">Personnage absent</td>
              <td class="speaking configuration">1</td>
              <td class="caption">Personnage présent </td>
              <td class="mute configuration">2</td>
              <td class="caption">Personnage muet </td>
          </tr>
      </table>
      <br/>
      <table class="legend result pattern">
          <tr>
              <td class="configuration"></td>
              <td class="configuration"></td>
              <td class="caption">Scènes liées entre elles</td>
              <td class="configuration"></td>
              <td class="configuration break"></td>
              <td class="caption">Rupture de la liaison des scènes</td>
          </tr>
      </table>
    </body> 
</html>