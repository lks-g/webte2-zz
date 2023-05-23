<?php
$lang = array(
    // Page Titles
    'title1' => 'Prihlásenie',
    'title2' => 'Domovská stránka učiteľa',
    'title3' => 'Generovanie príkladov',
    'title4' => 'Prehľad hodnotenia študentov',
    'title5' => 'Profil študenta',
    'title6' => 'Návod na použitie',
    'title7' => 'Registrácia',
    
     // Login page
    'login' => 'Prihlásenie',
    'username' => 'Používateľské meno',
    'email' => 'Email',
    'username_placeholder' => 'Zadajte používateľské meno',
    'password_placeholder' => 'Zadajte heslo',
    'email_placeholder' => 'Zadajte email',
    'password' => 'Heslo',
    'submit' => 'Prihlásiť sa',
    'register-text' => 'Registrácia',
    'noAccount' => 'Nemáte účet?',
    'register' => 'Zaregistruj sa',
    'logout' => 'Odhlásiť sa',

    // Register page
    'register-title' => 'Registrácia',
    'nick' => 'Prezývka',
    'aisID' => 'AIS ID',
    'aisIDError' => 'Ais ID už je zabraté, alebo je zle zadané.',
    'aisIDLengthError' => 'Ais ID musí mať 6 znakov.',
    'emailError' => 'Email už je zabratý, alebo je zle zadaný.',
    'retryPassword' => 'Zopakujte heslo',
    'passError' => 'Heslá sa nezhodujú.',
    'student' => 'Študent',
    'teacher' => 'Učiteľ',
    'haveAcc' => 'Máte už účet?',
    'loginNow' => 'Prihláste sa',

     // Teacher home page
    'header' => 'Domovská stránka učiteľa',
    'menu1' => 'Zadávanie úloh',
    'menu2' => 'Prehľad hodnotenia študentov',
    'menu3' => 'Návod na použitie',
    'welcome' => 'Vitajte na domovskej stránke ',
    'rights' => '© 2023 - Domovská stránka učiteľa.',
    'session-info' => 'Ste prihlásený ako ',

    // Generator
    'file-input' => 'Vyberte LaTeX súbor:',
    'problem-count' => 'Zadajte počet príkladov:',
    'generator' => 'Generovať príklady z LaTeX súboru',
    'problem' => 'Príklad',
    'solution' => 'Riešenie',
    'select' => 'Vybrať súbor',
    'generate' => 'Generovať',
    'sets' => 'Testy',
    'asgName' => 'Názov testu',
    'asgDateStart' => 'Dátum od kedy',
    'asgDateEnd' => 'Dátum do kedy',
    'asgPoints' => 'Počet bodov',
    'deleteBtn' => 'Vymazať',
    'asgNoSetsError' => 'Nenašli sa žiadne testy.',
    'asgInsertError' => 'Test s rovnakými detailami už existuje.',
    'asgInsertSuccess' => 'Test bol úspešne vytvorený.',
    'dbsFileTitle' => 'Súbory v databáze',
    'fileName' => 'Názov súboru',
    'fileUploadDate' => 'Dátum nahratia',
    'fileActions' => 'Akcie',
    'showBtn' => 'Zobraziť',
    'noFilesError' => 'Nenašli sa žiadne súbory v databáze.',
    'connFail' => 'Pripojenie k databáze zlyhalo',
    'createAsg' => 'Vytvoriť test',
    'selectSet' => 'Vybrať sadu',
    'create' => 'Vytvoriť',
    'texOnly' => 'Povolené sú iba .tex súbory',
    'uploadMsg' => 'Prosím nahrajte súbor',

    // Overview
    'firstName' => 'Meno',
    'lastName' => 'Priezvisko',
    'studentID' => 'ID študenta',
    'setName' => 'Názov testu',
    'tests' => 'Vygenerované príklady',
    'points' => 'Body',
    'exportCSV' => 'Exportovať do CSV',
    'exportPDF' => 'Exportovať do PDF',
    'goBack' => 'Späť',

    //Tutorial page
    'tutorial' => 'Návod na použitie',
    'tSite' => 'Stránka učiteľa',
    'teacher-tuto' => 'Domovská stránka pre učiteľa je rozdelená do troch podstránok. Prvá podstránka s názvom "Zadávanie úloh" umožňuje učiteľovi nahrať LaTeX súbor s príkladmi, ktoré sú štruktúrované ako priložené LaTeX súbory blokovkaXXpr.tex alebo odozvaXXpr.tex. Po nahratí týchto súborov sa údaje o príkladoch uložia do databázy a učiteľovi sa zobrazí ukážka každého príkladu zo súboru pre kontrolu správnosti nahratých údajov. Po nahratí súboru je možné vytvoriť testy pre študentov. To funguje tak, že učiteľ si vyberie súbor príkladov z databázy, z ktorých chce danú sadu vytvoriť. Ďalej si zvolí názov testu, určí dátum od kedy do kedy má byť tento test dostupný a počet bodov, ktoré môže študent za tento test získať. Na záver stačí kliknúť na tlačidlo "Vytvoriť" a test bude vytvorený a uložený do databázy. Informácie o všetkých súboroch v databáze a vytvorené testy sa zobrazujú v tabuľkách <strong>Testy</strong>  a  <strong> Súbory v databáze</strong> spolu s možnosťou vymazať tieto údaje z databázy. <strong> Po nahratí je potrebné stránku obnoviť aby sa aktualizovali tabuľky. </strong>. Ďalšia podstránka s názvom "Prehľad hodnotenia študentov" obsahuje tabuľku všetkých študentov, ktorú je možné usporiadať podľa mena, priezviska, id a počtu bodov z testov, pričom je možné kliknúť na ľubovoľného študenta pre zobrazenie jeho osobného profilu, kde sa zobrazia informácie o všetkých testoch ktoré písal a body ktoré z nich získal. Pod tabuľkou je tlačidlo ktoré umožňuje exportovať tieto údaje do CSV súboru.',
    'sSite' => 'Stránka študenta',
    'student-tuto' => 'Domovská stránka pre študenta je rozdelená do troch podstránok. Prvá podstránka s názvom Vygeneruj príklad umožňuje študentovi vygenerovať príklad zo sady úloh ktorú zadal učiteľ(z každej sady sa vygeneruje náhodne 1 príklad). Po vygenerovaní príkladov ich študent vidí na stránke spoločne s grafickým matematickým editorom kde môže zadávať svoje výsledky, ak študent vyplní odpovede na všetky úlohy, môže stlačiť tlačídlo v spodnej časti stránky "Odoslať odpovede" s ktorým odošle odpovede, ktoré musí následne potvrdiť v prehľade jeho príkladov. Dalšia podstránka je Prehľad príkladov, kde študent vidí svoje ID, meno testu, koľko môže získať bodov za test, koľko získal bodov za test, či uz potvrdil odoslanie výsledkov a jeho výsledok, ak študent potvrdí odoslanie výsledkov, autimaticky sa vyhodnotí výsledok a študentovi v tabulke pribudnú body za príklad a submitted sa nastaví na  "true"',
    'problemsTuto' => 'Ako riešiť príklady',

    // Student home page
    'student-title' => 'Domovská stránka študenta',
    'student-asgTitle' => 'Testy',
    'generateAssignments' => 'Vygeneruj príklad',
    'assignmentsOverview' => 'Prehľad príkladov',
    'student-homepage' => 'Domovská stránka študenta',
    'student-welcome' => 'Vitajte na domovskej stránke študenta ',
    'loggedIn' => 'Ste prihlásený ako ',
    'student-role' => 'pod rolou ',
    'student-rights' => '© 2023 - Domovská stránka študenta',
    'generate-title' => 'Študentská stránka pre generovanie príkladov',
    'generate-click' => 'Kliknite na tlačidlo "Vygeneruj príklad" pre vygenerovanie príkladu.',
    'taskFrom' => 'Príklad z testu ',
    'sendAnswer' => 'Odošli odpoveď',
    'randomTask' => 'Náhodný príklad z $setName a $fileName',
    'noTaskFound' => 'Nenašiel sa žiadny príklad v LaTeX súbore.',
    'noAsgFound' => 'Nenašiel sa žiadny test v databáze.',
    'logOut' => 'Odhlásiť sa',
    'active' => 'Vaše pridelené zadania:',
    'tID' => 'ID',
    'tName' => 'Názov testu',
    'tMaxPoints' => 'Maximálny počet bodov',
    'tPoints' => 'Počet získaných bodov',
    'tSubmitted' => 'Odoslané',
    'tResult' => 'Vaša odpoveď',
    'submitAsg' => 'Odoslať moje odpovede',
    'tasksForId' => 'Tasks for Student ID: ',
    'inputTxt' => 'Zadajte svoje odpovede do editorov a potom odošlite všetky odpovede v dolnej časti stránky.',
    'noSetnames' => 'Nenašiel sa žiadny test v databáze.',

    //Asnwers overview
    'testingTitle' => 'Prehľad príkladov',
    'resp' => 'Odozva',
    'answer' => 'Odpoveď',
    'compare' => 'Porovnať',
    'backToHomepage' => 'Späť na domovskú stránku',
    'correct' => 'Správne',
    'incorrect' => 'Nesprávne',
    'menu4' => 'Testovanie príkladov',
);
