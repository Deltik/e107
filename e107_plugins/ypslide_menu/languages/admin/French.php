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

define("ypslide_LAN1","Actualiser");
define("ypslide_LAN2","Options générales");
define("ypslide_LAN3","Design");
define("ypslide_LAN4","Position");
define("ypslide_LAN5","Absolue");
define("ypslide_LAN6","Relative");
define("ypslide_LAN7","Si vous choisissez <i>Absolue</i> vous allez devoir définir la position du menu sur la page (Utilisez les champs suivants pour définir où exactement). Avec <i>Relative</i>, le menu sera affiché dans l'une des zones de menus de votre theme (Vous devrez vraisemblablement remettre à zéro (ou laisser vide) les 2 champs suivants pour afficher correctement le menu). ATTENTION : Si vous placez le menu en bas d'écran, les sous menus seront sans doute affichées à un eposition un peu déroutante. Placez donc plutot votre menu en haut de l'écran.");
define("ypslide_LAN8","Direction du scroll des sous-menus");
define("ypslide_LAN9","Vertical");
define("ypslide_LAN10","Horizontal");
define("ypslide_LAN11","Position-X");
define("ypslide_LAN12","Position-Y");
define("ypslide_LAN13","Espace (en pixels) entre le bord gauche du menu et le bord gauche de la fenetre du navigateur. Vous pouvez laisser vide pour 0 (nul) pixel. SEULEMENT POUR LA POSITION ABSOLUE.");
define("ypslide_LAN14","Espace (en pixels) entre le bord haut du menu et le bord haut de la fenetre du navigateur. Vous pouvez laisser vide pour 0 (nul) pixel. SEULEMENT POUR LA POSITION ABSOLUE.");
define("ypslide_LAN15","<b>NOTE:</b> le moyen le plus simple d'utiliser ce menu est d'utiliser également eDynamicMenu (téléchargeable sur e107coders.org ou touchatou.org). Si vous ne voulez pas utiliser eDynamicMenu, vous devrez surement modifier votre sitelinks function. Forums e107france.org ou e107.org pour savoir comment faire...");
define("ypslide_LAN16","Largeur du menu");
define("ypslide_LAN17","Largeur pour le menu complet. Si vous utilisez ce menu dans une aire de menus de votre site, ce mneu devra être redimensionné en conséquence.<br /><br />Vous pouvez également créer une zone spécifique pour ce menu ou utiliser la position <i>Absolue</i>.");
define("ypslide_LAN18","Largeur pour les sous-menus");
define("ypslide_LAN19","Configuration actualisée pour le menu DHTML");
define("ypslide_LAN20","Menu vertical ?");
define("ypslide_LAN21","Si vous souhaitez afficher le menu ans une petite aire de menus, il est probable que vous souhaitiez insérer des sauts de lignes entra chaque lien pour les afficher les uns en dessous des autres...<br />Pour afficher les liens sur une ligne, décochez la case.");
define("ypslide_LAN22","Configuration barre principale");
define("ypslide_LAN23","Couleur des liens");
define("ypslide_LAN24","Changez seulement si vous êtes sur !!! Utilisez des attributs CSS2 corrects.");
define("ypslide_LAN25","Image de fond");
define("ypslide_LAN26","Style bordure");
define("ypslide_LAN27","Decoration Texte");
define("ypslide_LAN28","Couleur de fond");
define("ypslide_LAN29","Liens actifs barre principale");
define("ypslide_LAN30","Liens sous-menus");
define("ypslide_LAN31","Position des sous-menus");
define("ypslide_LAN32","Si vous choisissez <i>Absolue</i>, les sous-menus seront affichés à la même position sous chaque lien, si vous choisissez <i>Relative</i>, les sous-menus seront positionnés en utilisant la position du pointeur de la souris.");
define("ypslide_LAN33","Menu principal basé sur un javascript original de http://youngpup.net");
define("ypslide_LAN34","Veuillez ajouter cette mention dans la Mise en garde du site (<a href=\"".e_ADMIN."prefs.php\" >page préférences</a>) (<b>ou certains de vos visiteurs seront dérangés par un popup</b> les alertant que vous ne respectez pas les droits d'auteurs) :");
define("ypslide_LAN35","Comment utiliser ce menu...");
define("ypslide_LAN36","<br /><br />
Le menu 'ypslide' vous permet de donner une touche dynamique à votre site en affichant un menu principal avec un jeu de sous-menus affichés par mouseover.
<br /><br />
<b>Pour ajouter des liens</b> dnas le menu, procédez normalement depuis <a href=\"".e_ADMIN."links.php\" >la page pour gérer les liens</a> de votre site. En créant un lien normal pour la catégorie correspondant à votre menu principal, vous créez un lien affiché par défaut. Pour créer des sous-menus, il faut respecter une regle précise pour le label des liens, utilisant un préfixe différent pour chaque catégorie de liens : submenu.label_lien_parent.label_lien_sousmenu
<br /><br />
Voici un exemple pour faire plus simple: vous souhaitez un lien 'Downloads', qui affiche 2 liens lorsque le pointeur de la souris arrive dessus : 'Themes' et 'Plugins'.
<br /><br />
1. Créez d'abord votre lien appelé Downloads..<br />
2. Puis un lien: submenu.Downloads.Themes<br />
3. Et un lien: submenu.Downloads.Plugins<br />
<br />
Vous verrez alors un bouton appelé Downloads, et les deux liens apparaitront lorsque le visiteur amène le curseur de la souris sur ce bouton.
<br /><br />
Vous pouvez ajouter autant de liens et de sous-menus que vous le souhaitez. Pensez juste à ne pas rendre votre menu trop confus pour vos visiteurs. Notez que vous ne pouvez créer qu'un niveau de navigation (vous ne pouvez pas ajouter de sousmenus pour les liens Themes et Plugins dans notre exemple précédent)
<br /><br />
NOTE : Les liens réservés à certaines catégories de vos visiteurs ne seront pas affichés, les icones utilisés pour les liens du menu principal (pas les sous-menus) seront affichés.
<br /><br />
Pour déactiver votre menu principal, vous pouvez éditer votre fichier theme.php et chercher {SITELINKS}. Effacez ce code, et le menu sera effacé de votre site.
<br />
Autre solution : Utilisez eDynamicMenu qui masque automatiquement votre menu principal.
<br /><br /><hr /><br />
<b>Pour configurer le menu à proprement parler</b>, utilisez les options de cette page.
<br />
<br />
Vous pouvez configurer des options générales comme la position, le mouvement de scroll des sous-menus, le style etc...
<br />
<br />
Si vous veniez à trop jouer avec ces options et êtes perdus pour retrouver une allure correcte, vous pouvez désinstaller et réinstaller ce plugin avec le Gestionnaire de Plugin pour retrouver la configuration initiale.
Les liens ne seront pas perdus, seules les préférences propres à ypslide menu seront perdues. ;)
<br /><br />
Mais cela ne devrait pas arriver, si vous prenez soin de sauver la configuration de votre menu.
<br />
Si vous choisissez de nommer votre sauvegarde comme l'un de vos themes, cette sauvegarde sera automatiquement utilisée pour les visiteurs avec ce theme.
<br /><br />
<b>Par exemple :</b> Si vous avez 3 themes installés (e107, example et nagrunium) et choississez de sauvegarder un design avec le nom example, tous les visiteurs qui auront choisis ce theme (sous reserve que vous les autorisiez à changer de theme) utiliseront ce design, les autres utiliseront le design par defaut que vous pouvez voir dans l'administration
<br /><br />
Pour avoir plus d'aide pour utiliser ce menu, rendez-vous sur <a href=\"http://www.touchatou.org\" >www.touchatou.org</a> (Lolo Irie Website), www.e107.org (Official e107 Website) ou e107coders.org (Site for e107 plugins).
<br /><br />
<a href=\"ypslidemenu_README.php\" ><b>Pour lire le fichier ReadMe, c'est là !</b></a>");
define("ypslide_LAN37","Comment configurer le menu 'ypslide' ");
define("ypslide_LAN38","Mention requise pas l'auteur du script original");
define("ypslide_LAN39","Espace (en pixels) entre le bord gauche du lien principal et le bord gauche du sous-menu.  Vous pouvez laisser vide pour 0 (nul) pixel.");
define("ypslide_LAN40","Espace (en pixels) entre le bord haut du lien principal et le bord haut du sous-menu. Cette valeur doit sans doute être modifiée si vous changez des attributs de style de la barre principale du menu, comme la taille de police (en bas d'écran). Vous pouvez laisser vide pour 0 (nul) pixel.");
define("ypslide_LAN41","Clic");
define("ypslide_LAN42","Ajouter vos images dans ypslide_menu/images folder.");
define("ypslide_LAN43","Choisissez votre image");
define("ypslide_LAN44","Cliquez ici pour afficher/masquer le formulaire de configuration");
define("ypslide_LAN45","Police de caractères");
define("ypslide_LAN46","Taille de la police");
define("ypslide_LAN47","Design actuel<br /><b class=\"smalltext\" >(pour voir vos modifications, veuillez actualiser les valeurs avec le bouton en bas du formulaire)</b>");
define("ypslide_LAN48","Lien Principal");
define("ypslide_LAN49","Sous-lien");
define("ypslide_LAN50","Sous-lien activé");
define("ypslide_LAN51","Liens sous-menus actifs");
define("ypslide_LAN52","Alignement texte");
define("ypslide_LAN53","Si vous souhaitez rajouter d'autres attributs de style, veuillez éditer le fichier ypslide_menu.php entre les lignes 100-160 (pour la version 1.0).");
define("ypslide_LAN54","Icone pour les liens avec sous-menu ?");
define("ypslide_LAN55","Affichera l'icone à droite du titre du lien");
define("ypslide_LAN56","Autres attributs de style");
define("ypslide_LAN57","ATTENTION : Pour ce champ vous devez insérer le code exactement comme dans un ficher css avec noms des attributes ET valeurs (ex: font-weight: bold;) et pas uniquement la valeur comme pour les champs précédents.");
define("ypslide_LAN58","Sauvegarder/charger/effacer configurations");
define("ypslide_LAN59","Actualiser/Sauvegarder");
define("ypslide_LAN60","Donner un nom à cette configuration :");
define("ypslide_LAN61","Configurations existantes :");
define("ypslide_LAN62","Charger");
define("ypslide_LAN63","Votre configuration a été correctement sauvegardée.");
define("ypslide_LAN64","Configuration chargée !");
define("ypslide_LAN65","Cette configuration a été effacée :");
define("ypslide_LAN66","Effacer une configuration :");
define("ypslide_LAN67","Effacer");
define("ypslide_LAN68","Sauver votre configuration actuelle");
define("ypslide_LAN69","Charger ou effacer une configuration");
define("ypslide_LAN70","Cette configuration existe déjà, si vous continuez vous allez l\'actualiser. Cliquer sur \'Annuler\' MAINTENANT pour utiliser un uatre nom si besoin.");
define("ypslide_LAN71","Votre configuration a été correctement actualisée.");
define("ypslide_LAN72","1 Choisissez dans le menu déroulant la configuration à charger ou effacer<br />2 Pour charger ue configuration existante, cliquez sur le bouton Charger ou...<br /> Pour effacer, cochez la case pour confirmer la suppression avant de cliquer sur le bouton Effacer");
define("ypslide_LAN73","Cochez d\'abord la case pour confirmer la suppression");
define("ypslide_LAN74","Aucune configuration effacée, car vous n'aviez pas coché la case pour confirmer...");

define ("colpick_LAN1","Color Picker");
define ("colpick_LAN2","Cliquez sur la couleur à utiliser");
define ("colpick_LAN3","Cliquez pour choisir facilement la couleur");
define ("colpick_LAN4","");
define ("colpick_LAN5","");


define("SUB_TOUCHATOU_1","Un lien sur Touchatou");
define("SUB_TOUCHATOU_2","Inscrire votre site sur Touchatou.org pour ce plugin ?");
define("SUB_TOUCHATOU_3","Pourquoi s'inscrire ?..");
define("SUB_TOUCHATOU_4","Si vous cochez cette box, vous allez inscrire votre site sur Touchatou dans la liste des sites utiliant mes plugins..
<br />
<br />
Si votre site ne répond pas aux critères de Touchatou (pas de sites pornos, de warez ou autre contenu illegal), il sera effacé !!!
<br />
<br />
<b>Aucune information privée n'est envoyée, uniquement votre pseudo, nom du site, url et description.</b>");
define("SUB_TOUCHATOU_5","Inscrire votre site !");
define("SUB_TOUCHATOU_6","Vous devez cochez la case pour pouvoir soumettre votre site.");
define("SUB_TOUCHATOU_7","La fonction pour soumettre votre site sur Touchatou est maintenant desactivée.");
define("SUB_TOUCHATOU_8","Message");
define("SUB_TOUCHATOU_9","Support sur Touchatou");
define("SUB_TOUCHATOU_10","<a href=\"http://touchatou.org/forum.php\" >Postez donc un message sur les forums de Touchatou</a> pour obtenir de l'aide.
<br />
<br />
<br />
Sur Touchatou, vous découvrirez d'autres pages relatives à e107 :
<br />
- Le Ring (Si vous n'etes pas encore membre, inscrivez vous, c'est gratuit !) et le menu à télécharger pour l'installer sur votre site comme tous les membres<br />
- Démos en Flash pour présenter les bases d'e107<br />
- Tous les titres des news des sites officiels d'e107<br />
- Tous les plugins de Lolo Irie : eChat, eChess, eContact, eCountDown, eGoogle, eNewsletter, ePreview, eQuizz, eTellAFriend<br />
- Si e107coders.org ou/et e107themes.org sont inaccessibles, vous trouverez tous les plugins et themes pour e107version6<br />
<br />
Ainsi que des pages sur la musique.
<br />
Si vous aussi êtes passionné de musique, retrouvez moi sur mes forums.
<br />
Merci pour votre intéret.
");

?>