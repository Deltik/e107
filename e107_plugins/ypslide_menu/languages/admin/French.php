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
define("ypslide_LAN2","Options g�n�rales");
define("ypslide_LAN3","Design");
define("ypslide_LAN4","Position");
define("ypslide_LAN5","Absolue");
define("ypslide_LAN6","Relative");
define("ypslide_LAN7","Si vous choisissez <i>Absolue</i> vous allez devoir d�finir la position du menu sur la page (Utilisez les champs suivants pour d�finir o� exactement). Avec <i>Relative</i>, le menu sera affich� dans l'une des zones de menus de votre theme (Vous devrez vraisemblablement remettre � z�ro (ou laisser vide) les 2 champs suivants pour afficher correctement le menu). ATTENTION : Si vous placez le menu en bas d'�cran, les sous menus seront sans doute affich�es � un eposition un peu d�routante. Placez donc plutot votre menu en haut de l'�cran.");
define("ypslide_LAN8","Direction du scroll des sous-menus");
define("ypslide_LAN9","Vertical");
define("ypslide_LAN10","Horizontal");
define("ypslide_LAN11","Position-X");
define("ypslide_LAN12","Position-Y");
define("ypslide_LAN13","Espace (en pixels) entre le bord gauche du menu et le bord gauche de la fenetre du navigateur. Vous pouvez laisser vide pour 0 (nul) pixel. SEULEMENT POUR LA POSITION ABSOLUE.");
define("ypslide_LAN14","Espace (en pixels) entre le bord haut du menu et le bord haut de la fenetre du navigateur. Vous pouvez laisser vide pour 0 (nul) pixel. SEULEMENT POUR LA POSITION ABSOLUE.");
define("ypslide_LAN15","<b>NOTE:</b> le moyen le plus simple d'utiliser ce menu est d'utiliser �galement eDynamicMenu (t�l�chargeable sur e107coders.org ou touchatou.org). Si vous ne voulez pas utiliser eDynamicMenu, vous devrez surement modifier votre sitelinks function. Forums e107france.org ou e107.org pour savoir comment faire...");
define("ypslide_LAN16","Largeur du menu");
define("ypslide_LAN17","Largeur pour le menu complet. Si vous utilisez ce menu dans une aire de menus de votre site, ce mneu devra �tre redimensionn� en cons�quence.<br /><br />Vous pouvez �galement cr�er une zone sp�cifique pour ce menu ou utiliser la position <i>Absolue</i>.");
define("ypslide_LAN18","Largeur pour les sous-menus");
define("ypslide_LAN19","Configuration actualis�e pour le menu DHTML");
define("ypslide_LAN20","Menu vertical ?");
define("ypslide_LAN21","Si vous souhaitez afficher le menu ans une petite aire de menus, il est probable que vous souhaitiez ins�rer des sauts de lignes entra chaque lien pour les afficher les uns en dessous des autres...<br />Pour afficher les liens sur une ligne, d�cochez la case.");
define("ypslide_LAN22","Configuration barre principale");
define("ypslide_LAN23","Couleur des liens");
define("ypslide_LAN24","Changez seulement si vous �tes sur !!! Utilisez des attributs CSS2 corrects.");
define("ypslide_LAN25","Image de fond");
define("ypslide_LAN26","Style bordure");
define("ypslide_LAN27","Decoration Texte");
define("ypslide_LAN28","Couleur de fond");
define("ypslide_LAN29","Liens actifs barre principale");
define("ypslide_LAN30","Liens sous-menus");
define("ypslide_LAN31","Position des sous-menus");
define("ypslide_LAN32","Si vous choisissez <i>Absolue</i>, les sous-menus seront affich�s � la m�me position sous chaque lien, si vous choisissez <i>Relative</i>, les sous-menus seront positionn�s en utilisant la position du pointeur de la souris.");
define("ypslide_LAN33","Menu principal bas� sur un javascript original de http://youngpup.net");
define("ypslide_LAN34","Veuillez ajouter cette mention dans la Mise en garde du site (<a href=\"".e_ADMIN."prefs.php\" >page pr�f�rences</a>) (<b>ou certains de vos visiteurs seront d�rang�s par un popup</b> les alertant que vous ne respectez pas les droits d'auteurs) :");
define("ypslide_LAN35","Comment utiliser ce menu...");
define("ypslide_LAN36","<br /><br />
Le menu 'ypslide' vous permet de donner une touche dynamique � votre site en affichant un menu principal avec un jeu de sous-menus affich�s par mouseover.
<br /><br />
<b>Pour ajouter des liens</b> dnas le menu, proc�dez normalement depuis <a href=\"".e_ADMIN."links.php\" >la page pour g�rer les liens</a> de votre site. En cr�ant un lien normal pour la cat�gorie correspondant � votre menu principal, vous cr�ez un lien affich� par d�faut. Pour cr�er des sous-menus, il faut respecter une regle pr�cise pour le label des liens, utilisant un pr�fixe diff�rent pour chaque cat�gorie de liens : submenu.label_lien_parent.label_lien_sousmenu
<br /><br />
Voici un exemple pour faire plus simple: vous souhaitez un lien 'Downloads', qui affiche 2 liens lorsque le pointeur de la souris arrive dessus : 'Themes' et 'Plugins'.
<br /><br />
1. Cr�ez d'abord votre lien appel� Downloads..<br />
2. Puis un lien: submenu.Downloads.Themes<br />
3. Et un lien: submenu.Downloads.Plugins<br />
<br />
Vous verrez alors un bouton appel� Downloads, et les deux liens apparaitront lorsque le visiteur am�ne le curseur de la souris sur ce bouton.
<br /><br />
Vous pouvez ajouter autant de liens et de sous-menus que vous le souhaitez. Pensez juste � ne pas rendre votre menu trop confus pour vos visiteurs. Notez que vous ne pouvez cr�er qu'un niveau de navigation (vous ne pouvez pas ajouter de sousmenus pour les liens Themes et Plugins dans notre exemple pr�c�dent)
<br /><br />
NOTE : Les liens r�serv�s � certaines cat�gories de vos visiteurs ne seront pas affich�s, les icones utilis�s pour les liens du menu principal (pas les sous-menus) seront affich�s.
<br /><br />
Pour d�activer votre menu principal, vous pouvez �diter votre fichier theme.php et chercher {SITELINKS}. Effacez ce code, et le menu sera effac� de votre site.
<br />
Autre solution : Utilisez eDynamicMenu qui masque automatiquement votre menu principal.
<br /><br /><hr /><br />
<b>Pour configurer le menu � proprement parler</b>, utilisez les options de cette page.
<br />
<br />
Vous pouvez configurer des options g�n�rales comme la position, le mouvement de scroll des sous-menus, le style etc...
<br />
<br />
Si vous veniez � trop jouer avec ces options et �tes perdus pour retrouver une allure correcte, vous pouvez d�sinstaller et r�installer ce plugin avec le Gestionnaire de Plugin pour retrouver la configuration initiale.
Les liens ne seront pas perdus, seules les pr�f�rences propres � ypslide menu seront perdues. ;)
<br /><br />
Mais cela ne devrait pas arriver, si vous prenez soin de sauver la configuration de votre menu.
<br />
Si vous choisissez de nommer votre sauvegarde comme l'un de vos themes, cette sauvegarde sera automatiquement utilis�e pour les visiteurs avec ce theme.
<br /><br />
<b>Par exemple :</b> Si vous avez 3 themes install�s (e107, example et nagrunium) et choississez de sauvegarder un design avec le nom example, tous les visiteurs qui auront choisis ce theme (sous reserve que vous les autorisiez � changer de theme) utiliseront ce design, les autres utiliseront le design par defaut que vous pouvez voir dans l'administration
<br /><br />
Pour avoir plus d'aide pour utiliser ce menu, rendez-vous sur <a href=\"http://www.touchatou.org\" >www.touchatou.org</a> (Lolo Irie Website), www.e107.org (Official e107 Website) ou e107coders.org (Site for e107 plugins).
<br /><br />
<a href=\"ypslidemenu_README.php\" ><b>Pour lire le fichier ReadMe, c'est l� !</b></a>");
define("ypslide_LAN37","Comment configurer le menu 'ypslide' ");
define("ypslide_LAN38","Mention requise pas l'auteur du script original");
define("ypslide_LAN39","Espace (en pixels) entre le bord gauche du lien principal et le bord gauche du sous-menu.  Vous pouvez laisser vide pour 0 (nul) pixel.");
define("ypslide_LAN40","Espace (en pixels) entre le bord haut du lien principal et le bord haut du sous-menu. Cette valeur doit sans doute �tre modifi�e si vous changez des attributs de style de la barre principale du menu, comme la taille de police (en bas d'�cran). Vous pouvez laisser vide pour 0 (nul) pixel.");
define("ypslide_LAN41","Clic");
define("ypslide_LAN42","Ajouter vos images dans ypslide_menu/images folder.");
define("ypslide_LAN43","Choisissez votre image");
define("ypslide_LAN44","Cliquez ici pour afficher/masquer le formulaire de configuration");
define("ypslide_LAN45","Police de caract�res");
define("ypslide_LAN46","Taille de la police");
define("ypslide_LAN47","Design actuel<br /><b class=\"smalltext\" >(pour voir vos modifications, veuillez actualiser les valeurs avec le bouton en bas du formulaire)</b>");
define("ypslide_LAN48","Lien Principal");
define("ypslide_LAN49","Sous-lien");
define("ypslide_LAN50","Sous-lien activ�");
define("ypslide_LAN51","Liens sous-menus actifs");
define("ypslide_LAN52","Alignement texte");
define("ypslide_LAN53","Si vous souhaitez rajouter d'autres attributs de style, veuillez �diter le fichier ypslide_menu.php entre les lignes 100-160 (pour la version 1.0).");
define("ypslide_LAN54","Icone pour les liens avec sous-menu ?");
define("ypslide_LAN55","Affichera l'icone � droite du titre du lien");
define("ypslide_LAN56","Autres attributs de style");
define("ypslide_LAN57","ATTENTION : Pour ce champ vous devez ins�rer le code exactement comme dans un ficher css avec noms des attributes ET valeurs (ex: font-weight: bold;) et pas uniquement la valeur comme pour les champs pr�c�dents.");
define("ypslide_LAN58","Sauvegarder/charger/effacer configurations");
define("ypslide_LAN59","Actualiser/Sauvegarder");
define("ypslide_LAN60","Donner un nom � cette configuration :");
define("ypslide_LAN61","Configurations existantes :");
define("ypslide_LAN62","Charger");
define("ypslide_LAN63","Votre configuration a �t� correctement sauvegard�e.");
define("ypslide_LAN64","Configuration charg�e !");
define("ypslide_LAN65","Cette configuration a �t� effac�e :");
define("ypslide_LAN66","Effacer une configuration :");
define("ypslide_LAN67","Effacer");
define("ypslide_LAN68","Sauver votre configuration actuelle");
define("ypslide_LAN69","Charger ou effacer une configuration");
define("ypslide_LAN70","Cette configuration existe d�j�, si vous continuez vous allez l\'actualiser. Cliquer sur \'Annuler\' MAINTENANT pour utiliser un uatre nom si besoin.");
define("ypslide_LAN71","Votre configuration a �t� correctement actualis�e.");
define("ypslide_LAN72","1 Choisissez dans le menu d�roulant la configuration � charger ou effacer<br />2 Pour charger ue configuration existante, cliquez sur le bouton Charger ou...<br /> Pour effacer, cochez la case pour confirmer la suppression avant de cliquer sur le bouton Effacer");
define("ypslide_LAN73","Cochez d\'abord la case pour confirmer la suppression");
define("ypslide_LAN74","Aucune configuration effac�e, car vous n'aviez pas coch� la case pour confirmer...");

define ("colpick_LAN1","Color Picker");
define ("colpick_LAN2","Cliquez sur la couleur � utiliser");
define ("colpick_LAN3","Cliquez pour choisir facilement la couleur");
define ("colpick_LAN4","");
define ("colpick_LAN5","");


define("SUB_TOUCHATOU_1","Un lien sur Touchatou");
define("SUB_TOUCHATOU_2","Inscrire votre site sur Touchatou.org pour ce plugin ?");
define("SUB_TOUCHATOU_3","Pourquoi s'inscrire ?..");
define("SUB_TOUCHATOU_4","Si vous cochez cette box, vous allez inscrire votre site sur Touchatou dans la liste des sites utiliant mes plugins..
<br />
<br />
Si votre site ne r�pond pas aux crit�res de Touchatou (pas de sites pornos, de warez ou autre contenu illegal), il sera effac� !!!
<br />
<br />
<b>Aucune information priv�e n'est envoy�e, uniquement votre pseudo, nom du site, url et description.</b>");
define("SUB_TOUCHATOU_5","Inscrire votre site !");
define("SUB_TOUCHATOU_6","Vous devez cochez la case pour pouvoir soumettre votre site.");
define("SUB_TOUCHATOU_7","La fonction pour soumettre votre site sur Touchatou est maintenant desactiv�e.");
define("SUB_TOUCHATOU_8","Message");
define("SUB_TOUCHATOU_9","Support sur Touchatou");
define("SUB_TOUCHATOU_10","<a href=\"http://touchatou.org/forum.php\" >Postez donc un message sur les forums de Touchatou</a> pour obtenir de l'aide.
<br />
<br />
<br />
Sur Touchatou, vous d�couvrirez d'autres pages relatives � e107 :
<br />
- Le Ring (Si vous n'etes pas encore membre, inscrivez vous, c'est gratuit !) et le menu � t�l�charger pour l'installer sur votre site comme tous les membres<br />
- D�mos en Flash pour pr�senter les bases d'e107<br />
- Tous les titres des news des sites officiels d'e107<br />
- Tous les plugins de Lolo Irie : eChat, eChess, eContact, eCountDown, eGoogle, eNewsletter, ePreview, eQuizz, eTellAFriend<br />
- Si e107coders.org ou/et e107themes.org sont inaccessibles, vous trouverez tous les plugins et themes pour e107version6<br />
<br />
Ainsi que des pages sur la musique.
<br />
Si vous aussi �tes passionn� de musique, retrouvez moi sur mes forums.
<br />
Merci pour votre int�ret.
");

?>