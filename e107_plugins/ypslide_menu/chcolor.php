<?php
/*
Color picker popup by Lolo Irie, www.touchatou.org based on an original javascript from Bobinours
*/

require_once("../../class2.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }
require_once(e_PLUGIN."ypslide_menu/languages/admin/".e_LANGUAGE.".php");

$caption = colpick_LAN1;
echo "
<html>
<!-- Date de création: 10/11/2002 -->
<head>
<title>Color</title>
<meta name=\"description\" content=\"\">
<meta name=\"keywords\" content=\"\">
<meta name=\"author\" content=\"Laurent Dorier\">
<meta name=\"generator\" content=\"WebExpert 5\">
<link rel=\"stylesheet\" href=\"".THEME."style.css\" type=\"text/css\" />
<link rel=\"stylesheet\" href=\"".e_FILE."e107.css\" type=\"text/css\" />";
if(file_exists(e_FILE."style.css")){ echo "\n<link rel='stylesheet' href='".e_FILE."style.css' type=\"text/css\" />\n"; }


echo "</head>
<body style=\"text-align: center;\" >
";

$text = "<table cellpadding=\"0\" cellspacing=\"1\" style=\"border: dotted 1px #000;\" > 
<tr>
<script>
var maxParLigne = 18; 
var nbParLigne = -1; 
var couleursHexa = new Array('00', '33', '66', '99', 'CC', 'FF'); 

function newscolor(a,b,c){
	opener.document.getElementById(opener.document.getElementById('nbrcolor').value).value ='#' + a+b+c;
	return;
}

for (i=0; i < couleursHexa.length; i++) { 
      for (j=0; j < couleursHexa.length; j++) { 
            for (k=0; k < couleursHexa.length; k++) { 

                  nbParLigne++; 

                  if (nbParLigne % maxParLigne == 0) { 
                        document.write('</tr>'); 
                        document.write('<tr>'); 
                        nbParLigne = 0; 
                  } 
                  document.write('<td style=\"background-color: #' + couleursHexa[i] + couleursHexa[j] + couleursHexa[k] + '\" width=\"8\" height=\"6\" style=\"cursor:pointer;\" alt=\"#' + couleursHexa[i] + couleursHexa[j] + couleursHexa[k] + '\" title=\"#' + couleursHexa[i] + couleursHexa[j] + couleursHexa[k] + '\" onclick=\"newscolor(\''+couleursHexa[i]+'\',\''+couleursHexa[j]+'\',\''+couleursHexa[k]+'\'); window.close();\"><img alt=\"#' + couleursHexa[i] + couleursHexa[j] + couleursHexa[k] + '\" title=\"#' + couleursHexa[i] + couleursHexa[j] + couleursHexa[k] + '\" src=\"../themes/shared/generic/blank.gif\" border=\"0\" width=\"12\" height=\"8\" alt=\"\"></td>');
            } 
      } 
}
</script>

</tr> 
</table>";
$text .= colpick_LAN2;


$ns->tablerender($caption, $text);
echo "</body>
</html>";

?>