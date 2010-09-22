<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_themes/templates/bbcode_template.php $
|     $Revision: 11783 $
|     $Id: bbcode_template.php 11783 2010-09-14 00:30:38Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
// How to register your own BBcode button.
// Uncomment the 2 commented lines below to see it in action. (only applies to the user area)

// $register_bb['blank'] = array("", "[blank][/blank]","Blank example helper text",e_IMAGE."generic/bbcode/template.png");

$BBCODE_TEMPLATE = "
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=center}{BB=left}{BB=right}{BB=justify}
	{BB=bq}{BB=code}{BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}{BB=youtube}
";

// $BBCODE_TEMPLATE .= "{BB=blank}";


// $sc_style['BB_HELP']['pre'] = "<div style='text-align:center'>";
// $sc_style['BB_HELP']['post'] = "</div>";

$BBCODE_TEMPLATE_SUBMITNEWS = "
	{BB_HELP}<br />
    {BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=center}{BB=left}{BB=right}{BB=justify}
	{BB=bq}{BB=code}{BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}{BB=flash}{BB=youtube}
";


// --------   Admin Templates ----------------------

$BBCODE_TEMPLATE_ADMIN = "
	{BB_HELP=admin}<br />
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=center}{BB=left}{BB=right}{BB=justify}
	{BB=bq}{BB=code}{BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR=".e_IMAGE."}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=youtube}
";

// $BBCODE_TEMPLATE_ADMIN .= "{BB=blank}";

$BBCODE_TEMPLATE_NEWSPOST = "
	{BB_HELP=$mode}<br />
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=center}{BB=left}{BB=right}{BB=justify}
	{BB=bq}{BB=code}{BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR=".e_IMAGE."newspost_images/}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=youtube}
";

$BBCODE_TEMPLATE_CPAGE = "
	{BB_HELP}<br />
	{BB=newpage}
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=center}{BB=left}{BB=right}{BB=justify}
	{BB=bq}{BB=code}{BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR=".e_IMAGE."custom/}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=youtube}
";



?>