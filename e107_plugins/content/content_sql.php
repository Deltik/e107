<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/content/content_sql.php $
|     $Revision: 12092 $
|     $Id: content_sql.php 12092 2011-03-11 18:49:56Z e107steved $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/
header("location:../index.php");
exit;
?>
# Table structure for table `content`
#

CREATE TABLE pcontent (
  content_id int(10) unsigned NOT NULL auto_increment,
  content_heading varchar(255) NOT NULL default '',
  content_subheading varchar(255) NOT NULL default '',
  content_summary text NOT NULL,
  content_text longtext NOT NULL,
  content_author varchar(255) NOT NULL default '',
  content_icon varchar(255) NOT NULL default '',
  content_file text NOT NULL,
  content_image text NOT NULL,
  content_parent varchar(50) NOT NULL default '',
  content_comment tinyint(1) unsigned NOT NULL default '0',
  content_rate tinyint(1) unsigned NOT NULL default '0',
  content_pe tinyint(1) unsigned NOT NULL default '0',
  content_refer text NOT NULL,
  content_datestamp int(10) unsigned NOT NULL default '0',
  content_enddate int(10) unsigned NOT NULL default '0',
  content_class varchar(255) NOT NULL default '',
  content_pref text NOT NULL, 
  content_order varchar(10) NOT NULL default '0',
  content_score tinyint(3) unsigned NOT NULL default '0',
  content_meta text NOT NULL,
  content_layout varchar(255) NOT NULL default '',
  PRIMARY KEY  (content_id)
) ENGINE=MyISAM;
# --------------------------------------------------------

