# Assignment 

# WEBTE2 - Záverečné zadanie - LS 2022/2023

# 1\. Všeobecné pokyny

*•* Projekty sa budú robiť **po štyroch**, pričom je potrebné,
aby ste si úlohy rozdelili rovnomerne.
*•* Zadanie je potrebné odovzdať spakované do prostredia MS Teams
najneskôr do 18.5.2023 (23:55) **jedným členom** tímu. Neskoršie
odovzdanie projektu bude penalizované\
1 bodom za každý deň omeškania na každého člena tímu.

*•* Pre účely ukončenia predmetu je potrebné mať celý projekt
umiestnený **na školskom serveri**.

*•* Pri odovzdávaní do MS Teams, uveďte do poznámky adresu
stránky na školskom serveri.*•* Stránka musí byť optimalizovaná
pre **Chrome a Firefox**.

**plagiátorstvo** v rozsahu viac ako 10 riadkov kódu (cca 200
znakov), tak to automaticky*•* Odporúčanie: pracujte s Internetom,
inšpirujte sa rôznymi stránkami. Ak na to však prídeme znamená to 0
bodov zo záverečného zadania, čo má za následok známku FX z
predmetu.

*•* Ak pri prípadnej obhajobe člen tímu nebude vedieť
zodpovedať na otázku, ako naprogramoval danú časť, považuje sa
to za nesplnené.

*•* Pekne vypracované projekty, resp. ich časti, môžu byť
zverejnené verejnosti.

# 2\. Zadanie

Úlohou je naprogramovať aplikáciu, ktorá umožní náhodné
vygenerovanie matematických príkladov študentovi a neskoršiu
kontrolu ich výsledkov.

Nezabudnite na to, že sa hodnotí aj grafický dizajn vytvorenej
aplikácie, vhodne navrhnuté členenie, ľahkosť orientácie v
prostredí. Pamätať by ste mali aj na zabezpečenie celej
aplikácie. Na vypracovanie projektu je možné použiť už aj PHP
framework.

Vytvorená aplikácia bude spĺňať aj nasledovné požiadavky:
**\(1\)** Pri práci na projekte je potrebné používať verzionovací
systém, napr. github", gitlab", bitbucket".

**\(2\)** Vytvorená webstránka bude navrhnutá ako dvojjazyčná
(slovenčina, angličtina).

*Pozn.*: ak sa prepínate medzi jazykmi, musíte zostať na tej istej
stránke ako ste boli pred prepnutím a nie vrátiť sa na domovskú
stránku aplikácie.

**\(3\)** Celá stránka bude responzívna vrátane použitej grafiky.

**\(4\)** Aplikácia bude vyžadovať 2 typy rolí: študent a učiteľ.

**\(5\)** Po prihlásení sa, študent bude mať k dispozícii dve
funcionality:\

*•* vygenerovanie príkladov na riešenie, *•* prehľad zadaných
úloh (t.j. spolu s možnosťou odovzdania ich riešenia. Z prehľadu
bude aj jasné, ktoré úlohy úloh, ktoré boli vygenerované pre
daného študenta)

už boli odovzdané a ktoré nie.

Každú úlohu je možné generovať a aj odovzdávať samostatne.

**\(6\)** Generovanie úloh bude robené na základe latexových súborov,
ktoré sú prílohou zadania. *•* Počet súborov nie je vopred daný,
to znamená, že ak do aplikácie bude pridaný ďaľší latexový
súbor, aplikácia to musí vedieť ošetriť a spracovať aj ten.

*•* Latexový súbor sa môže odvolávať na obrázky, ktoré je
treba tiež vedieť do aplikácie načítať. Forma ich načítania a
spracovania nie je v zadaní definovaná, t.j. závisí od vašich
individuálnych preferencií.

*•* Každý súbor predstavuje sadu príkladov (počet príkladov v
súbore nie je vopred daný), z ktorej môže byť študentovi
náhodne vygenerovaný 1 príklad na riešenie.

*•* Učiteľ bude mať možnosť definovať, z ktorých latexových
súborov si bude môcť študent generovať príklady na riešenie a v
ktorom období si ich bude môcť generovať.

Každá sada príkladov môže mať iný dátum, kedy môže byť
použitá. Ak dátum nebude určený, tak generovanie príkladov z
tejto sady je otvorené.

*•* Z učiteľom vymedzenej skupiny súborov si študent bude mať
možnosť zvoliť, z ktorých súborov chce mať vygenerované
príklady (môže si vybrať jeden súbor, ale aj všetky).

**\(7\)** Odovzdanie úlohy spočíva v napísaní odpovede, ktorá bude
vo väčšine prípadov vo forme matematického výrazu (napr. zlomok,
diferenciálna rovnica, \...).

Na zápis odpovede použite niektorý z dostupných nástrojov na
Internete, napr. matematický editor
http://camdenre.github.io/src/app/html/EquationEditor\

**\(8\)** Správnosť odpovede je potrebné skontrolovať voči výsledku,
ktorý je zadaný v dodanom latexovom súbore. Treba si však
uvedomiť, že výsledok, ktorý zadá študent, nemusí byť vždy

presne v tom istom formáte ako je ten, ktorý je zapísaný v          
súbore. Napr.    

$$
\frac{3}{4}
$$

je to isté ako 0.75 a

$$
\frac{2s + 1}{6s + 4}
$$

je to isté ako

$$
\frac{s + 0.5}{3s + 2}
$$ 

alebo

$$
\frac{0.5s + 0.25}{1.5 + 1}.
$$

V prípade potreby zaokrúhľovania kvôli kontrole výsledkov,          
zaokrúhľujte na 4 desatinné miesta.                                
                                                                    
Na vyhodnotenie správnosti odpovede je možné použiť nejakú          
voľne dostupnú knižnicu alebo dokonca aj voľne dostupný CAS         
(Computer Aided System) ako je napríklad Maxima alebo Octave. V     
takom prípade si je ho potrebné nainštalovať na server.             
                                                                    
**\(9\)** Učiteľ bude mať možnosť okrem funkcionality definovanej       
v bode č.6:                                                       
*•* zadefinovať, koľko bodov môže študent získať, za ktorú          
sadu príkladov (všetky príklady zadefinované v jednom súbore        
budú mať rovnaké hodnotenie, t.j. toto hodnotenie bude mať aj       
príklad vygenerovaný pre študenta).                                 
                                                                    
*•* si prezerať prehľadnú tabuľku všetkých študentov (meno,         
priezvisko, ID študenta) s informáciou, koľko úloh si ktorý         
študent vygeneroval, koľko ich odovzdal a koľko za ne získal        
bodov. Študentov bude možné zotrieďovať poda všetkých               
vyššie uvedených                                                    
                                                                    
informácií (pri rovnosti číselných hodnôt sa ako druhé              
kritérium berie zoradenie podľa priezviska). Túto tabuľku je        
potrebné exportovať aj do CSV súboru.                               
                                                                    
*•* si prezerať, aké úlohy si ktorý študent vygeneroval, aké        
odovzdal, odovzdaný výsledok spolu s informáciou, či bol            
správny a koľko získal za ktorú úlohu bodov.                        
                                                                    
**\(10\)** Súčasťou aplikácie bude návod, ako je možné                  
aplikáciu používať zo strany študenta a aj zo strany                
učiteľa. Tento návod je potrebné umožniť vygenerovať do PDF         
súboru. V prípade zmeny v návode na stránke, sa táto zmena          
musí odraziť aj vo vygenerovanom PDF súbore (t.j. súbor je          
treba generovať dynamicky).                                         
                                                                    
**\(11\)** Vytvorenú aplikáciu je potrebné odovzdať vo forme docker     
balíčka.                                                            
                                                                    
**\(12\)** Vytvorte video, ktorým budete dokumentovať celú              
funkcionalitu vytvorenej aplikácie. Ak niektorá funkcionalita       
nebude ukázaná na videu, tak ju môžeme považovať za                 
nespravenú.                                                         

# 3\. Ďalšie požiadavky

Odovzdanie projektu sa robí cez MS Teams a je tam potrebné
vložiť:\
*•* technickú dokumentáciu (rovnaké požiadavky ako pri iných
zadaniach), nezabudnite v nej uviesť\
**--** login a heslo pre administrátorský prístup do databázy a do
aplikácie, **--** rozdelenie úloh medzi jednotlivých členov
tímu,\
**--** v prípade neurobenia niektorej z úloh, to treba jasne vyznačiť.

*•* samotnú aplikáciu ako**--** docker balíček\ alebo\
**--** spakované súbory vrátane konfiguračného súboru, v ktorom
je potrebné definovať všetky nastavenia,\
**--** sql súbor pre naplnenie databázy.

Okrem toho pri odovzdávaní je potrebné uviesť:*•* vytvorené video

*•* adresu umiestnenia, aby sme vedeli, pod koho menom máme projekt
hľadať,*•* adresu projektu vo verzionovacom systéme.

# 4\. Návrh hodnotenia

| **Úlohy**                                                    | **Body**   |
| ------------------------------------------------------------ | ---------- |
| dvojjazyčnosť                                                | 6          |
| prihlasovanie sa do aplikácie (študent, učiteľ)              | 6          |
| GUI a funkcionalita študenta (vrátane matematického editora) | 16         |
| GUI a funkcionalita učiteľa                                  | 16         |
| kontrola správnosti výsledku                                 | 16         |
| export do csv a pdf                                          | 10         |
| docker balíček                                               | 16         |
| používanie verzionovacieho systému všetkými členmi tímu (1)  | 8          |
| finalizácia aplikácie (2)                                    | 18         |
| video                                                        | 8          |

> 1 každý člen musí mať minimálne 3 commit-y\
> 2 grafický layout, štruktúra, orientácia v aplikácii, voľba db
> tabuliek, úplnosť odovzdania projektu, \...
