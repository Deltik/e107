<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	YPSlideMenu by Youngpup.net (original code)/ Jalist (Convert for e107)/ Lisa (Submenus displayed with relative position and not function of the mouse position) and Lolo Irie (Javascript and PHP fix, plugins features)
|
|	Dutch file from Lisa
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

define("ypslide_LAN1","Bijwerken");
define("ypslide_LAN2","Hoofd kenmerken");
define("ypslide_LAN3","Ontwerp");
define("ypslide_LAN4","Positie");
define("ypslide_LAN5","Absoluut");
define("ypslide_LAN6","Relatief");
define("ypslide_LAN7","Indien je <i>Absoluut</i> kiest, bepaal je de positie van je menu in de complete pagina (Gebruik de volgende velden om exact te bepalen waar). Met de <i>Relatief</i> keuze, wordt dit menu in een geactiveerd menu gedeelte getoond (Je zal de volgende velden waarschijnlijk op 0 moeten zetten om het juist af te beelden). Met de <i>Relatief</i> keuze, wordt dit menu getoond in een geactiveerd menu gedeelte (Je zal waarschijnlijk de twee volgende velden naar 0 moeten zetten om het juist te tonen). BELANGRIJKE NOOT :  Als je dit menu in een menu gedeelte onder aan de pagina activeerd, kunnen de submenu's niet op de goede plaats verschijnen wat voor gebruikers erg vervelend kan zijn. aangeraden wordt het menu bovenaan de pagina te plaatsen !";
define("ypslide_LAN8","Uitklap richting");
define("ypslide_LAN9","Verticaal");
define("ypslide_LAN10","Horizontaal");
define("ypslide_LAN11","Positie-X");
define("ypslide_LAN12","Positie-Y");
define("ypslide_LAN13","Ruimte (in pixels) tussen de linkerkant van je browser scherm en het menu. Laat leeg voor 0 (nul) pixel.)<br />ALLEEN VOOR ABSOLUTE POSITIE";
define("ypslide_LAN14","Ruimte (in pixels) tussen de bovenkant van je browser scherm en het menu. Laat leeg voor 0 (nul) pixel.)<br />ALLEEN VOOR ABSOLUTE POSITIE";
define("ypslide_LAN15","<b>NOOT:</b> De gemakkelijkste manier om dit menu te gebruiken, is gecombineerd samen met een ander plugin genaamd eDynamicMenu (te downloaden op e107coders.org of touchatou.org). Als je het eDynamicMenu niet wilt gebruiken, moet je misschien je sitelinks functie wijzigen. Ga naar e107.org forums om meer te weten over...");
define("ypslide_LAN16","Totale breedte");
define("ypslide_LAN17","Breedte voor het complete menu. Indien je dit menu in een bestaand menu gedeelte gebruikt, wordt dit menu gedeelte aangepast aan deze waarde.<br /><br />Je moet wellicht een specifiek menu gedeelte voor dit menu creëren of een <i>Absolute</i> positie kiezen.)";
define("ypslide_LAN18","Breedte voor submenu's");
define("ypslide_LAN19","Configuratie bijgewerkt voor het DHTML menu");
define("ypslide_LAN20","Menu verticaal ?");
define("ypslide_LAN21","Indien je het menu in een menu gedeelte met een kleine breedte wilt tonen, vink deze box aan om een regelafbreking na elke link te geven.<br />Om het menu op één lijn te tonen, deze box uitvinken");
define("ypslide_LAN22","Hoofdbar configuratie");
define("ypslide_LAN23","Hoofd links Kleur");
define("ypslide_LAN24","Verander alleen als je het zeker weet !!! Gebruik standaard CSS2 attributen.");
define("ypslide_LAN25","Achtergrond afbeelding");
define("ypslide_LAN26","Border stijl");
define("ypslide_LAN27","Tekst decoratie");
define("ypslide_LAN28","Achtergrond kleur");
define("ypslide_LAN29","Hoofdbar geactiveerde link");
define("ypslide_LAN30","Sublinks stijl");
define("ypslide_LAN31","Positie voor submenu's");
define("ypslide_LAN32","Als je <i>Absoluut</i> kiest, zullen submenu's altijd getoond worden op dezelfde plaats, vlakbij de hoofdlink, met de <i>Relatieve</i> keuze, zullen submenu's getoond worden afhankelijk van de positite van de muis cursor. Als je enkele fouten constateerd bij het tonen van submenu's, kan je proberen eerste deze instelling te wijzigen en controleer of het probleem is opgelost.<br />Als je deze plugin bevalt, meldt dan in elk geval fouten die je constateerd. ;)");
define("ypslide_LAN33","Hoofd Menu gebasser op de originele code van http://youngpup.net");
define("ypslide_LAN34","Voeg de volgende melding aub toe aan je <a href=\"".e_ADMIN."prefs.php\" >Site Disclaimer</a> (<b>anders worden sommige bezoekers gestoord door een pop-up</b> om erop te wijzen dat je originele auteursrechten niet respecteerd) :");
define("ypslide_LAN35","Hoe gebruik je dit menu...");

define("ypslide_LAN36","<br /><br />
Het ypslide menu staat je toe hoofd categorieën met sublinks te hebben, die met een mooi DHTML uitklap effect worden getoond wanneer de muis zich over een hoofd categorie bevindt.
<br /><br />
<b>Om links toe te voegen<b> in het menu door normale links in de <a href=\"".e_ADMIN."links.php\" >admin/links pagina</a> in te voegen. Invoeren van een normale link in de hoofd categorie maakt een hoofd link, of een link met muisover effect om meer links te tonen (subcategorieën). Invoeren van een link ook in de hoofdcategorie maar genaamd submenu.hoofdlink.linknaam maakt een subcategorie voor je hoofdlink.
<br /><br />
Om het eenvoudiger te maken, laten we een hoofdlink genaamd Downloads maken en twee sublinks genaamd Themes en Plugins.
<br /><br />
1. Maak een link genaamd Downloads..<br />
2. Maak een link genaamd submenu.Downloads.Themes<br />
3. Maak een link genaamd submenu.Downloads.Plugins<br />
<br />
Je zal zien als je het menu geactiveerd hebt, dat je een hoofdlink genaamd Downloads hebt, en wanneer de muis erover beweegt, twee links toont genaamd Themes en Plugins.
<br /><br />
Je kan zoveel hoofdlinks en sublinks aanmaken als je wilt, alhoewel subcategorieën slechts een level diep kunnen zijn (je kan geen sub-subcategorieën hebben).
<br /><br />
NOOT : Gebruikersklas beperkingen worden gebruikt voor hoofd en subcategoriën, iconen alleen voor hoofdcategoriën.
<br /><br />
Om je normale navigatie menu uit te schakelen, moet je je theme.php bestand wijzigen. Verwijder {SITELINKS} en je normale hoofd menu is verdwenen.
<br />
Andere oplossing : Gebruik eDynamicMenu plugin die automatisch je hoofdmenu verbergt.
<br /><br /><hr /><br />
<b>Om het ypslide menu meer te configureren, gebruik deze pagina.</b>
<br />
<br />
Je kan algemene instellingen instellen zoals positie, uitklap richting en ook het ontwerp van de stijl attributen.
<br />
<br />
Als je kiest om een configuratie te bewaren, ben je in staat het te hergebruiken wanneer het nodig is.
<br />
<b>Als je een configuratie bewaard met een thema naam, zal deze configuratie automatisch gebruikt worden voor alle gebruikers met dit thema geslecteerd (erg handig, als je ervoor kiest het usertheme menu te tonen)</b>
<br />
<br />
<b>Bijvoorbeeld :</b> je hebt 3 thema's voor je site : e107, example en nagrunium. Bewaar 3 configuraties met de volgende namen : e107, example en nagrunium en ze worden juist geladen voor elke gebruiker afhankelijk van het geselcteerde thema.
<br /><br />
Als je teveel met deze instellingen speelt en niet meer in staat bent een correcte layout te laden (geen configuraties bewaard), de-installeer en installeer het ypslide menu opnieu om de standaard waarden te laden.
Links worden niet verwijderd, alleen layout instellingen. ;)
<br />
Als je enkele configuraties opgeslagen hebt, kies een eerdere configuratie om te laden om een correcte layout te verkrijgen.
<br /><br />
Om meer hulp voor deze plugin te krijgen, go on <a href=\"http://www.touchatou.org\" >www.touchatou.org</a> (Lolo Irie Website), <a href=\"http://www.e107.org\" >www.e107.org</a> (Officiële e107 Website) of <a href=\"http://www.e107coders.org\" >e107coders.org</a> (Site voor e107 plugins).
<br /><br />
<a href=\"ypslidemenu_README.php\" ><b>Klik hier om het readme bestand te lezen !</b></a>");
define("ypslide_LAN37","Hoe het ypslide menu te configureren ");
define("ypslide_LAN38","Melding van de auteur van het originele script verplicht");
define("ypslide_LAN39","Ruimte (in pixels) tussen de linker border van de hoofd link en de linker border van het submenu Laat leeg voor 0 (nul) pixels.");
define("ypslide_LAN40","Ruimte (in pixels) tussen de top border van de hoofd link en de top border van het submenu. Deze waarde zal waarschijnlijk aangepast moeten worden als je stijl attributen van de hoofdbar configuratie wijzigt zoals de font grootte. Laat leeg for 0 (nul) pixels.");
define("ypslide_LAN41","Klik");
define("ypslide_LAN42","Plaats je eigen achtergrond afbeeldingen in de ypslide_menu/images folder.");
define("ypslide_LAN43","Kies afbeelding");
define("ypslide_LAN44","Klik hier om het beheer formulier te openen/sluiten");
define("ypslide_LAN45","Font Familie");
define("ypslide_LAN46","Font Grootte");
define("ypslide_LAN47","Huidig design<br /><b class=\"smalltext\" >(om wijzigingen te zien, update het formulier met een klik op de knop onderaan)</b>");
define("ypslide_LAN48","Hoofd Link");
define("ypslide_LAN49","SubLink");
define("ypslide_LAN50","Design SubLink Activeren");
define("ypslide_LAN51","Sublinks geactiveerd");
define("ypslide_LAN52","Tekst richting");
define("ypslide_LAN53","Als je deze stijl wilt maken met je eigen stijl attributen, wijzig dan het bestand ypslide_menu.php (regels 100 - 160 in versie 1.0 van de ypslide menu plugin)");
define("ypslide_LAN54","Icoon voor links met submenu?");
define("ypslide_LAN55","toon een icoon rechts van de link naam");
define("ypslide_LAN56","Andere stijl attributen");
define("ypslide_LAN57","PAS OP : Voor dit veld moet je attribuut namen en waarden invoeren exact zoals in een css bestand (bv: font-weight: bold;) en niet alleen de waarde zoals in de vorige velden !!!");
define("ypslide_LAN58","Opslaan/Laad/Verwijder Configuraties");
define("ypslide_LAN59","Aanpassen/Opslaan");
define("ypslide_LAN60","Geef een naam voor je huidige configuratie:");
define("ypslide_LAN61","Bestaande configuraties:");
define("ypslide_LAN62","Laad Nu");
define("ypslide_LAN63","Je huidige instellingen zijn succesvol opgeslagen.");
define("ypslide_LAN64","Nieuwe instellingen zijn nu ingesteld !");
define("ypslide_LAN65","Deze invoer is verwijderd:");
define("ypslide_LAN66","Verwijder een bestaande invoer:");
define("ypslide_LAN67","Verwijder Nu");
define("ypslide_LAN68","Opslaan van je huidige configuratie");
define("ypslide_LAN69","Laad of verwijder een configuratie");
define("ypslide_LAN70","Deze configuratie bestaat al, je gaat deze aanpassen of klik STOP het process nu en verander de naam");
define("ypslide_LAN71","Je configuratie instellingen zijn correct aangepast.");
define("ypslide_LAN72","1 selecteer de optie in het uitklap menu dat je wilt inladen of verwijderen<br >2 Klik op de knop Laad Nu om een opgeslagen configuratie te gebruiken of...<br />3 Vink de box aan om de verwijdering te bevestigen en klik op de knop Verwijder Nu om een opgeslagen configuratie te verwijderen");
define("ypslide_LAN73","Vink aan om te bevestigen");
define("ypslide_LAN74","Invoer NIET verwijderd, omdat je de box om te bevestigen niet aangevinkt hebt...");
define ("colpick_LAN1","Kleuren kiezer");
define ("colpick_LAN2","Klik op de kleur om te kiezen");
define ("colpick_LAN3","Klik om HTML codes van een kleurpaneel te verkrijgen");
define ("colpick_LAN4","");
define ("colpick_LAN5","");

define("SUB_TOUCHATOU_1","Registreer voor een link op Touchatou.org");
define("SUB_TOUCHATOU_2","Registreer je site op Touchatou.org?");
define("SUB_TOUCHATOU_3","Waarom registeren?");
define("SUB_TOUCHATOU_4","Als je deze box aanvinkt, wordt je site toegevoegd aan de <b>www.touchatou.org</b> lijst van sites die Lolo's plugin gebruiken/<br /><br />
Je kan je site registeren voor elke plugin die je gebruikt !!!
	<br /><br />
	Geen sites met illegale inhoud zullen worden gelinkt. Als je site porno, warez of dergelijke bevat, meld je niet aan aangezien je link wordt verwijderd!
	<br />
	<br />
	<b>Geen persoonlijke informatie zal worden verstuurd. We zullen je e107 username, en de naam, beschrijving en URL van je e107 site opnemen.</b>");
define("SUB_TOUCHATOU_5","Voeg nu toe!");
define("SUB_TOUCHATOU_6","Je moet de box aanvinken voor het versturen!");
define("SUB_TOUCHATOU_7","Je site is aangemeld!<br /><br />Deze optie zal vanaf nu onzichtbaar zijn.");
define("SUB_TOUCHATOU_8","Bericht");
define("SUB_TOUCHATOU_9","Steun op Touchatou");
define("SUB_TOUCHATOU_10","<a href=\"http://touchatou.org/forum.php\" >Plaats in Touchatou's forum</a> om help te krijgen.
<br />
<br />
<br />
Op Touchatou vind je andere e107 gerelateerde pagina's:
<br />
- De Webring (Als je nog niet aangemeld bent, wachten we op je...) en het te installeren menu op je site voor e107 versie5+ of versie6+.<br />
- Flash demos (Voor beginners, om te leren hoe je e107 installeert of je eerste plugin codeert)<br />
- Nieuwserichten van e107.org, e107coders.org en e107themes.org om alles in een keer te weten<br />
- Alle plugin van Lolo (info+download) : eChat, eChess, eContact, eCountdown, eGoogle, eNewsletter, ePreview eQuizz, eTellAFriend<br />
- Als e107coders.org of e107themes.org uit de lucht zijn, zal je de meeste plugins en thema's kunnen downloaden. Aleen te gebruiken bij noodzaak, omdat ze niet zijn gesorteerd !<br />
<br />
En andere pagina's over live muziek, mijn hoofd hobby !!!
<br />
<b>Ik heb een grote interesse om muzikale ervaring en informatie over regionale bands met je te delen.<b><br />
Laat me je favoriete muziek weten, plaats het in het forum... ;)
<br />
Bedankt voor je interesse.
");

?>