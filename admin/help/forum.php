<?php
$caption = "Forum Help";
$text = "<b>General</b><br />
Use this screen to create or edit your forums<br />
<br />
<b>Parents/Forums</b><br />
A parent is a heading that other forums are displayed under, this makes layout simpler and makes navigating around your forums much simpler for visitors.
<br /><br />
<b>Accessability</b>
<br />
You can set your forums to only be accessable to certain visitors. Once you have set the 'class' of the visitors you can tick the 
class to only allow those visitors access to the forum. You can set parents or individual forums up in this way.
<br /><br />
<b>Moderators</b>
<br />
Tick the names of the listed administrators to give them moderator status on the forum. The administrator must have forum moderation permissions to be listed here.
";
$ns -> tablerender($caption, $text);
unset($text);
?>