<?php
/*
+---------------------------------------------------------------+
|	e107 website system					|
|	language file: Swedish					|
|								|
|	�Steve Dunstan 2001-2002				|
|	http://jalist.com					|
|	stevedunstan@jalist.com					|
| 	Transalation by TheFrog					|																						|
|	Updated 2003-06-12 by Kimmo
|								|
|	Released under the terms and conditions of the		|
|	GNU General Public License (http://gnu.org).		|
+---------------------------------------------------------------+
*/

setlocale(LC_ALL, 'swedish');

//articles.php/comment.php
define(LAN_0, "[blockerad av administrat�ren]");
define(LAN_1, "�ppna");
define(LAN_2, "Blockera");
define(LAN_3, "Ta bort");
define(LAN_4, "Information");
define(LAN_5, "Kommentarer ...");
define(LAN_6, "Du m�ste vara inloggad f�r att f� posta kommentarer p� denna sida - logga in, eller om du inte �r registrerad klicka <a href=\"signup.php\">h�r</a> f�r att registrera dig som medlem");
define(LAN_7, "Namn: ");
define(LAN_8, "Kommentar");
define(LAN_9, "Skicka kommentar");
define(LAN_10, "Till�tna <em>taggar</em>: [b] [i] [u] [img] [center] [link]<br />anv�nd [link=l�nk text] l�nk url [/link] f�r l�nkar<br />Nya rader (&lt;br /&gt;) l�ggs automatiskt till.");

//chat.php
define(LAN_11, "Chatbox (alla poster)"); //Hard to transalate..
define(LAN_12, "Chat Poster"); //Hard to transalate..

//class.php
define(LAN_13, "Nyheten borttagen.");
define(LAN_14, "Nyheten uppdaterad i databasen.");
define(LAN_15, "Nyheten inlaggd i databasen.");
define(LAN_16, "Anv�ndarnamn: ");
define(LAN_17, "L�senord: ");
define(LAN_18, "Logga in");
define(LAN_19, "Kunde inte skriva till filen news.xml p� servern, var god och s�kerst�ll att php har r�ttigheter att skriva till katalogen /backend (permissions 666 under UNIX/Linux)");
define(LAN_20, "Fel");
define(LAN_21, "Bes�kare p� denna sida idag: "); //Any other ideas??
define(LAN_22, "Bes�kare p� denna sida n�gonsin: "); //Any other ideas??
define(LAN_23, "Totalt antal bes�kare p� siten: ");
define(LAN_24, "knulla|skit|fitta|kuk"); //This is strictly language dependent...
define(LAN_25, "F�reg�ende sida");
define(LAN_26, "N�sta sida");

//forum.php
define(LAN_27, "Du l�mnade n�gra obligatoriska f�lt tomma");
define(LAN_28, "�hm, du postade ingenting...");
define(LAN_29, "Redigerad");
define(LAN_30, "V�lkommen");
define(LAN_31, "Det finns inga nya poster ");
define(LAN_32, "Det finns 1 ny post ");
define(LAN_33, "Det finns");
define(LAN_34, "nya poster");
define(LAN_35, "sedan ditt senaste bes�k.");
define(LAN_36, "Du var sist h�r ");
define(LAN_37, "Nu �r det ");
define(LAN_38, ", alla tider ges i GMT.");
define(LAN_39, "totalt �mnen");
define(LAN_40, "totalt poster.");
define(LAN_41, "Senaste medlemmen: ");
define(LAN_42, "Registerade medlemmar: ");
define(LAN_43, "Dessa forum kan l�sas, och det g�r att g�ra inl�gg, av icke-registrerade anv�ndare, men om du �nskar att se information relaterad om nya poster, redigera/ta bort dina poster osv. m�ste du vara <a href=\"signup.php\">registerad</a> och inloggad.");
define(LAN_44, "Dessa forum kan l�sas, och det g�r att g�ra inl�gg, av icke-registrerade anv�ndare, din ip adress och host kommer att registreras.");
define(LAN_45, "Dessa forum kan endast l�sas och debatteras i av registrerade anv�ndare, klicka <a href=\"signup.php\">h�r</a> f�r att registrera dig.");
define(LAN_46, "Forum");
define(LAN_47, "Tr�dar");
define(LAN_48, "Svar");
define(LAN_49, "Senaste Posten");
define(LAN_50, "Moderatorer");
define(LAN_51, "Inga forum �nnu, kom tillbaka snart.");
define(LAN_52, "Inga forum i denna sektion �nnu, kom tillbaka snart.");
define(LAN_53, "Tr�d");
define(LAN_54, "Startad av"); //I need to use e107 to transalate here..
define(LAN_55, "Svar");
define(LAN_56, "L�sningar"); //No good swedish word for this...
define(LAN_57, "Senaste posten");
define(LAN_58, "Det finns inga �mnen i detta forum �nnu.");
define(LAN_59, "Du m�ste vara en registrerad anv�ndare, och inloggad f�r att posta i detta forum. Klicka <a href=\"signup.php\">h�r</a> f�r att registrera dig, eller logga in via loginmenyn.");
define(LAN_60, "Starta en ny tr�d");
define(LAN_61, "Ditt namn: ");
define(LAN_62, "�mne: ");
define(LAN_63, "Post: ");
define(LAN_64, "Skicka ny tr�d");
define(LAN_65, "Administrat�r detekterad - moderation p�");
define(LAN_66, "Denna tr�d �r nu st�ngd");
define(LAN_67, "poster");
define(LAN_68, "redigera");
define(LAN_69, "ta bort");
define(LAN_70, "flytta");
define(LAN_71, "Inga svar.");
define(LAN_72, "Ursprungligen postad av");
define(LAN_73, "Svar: ");
define(LAN_74, "Svara p� tr�d");
define(LAN_75, "Skicka svar");
define(LAN_76, "Svara");
define(LAN_77, "Uppdatera tr�d");
define(LAN_78, "Uppdatera svar");
define(LAN_79, "Nya poster");
define(LAN_80, " Inga nya poster");
define(LAN_81, "St�ngd tr�d");

//index.php
define(LAN_82, "Nyheter - Kategori");
define(LAN_83, "Inga nyheter �nnu - kom tillbaka snart.");
define(LAN_84, "Nyheter");

//links.php
define(LAN_85, "Inga l�nkar �nnu.");
define(LAN_86, "Kategori:");
define(LAN_87, "Knappar f�r");
define(LAN_88, "H�nvisningar:");
define(LAN_89, "Admininstrat�r: ");
define(LAN_90, "l�gg till en ny l�nk i denna kategori");
define(LAN_91, "l�gg till en ny kategori");

//oldpolls.php
define(LAN_92, "Gamla omr�stningar");
define(LAN_93, "Inga gamla omr�stningar �nnu.");
define(LAN_94, "Postad av");
define(LAN_95, "Totalt antal r�ster:");
define(LAN_96, "Omr�stningar");

//search.php
define(LAN_97, "Inga tr�ffar.");
define(LAN_98, "Nyheter");
define(LAN_99, "Kommentarer");
define(LAN_100, "Artiklar");
define(LAN_101, "Chatbox");
define(LAN_102, "L�nkar");
define(LAN_103, "Forum");

//signup.php
define(LAN_104, "Detta anv�ndarnamn existerar redan i databasen, v�lj ett annat anv�ndarnamn.");
define(LAN_105, "L�senorden �r inte samma, skriv in dem p� nytt.");
define(LAN_106, "Det verkar inte vara en riktig e-post address, var god och skriv in en riktig e-postaddress.");
define(LAN_107, "Tack! Du �r nu en registrerad medlem av ".SITENAME.", skriv ner ditt namn och l�senord eftersom de inte g�r att f� tillbaka om du gl�mmer bort dem.<br /><br />Du kan nu logga in fr�n loginboxen.");
define(LAN_108, "Registreringen klar");
define(LAN_109, "Dessa sidor f�ljer <em>The Children's Online Privacy Protection Act fr�n 1998 (COPPA)</em> och kan d�rf�r inte acceptera registreringar fr�n anv�ndare yngre �n 13 �r utan en skriftlig till�telse fr�n deras f�rmyndare. F�r ytterligare information kan du l�sa mer <a href=\"http://www.cdt.org/legislation/105th/privacy/coppa.html\">h�r</a>. Kontakta administrat�ren <a href=\"mailto:".SITEADMINEMAIL."\">h�r</a> om du beh�ver ytterligare assistans.<br /><br /><div style=\"text-align:center\"><b>Om du �r �ldre �n 13 �r klicka <a href=\"signup.php?stage1\">h�r</a> f�r att forts�tta.");
define(LAN_110, "Registrering");
define(LAN_111, "Ange l�senordet igen: ");
define(LAN_112, "E-post: ");
define(LAN_113, "D�lj e-post?: ");
define(LAN_114, "(Detta kommer att hindra andra fr�n att se din e-post address)");
define(LAN_115, "ICQ nummer: ");
define(LAN_116, "AIM Nickname: ");
define(LAN_117, "MSN Nickname: ");
define(LAN_118, "F�delsedag: ");
define(LAN_119, "Vart befinner du dig?: ");
define(LAN_120, "Signatur: ");
define(LAN_121, "Bild: ");
define(LAN_122, "Tidszon:");
define(LAN_123, "Registrera");

//stats.php
define(LAN_124, "Totalt unika bes�kare: ");
define(LAN_125, "Totalt antal bes�kare: ");
define(LAN_126, "Unika bes�k per sida: ");
define(LAN_127, "Totalt bes�k per sida: ");
define(LAN_128, "Webl�sare: ");
define(LAN_129, "Operativsystem: ");
define(LAN_130, "Bes�k fr�n land/dom�n: ");
define(LAN_131, "H�nvisningar: ");
define(LAN_132, "Statistik");

//submitnews.php
define(LAN_133, "Tack");
define(LAN_134, "Din post har sparats och kommer att ses �ver av en av sitens administrat�rer snart.");
define(LAN_135, "Nyhet: ");
define(LAN_136, "Skicka nyhet");

//user.php
define(LAN_137, "Det finns ingen information om den anv�ndaren eftersom han/hon inte �r registrerad �n");
define(LAN_138, "Registrerade medlemmar: ");
define(LAN_139, "Ordning: ");
define(LAN_140, "Registrerade medlemmar");
define(LAN_141, "Inga registrerade medlemmar �nnu.");
define(LAN_142, "Medlem");
define(LAN_143, "[dold enligt �nskem�l]");
define(LAN_144, "Hemdisa: ");
define(LAN_145, "Registrerad: ");
define(LAN_146, "Bes�k sedan registreringen: ");
define(LAN_147, "Poster i Chatboxen: ");
define(LAN_148, "Antal kommentarer: ");
define(LAN_149, "Poster i forum: ");

//usersettings.php
define(LAN_150, "Inst�llningarna uppdaterade.");
define(LAN_151, "OK");
define(LAN_152, "Nytt l�senord: ");
define(LAN_153, "Ange det nya l�senordet igen: ");
define(LAN_154, "Uppdatera inst�llningarna");
define(LAN_155, "Uppdatera anv�ndarens inst�llningar");
define(LAN_185, "Du fyllde inte i l�senordet,");

//plugins
define(LAN_156, "Skicka");
define(LAN_157, "�terst�ll");
define(LAN_158, "Inga meddelanden �nnu.");
define(LAN_159, "Visa alla poster");
define(LAN_160, "Webmaster: ");
define(LAN_161, "Rubriker");
define(LAN_162, "Ingen aktiv omr�stning.");
define(LAN_163, "Skicka r�st");
define(LAN_164, "R�ster: ");
define(LAN_165, "Gamla omr�stningar");

//menus
define(LAN_166, "Inga artiklar �nnu.");
define(LAN_167, "Artiklar");
define(LAN_168, "V�ra rubriker kan du komma �t antingen via v�r rss eller text filer."); //This is badly done I know...
define(LAN_169, "Backend"); //There is no Swedish word for this
define(LAN_170, "F�ljer W3C standarden"); //...
define(LAN_171, "Anv�ndarid icke godk�nnt (kan vara en korrupt kaka (cookie)).<br /><a href=\"index.php?logout\">Klicka h�r</a> f�r att nollst�lla kakan.");
define(LAN_172, "Logga ut");
define(LAN_173, "Fel vid inloggning");
define(LAN_174, "Bli medlem"); //FIX
define(LAN_175, "Logga in"); //Might be changed to Logga in
define(LAN_176, "Anv�ndare p� denna sida: ");
define(LAN_177, "Anv�ndare p� siten: ");
define(LAN_178, "Inloggade anv�ndare: ");
define(LAN_179, "Online");
define(LAN_180, "S�k");
define(LAN_181, "L�nka till oss");
define(LAN_182, "Chatbox");
define(LAN_183, "Huvudmeny");
define(LAN_184, "Omr�stning");

// #### Added in v5 #### //

define(LAN_186, "Skicka nyhet");
define(LAN_187, "E-postaddress att skicka till");
define(LAN_188, "Jag tror att du kunde vara intresseard av denna nyhet fr�n");
define(LAN_189, "Powered by"); //No translation exist..
define(LAN_190, "Recension");
define(LAN_191, "Information");
define(LAN_192, "Anv�ndarna av detta forum har postat totalt ");
define(LAN_193, "Forum moderator");
define(LAN_194, "G�st");
define(LAN_195, "Registrerad medlem");
define(LAN_196, "Du har l�st ");
define(LAN_197, " av dessa poster.");
define(LAN_198, " Alla nya poster har l�sts.");
define(LAN_199, "Markera alla poster som l�sta");
define(LAN_200, "st�ng denna tr�d");
define(LAN_201, "�ter�ppna denna tr�d");
define(LAN_202, "<em>Sticky</em> tr�d");
define(LAN_203, "<em>Sticky</em>/St�ngd tr�d");
define(LAN_204, "Du <b>kan</b> starta nya tr�dar");
define(LAN_205, "Du <b>kan inte</b> starta nya tr�dar");
define(LAN_206, "Du <b>kan</b> posta svar");
define(LAN_207, "Du <b>kan inte</b> posta svar");
define(LAN_208, "Du <b>kan</b> redigera dina poster");
define(LAN_209, "Du <b>kan inte</b> redigera dina poster");
define(LAN_210, "Du <b>kan</b> ta bort dina poster");
define(LAN_211, "Du <b>kan inte</b> ta bort dina poster");
define(LAN_212, "Gl�mt l�senordet?");
define(LAN_213, "Anv�ndarnamnet/e-postadressen hittades inte i databasen.");
define(LAN_214, "Kunde inte �terst�lla l�senordet");
define(LAN_215, "Ditt l�senord p� ".SITENAME." har �terst�llts. Ditt nya l�senord �r\n\n");
define(LAN_216, "G� till f�ljande url f�r att validera ditt nya l�senord...");
define(LAN_217, "Tack, ditt nya l�senord �r nu validerat. Du kan nu logga in med ditt nya l�senord.");
define("LAN_281", "G�ster: ");

define(LAN_300, "Anv�ndarnamnet finns inte i databasen.<br /><br />");
define(LAN_301, "Fel l�senord.<br /><br />");
define(LAN_302, "Du har inte aktiverat ditt konto. Du borde ha f�tt ett mail med instruktioner om hur du aktiverar ditt konto, om inte kontakta en administrat�r.<br /><br />");
define(LAN_303, "Denna Nyhet �r fr�n ");
define(LAN_304, "Artikeltitel: ");
define(LAN_305, "Underrubrik: ");
define(LAN_306, "Denna artikel �r fr�n ");
define(LAN_307, "Totalt antal poster i den h�r kategorin: ");
define(LAN_308, "Riktigt namn: ");
define(LAN_309, "Mata in dina uppgifter h�r - <b>ett aktiveringsmail kommer att bli skickat till den e-post address du skriver h�r, </b>vill du inte visa din e-post address p� den h�r sidan klickar du i g�m min mail.");
define(LAN_310, "Kan inte acceptera post, namnet �r redan registrerat - om det �r ditt namn, logga in f�r att posta.");
define(LAN_311, "Anonym");
define(LAN_312, "Dubbelpost - Kan inte acceptera.");
define(LAN_313, "V�lj vilken lista du vill visa...");
define(LAN_314, "Klasser: ");
define(LAN_315, "Anv�ndare: ");
define(LAN_316, "G� till sidan ");
define(LAN_317, "Inga");
define(LAN_318, "moderator optioner: ");
define(LAN_319, "Ta bort kladdig");
define(LAN_320, "Kladdig");
define(LAN_321, "Moderatorer: ");
define(LAN_322, "Postad: ");
define(LAN_323, "F�rhandsvisa");
define(LAN_324, "Ditt meddelande har blivit postat.");
define(LAN_325, "Klicka h�r f�r att visa ditt meddelande");
define(LAN_326, "Klicka h�r f�r att �terg� till forumet");
define(LAN_327, "Recension");
define(LAN_328, "Inst�llningar");   // "Settings" as used in default-header.
define(LAN_329, "Auto Login"); // Auto Login

define("LAN_350", "OK");
define("LAN_351", "V�lj tema");
define("LAN_352", "OK");
define("LAN_353", "V�lj spr�k");
define("LAN_354", "(�tkomst nekad)" );
define("LAN_355", "Inga filer i denna kategori finns tillg�ngliga" );
define("LAN_356", "Total nerladdningsstorlek: " );
define("LAN_357", "Filer nerladdade: " );
define("LAN_358", "Tillg�ngliga filer: " );
define("LAN_359", "Ranka denna download" );
define("LAN_360", "Tack f�r att du r�stade!" );
define("LAN_361", "downloads fr�n" ); 
define("LAN_362", "filer" );
define("LAN_363", "Downloads" );
define("LAN_364", "Sortera efter" );
define("LAN_365", "Datum" );
define("LAN_366", "Filstorlek" );
define("LAN_367", "Download" );
define("LAN_368", "Inga filer tillg�ngliga, kom tillbaka senare!" );
define("LAN_369", "Inte rankat �n");
define("LAN_370", "Rankning: ");
define("LAN_371", "Loggning �r inte aktiverat �n, g� till admin sidan, klicka p� Logger och Activate Logging/Counter rutan.");
define("LAN_372", "Egenskaperna p� denna sida �r inaktiverade.");
define("LAN_373", "Har inte startat �n");
define("LAN_374", "Loggning startade:");
define("LAN_375", "Visa alla");
define("LAN_376", "Senaste 10 bes�karna"); 
define("LAN_377", "alla");
define("LAN_378", "top 10");
define("LAN_379", "Sk�rm uppl�sningar");
define("LAN_380", "Om du �nskar att bli notifierad via e-post n�r ett svar postas under detta inl�gg, bocka d� i denna ruta");
define("LAN_381", "Du har mottagit ett svar p� ditt forum inl�gg hos ");
define("LAN_382", "Post skickad: ");
define("LAN_383", "Klicka p� l�nken f�r att l�sa hela tr�den...");
define("LAN_384", "Forum svar fr�n ");
define("LAN_385", "Posta: ");
define("LAN_386", "Om du �nskar att aktivera en omr�stning f�r ditt inl�gg, l�mna d� detta f�lt tomt ");
define("LAN_387", "Du m�ste vara medlem samt inloggad f�r att kunna r�sta.");
define("LAN_388", "Popul�r tr�d");


define("LAN_389", "F�reg�ende tr�d");
define("LAN_390", "N�sta tr�d");
define("LAN_391", "Sp�ra denna tr�d");
define("LAN_392", "Sluta sp�ra denna tr�d"); // Track

define("LAN_393", "Lista sp�rade tr�dar");
define("LAN_394", "St�ngt forum");
define("LAN_395", "[popul�r]");
define("LAN_396", "Tillk�nnagivande"); // Announcement
define("LAN_397", "Sp�rade tr�dar");


define("LAN_398", "Ingen sammanfattning.");
define("LAN_399", "Forts�tt");
define("LAN_400", "An�ndarnamn och l�senord �r <b>case-sensitive</b>");
define("LAN_401", "L�mna blankt f�r att beh�lla nuvarnade l�senord");

define("LAN_402", "Du m�ste vara registrerad medlem f�r att kunna ladda upp filer p� denna server.");
define("LAN_403", "Du har inte r�ttigheter att ladda upp filer till denna server.");
define("LAN_404", "Tack. Din uppladdning kommer att granskas av en administrat�r och eventuellt postas p� denna site.");
define("LAN_405", "Filen �verskrider till�ten filstorlek - raderad.");
define("LAN_406", "<b>Notera</b><br />Till�tna filtyper:");
define("LAN_407", "�vriga filtyper som laddas upp kommer att raderas omedelbart.");
define("LAN_408", "<u>Understrykna</u> f�lt m�ste fyllas i");

define("LAN_409", "Filnamn");
define("LAN_410", "Version");
define("LAN_411", "Fil");
define("LAN_412", "Sk�rmdump");
define("LAN_413", "Beskrivning");


define("LAN_414", "Fungerande demo");
define("LAN_415", "skriv in webadressen d�r en demo kan ses");
define("LAN_416", "Skicka och ladda upp");
define("LAN_417", "Ladda upp fil");


?>
