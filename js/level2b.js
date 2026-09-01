/* BULIG — Level 2B Phonological Awareness quest engine
   PHP supplies SERVER_STATE + SAVE_URL before this file loads.

   This build maps the full Level 2B module ("Phonological Awareness",
   Book 2 / continuation booklet): 6 skill Units, each containing every
   one of its real numbered Activities from the module (22 activities
   total, Activities 1-22) as sequential stages inside the same unit --
   so nothing from the module is skipped, combined, or invented, while
   the quest MAP still shows a clean 6-node path like Level 1 / Level 2A:

     Unit 1 - Blending Onsets and Rimes into Words   (Activities 1-4)
     Unit 2 - Segmenting Words into Onsets and Rimes  (Activities 5-7)
     Unit 3 - Segmenting Words into Syllables         (Activities 8-10)
     Unit 4 - Blending Syllables into Words           (Activities 11-15)
     Unit 5 - Sentence Segmentation                   (Activities 16-18)
     Unit 6 - Rhymes and Rhyming Songs                (Activities 19-22)

   Note: the module's own Table of Contents lists an "Activity 23" under
   Rhymes and Rhyming Songs, but the source document itself only contains
   worksheets for Activities 19-22 there before the Post-Assessment
   divider page -- Activity 23 does not physically exist in the module.
   This build ships the 22 activities that actually exist rather than
   inventing a 23rd to match a TOC typo.

   Every stage still runs on Level 1/2A's three existing engines --
   "quiz", "sequence", and "sentence" -- so no new rendering code, CSS,
   XP model, or TTS mechanism was invented; only content and the same
   thin "which stage am I on" wrapper used by Level 2A. Read Aloud (Web
   Speech API), XP, badges, locking and the celebration screen all work
   exactly like Level 1 and Level 2A. */

/* ============ DATA ============
   Every word/example below is taken directly from the Level 2B module
   (Activities 1-22, Pre-Assessment, Post-Assessment) rather than
   invented, so the digital version matches what's actually printed in
   the workbook. Picture-matching worksheets (e.g. Activities 1-4, 7, 11)
   were converted into equivalent multiple-choice blending/segmenting
   questions using the exact same target words shown in the module. */
const LESSONS = [

 /* ---------- UNIT 1 -- Blending Onsets and Rimes into Words (Activities 1-4) ---------- */
 {id:1, title:"Sound Blenders", sub:"Blending Onsets and Rimes into Words", icon:"\ud83e\udde9",
  activities:[
   {title:"Activity 1: Choose the Onset", engine:"quiz", data:{questions:[
    {q:"Which onset + \"ed\" makes the word for a place to sleep: \u201cbed\u201d?",options:["f","p","b"],correct:2},
    {q:"Which onset + \"am\" makes \u201cjam\u201d?",options:["l","j","b"],correct:1},
    {q:"Which onset + \"it\" makes a first-aid \u201ckit\u201d?",options:["l","k","m"],correct:1},
    {q:"Which onset + \"et\" makes \u201cpet\u201d?",options:["w","k","p"],correct:2},
    {q:"Which onset + \"et\" makes \u201cvet\u201d?",options:["l","v","j"],correct:1},
    {q:"Which onset + \"up\" makes \u201ccup\u201d?",options:["s","g","c"],correct:2},
    {q:"Which onset + \"et\" makes a fishing \u201cnet\u201d?",options:["b","n","g"],correct:1},
    {q:"Which onset + \"en\" makes \u201chen\u201d?",options:["p","b","h"],correct:2},
    {q:"Which onset + \"ar\" makes \u201ccar\u201d?",options:["d","h","c"],correct:2},
    {q:"Which onset + \"eb\" makes a spider's \u201cweb\u201d?",options:["f","w","t"],correct:1}
   ]}},
   {title:"Activity 2: Fill in the Missing Onset", engine:"quiz", data:{questions:[
    {q:"A small amount of sauce for dipping chips is a ___ip. Which onset finishes the word?",options:["d","l","s"],correct:0},
    {q:"The soft parts of your mouth are your ___ip(s). Which onset finishes the word?",options:["l","s","r"],correct:0},
    {q:"Tearing paper makes a ___ip. Which onset finishes the word?",options:["r","s","d"],correct:0},
    {q:"To rest on a chair is to ___it. Which onset finishes the word?",options:["s","k","f"],correct:0},
    {q:"A small bag of bandages is a first aid ___it. Which onset finishes the word?",options:["k","s","f"],correct:0},
    {q:"A carpet on the floor is a ___ug. Which onset finishes the word?",options:["r","d","b"],correct:0},
    {q:"A pink farm animal that oinks is a ___ig. Which onset finishes the word?",options:["p","d","b"],correct:0},
    {q:"Using a shovel in the garden, you ___ig. Which onset finishes the word?",options:["d","p","w"],correct:0},
    {q:"A container for trash is a ___in. Which onset finishes the word?",options:["b","w","s"],correct:0},
    {q:"To be first in a race is to ___in. Which onset finishes the word?",options:["w","b","s"],correct:0}
   ]}},
   {title:"Activity 3: Connect the Onset to the Rime", engine:"quiz", data:{questions:[
    {q:"Blend /r/ + \u201can\u201d. What word do you make?",options:["ran","rag","ram"],correct:0},
    {q:"Blend /t/ + \u201cag\u201d. What word do you make?",options:["tag","tap","tab"],correct:0},
    {q:"Blend /b/ + \u201cat\u201d. What word do you make?",options:["bat","ban","bag"],correct:0},
    {q:"Blend /m/ + \u201cap\u201d. What word do you make?",options:["map","mat","man"],correct:0},
    {q:"Blend /d/ + \u201cam\u201d. What word do you make?",options:["dam","dad","dan"],correct:0},
    {q:"Blend /j/ + \u201cam\u201d. What word do you make?",options:["jam","jab","jag"],correct:0},
    {q:"Blend /r/ + \u201cat\u201d. What word do you make?",options:["rat","ran","rag"],correct:0},
    {q:"Blend /s/ + \u201cag\u201d. What word do you make?",options:["sag","sat","sad"],correct:0},
    {q:"Blend /m/ + \u201cad\u201d. What word do you make?",options:["mad","map","man"],correct:0},
    {q:"Blend /f/ + \u201cat\u201d. What word do you make?",options:["fat","fan","far"],correct:0}
   ]}},
   {title:"Activity 4: Connect the Onset to the Rime", engine:"quiz", data:{questions:[
    {q:"Blend /p/ + \u201can\u201d. What word do you make (like the pan you cook with)?",options:["pan","pat","pad"],correct:0},
    {q:"Blend /h/ + \u201cat\u201d. What word do you make (a cowboy wears one)?",options:["hat","ham","hap"],correct:0},
    {q:"Blend /c/ + \u201cam\u201d. What word do you make (an action ___era)?",options:["cam","cap","can"],correct:0},
    {q:"Blend /t/ + \u201cap\u201d. What word do you make (water comes from the ___)?",options:["tap","tag","tan"],correct:0},
    {q:"Blend /b/ + \u201cag\u201d. What word do you make (you carry things in it)?",options:["bag","bat","ban"],correct:0},
    {q:"Blend /c/ + \u201cat\u201d. What word do you make (a furry pet that meows)?",options:["cat","cap","can"],correct:0},
    {q:"Blend /t/ + \u201can\u201d. What word do you make (skin color after the sun)?",options:["tan","tap","tag"],correct:0},
    {q:"Blend /n/ + \u201cap\u201d. What word do you make (a short sleep)?",options:["nap","nag","nab"],correct:0},
    {q:"Blend /h/ + \u201cam\u201d. What word do you make (sliced meat)?",options:["ham","hat","hap"],correct:0},
    {q:"Blend /t/ + \u201cag\u201d. What word do you make (a price ___ on clothes)?",options:["tag","tap","tan"],correct:0}
   ]}}
  ]},

 /* ---------- UNIT 2 -- Segmenting Words into Onsets and Rimes (Activities 5-7) ---------- */
 {id:2, title:"Sound Splitters", sub:"Segmenting Words into Onsets and Rimes", icon:"\ud83d\udd0d",
  activities:[
   {title:"Activity 5: Identify the Onset", engine:"quiz", data:{questions:[
    {q:"What is the ONSET (beginning sound) of \u201cham\u201d?",options:["h","a","m"],correct:0},
    {q:"What is the ONSET of \u201csad\u201d?",options:["s","a","d"],correct:0},
    {q:"What is the ONSET of \u201crat\u201d?",options:["r","a","t"],correct:0},
    {q:"What is the ONSET of \u201cvan\u201d?",options:["v","a","n"],correct:0},
    {q:"What is the ONSET of \u201cpen\u201d?",options:["p","e","n"],correct:0},
    {q:"What is the ONSET of \u201ccat\u201d?",options:["c","a","t"],correct:0},
    {q:"What is the ONSET of \u201cmop\u201d?",options:["m","o","p"],correct:0},
    {q:"What is the ONSET of \u201cnet\u201d?",options:["n","e","t"],correct:0},
    {q:"What is the ONSET of \u201cbag\u201d?",options:["b","a","g"],correct:0},
    {q:"What is the ONSET of \u201cwig\u201d?",options:["w","i","g"],correct:0}
   ]}},
   {title:"Activity 6: Identify the Rime", engine:"quiz", data:{questions:[
    {q:"What is the RIME (ending part) of \u201cpet\u201d?",options:["et","at","ot"],correct:0},
    {q:"What is the RIME of \u201cset\u201d?",options:["et","at","it"],correct:0},
    {q:"What is the RIME of \u201ccap\u201d?",options:["ap","at","ip"],correct:0},
    {q:"What is the RIME of \u201cmat\u201d?",options:["at","ap","it"],correct:0},
    {q:"What is the RIME of \u201clap\u201d?",options:["ap","at","op"],correct:0},
    {q:"What is the RIME of \u201cbin\u201d?",options:["in","an","en"],correct:0},
    {q:"What is the RIME of \u201cpin\u201d?",options:["in","an","un"],correct:0},
    {q:"What is the RIME of \u201crat\u201d?",options:["at","it","et"],correct:0},
    {q:"What is the RIME of \u201csit\u201d?",options:["it","at","et"],correct:0},
    {q:"What is the RIME of \u201clip\u201d?",options:["ip","ap","op"],correct:0}
   ]}},
   {title:"Activity 7: Match Onset, Rime, and Picture", engine:"quiz", data:{questions:[
    {q:"What is the ONSET of \u201cpig\u201d?",options:["p","i","g"],correct:0},
    {q:"What is the ONSET of \u201cdog\u201d?",options:["d","o","g"],correct:0},
    {q:"What is the ONSET of \u201cvan\u201d?",options:["v","a","n"],correct:0},
    {q:"What is the ONSET of \u201clip\u201d?",options:["l","i","p"],correct:0},
    {q:"What is the ONSET of \u201crat\u201d?",options:["r","a","t"],correct:0},
    {q:"What is the RIME of \u201cham\u201d?",options:["am","at","an"],correct:0},
    {q:"What is the RIME of \u201cstar\u201d?",options:["ar","at","an"],correct:0},
    {q:"What is the RIME of \u201cpen\u201d?",options:["en","in","an"],correct:0},
    {q:"What is the RIME of \u201cmop\u201d?",options:["op","ap","ip"],correct:0},
    {q:"What is the RIME of \u201cwig\u201d?",options:["ig","ag","og"],correct:0}
   ]}}
  ]},

 /* ---------- UNIT 3 -- Segmenting Words into Syllables (Activities 8-10) ---------- */
 {id:3, title:"Syllable Sorters", sub:"Segmenting Words into Syllables", icon:"\u2702\ufe0f",
  activities:[
   {title:"Activity 8: Count the Syllables (Fruits)", engine:"quiz", data:{questions:[
    {q:"How many syllables in \u201catis\u201d (a-tis)?",options:["2","3","4"],correct:0},
    {q:"How many syllables in \u201cmelon\u201d (me-lon)?",options:["2","3","4"],correct:0},
    {q:"How many syllables in \u201csili\u201d (si-li)?",options:["2","3","4"],correct:0},
    {q:"How many syllables in \u201ctisa\u201d (ti-sa)?",options:["2","3","4"],correct:0},
    {q:"How many syllables in \u201ckiwi\u201d (ki-wi)?",options:["2","3","4"],correct:0},
    {q:"How many syllables in \u201cguyabano\u201d (gu-ya-ba-no)?",options:["2","3","4"],correct:2},
    {q:"How many syllables in \u201cbanana\u201d (ba-na-na)?",options:["2","3","4"],correct:1},
    {q:"How many syllables in \u201cdurian\u201d (du-ri-an)?",options:["2","3","4"],correct:1},
    {q:"How many syllables in \u201crambutan\u201d (ram-bu-tan)?",options:["2","3","4"],correct:1},
    {q:"How many syllables in \u201clanzones\u201d (lan-zo-nes)?",options:["2","3","4"],correct:1}
   ]}},
   {title:"Activity 9: Count the Syllables (Animals)", engine:"quiz", data:{questions:[
    {q:"How many syllables in \u201cladybug\u201d (la-dy-bug)?",options:["1","2","3"],correct:2},
    {q:"How many syllables in \u201cdog\u201d?",options:["1","2","3"],correct:0},
    {q:"How many syllables in \u201cchicken\u201d (chick-en)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201cbuffalo\u201d (buf-fa-lo)?",options:["1","2","3"],correct:2},
    {q:"How many syllables in \u201crabbit\u201d (rab-bit)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201ctiger\u201d (ti-ger)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201ccrocodile\u201d (croc-o-dile)?",options:["1","2","3"],correct:2},
    {q:"How many syllables in \u201cmonkey\u201d (mon-key)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201czebra\u201d (ze-bra)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201cpiglet\u201d (pig-let)?",options:["1","2","3"],correct:1}
   ]}},
   {title:"Activity 10: Count the Syllables (Big Words)", engine:"quiz", data:{questions:[
    {q:"How many syllables in \u201cbeautiful\u201d (beau-ti-ful)?",options:["2","3","4"],correct:1},
    {q:"How many syllables in \u201cprincipal\u201d (prin-ci-pal)?",options:["2","3","4"],correct:1},
    {q:"How many syllables in \u201cgarden\u201d (gar-den)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201cElementary\u201d (E-le-men-ta-ry)?",options:["3","4","5"],correct:2},
    {q:"How many syllables in \u201cbackyard\u201d (back-yard)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201chelicopter\u201d (he-li-cop-ter)?",options:["3","4","5"],correct:1},
    {q:"How many syllables in \u201cteacher\u201d (teach-er)?",options:["1","2","3"],correct:1},
    {q:"How many syllables in \u201cability\u201d (a-bil-i-ty)?",options:["3","4","5"],correct:1},
    {q:"How many syllables in \u201ceducation\u201d (ed-u-ca-tion)?",options:["3","4","5"],correct:1},
    {q:"How many syllables in \u201cmother\u201d (moth-er)?",options:["1","2","3"],correct:1}
   ]}}
  ]},

 /* ---------- UNIT 4 -- Blending Syllables into Words (Activities 11-15) ---------- */
 {id:4, title:"Word Builders", sub:"Blending Syllables into Words", icon:"\ud83e\uddf1",
  activities:[
   {title:"Activity 11: Blend the Letters", engine:"quiz", data:{questions:[
    {q:"Blend /h/-/e/-/n/. What word do they make?",options:["hen","hem","her"],correct:0},
    {q:"Blend /c/-/o/-/w/. What word do they make?",options:["cow","cop","cot"],correct:0},
    {q:"Blend /b/-/o/-/y/. What word do they make?",options:["boy","bay","buy"],correct:0},
    {q:"Blend /m/-/a/-/t/. What word do they make?",options:["mat","map","man"],correct:0},
    {q:"Blend /b/-/a/-/g/. What word do they make?",options:["bag","bad","ban"],correct:0},
    {q:"Blend /c/-/a/-/p/. What word do they make?",options:["cap","cat","can"],correct:0},
    {q:"Blend /b/-/a/-/t/. What word do they make?",options:["bat","bad","ban"],correct:0},
    {q:"Blend /i/-/c/-/e/. What word do they make?",options:["ice","ace","ike"],correct:0},
    {q:"Blend /r/-/a/-/t/. What word do they make?",options:["rat","ran","rag"],correct:0},
    {q:"Blend /m/-/u/-/d/. What word do they make?",options:["mud","mad","mug"],correct:0}
   ]}},
   {title:"Activity 12: Choose the Correct Blend (Act. #1)", engine:"quiz", data:{questions:[
    {q:"Which blend completes \u201c__ab\u201d to make \u201ccrab\u201d?",options:["cr","dr","fr"],correct:0},
    {q:"Which blend completes \u201c__og\u201d to make \u201cfrog\u201d?",options:["fr","dr","cr"],correct:0},
    {q:"Which blend completes \u201c__ink\u201d to make \u201cdrink\u201d?",options:["dr","fr","tr"],correct:0},
    {q:"Which blend completes \u201c__ies\u201d to make \u201cfries\u201d?",options:["fr","tr","dr"],correct:0},
    {q:"Which blend completes \u201c__oss\u201d to make \u201ccross\u201d?",options:["cr","tr","br"],correct:0},
    {q:"Which blend completes \u201c__uit\u201d to make \u201cfruit\u201d?",options:["fr","dr","tr"],correct:0},
    {q:"Which blend completes \u201c__ush\u201d to make \u201cbrush\u201d?",options:["br","dr","cr"],correct:0},
    {q:"Which blend completes \u201c__um\u201d to make \u201cdrum\u201d?",options:["dr","br","tr"],correct:0},
    {q:"Which blend completes \u201c__ee\u201d to make \u201ctree\u201d?",options:["tr","fr","cr"],correct:0},
    {q:"Which blend completes \u201c__oom\u201d to make \u201cbroom\u201d?",options:["br","cr","dr"],correct:0}
   ]}},
   {title:"Activity 13: Choose the Correct Blend (Act. #2)", engine:"quiz", data:{questions:[
    {q:"Which blend completes \u201c__irrel\u201d to make \u201csquirrel\u201d?",options:["squ","scr","str"],correct:0},
    {q:"Which blend completes \u201c__eam\u201d to make \u201cscream\u201d?",options:["scr","squ","spr"],correct:0},
    {q:"Which blend completes \u201c__inkler\u201d to make \u201csprinkler\u201d?",options:["spr","str","thr"],correct:0},
    {q:"Which blend completes \u201c__id\u201d to make \u201csquid\u201d?",options:["squ","scr","str"],correct:0},
    {q:"Which blend completes \u201c__ead\u201d to make \u201cthread\u201d?",options:["thr","str","scr"],correct:0},
    {q:"Which blend completes \u201c__awberry\u201d to make \u201cstrawberry\u201d?",options:["str","spr","scr"],correct:0},
    {q:"Which blend completes \u201c__ee\u201d to make \u201cthree\u201d?",options:["thr","scr","str"],correct:0},
    {q:"Which blend completes \u201c__ay\u201d to make \u201cspray\u201d?",options:["spr","str","scr"],correct:0},
    {q:"Which blend completes \u201c__ash\u201d to make \u201cthrash\u201d?",options:["thr","str","spr"],correct:0},
    {q:"Which blend completes \u201c__ew\u201d to make \u201cscrew\u201d?",options:["scr","spr","str"],correct:0}
   ]}},
   {title:"Activity 14: Choose the Correct Blend (Act. #1)", engine:"quiz", data:{questions:[
    {q:"Which blend completes \u201c__obe\u201d to make \u201cglobe\u201d?",options:["gl","bl"],correct:0},
    {q:"Which blend completes \u201c__oves\u201d to make \u201cgloves\u201d?",options:["gl","cl"],correct:0},
    {q:"Which blend completes \u201c__ant\u201d to make \u201cplant\u201d?",options:["pl","sl"],correct:0},
    {q:"Which blend completes \u201c__ower\u201d to make \u201cflower\u201d?",options:["fl","sc"],correct:0},
    {q:"Which blend completes \u201c__own\u201d to make \u201cclown\u201d?",options:["cl","sc"],correct:0},
    {q:"Which blend completes \u201c__ouds\u201d to make \u201cclouds\u201d?",options:["cl","bl"],correct:0},
    {q:"Which blend completes \u201c__ell\u201d to make \u201cshell\u201d?",options:["sh","sl"],correct:0},
    {q:"Which blend completes \u201c__ass\u201d to make \u201cglass\u201d?",options:["gl","bl"],correct:0},
    {q:"Which blend completes \u201c__ane\u201d to make \u201cplane\u201d?",options:["pl","bl"],correct:0},
    {q:"Which blend completes \u201c__ame\u201d to make \u201cframe\u201d?",options:["fr","pr"],correct:0}
   ]}},
   {title:"Activity 15: Choose the Correct Blend (Act. #2)", engine:"quiz", data:{questions:[
    {q:"Which blend completes \u201c__icks\u201d to make \u201csticks\u201d?",options:["st","sc","sk"],correct:0},
    {q:"Which blend completes \u201c__ile\u201d to make \u201csmile\u201d?",options:["sm","sp","sk"],correct:0},
    {q:"Which blend completes \u201c__ill\u201d to make \u201cskill\u201d?",options:["sk","sc","st"],correct:0},
    {q:"Which blend completes \u201c__arf\u201d to make \u201cscarf\u201d?",options:["sc","sk","st"],correct:0},
    {q:"Which blend completes \u201c__ate\u201d to make \u201cskate\u201d?",options:["sk","st","sp"],correct:0},
    {q:"Which blend completes \u201c__ace\u201d to make \u201cbrace\u201d?",options:["br","bl","sp"],correct:0},
    {q:"Which blend completes \u201c__ouse\u201d to make \u201cblouse\u201d?",options:["bl","br","cl"],correct:0},
    {q:"Which blend completes \u201c__ack\u201d to make \u201cblack\u201d?",options:["bl","cl","br"],correct:0},
    {q:"Which blend completes \u201c__eel\u201d to make \u201csteel\u201d?",options:["st","sp","sc"],correct:0},
    {q:"Which blend completes \u201c__ap\u201d to make \u201cclap\u201d?",options:["cl","sc","sk"],correct:0}
   ]}}
  ]},

 /* ---------- UNIT 5 -- Sentence Segmentation (Activities 16-18) ---------- */
 {id:5, title:"Sentence Detectives", sub:"Sentence Segmentation", icon:"\ud83d\udcac",
  activities:[
   {title:"Activity 16: Count the Words", engine:"quiz", data:{questions:[
    {q:"How many words: \u201cIt is a big fin.\u201d?",options:["4","5","6"],correct:1},
    {q:"How many words: \u201cThe bin is tan.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cIt is a tin can.\u201d?",options:["4","5","6"],correct:1},
    {q:"How many words: \u201cThe pin is on the bed.\u201d?",options:["6","7","8"],correct:1},
    {q:"How many words: \u201cMin did not win.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cThe fig is in the tin.\u201d?",options:["5","6","7"],correct:1},
    {q:"How many words: \u201cIt is a yellow bin.\u201d?",options:["4","5","6"],correct:1},
    {q:"How many words: \u201cGet a tin lid.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cJe has a pin.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cThe fin is red.\u201d?",options:["3","4","5"],correct:1}
   ]}},
   {title:"Activity 17: Count the Words", engine:"quiz", data:{questions:[
    {q:"How many words: \u201cWe run.\u201d?",options:["1","2","3"],correct:1},
    {q:"How many words: \u201cThe sun is hot.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cWe have fun.\u201d?",options:["2","3","4"],correct:1},
    {q:"How many words: \u201cPam is a nun.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cIt was for fun.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cI met a nun.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cWe run in the sun.\u201d?",options:["4","5","6"],correct:1},
    {q:"How many words: \u201cThe nun likes to run.\u201d?",options:["4","5","6"],correct:1},
    {q:"How many words: \u201cI want to eat a bun.\u201d?",options:["5","6","7"],correct:1},
    {q:"How many words: \u201cIt can be fun.\u201d?",options:["3","4","5"],correct:1}
   ]}},
   {title:"Activity 18: Count the Words", engine:"quiz", data:{questions:[
    {q:"How many words: \u201cWhat a fun day!\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cWe got to play.\u201d?",options:["3","4","5"],correct:1},
    {q:"How many words: \u201cCatch, go on the ship, and we got a fish!\u201d?",options:["9","10","11"],correct:1},
    {q:"How many words: \u201cWe saw a big shell.\u201d?",options:["4","5","6"],correct:1},
    {q:"How many words: \u201cWe had such a fun in the sun!\u201d?",options:["7","8","9"],correct:1}
   ]}}
  ]},

 /* ---------- UNIT 6 -- Rhymes and Rhyming Songs (Activities 19-22) ---------- */
 {id:6, title:"Rhyme Time", sub:"Rhymes and Rhyming Songs", icon:"\ud83c\udfb5",
  activities:[
   {title:"Activity 19: Choose the Rhyming Word", engine:"quiz", data:{questions:[
    {q:"Which word rhymes with \u201cram\u201d?",options:["ham","fun","dog"],correct:0},
    {q:"Which word rhymes with \u201cbun\u201d?",options:["run","cat","top"],correct:0},
    {q:"Which word rhymes with \u201cfed\u201d?",options:["bed","cup","sun"],correct:0},
    {q:"Which word rhymes with \u201cpot\u201d?",options:["hot","bag","fin"],correct:0},
    {q:"Which word rhymes with \u201cwig\u201d?",options:["pig","cow","hat"],correct:0},
    {q:"Which word rhymes with \u201ctoe\u201d?",options:["foe","cup","ran"],correct:0},
    {q:"Which word rhymes with \u201chand\u201d?",options:["band","fish","top"],correct:0},
    {q:"Which word rhymes with \u201cfat\u201d?",options:["rat","sun","pig"],correct:0},
    {q:"Which word rhymes with \u201csink\u201d?",options:["pink","dog","cup"],correct:0},
    {q:"Which word rhymes with \u201cbeep\u201d?",options:["deep","cat","run"],correct:0}
   ]}},
   {title:"Activity 20: Complete the Rhyme", engine:"quiz", data:{questions:[
    {q:"\u201cOne two, tie my ____.\u201d",options:["dress","pants","shoe"],correct:2},
    {q:"\u201cThree four, shut the ____.\u201d",options:["door","book","roof"],correct:0},
    {q:"\u201cFive six, pick up ____.\u201d",options:["sticks","trees","twigs"],correct:0},
    {q:"\u201cSeven eight, I got ____.\u201d",options:["all","it","you"],correct:1},
    {q:"\u201cNine ten, a big fat ____.\u201d",options:["hen","rat","cow"],correct:0},
    {q:"\u201cShe sells, sea ____.\u201d",options:["fish","horse","shells"],correct:2},
    {q:"\u201cLittle Miss Muffet, sat on ____.\u201d",options:["buffet","chair","table"],correct:0},
    {q:"\u201cThree little kittens, lost their ____.\u201d",options:["pies","mittens","socks"],correct:1},
    {q:"\u201cHumpty Dumpty sat on the wall, Humpty Dumpty had a great ____.\u201d",options:["fall","war","joy"],correct:0},
    {q:"\u201cHickory Dickory Dock, the mouse ran up the ____.\u201d",options:["roof","clock","road"],correct:1}
   ]}},
   {title:"Activity 21: Which Word Rhymes with the Picture", engine:"quiz", data:{questions:[
    {q:"Which word rhymes with \u201cball\u201d?",options:["fall","lap","blue"],correct:0},
    {q:"Which word rhymes with \u201cpan\u201d?",options:["can","saw","kite"],correct:0},
    {q:"Which word rhymes with \u201cbee\u201d?",options:["see","tag","put"],correct:0},
    {q:"Which word rhymes with \u201cbag\u201d?",options:["tag","book","back"],correct:0},
    {q:"Which word rhymes with \u201cboat\u201d?",options:["goat","horse","pig"],correct:0},
    {q:"Which word rhymes with \u201csun\u201d?",options:["fun","cut","bat"],correct:0},
    {q:"Which word rhymes with \u201cbell\u201d?",options:["well","mat","cut"],correct:0},
    {q:"Which word rhymes with \u201cwig\u201d?",options:["twig","she","well"],correct:0},
    {q:"Which word rhymes with \u201ccap\u201d?",options:["map","log","leaf"],correct:0},
    {q:"Which word rhymes with \u201cbook\u201d?",options:["look","rat","rug"],correct:0}
   ]}},
   {title:"Activity 22: Find the Word That Does NOT Rhyme", engine:"quiz", data:{questions:[
    {q:"Which word does NOT rhyme with the others: van, bob, can, bun?",options:["van","bob","can","bun"],correct:1},
    {q:"Which word does NOT rhyme with the others: fin, pen, hot, pin?",options:["fin","pen","hot","pin"],correct:2},
    {q:"Which word does NOT rhyme with the others: hot, pot, rat, ball?",options:["hot","pot","rat","ball"],correct:3},
    {q:"Which word does NOT rhyme with the others: try, cry, can, dry?",options:["try","cry","can","dry"],correct:2},
    {q:"Which word does NOT rhyme with the others: top, low, hop, cop?",options:["top","low","hop","cop"],correct:1},
    {q:"Which word does NOT rhyme with the others: kick, bed, pick, stick?",options:["kick","bed","pick","stick"],correct:1},
    {q:"Which word does NOT rhyme with the others: Red, yellow, bed, led?",options:["Red","yellow","bed","led"],correct:1},
    {q:"Which word does NOT rhyme with the others: tank, rank, bank, bark?",options:["tank","rank","bank","bark"],correct:3},
    {q:"Which word does NOT rhyme with the others: bug, rug, dust, hug?",options:["bug","rug","dust","hug"],correct:2},
    {q:"Which word does NOT rhyme with the others: pink, link, steal, blink?",options:["pink","link","steal","blink"],correct:2}
   ]}}
  ]}
];

/* PRETEST -- one diagnostic item per unit, matching the module's own
   Pre-Assessment (per-skill pretest pages) format and content. */
const PRETEST = [
 {lessonId:1, q:"Connect the onset to the rime: \u201cfl\u201d + \u201cower\u201d. What word do you make?"},
 {lessonId:2, q:"What are the onset and rime in the word \u201cbat\u201d? (Example: h-at)"},
 {lessonId:3, q:"Copy the word \u201ctaxi\u201d and divide it into syllables with a slash (/)."},
 {lessonId:4, q:"Blend the syllables: \u201crain\u201d + \u201cbow\u201d. What word do they make?"},
 {lessonId:5, q:"Read: \u201cPam is mad.\u201d How many words are in this sentence?"},
 {lessonId:6, q:"The old man's cane is on the ___. Which word rhymes: mat, box, lane?"}
];
/* POSTTEST -- sourced from the module's own Post-Assessment pages. */
const POSTTEST = [
 {lessonId:1, q:"Write the onset and rime for the word \u201cjam\u201d."},
 {lessonId:2, q:"Copy the word \u201cbeggar\u201d and divide it into syllables with a slash (/)."},
 {lessonId:3, q:"Blend the syllables: \u201csun\u201d + \u201cshine\u201d. What word do they make?"},
 {lessonId:4, q:"Choose the correct blend to complete the word: \u201c___ill\u201d (st, sp, sm)."},
 {lessonId:5, q:"Read: \u201cThe cat is big.\u201d How many words are in this sentence?"},
 {lessonId:6, q:"Encircle the two rhyming words in: \u201cThe bag has a tag.\u201d"}
];

/* ============ STATE (in-memory, backed by MySQL through SAVE_URL) ============ */
let PROGRESS = {
  xp: SERVER_STATE.xp || 0,
  completed: (SERVER_STATE.completed || []).slice(),
  preDone: !!SERVER_STATE.preDone,
  postDone: !!SERVER_STATE.postDone,
  preAnswers: SERVER_STATE.preAnswers || {},
  postAnswers: SERVER_STATE.postAnswers || {}
};
let openLessonState = null; // { id, activityIndex, engineState }

/* ============ TTS ============ */
function speak(text, rate){
  if(!window.speechSynthesis){
    toast("\u26a0\ufe0f This browser doesn't support read-aloud (Web Speech API).");
    return null;
  }
  window.speechSynthesis.cancel();
  const u = new SpeechSynthesisUtterance(text);
  u.rate = rate || 0.95;
  u.pitch = 1.05;
  u.onerror = function(e){
    console.error('Speech synthesis error:', e.error);
    toast('\u26a0\ufe0f Could not play audio (' + e.error + ').');
  };
  window.speechSynthesis.speak(u);
  return u;
}
function testSound(){
  const u = speak('Hello! Can you hear me? This is a sound test.', 1);
  if(u) toast('\ud83d\udd0a Playing test sound now...');
}

/* ============ SERVER SAVE HELPER ============ */
async function saveToServer(payload){
  try{
    const res = await fetch(SAVE_URL, { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
    return await res.json();
  }catch(e){ return { ok:false, error:'network' }; }
}

/* ============ UTIL ============ */
function escapeHtml(str){
  return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}
function attrJson(val){
  return escapeHtml(JSON.stringify(val));
}
function toast(msg){
  const t = document.createElement('div');
  t.className = 'l1-toast';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(()=>t.remove(), 2200);
}

/* ============ RENDER: QUEST MAP ============ */
function renderMap(){
  const root = document.getElementById('mapRoot');
  const allDone = PROGRESS.completed.length === LESSONS.length;

  let nodesHtml = '';
  LESSONS.forEach((lesson, idx) => {
    const done = PROGRESS.completed.includes(lesson.id);
    const unlocked = idx === 0 || PROGRESS.completed.includes(LESSONS[idx-1].id);
    const side = idx % 2 === 0 ? 'l1-left' : 'l1-right';
    let cls = 'l1-node';
    if(!unlocked) cls += ' l1-locked'; else if(done) cls += ' l1-done'; else cls += ' l1-current';
    nodesHtml += `<div class="l1-node-row ${side}">
      <div class="${cls}">
        <div class="l1-num">${lesson.id}</div>
        ${done ? '<div class="l1-check">\u2713</div>' : ''}
        <div class="l1-nicon">${lesson.icon}</div>
        <div class="l1-ntitle">${lesson.title}</div>
        <div class="l1-nsub">${lesson.sub}</div>
        <div class="l1-nsub" style="opacity:.75;">${lesson.activities.length} activities</div>
        <button ${unlocked ? `onclick="openLesson(${lesson.id})"` : 'disabled'}>${done ? 'Replay' : unlocked ? 'Play' : '\ud83d\udd12 Locked'}</button>
      </div>
    </div>`;
  });

  root.innerHTML = `
  <div style="text-align:center;margin-bottom:14px;">
    <button class="l1-pill" onclick="testSound()">\ud83d\udd0a Test My Sound</button>
    <div style="font-size:11.5px;color:var(--ink-soft);margin-top:4px;">Tap this first if "Read Aloud" buttons seem silent.</div>
  </div>
  <div class="stat-grid">
    <div class="stat-card">
      <span class="stat-icon">\u2b50</span>
      <div class="stat-value">${PROGRESS.xp} XP</div>
      <div class="stat-label">Level 2B Experience</div>
      <div class="stat-note">Earn 100 XP per unit, 50 XP per assessment</div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">\ud83d\udd24</span>
      <div class="stat-value">${PROGRESS.completed.length} / ${LESSONS.length}</div>
      <div class="stat-label">Units Completed</div>
      <div class="xp-bar-track"><div class="xp-bar-fill" style="width:${Math.round(PROGRESS.completed.length/LESSONS.length*100)}%"></div></div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">${allDone ? '\ud83c\udfc6' : '\ud83d\udd13'}</span>
      <div class="stat-value">${allDone ? 'Champion!' : (LESSONS.length - PROGRESS.completed.length) + ' to go'}</div>
      <div class="stat-label">${allDone ? 'Level 2B complete' : 'Units remaining'}</div>
      <div class="stat-note">${allDone ? "You've unlocked the Post-Assessment!" : 'Keep going -- you can do it!'}</div>
    </div>
  </div>

  <div class="action-grid">
    <a href="javascript:void(0)" class="action-card" onclick="openAssessment('pre')">
      <span class="action-icon">\ud83d\udcdd</span>
      <h3>Pre-Assessment</h3>
      <p>A quick check-in before you start -- your teacher will review it.</p>
      <span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.preDone ? 'Review your answers \u2192' : 'Start \u2192'}</span>
    </a>
    <a href="javascript:void(0)" class="action-card ${allDone ? '' : 'is-soon'}" ${allDone ? "onclick=\"openAssessment('post')\"" : ''}>
      <span class="action-icon">${allDone ? '\ud83c\udfc5' : '\ud83d\udd12'}</span>
      <h3>Post-Assessment</h3>
      <p>${allDone ? "Show off everything you've learned!" : 'Unlocks after all 6 units are complete.'}</p>
      ${allDone ? `<span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.postDone ? 'Review your answers \u2192' : 'Start \u2192'}</span>` : '<span class="pill-soon">Locked</span>'}
    </a>
  </div>

  <h2 class="section-title">\ud83d\uddfa\ufe0f Your Sound Path</h2>
  <div class="l1-path">${nodesHtml}</div>`;
}

function openLesson(id){ openLessonState = { id, activityIndex:0, engineState:{} }; renderLessonOverlay(); }
function closeLesson(){
  window.speechSynthesis && window.speechSynthesis.cancel();
  const overlay = document.getElementById('lessonOverlay');
  if(overlay) overlay.remove();
  openLessonState = null;
}
function currentActivity(){
  const lesson = LESSONS.find(l => l.id === openLessonState.id);
  return lesson.activities[openLessonState.activityIndex];
}

/* ============ LESSON OVERLAY ============ */
function renderLessonOverlay(){
  const lesson = LESSONS.find(l => l.id === openLessonState.id);
  const activity = currentActivity();
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className='l1-overlay l1-scope'; document.body.appendChild(overlay); }

  const dots = lesson.activities.map((a,i) => `<span class="l1-quiz-progress" style="display:inline-block;margin:0 3px;padding:3px 9px;border-radius:20px;${i===openLessonState.activityIndex ? 'background:var(--bulig-green);color:#fff;' : i<openLessonState.activityIndex ? 'background:var(--bulig-gold);' : 'background:#eee;'}">${i+1}</span>`).join('');

  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeLesson()">\u2715</button>
      <div>
        <div class="l1-ltitle">${lesson.icon} ${lesson.title}</div>
        <div class="l1-lsub">${lesson.sub} \u00b7 Activity ${openLessonState.activityIndex+1} of ${lesson.activities.length}: ${escapeHtml(activity.title)}</div>
      </div>
    </div>
    <div style="text-align:center;margin:6px 0 12px;">${dots}</div>
    <div class="l1-lesson-body" id="lessonBody"></div>
  `;
  renderEngine(activity);
}
function renderEngine(activity){
  const body = document.getElementById('lessonBody');
  const engines = { sentence: engineSentence, quiz: engineQuiz, sequence: engineSequence };
  body.innerHTML = engines[activity.engine](activity);
}

function advanceActivity(){
  const lesson = LESSONS.find(l => l.id === openLessonState.id);
  if(openLessonState.activityIndex < lesson.activities.length - 1){
    toast('\u2b50 Nice work! Activity complete.');
    openLessonState.activityIndex++;
    openLessonState.engineState = {};
    renderLessonOverlay();
  } else {
    finishLesson(lesson.id);
  }
}

/* ---- Sentence engine (kept for parity with Level 1/2A; unused by
   Level 2B's own activities but available if a future module needs it) ---- */
function engineSentence(activity){
  const fieldsHtml = activity.data.fields.map((f,i) => `
    <div class="l1-field">
      <label>${escapeHtml(f.label)}</label>
      <input id="sf_${i}" placeholder="${escapeHtml(f.placeholder)}" oninput="checkReady(${activity.data.fields.length})">
      <button class="l1-speak-btn" style="margin:-8px 0 14px;" onclick="speak(${attrJson(f.label)})">\ud83d\udd0a Read aloud</button>
    </div>`).join('');
  return `
    <div class="l1-card">
      <p>${escapeHtml(activity.data.intro)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(activity.data.intro)})">\ud83d\udd0a Read aloud</button>
    </div>
    <div class="l1-card">${fieldsHtml}</div>
    <div class="l1-footer-actions"><button class="l1-btn l1-disabled" id="finishBtn" onclick="advanceActivity()">\u2705 Next</button></div>`;
}
function checkReady(count){
  let ready = true;
  for(let i=0;i<count;i++){
    const el = document.getElementById('sf_'+i);
    if(!el || !el.value.trim()) ready = false;
  }
  const btn = document.getElementById('finishBtn');
  if(btn){ if(ready) btn.classList.remove('l1-disabled'); else btn.classList.add('l1-disabled'); }
}

/* ---- Quiz engine (reused across every Level 2B activity) ---- */
function engineQuiz(activity){
  openLessonState.engineState = { qIndex:0, score:0, answered:false };
  return renderQuizQuestion(activity);
}
function renderQuizQuestion(activity){
  const es = openLessonState.engineState;
  const total = activity.data.questions.length;
  if(es.qIndex >= total){
    return `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">\ud83c\udf89</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">Great effort!</h3>
      <p>You got ${es.score} out of ${total} correct. Every try helps you learn!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="advanceActivity()">\u2705 Next</button></div>`;
  }
  const q = activity.data.questions[es.qIndex];
  const optsHtml = q.options.map((opt,i) => `<button class="l1-quiz-opt" id="opt_${i}" onclick="answerQuiz(${i})">${escapeHtml(opt)}</button>`).join('');
  return `
    <div class="l1-quiz-progress">Question ${es.qIndex+1} of ${total}</div>
    <div class="l1-card">
      <div class="l1-quiz-q">${escapeHtml(q.q)}</div>
      <button class="l1-speak-btn" style="margin-bottom:12px;" onclick="speak(${attrJson(q.q)})">\ud83d\udd0a Read aloud</button>
      <div>${optsHtml}</div>
    </div>
    <div class="l1-footer-actions" id="quizNextWrap"></div>`;
}
function answerQuiz(idx){
  const activity = currentActivity();
  const es = openLessonState.engineState;
  if(es.answered) return;
  es.answered = true;
  const q = activity.data.questions[es.qIndex];
  document.getElementById('opt_'+idx).classList.add(idx===q.correct?'l1-correct':'l1-wrong');
  if(idx===q.correct){ es.score++; toast('\u2705 +1 point!'); } else { document.getElementById('opt_'+q.correct).classList.add('l1-correct'); }
  document.getElementById('quizNextWrap').innerHTML = `<button class="l1-btn" onclick="nextQuiz()">Next \u279c</button>`;
}
function nextQuiz(){
  const activity = currentActivity();
  openLessonState.engineState.qIndex++;
  openLessonState.engineState.answered = false;
  document.getElementById('lessonBody').innerHTML = renderQuizQuestion(activity);
}

/* ---- Sequence engine (kept for parity with Level 1/2A; unused by
   Level 2B's own activities but available if a future module needs it) ---- */
function engineSequence(activity){
  openLessonState.engineState = { roundIndex: 0, order: [] };
  return renderSequence(activity);
}
function renderSequence(activity){
  const es = openLessonState.engineState;
  const rounds = activity.data.rounds;
  if(es.roundIndex >= rounds.length){
    return `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">\ud83c\udf89</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">All blended!</h3>
      <p>You blended ${rounds.length} words. Great listening!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="advanceActivity()">\u2705 Next</button></div>`;
  }
  const round = rounds[es.roundIndex];
  const remaining = round.cards.filter(c => !es.order.includes(c.id));
  const poolHtml = remaining.map(c => `<div class="l1-seq-card" onclick="pickSeq('${c.id}')"><div class="l1-ic">${c.icon}</div>${escapeHtml(c.label)}</div>`).join('') || '<div style="font-size:12px;color:var(--ink-soft);">All sounds placed!</div>';
  const slotHtml = es.order.map((id,i) => {
    const c = round.cards.find(x=>x.id===id);
    return `<div class="l1-seq-card" style="background:var(--bulig-gold);">${i+1}. ${c.icon} ${escapeHtml(c.label)}</div>`;
  }).join('');
  const complete = es.order.length === round.cards.length;
  let feedback = '';
  if(complete){
    const correct = JSON.stringify(es.order) === JSON.stringify(round.correctOrder);
    feedback = correct
      ? `<div class="l1-card" style="background:#DFF5E4;text-align:center;">\ud83c\udf89 Perfect blend! Together those sounds make "${escapeHtml(round.answerWord)}"!</div>`
      : `<div class="l1-card" style="background:#FBE3DF;text-align:center;">Good try! The order isn't quite right yet -- tap Reset and try again.</div>`;
  }
  return `
    <div class="l1-quiz-progress">Word ${es.roundIndex+1} of ${rounds.length}</div>
    <div class="l1-card">
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Your blended word:</div>
      <div class="l1-seq-slot-row">${slotHtml}</div>
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Tap each sound in order:</div>
      <div class="l1-seq-pool">${poolHtml}</div>
      <button class="l1-pill" onclick="resetSeq()">\u21ba Reset</button>
    </div>
    ${feedback}
    <div class="l1-footer-actions"><button class="l1-btn ${complete?'':'l1-disabled'}" onclick="nextSeqRound()">${es.roundIndex === rounds.length-1 ? '\u2705 Next' : 'Next word \u279c'}</button></div>`;
}
function pickSeq(cardId){
  const activity = currentActivity();
  const round = activity.data.rounds[openLessonState.engineState.roundIndex];
  const card = round.cards.find(c=>c.id===cardId);
  if(card) speak(card.label, 0.8);
  openLessonState.engineState.order.push(cardId);
  document.getElementById('lessonBody').innerHTML = renderSequence(activity);
}
function resetSeq(){
  openLessonState.engineState.order = [];
  document.getElementById('lessonBody').innerHTML = renderSequence(currentActivity());
}
function nextSeqRound(){
  const activity = currentActivity();
  const es = openLessonState.engineState;
  if(es.order.length !== activity.data.rounds[es.roundIndex].cards.length) return;
  es.roundIndex++;
  es.order = [];
  document.getElementById('lessonBody').innerHTML = renderSequence(activity);
}

/* ============ FINISH / CELEBRATE (whole unit -- all activities done) ============ */
async function finishLesson(lessonId){
  const lesson = LESSONS.find(l=>l.id===lessonId);
  const result = await saveToServer({ type:'lesson', lesson_id: lessonId });
  if(!result.ok){ toast('\u26a0\ufe0f Could not save -- check your connection and try again.'); return; }
  if(!result.already_done){
    PROGRESS.completed.push(lessonId);
    PROGRESS.xp += result.xp_awarded;
  }
  window.speechSynthesis && window.speechSynthesis.cancel();
  showCelebration(lesson.icon, lesson.title, result.already_done, result.xp_awarded);
}
function showCelebration(icon, title, already, xpAwarded){
  const allDone = PROGRESS.completed.length === LESSONS.length;
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">${allDone ? '\ud83c\udfc6' : '\ud83c\udf89'}</div>
    <h2>${allDone ? 'Phonological Awareness Champion!' : 'Unit Complete!'}</h2>
    <p>${already ? "Nice replay! Keep practicing " + title + "." : "You finished every activity in " + icon + " " + title + " and earned +" + xpAwarded + " XP!"}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Sound Path</button>
  </div>`;
  document.body.appendChild(div);
}
function backToMap(){
  document.querySelectorAll('.l1-celebrate').forEach(e=>e.remove());
  closeLesson();
  renderMap();
}

/* ============ ASSESSMENTS ============ */
function openAssessment(kind){
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className='l1-overlay l1-scope'; document.body.appendChild(overlay); }
  const set = kind === 'pre' ? PRETEST : POSTTEST;
  const answers = kind === 'pre' ? PROGRESS.preAnswers : PROGRESS.postAnswers;
  const done = kind === 'pre' ? PROGRESS.preDone : PROGRESS.postDone;
  const label = kind === 'pre' ? 'Pre-Assessment' : 'Post-Assessment';
  const itemsHtml = set.map((item,i) => {
    const lesson = LESSONS.find(l=>l.id===item.lessonId);
    const val = answers[item.lessonId] || '';
    return `<div class="l1-card">
      <div style="font-size:11.5px;font-weight:800;color:var(--bulig-green);text-transform:uppercase;letter-spacing:.03em;">${lesson.icon} Unit ${item.lessonId} \u00b7 ${escapeHtml(lesson.title)}</div>
      <div class="l1-field" style="margin-top:6px;">
        <label>${escapeHtml(item.q)}</label>
        <textarea id="as_${i}" rows="2" placeholder="Type or say your answer, then write it here...">${escapeHtml(val)}</textarea>
      </div>
      <button class="l1-speak-btn" onclick="speak(${attrJson(item.q)})">\ud83d\udd0a Read aloud</button>
    </div>`;
  }).join('');
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeLesson()">\u2715</button>
      <div>
        <div class="l1-ltitle">\ud83d\udcdd ${label}</div>
        <div class="l1-lsub">6 quick questions, one per unit</div>
      </div>
    </div>
    <div class="l1-lesson-body">
      <div class="l1-card"><p>${done ? "You've already submitted this -- your teacher can see your answers. You can still update them below." : "Answer each question the best you can. There's no wrong answer here -- this just helps your teacher see where to help you!"}</p></div>
      ${itemsHtml}
      <div class="l1-footer-actions"><button class="l1-btn" onclick="submitAssessment('${kind}',${set.length})">${done ? '\ud83d\udcbe Update Answers' : '\u2705 Submit to Teacher'}</button></div>
    </div>`;
}
async function submitAssessment(kind, count){
  const set = kind === 'pre' ? PRETEST : POSTTEST;
  const target = {};
  for(let i=0;i<count;i++){
    const el = document.getElementById('as_'+i);
    if(el) target[set[i].lessonId] = el.value;
  }
  const result = await saveToServer({ type: kind, answers: target });
  if(!result.ok){ toast('\u26a0\ufe0f Could not save -- check your connection and try again.'); return; }

  if(kind === 'pre'){ PROGRESS.preAnswers = target; if(!result.already_done){ PROGRESS.preDone = true; PROGRESS.xp += result.xp_awarded; } }
  else { PROGRESS.postAnswers = target; if(!result.already_done){ PROGRESS.postDone = true; PROGRESS.xp += result.xp_awarded; } }

  window.speechSynthesis && window.speechSynthesis.cancel();
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">\ud83d\udcee</div>
    <h2>${result.already_done ? 'Answers Updated!' : 'Sent to Your Teacher!'}</h2>
    <p>${result.already_done ? 'Your teacher will see your updated answers.' : "Great job! You earned +" + result.xp_awarded + " XP for completing the " + (kind==='pre'?'Pre-Assessment':'Post-Assessment') + "."}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Sound Path</button>
  </div>`;
  document.body.appendChild(div);
}

/* ============ BOOT ============ */
renderMap();
