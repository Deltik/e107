<?php
$text = "From this page you can add single or multi-page articles.<br />
 For a multi-page article seperate each page with the text [newpage], ie <br /><code>Test1 [newline] Test2</code><br /> would create a two page article with 'Test1' on page 1 and 'Test2' on page 2.
<br /><br />
If your article contains HTML tags that you wish to preserve, enclose the code with [preserve] [/preserve]. For example, if you entered the text '&lt;table>&lt;tr>&lt;td>Hello&lt;/td>&lt;/tr>&lt;/table>' in your article, a table would be shown containing the word hello. If you entered '[preserve]&lt;table>&lt;tr>&lt;td>Hello&lt;/td>&lt;/tr>&lt;/table>[/preserve]' the code as you entered it would be shown and not the table that the code generates.";
$ns -> tablerender("Article Help", $text);
?>