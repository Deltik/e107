<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvsroot/e107/e107_0.7/e107_plugins/rss_menu/rss.php,v $
|     $Revision: 1.47 $
|     $Date: 2006/04/05 12:19:18 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

/*
Query string: content_type.rss_type.[topic id]
1: news
5: comments
12: downloads (option: specify category)

The following should be using $eplug_rss in their plugin.php file (see chatbox)
----------------------------------------------------------------
2: articles
3: reviews
4: content pages
6: forum threads
7: forum posts
8: forum specific post (specify id)
10: bugtracker
11: forum
*/

require_once("../../class2.php");

if (!is_object($tp->e_bb)) {
	require_once(e_HANDLER.'bbcode_handler.php');
	$tp->e_bb = new e_bbcode;
}

if (is_readable(e_PLUGIN."rss_menu/languages/".e_LANGUAGE.".php")) {
	include_once(e_PLUGIN."rss_menu/languages/".e_LANGUAGE.".php");
} else {
	include_once(e_PLUGIN."rss_menu/languages/English.php");
}

$namearray[1] = RSS_NEWS;
$namearray[2] = RSS_ART;
$namearray[3] = RSS_REV;
$namearray[5] = RSS_COM;
$namearray[6] = RSS_FT;
$namearray[7] = RSS_FP;
$namearray[8] = RSS_FSP;
$namearray[10] = RSS_BUG;
$namearray[11] = RSS_FOR;
$namearray[12] = RSS_DL;

list($content_type, $rss_type, $topic_id) = explode(".", e_QUERY);
if (intval($rss_type) == false) {
	echo "No type specified";
	exit;
}

if($rss = new rssCreate($content_type, $rss_type, $topic_id))
{
	$rss_title = (is_numeric($content_type) && $namearray[$content_type]) ? $namearray[$content_type] : ucfirst($content_type);
	$rss->buildRss ($rss_title);
}

class rssCreate {

	var $contentType;
	var $rssType;
	var $path;
	var $rssItems;
	var $rssQuery;
	var $topicid;
	var $offset;
	var $rssNamespace;
	var $rssCustomChannel;

	function rssCreate($content_type, $rss_type, $topic_id) {
		// constructor
		$sql_rs = new db;
		global $tp, $sql, $e107, $PLUGINS_DIRECTORY, $pref;
		$this -> path = e_PLUGIN."rss_menu/";
		$this -> rssType = $rss_type;
		$this -> topicid = $topic_id;
		$this -> offset = $pref['time_offset'] * 3600;

		switch ($content_type) {
			case 1:
				$topic = (is_numeric($topic_id))? " AND news_category = ".intval($topic_id) : "";
				$this -> contentType = "news";
				$this -> rssQuery = "
				SELECT n.*, u.user_id, u.user_name, u.user_email, u.user_customtitle, nc.category_name, nc.category_icon FROM #news AS n
				LEFT JOIN #user AS u ON n.news_author = u.user_id
				LEFT JOIN #news_category AS nc ON n.news_category = nc.category_id
				WHERE n.news_class IN (".USERCLASS_LIST.") AND n.news_start < ".time()." AND (n.news_end=0 || n.news_end>".time().") AND n.news_render_type!=2 $topic ORDER BY news_datestamp DESC LIMIT 0,9";
				$sql->db_Select_gen($this -> rssQuery);

				$tmp = $sql->db_getList();

				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {

					$this -> rssItems[$loop]['title'] = $value['news_title'];
					$this -> rssItems[$loop]['link'] = "http://".$_SERVER['HTTP_HOST'].e_HTTP."news.php?item.".$value['news_id'].".".$value['news_category'];
                    if($value['news_summary']){
                        	$this -> rssItems[$loop]['description'] = $value['news_summary'];
					}else{
						$this -> rssItems[$loop]['description'] = $value['news_body'];
                    }
					$this -> rssItems[$loop]['author'] = $value['user_name'];
                    $this -> rssItems[$loop]['author_email'] = $value['user_email'];
					$this -> rssItems[$loop]['category'] = "<category domain='".SITEURL."news.php?cat.".$value['news_category']."'>".$value['category_name']."</category>";

					if($value['news_allow_comments']){
						$this -> rssItems[$loop]['comment'] = "http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?comment.news.".$news_id;
                    }
					$this -> rssItems[$loop]['pubdate'] = $value['news_datestamp'];

					$loop++;
				}

				break;
			case 2:
				$this -> contentType = "articles";
				break;
			case 3:
				$this -> contentType = "reviews";
				break;
			case 4:
				$this -> contentType = "content";
				break;
			case 5:
				$this -> contentType = "comments";
				$this -> rssQuery = "SELECT * FROM #comments ORDER BY comment_datestamp DESC LIMIT 0,9";
				$sql->db_Select_gen($this -> rssQuery);

				$tmp = $sql->db_getList();

				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {
					$this -> rssItems[$loop]['title'] = $value['comment_subject'];

					switch ($value['comment_type']) {
						case 0:
							$this -> rssItems[$loop]['link'] = "http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?comment.news.".$value['comment_item_id'];
							break;
						case 4:
							$this -> rssItems[$loop]['link'] = "http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?comment.poll.".$value['comment_item_id'];
							break;
					}

					$this -> rssItems[$loop]['description'] = $value['comment_comment'];
					$this -> rssItems[$loop]['author'] = substr($value['comment_author'], (strpos($value['comment_author'], ".")+1));
					$loop++;
				}

				break;
			case 6:
				$this -> contentType = "forum threads";
				$this -> rssQuery =
				"SELECT t.thread_thread, t.thread_id, t.thread_name, t.thread_datestamp, t.thread_parent, t.thread_user, t.thread_views, t.thread_lastpost, t.thread_lastuser, t.thread_total_replies, u.user_name, u.user_email FROM #forum_t AS t
				LEFT JOIN #user AS u ON FLOOR(t.thread_user) = u.user_id
				LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
				WHERE f.forum_class IN (0, 251, 252) AND t.thread_parent=0
				ORDER BY t.thread_datestamp DESC LIMIT 0, 9
				";
				$sql->db_Select_gen($this -> rssQuery);
				$tmp = $sql->db_getList();

				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {

					if($value['user_name']) {
						$this -> rssItems[$loop]['author'] = $value['user_name'];
                       	$this -> rssItems[$loop]['author_email'] = $value['user_email'];  // must include an email address to be valid.
				} else {
						$tmp=explode(".", $value['thread_user'], 2);
						list($this -> rssItems[$loop]['author'], $ip) = explode(chr(1), $tmp[1]);
					}

					$this -> rssItems[$loop]['title'] = $value['thread_name'];
					$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$value['thread_id'];

					$this -> rssItems[$loop]['description'] = $value['thread_thread'];

					$loop++;
				}
				break;

			case 7:
				$this -> contentType = "forum posts";
				$this -> rssQuery = "SELECT tp.thread_name AS parent_name, t.thread_thread, t.thread_id, t.thread_name, t.thread_datestamp, t.thread_parent, t.thread_user, t.thread_views, t.thread_lastpost, t.thread_lastuser, t.thread_total_replies, f.forum_id, f.forum_name, f.forum_class, u.user_name, u.user_email FROM #forum_t AS t
				LEFT JOIN #user AS u ON FLOOR(t.thread_user) = u.user_id
				LEFT JOIN #forum_t AS tp ON t.thread_parent = tp.thread_id
				LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
				WHERE f.forum_class  IN (0, 251, 252)
				ORDER BY t.thread_datestamp DESC LIMIT 0, 9";
				$sql->db_Select_gen($this -> rssQuery);
				$tmp = $sql->db_getList();
				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {

					if($value['user_name']) {
						$this -> rssItems[$loop]['author'] = $value['user_name'];
						$this -> rssItems[$loop]['author_email'] = $value['user_email'];  // must include an email address to be valid.
					} else {
						$tmp=explode(".", $value['thread_user'], 2);
						list($this -> rssItems[$loop]['author'], $ip) = explode(chr(1), $tmp[1]);
					}

					if($value['parent_name']) {
						$this -> rssItems[$loop]['title'] = "Re: ".$value['parent_name'];
						$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$value['thread_parent'];
					} else {
						$this -> rssItems[$loop]['title'] = $value['thread_name'];
						$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$value['thread_id'];
					}

					$this -> rssItems[$loop]['description'] = $value['thread_thread'];

					$loop++;
				}
				break;

			case 8:
				if(!$this -> topicid) {
					return FALSE;
				}
				$this -> contentType = "forum topic / replies";

				/* get thread ...  */
				$this -> rssQuery = "SELECT t.thread_name, t.thread_thread, t.thread_id, t.thread_name, t.thread_datestamp, t.thread_parent, t.thread_user, t.thread_views, t.thread_lastpost, f.forum_id, f.forum_name, f.forum_class, u.user_name
				FROM #forum_t AS t
				LEFT JOIN #user AS u ON FLOOR(t.thread_user) = u.user_id
				LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
				WHERE f.forum_class  IN (0, 251, 255) AND t.thread_id=".intval($this -> topicid);
				$sql->db_Select_gen($this -> rssQuery);
				$topic = $sql->db_Fetch();

				/* get replies ...  */
				$this -> rssQuery = "SELECT t.thread_name, t.thread_thread, t.thread_id, t.thread_name, t.thread_datestamp, t.thread_parent, t.thread_user, t.thread_views, t.thread_lastpost, f.forum_id, f.forum_name, f.forum_class, u.user_name, u.user_email
				FROM #forum_t AS t
				LEFT JOIN #user AS u ON FLOOR(t.thread_user) = u.user_id
				LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
				WHERE f.forum_class  IN (0, 251, 255) AND t.thread_parent=".intval($this -> topicid);
				$sql->db_Select_gen($this -> rssQuery);
				$replies = $sql->db_getList();

				$this -> rssItems = array();
				$loop = 0;

				if($value['user_name']) {
					$this -> rssItems[$loop]['author'] = $value['user_name'] . " ( ".$e107->base_path."user.php?id.".intval($value['thread_user'])." )";
				} else {
					$tmp=explode(".", $value['thread_user'], 2);
					list($this -> rssItems[$loop]['author'], $ip) = explode(chr(1), $tmp[1]);
				}

				$this -> rssItems[$loop]['title'] = $topic['thread_name'];
				$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$topic['thread_id'];
				$this -> rssItems[$loop]['description'] = $topic['thread_thread'];
				$loop ++;
				foreach($replies as $value) {
					if($value['user_name']) {
						$this -> rssItems[$loop]['author'] = $value['user_name'];
						$this -> rssItems[$loop]['author_email'] = $value['user_email'];  // must include an email address to be valid.
					} else {
						$tmp=explode(".", $value['thread_user'], 2);
						list($this -> rssItems[$loop]['author'], $ip) = explode(chr(1), $tmp[1]);
					}
					$this -> rssItems[$loop]['title'] = "Re: ".$topic['thread_name'];
					$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$this -> topicid;
					$this -> rssItems[$loop]['description'] = $value['thread_thread'];
					$loop++;
				}
			break;


			case 10:
				$this -> contentType = "bugtracker reports";
				$sql->db_Select("bugtrack2_bugs", "*", "bugtrack2_bugs_status=0 ORDER BY bugtrack2_bugs_datestamp");
				$tmp = $sql->db_getList();
				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {
					$nick = preg_replace("/[0-9]+\./", "", $value['bugtrack2_bugs_poster']);
					$this -> rssItems[$loop]['author'] = $nick;
					$this -> rssItems[$loop]['title'] = $value['bugtrack2_bugs_summary'];
					$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."bugtracker2/bugtracker2.php?0.bug.".$value['bugtrack2_bugs_id'];
					$this -> rssItems[$loop]['description'] = $value['bugtrack2_bugs_description'];
					$loop++;
				}
			break;

			case 11:
				$this -> rssQuery = "
				SELECT f.forum_id, f.forum_name, f.forum_class, tp.thread_name AS parent_name, t.*, u.user_name, u.user_email from #forum_t as t
				LEFT JOIN #user AS u ON FLOOR(t.thread_user) = u.user_id
				LEFT JOIN #forum_t AS tp ON t.thread_parent = tp.thread_id
				LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
				WHERE t.thread_forum_id = ".intval($this->topicid)."
				AND f.forum_class IN (0, 251, 255)
				ORDER BY
				t.thread_datestamp DESC
				LIMIT 0, 9
				";
				$sql->db_Select_gen($this -> rssQuery);
				$tmp = $sql->db_getList();
				$this -> contentType = "forum: ".$tmp[1]['forum_name'];
				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {
					if($value['user_name']) {
						$this -> rssItems[$loop]['author'] = $value['user_name'];
						$this -> rssItems[$loop]['author_email'] = $value['user_email'];
					} else {
						$tmp=explode(".", $value['thread_user'], 2);
						list($this -> rssItems[$loop]['author'], $ip) = explode(chr(1), $tmp[1]);
					}

					if($value['parent_name']) {
						$this -> rssItems[$loop]['title'] = "Re: ".$value['parent_name'];
						$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$value['thread_id'].".post";
					} else {
						$this -> rssItems[$loop]['title'] = $value['thread_name'];
						$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$value['thread_id'];
					}
					$this -> rssItems[$loop]['description'] = $value['thread_thread'];
					$loop++;
				}
			break;

			case 12:
				$topic = ($topic_id) ? "download_category='".intval($topic_id)."' AND " : "";
				$this -> contentType = "downloads";
				$class_list = "0,251,252,253";
				$sql->db_Select("download", "*", "{$topic} download_active > 0 AND download_class IN (".$class_list.") ORDER BY download_datestamp DESC LIMIT 0,29");
				$tmp = $sql->db_getList();
				$this -> rssItems = array();
				$loop=0;
				foreach($tmp as $value) {
					if($value['download_author']){
				   		$nick = preg_replace("/[0-9]+\./", "", $value['download_author']);
						$this -> rssItems[$loop]['author'] = $nick;
					}
					$this -> rssItems[$loop]['author_email'] = $value['download_author_email'];
					$this -> rssItems[$loop]['title'] = $value['download_name'];
					$this -> rssItems[$loop]['link'] = $e107->base_path."download.php?view.".$value['download_id'];
					$this -> rssItems[$loop]['description'] = ($rss_type == 3 ? $value['download_description'] : $value['download_description']);
					$this -> rssItems[$loop]['enc_url'] = $e107->base_path."request.php?".$value['download_id'];
					$this -> rssItems[$loop]['enc_leng'] = $value['download_filesize'];
					$this -> rssItems[$loop]['enc_type'] = $this->getmime($value['download_url']);
					$this -> rssItems[$loop]['pubdate'] = $value['download_datestamp'];
				$loop++;
				}
			break;
		}

	// Get Plugin RSS feeds.
	if($sql_rs->db_Select("plugin","*","plugin_rss REGEXP('".$tp -> toDB($content_type, true)."')")){
		$row2 = $sql_rs -> db_Fetch();
		require_once(e_PLUGIN.$row2['plugin_path']."/plugin.php");
		foreach($eplug_rss as $key=>$rs){
			extract($rs);  // id, author, link, linkid, title, description, query, category, datestamp, enc_url, enc_length, enc_type
	// dear McFly, I remember why I used extract() now..  to avoid this: $row[($something['whatever'])]
				if($sql -> db_Select_gen($query)){
					$this -> contentType = $content_type;
					$this -> rssNamespace = $namespace;
					$this -> rssCustomChannel = $custom_channel;
					$this -> rssItems = array();
					$tmp = $sql->db_getList();
					$loop=0;
					foreach($tmp as $row) {

						$this -> rssItems[$loop]['author'] = $row[$author];
						$this -> rssItems[$loop]['author_email'] = $row[$author_email];
						$this -> rssItems[$loop]['title'] = $row[$title];
						$item = ($itemid) ? $row[$itemid] : "";
						$link2 = str_replace("#",$item,$link);
						if($link2){
							if(eregi("http",$link2)){
                                $this -> rssItems[$loop]['link'] = $link2;
							}else{
                            	$this -> rssItems[$loop]['link'] = $e107->base_path.$PLUGINS_DIRECTORY.$link2;
							}
                                            }
						$this -> rssItems[$loop]['description'] = $row[$description];

						if($enc_url){ $this -> rssItems[$loop]['enc_url'] = $e107->base_path.$PLUGINS_DIRECTORY.$enc_url.$row[$item_id]; }
               			if($enc_leng){ $this -> rssItems[$loop]['enc_leng'] = $row[$enc_leng]; }
						if($row[$enc_type]){
							$this -> rssItems[$loop]['enc_type'] = $this->getmime($row[$enc_type]);
						}elseif($enc_type){
							$this -> rssItems[$loop]['enc_type'] = $enc_type;
						}

						$catid = ($categoryid) ? $row[$categoryid] : "";
						$catlink = ($categorylink) ? str_replace("#",$catid,$categorylink) : "";
						if($categoryname && $catlink){
							$this -> rssItems[$loop]['category_name'] = $row[$categoryname];
							$this -> rssItems[$loop]['category_link'] = $e107->base_path.$catlink;
						}
						if($datestamp){
							$this -> rssItems[$loop]['pubdate'] = $row[$datestamp];
						}
						$loop++;
					}
				}
			}
		}
}

	function striptags($text)
	{
   		return $text;
	}

	function buildRss($rss_title) {
		global $sql, $pref, $tp;
		header('Content-type: application/xml', TRUE);

		$rss_title = $tp->toRss($pref['sitename']." : ".$rss_title);
        $rss_namespace = ($this->rssNamespace) ? "xmlns:".$this->rssNamespace : "";
        $rss_custom_channel = ($this->rssCustomChannel) ? $this->rssCustomChannel : "";
		$time = time();
		switch ($this -> rssType) {
			case 1:		// Rss 1.0
				echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\" ?>
						<!-- generator=\"e107\" -->
						<!-- content type=\"".$this -> contentType."\" -->
						<rss version=\"0.92\">
						<channel>
						<title>".$tp->toRss($rss_title)."</title>
						<link>".$pref['siteurl']."</link>
						<description>".$tp->toRss($pref['sitedescription'])."</description>
						<lastBuildDate>".$itemdate = date("r", ($time + $this -> offset))."</lastBuildDate>
						<docs>http://backend.userland.com/rss092</docs>\n";

					foreach($this -> rssItems as $value) {
						echo "
							<item>
							<title>".$tp->toRss($value['title'])."</title>
							<description>".$tp->toRss(substr($value['description'],0,150))."</description>
							<author>".$value['author']."&lt;".$this->nospam($value['author_email'])."&gt;</author>
							<link>".$value['link']."</link>
							</item>";
					}
					echo "
						</channel>
						</rss>";
					break;

				case 2: // rss 2.0
			$sitebutton = (strstr(SITEBUTTON, "http:") ? SITEBUTTON : SITEURL.str_replace("../", "", e_IMAGE).SITEBUTTON);
			echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>
				<!-- generator=\"e107\" -->
				<!-- content type=\"".$this -> contentType."\" -->

				<!-- test=\"".SITEDISCLAIMER."\" -->

				<rss {$rss_namespace} version=\"2.0\">
				<channel>
				<title>".$tp->toRss($rss_title)."</title>
				<link>".$pref['siteurl']."</link>
				<description>".$tp->toRss($pref['sitedescription'])."</description>\n";

			echo $tp->toRss($rss_custom_channel,TRUE)."\n";

			echo "<language>en-gb</language>
				<copyright>".preg_replace("#\<br \/\>|\n|\r#si", "", SITEDISCLAIMER)."</copyright>
				<managingEditor>".$pref['siteadmin']." - ".$pref['siteadminemail']."</managingEditor>
				<webMaster>".$pref['siteadminemail']."</webMaster>
				<pubDate>".date("r",($time + $this -> offset))."</pubDate>
				<lastBuildDate>".date("r",($time + $this -> offset))."</lastBuildDate>
				<docs>http://backend.userland.com/rss</docs>
				<generator>e107 (http://e107.org)</generator>
				<ttl>60</ttl>
				<image>
				<title>".$tp->toRss($rss_title)."</title>
				<url>".(strstr(SITEBUTTON, "http:") ? SITEBUTTON : SITEURL.str_replace("../", "", e_IMAGE).SITEBUTTON)."</url>
				<link>".$pref['siteurl']."</link>
				<width>88</width>
				<height>31</height>
				<description>".$tp->toRss($pref['sitedescription'])."</description>
				</image>
				<textInput>
				<title>Search</title>
				<description>Search ".$tp->toRss($pref['sitename'])."</description>
				<name>query</name>
				<link>".SITEURL.(substr(SITEURL, -1) == "/" ? "" : "/")."search.php</link>
				</textInput>";
			foreach($this -> rssItems as $value) {
				echo "
					<item>
					<title>".$tp->toRss($value['title'])."</title>\n";

				if($value['link']){
                	echo "<link>".$value['link']."</link>\n";
				}

				echo "<description>".$tp->toRss($value['description'])."</description>\n";

				if($value['category_name'] && $value['category_link']){
                	echo "<category domain='".$value['category_link']."'>".$tp -> toRss($value['category_name'])."</category>\n";
				}

				if($value['comment']){
					echo "<comments>".$tp->toRss($value['comment'])."</comments>\n";
				}

				if($value['author']){
					echo "<author>".$value['author']."&lt;".$this->nospam($value['author_email'])."&gt;</author>\n";
				}

				// enclosure support for podcasting etc.
		   		if($value['enc_url'] && $value['enc_leng'] && $value['enc_type']){
					echo "<enclosure url=\"".$value['enc_url']."\" length=\"".$value['enc_leng']."\" type=\"".$value['enc_type']."\"   />\n";
		   	 	}

				echo "<pubDate>".date("r", ($value['pubdate'] + $this -> offset))."</pubDate>\n";

				if($value['link']){
					echo "<guid isPermaLink=\"true\">".$value['link']."</guid>\n";
				}

				echo "</item>";
			}
			echo "
				</channel>
				</rss>";
			break;

			case 3: // rdf
			echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\" ?>
				<!-- generator=\"e107\" -->
				<!-- content type=\"".$this -> contentType."\" -->
				<rdf:RDF xmlns=\"http://purl.org/rss/1.0/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\" xmlns:admin=\"http://webns.net/mvcb/\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">
				<channel rdf:about=\"".$pref['siteurl']."\">
				<title>".$tp->toRss($rss_title)."</title>
				<link>".$pref['siteurl']."</link>
				<description>".$tp->toRss($pref['sitedescription'])."</description>
				<dc:language>en</dc:language>
				<dc:date>".$this->get_iso_8601_date($time + $this -> offset). "</dc:date>
				<dc:creator>".$pref['siteadminemail']."</dc:creator>
				<admin:generatorAgent rdf:resource=\"http://e107.org\" />
				<admin:errorReportsTo rdf:resource=\"mailto:".$pref['siteadminemail']."\" />
				<sy:updatePeriod>hourly</sy:updatePeriod>
				<sy:updateFrequency>1</sy:updateFrequency>
				<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase>
				<items>
				<rdf:Seq>";

			foreach($this -> rssItems as $value) {
				echo "
					<rdf:li rdf:resource=\"".$value['link']."\" />";
			}

			echo "
				</rdf:Seq>
				</items>
				</channel>";

			reset($this -> rssItems);
			foreach($this -> rssItems as $value) {
				echo "
					<item rdf:about=\"".$value['link']."\">
					<title>".$tp->toRss($value['title'])."</title>
					<link>".$value['link']."</link>
					<dc:date>".$this->get_iso_8601_date($time + $this -> offset)."</dc:date>
					<dc:creator>".$value['author']."</dc:creator>
					<dc:subject>".$tp->toRss($value['category_name'])."</dc:subject>
					<description>".$tp->toRss($value['description'])."</description>
					</item>";
			}
			echo "
				</rdf:RDF>";
			break;
		}
	}


	function getmime($file){
		$ext = strtolower(str_replace(".","",strrchr(basename($file), ".")));
		$mime["mp3"] = "audio/mpeg";
		return $mime[$ext];
	}


    function get_iso_8601_date($int_date) {
   //$int_date: current date in UNIX timestamp
   		$date_mod = date('Y-m-d\TH:i:s', $int_date);
   		$pre_timezone = date('O', $int_date);
   		$time_zone = substr($pre_timezone, 0, 3).":".substr($pre_timezone, 3, 2);
   		$date_mod .= $time_zone;
   		return $date_mod;
	}

	function nospam($text){
		$tmp = explode("@",$text);
		return ($tmp[0] != "") ? $tmp[0]."@nospam.com" : "noauthor@nospam.com";
	}

}

?>