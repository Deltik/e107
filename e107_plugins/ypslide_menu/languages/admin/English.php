<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	YPSlideMenu by Youngpup.net (original code)/ Jalist (Convert for e107)/ Lisa (Submenus displayed with relative position and not function of the mouse position) and Lolo Irie (Javascript and PHP fix, plugins features)
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

define("ypslide_LAN1","Update");
define("ypslide_LAN2","Main features");
define("ypslide_LAN3","Design");
define("ypslide_LAN4","Position");
define("ypslide_LAN5","Absolute");
define("ypslide_LAN6","Relative");
define("ypslide_LAN7","If you choose <i>Absolute</i> you will define the position of your menu in the complete page (Use following fields to define where exactly). With the <i>Relative</i> choice, this menu will be displayed in a activated menu area (You probably will need to reset to 0 both following fields to display it correctly). IMPORTANT NOTE : If you activate this menu in a menu area at the bottom of the page, submenus won't be display at the good place and it can be disturbing for users. Place your menu at the top of your page is recommended !");
define("ypslide_LAN8","Slide direction");
define("ypslide_LAN9","Vertical");
define("ypslide_LAN10","Horizontal");
define("ypslide_LAN11","Position-X");
define("ypslide_LAN12","Position-Y");
define("ypslide_LAN13","Space (in pixels) between the left border of the browser window and the menu. You can let empty for 0 (null) pixel.<br />ONLY FOR ABSOLUTE POSITION");
define("ypslide_LAN14","Space (in pixels)  between the top border of the browser window and the menu. You can let empty for 0 (null) pixel.<br />ONLY FOR ABSOLUTE POSITION");
define("ypslide_LAN15","<b>NOTE:</b> The easiest way to use this menu it to use it coupled with an other plugin called eDynamicMenu (downloadable on e107coders.org or touchatou.org). If you don't want to use eDynamicMenu, you will maybe need to hack your sitelinks function. Go on e107.org forums to know more about...");
define("ypslide_LAN16","Total width");
define("ypslide_LAN17","Width for the complete menu. If you are using this menu in an existing menu area, this menu area will be resized with this value.<br /><br />You will maybe need to create a specific menu area for this DHTML menu or use a <i>Absolute</i> position.");
define("ypslide_LAN18","Width for submenus");
define("ypslide_LAN19","Configuration updated for the DHTML menu");
define("ypslide_LAN20","Menu vertical ?");
define("ypslide_LAN21","If you need to display the menu in a menu area with small width, check this box to add a break line after each link.<br />To display the menu on one line uncheck the box.");
define("ypslide_LAN22","Mainbar configuration");
define("ypslide_LAN23","Main Links Color");
define("ypslide_LAN24","Change only if you are sure !!! Use standard CSS2 attributes.");
define("ypslide_LAN25","Background image");
define("ypslide_LAN26","Border style");
define("ypslide_LAN27","Text decoration");
define("ypslide_LAN28","Background color");
define("ypslide_LAN29","Mainbar activated link");
define("ypslide_LAN30","Sublinks style");
define("ypslide_LAN31","Position for submenus");
define("ypslide_LAN32","If you choose <i>Absolute</i>, submenus will be always displayed at the same place, near the main link, with the <i>Relative</i> choice, submenus will be displayed using the mouse pointer position. If you remark some bugs to display submenus, you can try to change here and check if it solves your issue.<br />Think anyway to repor bugs if you like this plugin. ;)");
define("ypslide_LAN33","Main Menu based on original javascript code from http://youngpup.net");
define("ypslide_LAN34","Please add the following mention in your <a href=\"".e_ADMIN."prefs.php\" >Site Disclaimer</a> (<b>or some visitors will be disturb by a popup</b> to alert you don't respect original author rights) :");
define("ypslide_LAN35","How to use this menu...");
define("ypslide_LAN36","<br /><br />
The ypslide menu allows you to have main categories and sublinks that be shown with a nice DHTML slide effect when the mouse pointer is over a main category button.
<br /><br />
<b>To insert links</b> in the menu by entering normal links from your <a href=\"".e_ADMIN."links.php\" >admin/links page</a>. Entering a normal link in the main category will make it a top link, or a link with mouseover effect to reveal more links (subcategory). Adding a link also in the main category but called submenu.mainlink.linkname will create a subcategory of your main link.
<br /><br />
To make it simpler, lets create a main link called Downloads, and two sublinks called Themes and Plugins.
<br /><br />
1. Create a link called Downloads..<br />
2. Create a link called submenu.Downloads.Themes<br />
3. Create a link called submenu.Downloads.Plugins<br />
<br />
You'll see when you activate the ypslide menu that you'll have a button called Downloads that when the mouse pointer is over it will reveal two more links called Themes and Plugins.
<br /><br />
You can add as many main and sub categories as you like, although subcategories can only be one level deep (you cant have sub-subcategories).
<br /><br />
NOTE : Userclass restrictions are used for main and subcategories, icons only used for main categories.
<br /><br />
To de-activate your normal navigation menu, you need to edit your theme.php and search for {SITELINKS}. Delete it and your normal main menu will be disappear.
<br />
Other solution : Use eDynamicMenu plugin which automatically hide your main menu.
<br /><br /><hr /><br />
<b>To configure deeper the ypslide menu, use this page.</b>
<br />
<br />
You can configure general settings like position, slide direction and also design style attributes.
<br />
<br />
If you choose to save a config, you will be able to restore it if required.
<br />
<b>If you save a config using a theme name, this config will be automatically used for all users with this theme selected (Very useful, if you choose to display the usertheme menu)</b>
<br />
<br />
<b>For Example :</b> you have 3 themes for your site : e107, example and nagrunium. Save 3 settings with following next names : e107, example and nagrunium, and they will correctly called for each user depending of the theme selected.
<br /><br />
If you are playing too much with these options and are no more able to restore a correct layout (no settings saved), uninstall and install again ypslide menu to restore default values. Links won't be deleted only layout preferences. ;)
<br />
If you saved some settings, choose to load a precedent settings to restore a correct layout.
<br /><br />
To get more help about this plugin, go on <a href=\"http://www.touchatou.org\" >www.touchatou.org</a> (Lolo Irie Website), <a href=\"http://www.e107.org\" >www.e107.org</a> (Official e107 Website) or <a href=\"http://www.e107coders.org\" >e107coders.org</a> (Site for e107 plugins).
<br /><br />
<a href=\"ypslidemenu_README.php\" ><b>Click here to read the ReadMe file !</b></a>");
define("ypslide_LAN37","Click here to read how to configure the ypslide menu ");
define("ypslide_LAN38","Mention required by the author of the original script");
define("ypslide_LAN39","Space (in pixels) between the left border of the main link and the left border of the submenu. You can let empty for 0 (null) pixel.");
define("ypslide_LAN40","Space (in pixels) between the top border of the main link and the top border of the submenu. This value need probably to be modified if you change some style attributes for the mainbar configuration like font size. You can let empty for 0 (null) pixel.");
define("ypslide_LAN41","Click");
define("ypslide_LAN42","Add your own background pictures in the ypslide_menu/images folder.");
define("ypslide_LAN43","Choose picture");
define("ypslide_LAN44","Click here to open/close the config form");
define("ypslide_LAN45","Font Family");
define("ypslide_LAN46","Font size");
define("ypslide_LAN47","Current design<br /><b class=\"smalltext\" >(to see modifications, update the form with a click on the button at the bottom)</b>");
define("ypslide_LAN48","Design Main Link");
define("ypslide_LAN49","Design SubLink");
define("ypslide_LAN50","Design SubLink Activate");
define("ypslide_LAN51","Sublinks activated");
define("ypslide_LAN52","Text alignement");
define("ypslide_LAN53","If you need to complete this stylesheet for other , please edit your file ypslide_menu.php (lines 100 - 160 in the version 1.0 of ypslide menu)");
define("ypslide_LAN54","Icon for links with submenu?");
define("ypslide_LAN55","Display an icon on the right of the link label");
define("ypslide_LAN56","Other style attributes");
define("ypslide_LAN57","BE CAREFUL : For this field, you need to insert attributes names and values exactly like in a css file (ex: font-weight: bold;) and not only the value like precedent fields !!!");
define("ypslide_LAN58","Save/Load/Delete Configs");
define("ypslide_LAN59","Update/Save");
define("ypslide_LAN60","Give a name for your current config:");
define("ypslide_LAN61","Existing configs:");
define("ypslide_LAN62","Load now");
define("ypslide_LAN63","Your current settings were successfully saved.");
define("ypslide_LAN64","New settings are applied now !");
define("ypslide_LAN65","This entry was deleted:");
define("ypslide_LAN66","Delete an existing entry:");
define("ypslide_LAN67","Delete now");
define("ypslide_LAN68","Save your current configuration");
define("ypslide_LAN69","Load or delete a configuration");
define("ypslide_LAN70","This configuration already exist, you will update it or cancel NOW the process and change the name");
define("ypslide_LAN71","Your configuration settings were correctly updated.");
define("ypslide_LAN72","1 Select the setting in the dropdown menu you want to load or delete<br />2 Click on the button Load now to use a saved config or...<br />3 Check the box to confirm delete and click on the button Delete Now to delete a saved config");
define("ypslide_LAN73","Check the box to confirm.");
define("ypslide_LAN74","Entry NOT deleted, because you didn't check the box to confirm...");

define ("colpick_LAN1","Color Picker");
define ("colpick_LAN2","Click on the color to insert");
define ("colpick_LAN3","Click to get HTML codes from a color panel");
define ("colpick_LAN4","");
define ("colpick_LAN5","");

define("SUB_TOUCHATOU_1","Register for a Link on Touchatou.org");
define("SUB_TOUCHATOU_2","Register your site on Touchatou.org?");
define("SUB_TOUCHATOU_3","Why register?");
define("SUB_TOUCHATOU_4","If you check this box, you will add your site to the <b>www.touchatou.org</b> list of sites using Lolo's plugin.<br /><br />
You can register your site once for each plugin used !!!
	<br /><br />
	No site with illegal content will be linked. If your site has porno, warez or anything like it, don't bother registering as the entry will be deleted!
	<br />
	<br />
	<b>No private information will be transmitted. We will record your e107 username, and the name, description and URL of your e107 site.</b>");
define("SUB_TOUCHATOU_5","Submit now!");
define("SUB_TOUCHATOU_6","You need to check the box before submitting!");
define("SUB_TOUCHATOU_7","Your site was submitted!<br /><br />This option will be hidden from now on.");
define("SUB_TOUCHATOU_8","Message");
define("SUB_TOUCHATOU_9","Support on Touchatou");
define("SUB_TOUCHATOU_10","<a href=\"http://touchatou.org/forum.php\" >Post in Touchatou's forum</a> to get help.
<br />
<br />
<br />
On Touchatou, you will find other e107 related pages:
<br />
- the Webring (If you are not yet subscribed, we are waiting for you...) and the menu to install on your site for e107 version5+ or version6+.<br />
- Flash demos (For beginners, to understand how to install e107 or code the first plugin)<br />
- Headlines from e107.org, e107coders.org and e107themes.org tto know everything in one step<br />
- All Lolo's plugins (info+download) : eChat, eChess, eContact, eCountDown, eGoogle, eNewsletter, ePreview eQuizz, eTellAFriend<br />
- IF e107coders.org or e107themes.org are down, you will find almost plugins and themes for download. To use only for emergency, because there are not sorted !<br />
<br />
And other pages about live music, my main hobby !!!
<br />
<b>I have big interest to share with you musical experience and info about regional bands.</b><br />
Let me know about your favorite music, just post in my forum... ;)
<br />
Thanks for interest.
");

?>