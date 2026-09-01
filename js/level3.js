/* BULIG — Level 3 Word Recognition quest engine
   PHP supplies SERVER_STATE + SAVE_URL before this file loads.

   This build maps the FULL 144-page "Level 3 - Word Recognition" module:
   25 Lessons in the module's own order --
     1-5   CVC short vowels (a, e, i, o, u)
     6-10  Long-vowel word families (ea/ai, oa/oo, ack/eck, all/ell, -nk/-sk)
     11-20 Consonant blends (br-/bl-, cr-/cl-, dr-, fr-/fl-, gl-/gr-,
           pl-/pr-, st-/str-, sh-/sl-, sp-/spr-/spl-, tr-)
     21-25 Fry's Basic Sight Words, lists 1-5
   -- each containing every one of its real numbered Activities as
   sequential stages, exactly like Level 2A did for its 8 units.

   Every stage runs on one of THREE existing engines -- "quiz",
   "sequence" and "sentence" -- plus ONE new engine, "flashcard"
   (a word/phrase/sentence "read it aloud" card with a Read Aloud/TTS
   button and a "Got it!" button to advance -- the digital match for the
   module's own repeated "Speed Read" / "Word Dart Board" / "Grab and
   Read" / "Assessment (Individual Reading)" activities). "flashcard"
   reuses the exact same CSS classes as the other engines (.l1-card,
   .l1-btn, .l1-speak-btn, .l1-quiz-progress) -- no new styles, no new
   colors, nothing added to css/level1.css.

   Picture-only worksheets (Build a Word, Tell Me the Word, dart boards,
   picture/image-word associations, fill-in-the-blank picture challenges)
   have no pupil-facing images in this app (same as Level 1/2A, which
   never rendered photographs either), so each was digitized onto the
   engine that matches its real mechanic -- e.g. "fill in the missing
   blend" became a blend-choice quiz, "listen and match" became a
   listen-then-choose quiz, "picture dart board" (identify the blend)
   became a classification quiz. Every word bank below is copied
   verbatim from the module's own worksheets/answer keys/assessment
   pages (including its own quirks, e.g. Lesson 3's Speed Read really
   does print "sim" instead of "sit") -- nothing invented.
   "Supplementary Activity Materials" pages are optional printable
   teacher aids the TOC lists separately from the numbered Activities
   (same scoping Level 2A used), so they aren't separate pupil stages,
   except where they contain their own extra reading-word list (Lessons
   12 and 15), which was folded into that lesson's practice stage. */

/* ============ DATA ============ */
const LESSONS = [

 /* ================= LESSONS 1-5: CVC SHORT VOWELS ================= */
 {id:1, title:"Short \u2018a\u2019 Words", sub:"CVC Short \u201Ca\u201D", icon:"\ud83d\udc31", activities:[
  {title:"Build a Word", engine:"sequence", data:{intro:"Tap the letters in order to build each short-\u2018a\u2019 word.",
   rounds:["cab","fan","tap","mad","man","cat","bag","van","fat","rag"].map(w=>({cards:w.split('').map((ch,i)=>({id:String(i),icon:"\ud83d\udd24",label:ch})), correctOrder:w.split('').map((ch,i)=>String(i)), answerWord:w}))}},
  {title:"Odd Word Out", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong?",options:["bag","rag","fan","ten"],correct:3},
   {q:"Which word does NOT belong?",options:["map","hit","tap","lap"],correct:1},
   {q:"Which word does NOT belong?",options:["cab","bed","mad","mat"],correct:1},
   {q:"Which word does NOT belong?",options:["hen","hat","fat","cat"],correct:0},
   {q:"Which word does NOT belong?",options:["mad","man","van","den"],correct:3},
   {q:"Which word does NOT belong?",options:["sit","jam","map","cab"],correct:0},
   {q:"Which word does NOT belong?",options:["fan","man","tap","pit"],correct:3},
   {q:"Which word does NOT belong?",options:["set","mat","hat","fat"],correct:0},
   {q:"Which word does NOT belong?",options:["map","tap","hip","lap"],correct:2},
   {q:"Which word does NOT belong?",options:["man","van","jam","men"],correct:3}
  ]}},
  {title:"Tell Me the Word", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["lap","tap","cap"],correct:0,say:"lap"},
   {q:"Listen: which word do you hear?",options:["hat","hut","hit"],correct:0,say:"hat"},
   {q:"Listen: which word do you hear?",options:["jam","jab","jog"],correct:0,say:"jam"},
   {q:"Listen: which word do you hear?",options:["map","mop","mat"],correct:0,say:"map"},
   {q:"Listen: which word do you hear?",options:["mat","mad","man"],correct:0,say:"mat"}
  ]}},
  {title:"Speed Read", engine:"flashcard", data:{intro:"Read each word quickly and clearly, then tap Got it!",
   items:["bag","rag","fan","map","tap","lap","cab","mad","mat","hat","fat","cat","man","van","jam"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["cab","tap","mad","man","bag","van","rag","fat","jam","map"]}}
 ]},

 {id:2, title:"Short \u2018e\u2019 Words", sub:"CVC Short \u201Ce\u201D", icon:"\ud83d\udc14", activities:[
  {title:"Build a Word", engine:"sequence", data:{intro:"Tap the letters in order to build each short-\u2018e\u2019 word.",
   rounds:["bed","men","set","red","pen","vet","wed","ten","wet","den"].map(w=>({cards:w.split('').map((ch,i)=>({id:String(i),icon:"\ud83d\udd24",label:ch})), correctOrder:w.split('').map((ch,i)=>String(i)), answerWord:w}))}},
  {title:"Odd Word Out", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong?",options:["fan","pen","ten","jet"],correct:0},
   {q:"Which word does NOT belong?",options:["wet","mat","keg","leg"],correct:1},
   {q:"Which word does NOT belong?",options:["bed","red","mad","wed"],correct:2},
   {q:"Which word does NOT belong?",options:["den","man","hen","men"],correct:1},
   {q:"Which word does NOT belong?",options:["pet","set","van","vet"],correct:2},
   {q:"Which word does NOT belong?",options:["hit","keg","leg","web"],correct:0},
   {q:"Which word does NOT belong?",options:["wed","dot","den","hen"],correct:1},
   {q:"Which word does NOT belong?",options:["red","ten","pin","leg"],correct:2},
   {q:"Which word does NOT belong?",options:["jet","pet","set","cat"],correct:3},
   {q:"Which word does NOT belong?",options:["vet","wet","tap","keg"],correct:2}
  ]}},
  {title:"Tell Me the Word", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["jet","jot","jut"],correct:0,say:"jet"},
   {q:"Listen: which word do you hear?",options:["keg","kag","kig"],correct:0,say:"keg"},
   {q:"Listen: which word do you hear?",options:["hen","hin","hun"],correct:0,say:"hen"},
   {q:"Listen: which word do you hear?",options:["pet","pit","pat"],correct:0,say:"pet"},
   {q:"Listen: which word do you hear?",options:["leg","log","lag"],correct:0,say:"leg"}
  ]}},
  {title:"Speed Read", engine:"flashcard", data:{intro:"Read each word quickly and clearly, then tap Got it!",
   items:["bed","red","wed","den","hen","men","pen","ten","jet","pet","set","vet","wet","keg","leg"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["bed","pet","red","vet","den","wet","hen","keg","jet","leg"]}}
 ]},

 {id:3, title:"Short \u2018i\u2019 Words", sub:"CVC Short \u201Ci\u201D", icon:"\ud83d\udc37", activities:[
  {title:"Build a Word", engine:"sequence", data:{intro:"Tap the letters in order to build each short-\u2018i\u2019 word.",
   rounds:["hid","tin","pit","kid","hip","sit","lid","lip","dig","bin"].map(w=>({cards:w.split('').map((ch,i)=>({id:String(i),icon:"\ud83d\udd24",label:ch})), correctOrder:w.split('').map((ch,i)=>String(i)), answerWord:w}))}},
  {title:"Odd Word Out", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong?",options:["hip","hat","kid","lid"],correct:1},
   {q:"Which word does NOT belong?",options:["bin","pin","pan","tin"],correct:2},
   {q:"Which word does NOT belong?",options:["hop","hip","lip","fit"],correct:0},
   {q:"Which word does NOT belong?",options:["dig","fog","pig","wig"],correct:1},
   {q:"Which word does NOT belong?",options:["pit","sit","sat","dig"],correct:2},
   {q:"Which word does NOT belong?",options:["van","lid","bin","fit"],correct:0},
   {q:"Which word does NOT belong?",options:["wig","son","bin","kid"],correct:1},
   {q:"Which word does NOT belong?",options:["hit","pit","hat","kid"],correct:2},
   {q:"Which word does NOT belong?",options:["fat","dig","lid","sit"],correct:0},
   {q:"Which word does NOT belong?",options:["bin","man","tin","pit"],correct:1}
  ]}},
  {title:"Tell Me the Word", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["fit","fat","fut"],correct:0,say:"fit"},
   {q:"Listen: which word do you hear?",options:["pig","peg","pug"],correct:0,say:"pig"},
   {q:"Listen: which word do you hear?",options:["pin","pen","pan"],correct:0,say:"pin"},
   {q:"Listen: which word do you hear?",options:["hit","hot","hat"],correct:0,say:"hit"},
   {q:"Listen: which word do you hear?",options:["wig","wag","weg"],correct:0,say:"wig"}
  ]}},
  {title:"Speed Read", engine:"flashcard", data:{intro:"Read each word quickly and clearly, then tap Got it!",
   items:["hip","kid","lid","bin","pin","tin","sim","lip","fit","hit","pit","sit","dig","pig","wig"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["hid","hip","kid","fit","lid","sit","bin","dig","pin","wig"]}}
 ]},

 {id:4, title:"Short \u2018o\u2019 Words", sub:"CVC Short \u201Co\u201D", icon:"\ud83d\udc38", activities:[
  {title:"Build a Word", engine:"sequence", data:{intro:"Tap the letters in order to build each short-\u2018o\u2019 word.",
   rounds:["cob","hog","cop","job","jog","son","rob","log","hop","nod"].map(w=>({cards:w.split('').map((ch,i)=>({id:String(i),icon:"\ud83d\udd24",label:ch})), correctOrder:w.split('').map((ch,i)=>String(i)), answerWord:w}))}},
  {title:"Odd Word Out", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong?",options:["jog","pig","log","mom"],correct:1},
   {q:"Which word does NOT belong?",options:["hip","nod","fog","hog"],correct:0},
   {q:"Which word does NOT belong?",options:["cop","son","men","hop"],correct:2},
   {q:"Which word does NOT belong?",options:["box","pin","cop","son"],correct:1},
   {q:"Which word does NOT belong?",options:["box","cop","nod","lid"],correct:3},
   {q:"Which word does NOT belong?",options:["leg","jog","log","mom"],correct:0},
   {q:"Which word does NOT belong?",options:["son","pin","hop","dot"],correct:1},
   {q:"Which word does NOT belong?",options:["cob","job","rob","pit"],correct:3},
   {q:"Which word does NOT belong?",options:["mom","hit","son","hot"],correct:1},
   {q:"Which word does NOT belong?",options:["pig","nod","fog","hog"],correct:0}
  ]}},
  {title:"Tell Me the Word", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["mom","mam","mum"],correct:0,say:"mom"},
   {q:"Listen: which word do you hear?",options:["dot","dit","dat"],correct:0,say:"dot"},
   {q:"Listen: which word do you hear?",options:["fog","fig","fag"],correct:0,say:"fog"},
   {q:"Listen: which word do you hear?",options:["box","bax","bix"],correct:0,say:"box"},
   {q:"Listen: which word do you hear?",options:["hot","hat","hit"],correct:0,say:"hot"}
  ]}},
  {title:"Speed Read", engine:"flashcard", data:{intro:"Read each word quickly and clearly, then tap Got it!",
   items:["cob","job","rob","nod","fog","hog","jog","log","mom","box","cop","son","hop","dot","hot"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["cob","hot","job","log","mom","son","nod","fog","rob","cop"]}}
 ]},

 {id:5, title:"Short \u2018u\u2019 Words", sub:"CVC Short \u201Cu\u201D", icon:"\ud83d\udc30", activities:[
  {title:"Build a Word", engine:"sequence", data:{intro:"Tap the letters in order to build each short-\u2018u\u2019 word.",
   rounds:["cub","bug","run","tub","mug","sun","mud","tug","nut","gum"].map(w=>({cards:w.split('').map((ch,i)=>({id:String(i),icon:"\ud83d\udd24",label:ch})), correctOrder:w.split('').map((ch,i)=>String(i)), answerWord:w}))}},
  {title:"Odd Word Out", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong?",options:["bug","beg","mug","tug"],correct:1},
   {q:"Which word does NOT belong?",options:["gum","yum","gem","bug"],correct:2},
   {q:"Which word does NOT belong?",options:["fin","bun","fun","run"],correct:0},
   {q:"Which word does NOT belong?",options:["sun","nut","sit","cup"],correct:2},
   {q:"Which word does NOT belong?",options:["fun","fat","run","sun"],correct:1},
   {q:"Which word does NOT belong?",options:["tub","mud","gum","van"],correct:3},
   {q:"Which word does NOT belong?",options:["gum","yum","sin","sun"],correct:2},
   {q:"Which word does NOT belong?",options:["tug","jog","cup","bus"],correct:1},
   {q:"Which word does NOT belong?",options:["can","sun","nut","mud"],correct:0},
   {q:"Which word does NOT belong?",options:["cup","tug","mug","map"],correct:3}
  ]}},
  {title:"Tell Me the Word", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["bun","ban","bin"],correct:0,say:"bun"},
   {q:"Listen: which word do you hear?",options:["cup","cap","cop"],correct:0,say:"cup"},
   {q:"Listen: which word do you hear?",options:["yum","yam","yim"],correct:0,say:"yum"},
   {q:"Listen: which word do you hear?",options:["fun","fan","fin"],correct:0,say:"fun"},
   {q:"Listen: which word do you hear?",options:["bus","bas","bos"],correct:0,say:"bus"}
  ]}},
  {title:"Speed Read", engine:"flashcard", data:{intro:"Read each word quickly and clearly, then tap Got it!",
   items:["cub","tub","mud","gum","yum","bug","mug","tug","bun","fun","run","sun","nut","cup","bus"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["tug","mud","cub","sun","bun","mug","gum","cup","nut","bus"]}}
 ]},

 /* ================= LESSONS 6-10: WORD FAMILIES ================= */
 {id:6, title:"\u201Cai\u201D and \u201Cea\u201D", sub:"Word Family", icon:"\ud83c\udf27\ufe0f", activities:[
  {title:"Word Sorting Worksheet", engine:"quiz", data:{questions:
   ["bait","nail","pail","tail","rain"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/ai/","/ea/"],correct:0}))
   .concat(["meal","hear","meat","peak","seat"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/ai/","/ea/"],correct:1})))}},
  {title:"Listen & Match Worksheet", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["nail","tail","pail"],correct:0,say:"nail"},
   {q:"Listen: which word do you hear?",options:["rain","rail","ran"],correct:0,say:"rain"},
   {q:"Listen: which word do you hear?",options:["tail","nail","tale"],correct:0,say:"tail"},
   {q:"Listen: which word do you hear?",options:["pail","pain","tail"],correct:0,say:"pail"},
   {q:"Listen: which word do you hear?",options:["hair","hear","hare"],correct:0,say:"hair"},
   {q:"Listen: which word do you hear?",options:["meal","meat","seal"],correct:0,say:"meal"},
   {q:"Listen: which word do you hear?",options:["meat","meal","seat"],correct:0,say:"meat"},
   {q:"Listen: which word do you hear?",options:["seat","seal","meat"],correct:0,say:"seat"},
   {q:"Listen: which word do you hear?",options:["peak","peek","pea"],correct:0,say:"peak"},
   {q:"Listen: which word do you hear?",options:["hear","hair","here"],correct:0,say:"hear"}
  ]}},
  {title:"Blend & Say Worksheet", engine:"sequence", data:{intro:"Build, blend, and say each word aloud.",
   rounds:[["p","ail"],["r","ain"],["h","air"],["t","ail"],["n","ail"],["s","eat"],["h","ear"],["m","eal"],["m","eat"],["p","eak"]]
    .map(([a,b])=>({cards:[{id:"a",icon:"\ud83d\udd24",label:a},{id:"b",icon:"\ud83d\udd24",label:b}], correctOrder:["a","b"], answerWord:a+b}))}},
  {title:"Spell & Complete Worksheet", engine:"sentence", data:{intro:"Write the missing letters to complete each word.",
   fields:[
    {label:"n _ _ l = ?", placeholder:"nail"},{label:"t _ _ l = ?", placeholder:"tail"},{label:"h _ _ r = ?", placeholder:"hair"},
    {label:"p _ _ l = ?", placeholder:"pail"},{label:"r _ _ n = ?", placeholder:"rain"},{label:"h _ _ r = ?", placeholder:"hear"},
    {label:"m _ _ l = ?", placeholder:"meal"},{label:"m _ _ t = ?", placeholder:"meat"},{label:"p _ _ k = ?", placeholder:"peak"},
    {label:"s _ _ t = ?", placeholder:"seat"}
   ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["nail","meal","rain","meat","tail","seat","pail","peak","hair","head"]}}
 ]},

 {id:7, title:"\u201Coa\u201D and \u201Coo\u201D", sub:"Word Family", icon:"\ud83d\udc10", activities:[
  {title:"Word Sorting Worksheet", engine:"quiz", data:{questions:
   ["goat","coat","foam","boat","road"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/oa/","/oo/"],correct:0}))
   .concat(["food","moon","roof","pool","spoon"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/oa/","/oo/"],correct:1})))}},
  {title:"Listen & Match Worksheet", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["boat","coat","goat"],correct:0,say:"boat"},
   {q:"Listen: which word do you hear?",options:["book","boot","look"],correct:0,say:"book"},
   {q:"Listen: which word do you hear?",options:["goat","coat","boat"],correct:0,say:"goat"},
   {q:"Listen: which word do you hear?",options:["road","roof","rod"],correct:0,say:"road"},
   {q:"Listen: which word do you hear?",options:["pool","poor","pull"],correct:0,say:"pool"},
   {q:"Listen: which word do you hear?",options:["roof","road","roost"],correct:0,say:"roof"},
   {q:"Listen: which word do you hear?",options:["moon","moan","mood"],correct:0,say:"moon"},
   {q:"Listen: which word do you hear?",options:["soap","soup","seep"],correct:0,say:"soap"},
   {q:"Listen: which word do you hear?",options:["coat","cot","coot"],correct:0,say:"coat"},
   {q:"Listen: which word do you hear?",options:["boot","boat","book"],correct:0,say:"boot"}
  ]}},
  {title:"Blend & Say Worksheet", engine:"sequence", data:{intro:"Build, blend, and say each word aloud.",
   rounds:[["b","oot"],["b","oat"],["g","oat"],["r","oof"],["f","oam"],["c","oat"],["m","oon"],["r","oad"],["p","ool"],["f","ood"]]
    .map(([a,b])=>({cards:[{id:"a",icon:"\ud83d\udd24",label:a},{id:"b",icon:"\ud83d\udd24",label:b}], correctOrder:["a","b"], answerWord:a+b}))}},
  {title:"Spell & Complete Worksheet", engine:"sentence", data:{intro:"Write the missing letters to complete each word.",
   fields:[
    {label:"b _ _ t = ?", placeholder:"boat"},{label:"r _ _ d = ?", placeholder:"road"},{label:"c _ _ t = ?", placeholder:"coat"},
    {label:"g _ _ t = ?", placeholder:"goat"},{label:"f _ _ m = ?", placeholder:"foam"},{label:"b _ _ t = ?", placeholder:"boot"},
    {label:"r _ _ f = ?", placeholder:"roof"},{label:"f _ _ d = ?", placeholder:"food"},{label:"m _ _ n = ?", placeholder:"moon"},
    {label:"p _ _ l = ?", placeholder:"pool"}
   ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["boat","roof","foam","moon","goat","food","road","pool","coat","book"]}}
 ]},

 {id:8, title:"\u201Cack\u201D and \u201Ceck\u201D", sub:"Word Family", icon:"\ud83c\udf92", activities:[
  {title:"Word Sorting Worksheet", engine:"quiz", data:{questions:
   ["pack","back","sack","hack","black"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/ack/","/eck/"],correct:0}))
   .concat(["beck","deck","heck","neck","peck"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/ack/","/eck/"],correct:1})))}},
  {title:"Listen & Match Worksheet", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["pack","back","tack"],correct:0,say:"pack"},
   {q:"Listen: which word do you hear?",options:["back","pack","black"],correct:0,say:"back"},
   {q:"Listen: which word do you hear?",options:["peck","deck","neck"],correct:0,say:"peck"},
   {q:"Listen: which word do you hear?",options:["sack","hack","tack"],correct:0,say:"sack"},
   {q:"Listen: which word do you hear?",options:["neck","peck","deck"],correct:0,say:"neck"},
   {q:"Listen: which word do you hear?",options:["black","back","black"],correct:0,say:"black"},
   {q:"Listen: which word do you hear?",options:["hack","sack","back"],correct:0,say:"hack"},
   {q:"Listen: which word do you hear?",options:["beck","deck","peck"],correct:0,say:"beck"},
   {q:"Listen: which word do you hear?",options:["deck","beck","heck"],correct:0,say:"deck"},
   {q:"Listen: which word do you hear?",options:["heck","neck","deck"],correct:0,say:"heck"}
  ]}},
  {title:"Blend & Say Worksheet", engine:"sequence", data:{intro:"Build, blend, and say each word aloud.",
   rounds:[["b","ack"],["b","eck"],["h","ack"],["s","ack"],["n","eck"],["p","eck"],["bl","ack"],["h","eck"],["p","ack"],["d","eck"]]
    .map(([a,b])=>({cards:[{id:"a",icon:"\ud83d\udd24",label:a},{id:"b",icon:"\ud83d\udd24",label:b}], correctOrder:["a","b"], answerWord:a+b}))}},
  {title:"Spell & Complete Worksheet", engine:"sentence", data:{intro:"Write the missing letters to complete each word.",
   fields:[
    {label:"l _ ck = ?", placeholder:"lack"},{label:"r _ ck = ?", placeholder:"rack"},{label:"j _ ck = ?", placeholder:"jack"},
    {label:"tr _ ck = ?", placeholder:"track"},{label:"sn _ ck = ?", placeholder:"snack"},{label:"ch _ ck = ?", placeholder:"check"},
    {label:"wr _ ck = ?", placeholder:"wreck"},{label:"h _ ck = ?", placeholder:"heck"},{label:"d _ ck = ?", placeholder:"deck"},
    {label:"n _ ck = ?", placeholder:"neck"}
   ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["pack","sack","back","hack","beck","heck","deck","neck","peck","black"]}}
 ]},

 {id:9, title:"\u201Call\u201D and \u201Cell\u201D", sub:"Word Family", icon:"\ud83d\udd14", activities:[
  {title:"Word Sorting Worksheet", engine:"quiz", data:{questions:
   ["ball","call","fall","tall","wall"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/all/","/ell/"],correct:0}))
   .concat(["bell","sell","tell","well","shell"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["/all/","/ell/"],correct:1})))}},
  {title:"Listen & Match Worksheet", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["ball","tall","call"],correct:0,say:"ball"},
   {q:"Listen: which word do you hear?",options:["call","ball","fall"],correct:0,say:"call"},
   {q:"Listen: which word do you hear?",options:["wall","tall","ball"],correct:0,say:"wall"},
   {q:"Listen: which word do you hear?",options:["tall","wall","fall"],correct:0,say:"tall"},
   {q:"Listen: which word do you hear?",options:["small","tall","smell"],correct:0,say:"small"},
   {q:"Listen: which word do you hear?",options:["bell","sell","tell"],correct:0,say:"bell"},
   {q:"Listen: which word do you hear?",options:["well","bell","sell"],correct:0,say:"well"},
   {q:"Listen: which word do you hear?",options:["sell","tell","well"],correct:0,say:"sell"},
   {q:"Listen: which word do you hear?",options:["shell","smell","sell"],correct:0,say:"shell"},
   {q:"Listen: which word do you hear?",options:["smell","shell","spell"],correct:0,say:"smell"}
  ]}},
  {title:"Blend & Say Worksheet", engine:"sequence", data:{intro:"Build, blend, and say each word aloud.",
   rounds:[["b","all"],["b","ell"],["c","all"],["sm","ell"],["f","all"],["t","ell"],["t","all"],["s","ell"],["w","all"],["sh","ell"]]
    .map(([a,b])=>({cards:[{id:"a",icon:"\ud83d\udd24",label:a},{id:"b",icon:"\ud83d\udd24",label:b}], correctOrder:["a","b"], answerWord:a+b}))}},
  {title:"Spell & Complete Worksheet", engine:"sentence", data:{intro:"Write the missing letters to complete each word.",
   fields:[
    {label:"w _ ll = ?", placeholder:"well"},{label:"s _ ll = ?", placeholder:"sell"},{label:"b _ ll = ?", placeholder:"ball"},
    {label:"c _ ll = ?", placeholder:"call"},{label:"sm _ ll = ?", placeholder:"smell"},{label:"sh _ ll = ?", placeholder:"shell"},
    {label:"f _ ll = ?", placeholder:"fall"},{label:"t _ ll = ?", placeholder:"tall"},{label:"t _ ll = ?", placeholder:"tell"},
    {label:"b _ ll = ?", placeholder:"ball"}
   ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["ball","call","bell","smell","fall","tall","sell","wall","shell","tell"]}}
 ]},

 {id:10, title:"\u201C-nk\u201D and \u201C-sk\u201D", sub:"Word Family", icon:"\ud83c\udf9a\ufe0f", activities:[
  {title:"Word Sorting Worksheet", engine:"quiz", data:{questions:
   ["tank","trunk","wink","link","plank"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["-nk","-sk"],correct:0}))
   .concat(["mask","tusk","flask","desk","whisk"].map(w=>({q:"Which word family does \u2018"+w+"\u2019 belong to?",options:["-nk","-sk"],correct:1})))}},
  {title:"Listen & Match Worksheet", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["mask","mash","mast"],correct:0,say:"mask"},
   {q:"Listen: which word do you hear?",options:["pink","pin","ping"],correct:0,say:"pink"},
   {q:"Listen: which word do you hear?",options:["tank","tang","tan"],correct:0,say:"tank"},
   {q:"Listen: which word do you hear?",options:["wink","wing","win"],correct:0,say:"wink"},
   {q:"Listen: which word do you hear?",options:["flask","flag","flash"],correct:0,say:"flask"},
   {q:"Listen: which word do you hear?",options:["ask","ass","asp"],correct:0,say:"ask"},
   {q:"Listen: which word do you hear?",options:["drink","drank","drip"],correct:0,say:"drink"},
   {q:"Listen: which word do you hear?",options:["desk","deck","dusk"],correct:0,say:"desk"},
   {q:"Listen: which word do you hear?",options:["bank","band","bang"],correct:0,say:"bank"},
   {q:"Listen: which word do you hear?",options:["whisk","wish","whist"],correct:0,say:"whisk"}
  ]}},
  {title:"Blend & Say Worksheet", engine:"sequence", data:{intro:"Build, blend, and say each word aloud.",
   rounds:[["p","ink"],["m","ask"],["fl","ask"],["w","ink"],["t","ask"],["pl","ank"],["t","ank"],["d","esk"],["tr","unk"],["wh","isk"]]
    .map(([a,b])=>({cards:[{id:"a",icon:"\ud83d\udd24",label:a},{id:"b",icon:"\ud83d\udd24",label:b}], correctOrder:["a","b"], answerWord:a+b}))}},
  {title:"Spell & Complete Worksheet", engine:"sentence", data:{intro:"Write the missing letters to complete each word.",
   fields:[
    {label:"wh _ sk = ?", placeholder:"whisk"},{label:"tr _ nk = ?", placeholder:"trunk"},{label:"d _ sk = ?", placeholder:"desk"},
    {label:"t _ nk = ?", placeholder:"tank"},{label:"pl _ nk = ?", placeholder:"plank"},{label:"t _ sk = ?", placeholder:"task"},
    {label:"w _ nk = ?", placeholder:"wink"},{label:"fl _ sk = ?", placeholder:"flask"},{label:"m _ sk = ?", placeholder:"mask"},
    {label:"l _ nk = ?", placeholder:"link"}
   ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["pink","mask","flask","wink","task","tank","bank","desk","drink","flask"]}}
 ]},

 /* ================= LESSONS 11-20: CONSONANT BLENDS ================= */
 {id:11, title:"Blend br-, bl-", sub:"Consonant Blend", icon:"\ud83e\uddf1", activities:[
  {title:"Fill-in-the-Blank Challenge", engine:"quiz", data:{questions:[
   {q:"Which blend completes \u2018__ender\u2019 to make \u2018blender\u2019?",options:["bl-","br-"],correct:0},
   {q:"Which blend completes \u2018__ick\u2019 to make \u2018brick\u2019?",options:["bl-","br-"],correct:1},
   {q:"Which blend completes \u2018__ack\u2019 to make \u2018black\u2019?",options:["bl-","br-"],correct:0},
   {q:"Which blend completes \u2018__anket\u2019 to make \u2018blanket\u2019?",options:["bl-","br-"],correct:0},
   {q:"Which blend completes \u2018__ind\u2019 to make \u2018blind\u2019?",options:["bl-","br-"],correct:0},
   {q:"Which blend completes \u2018__oken\u2019 to make \u2018broken\u2019?",options:["bl-","br-"],correct:1},
   {q:"Which blend completes \u2018__oom\u2019 to make \u2018broom\u2019?",options:["bl-","br-"],correct:1},
   {q:"Which blend completes \u2018__ock\u2019 to make \u2018block\u2019?",options:["bl-","br-"],correct:0},
   {q:"Which blend completes \u2018__ood\u2019 to make \u2018blood\u2019?",options:["bl-","br-"],correct:0},
   {q:"Which blend completes \u2018__ush\u2019 to make \u2018brush\u2019?",options:["bl-","br-"],correct:1}
  ]}},
  {title:"Word Dart Board", engine:"flashcard", data:{intro:"Tap each word like you're throwing a dart -- read it aloud!",
   items:["blender","brick","black","blanket","blind","broken","broom","block","blood","brush"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["bread","blue","broke","blind","brush","blood","broom","blade","brick","blanket"]}}
 ]},

 {id:12, title:"Blend cr-, cl-", sub:"Consonant Blend", icon:"\ud83e\udd80", activities:[
  {title:"Picture Dart Board", engine:"quiz", data:{questions:
   ["clock","cloud","climb","clap","clown"].map(w=>({q:"Does \u2018"+w+"\u2019 begin with cl- or cr-?",options:["cl-","cr-"],correct:0}))
   .concat(["crown","crab","crow","crawl","crocodile"].map(w=>({q:"Does \u2018"+w+"\u2019 begin with cl- or cr-?",options:["cl-","cr-"],correct:1})))}},
  {title:"Image-Word Association", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["crab","crown","clap"],correct:0,say:"crab"},
   {q:"Listen: which word do you hear?",options:["clock","clown","crow"],correct:0,say:"clock"},
   {q:"Listen: which word do you hear?",options:["cloud","crowd","clown"],correct:0,say:"cloud"},
   {q:"Listen: which word do you hear?",options:["crow","crown","cloud"],correct:0,say:"crow"},
   {q:"Listen: which word do you hear?",options:["crown","clown","crow"],correct:0,say:"crown"},
   {q:"Listen: which word do you hear?",options:["climb","clap","crab"],correct:0,say:"climb"},
   {q:"Listen: which word do you hear?",options:["crawl","clap","crab"],correct:0,say:"crawl"},
   {q:"Listen: which word do you hear?",options:["clown","crown","cloud"],correct:0,say:"clown"},
   {q:"Listen: which word do you hear?",options:["crawl","clap","climb"],correct:1,say:"clap"},
   {q:"Listen: which word do you hear?",options:["crocodile","clock","cloud"],correct:0,say:"crocodile"}
  ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["clock","crown","climb","crab","cloud","crow","clash","creep","clap","crocodile"]}}
 ]},

 {id:13, title:"Blend dr-", sub:"Consonant Blend", icon:"\ud83d\udc09", activities:[
  {title:"Map-Based Word Search", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["dragon","drum","dress"],correct:0,say:"dragon"},
   {q:"Listen: which word do you hear?",options:["drum","dragon","drop"],correct:0,say:"drum"},
   {q:"Listen: which word do you hear?",options:["dress","dry","drum"],correct:0,say:"dress"},
   {q:"Listen: which word do you hear?",options:["drink","drive","drop"],correct:0,say:"drink"},
   {q:"Listen: which word do you hear?",options:["drive","drink","drill"],correct:0,say:"drive"},
   {q:"Listen: which word do you hear?",options:["drill","drive","dry"],correct:0,say:"drill"},
   {q:"Listen: which word do you hear?",options:["draw","drop","dry"],correct:0,say:"draw"},
   {q:"Listen: which word do you hear?",options:["dribble","dress","drum"],correct:0,say:"dribble"},
   {q:"Listen: which word do you hear?",options:["drop","dress","draw"],correct:0,say:"drop"},
   {q:"Listen: which word do you hear?",options:["dry","draw","drum"],correct:0,say:"dry"}
  ]}},
  {title:"Word-Image Association", engine:"quiz", data:{questions:[
   {q:"Which is spelled correctly?",options:["dragon","dragen"],correct:0},
   {q:"Which is spelled correctly?",options:["drim","drum"],correct:1},
   {q:"Which is spelled correctly?",options:["dress","driss"],correct:0},
   {q:"Which is spelled correctly?",options:["drenk","drink"],correct:1},
   {q:"Which is spelled correctly?",options:["drive","drove"],correct:0},
   {q:"Which is spelled correctly?",options:["drell","drill"],correct:1},
   {q:"Which is spelled correctly?",options:["draw","droo"],correct:0},
   {q:"Which is spelled correctly?",options:["drobble","dribble"],correct:1},
   {q:"Which is spelled correctly?",options:["drap","drop"],correct:1},
   {q:"Which is spelled correctly?",options:["dry","droy"],correct:0}
  ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["dry","dress","drop","drum","drill","dragon","draw","drink","drive","dribble"]}}
 ]},

 {id:14, title:"Blend fr-, fl-", sub:"Consonant Blend", icon:"\ud83c\udf5f", activities:[
  {title:"Color My Word and Read Me!", engine:"quiz", data:{questions:[
   {q:"Which word begins with the fl- or fr- sound?",options:["flag","bag","rag"],correct:0},
   {q:"Which word begins with the fl- or fr- sound?",options:["frog","log","from"],correct:0},
   {q:"Which word begins with the fl- or fr- sound?",options:["cute","mute","flute"],correct:2},
   {q:"Which word begins with the fl- or fr- sound?",options:["free","tree","bee"],correct:0},
   {q:"Which word begins with the fl- or fr- sound?",options:["slash","cash","flash"],correct:2},
   {q:"Which word begins with the fl- or fr- sound?",options:["frame","game","name"],correct:0},
   {q:"Which word begins with the fl- or fr- sound?",options:["tip","sip","flip"],correct:2},
   {q:"Which word begins with the fl- or fr- sound?",options:["fresh","mesh","flesh"],correct:0},
   {q:"Which word begins with the fl- or fr- sound?",options:["flower","tower","power"],correct:0},
   {q:"Which word begins with the fl- or fr- sound?",options:["lost","cost","frost"],correct:2}
  ]}},
  {title:"Complete My Name", engine:"quiz", data:{questions:[
   {q:"Which blend completes \u2018__ashlight\u2019 to make \u2018flashlight\u2019?",options:["fl-","fr-"],correct:0},
   {q:"Which blend completes \u2018__uits\u2019 to make \u2018fruits\u2019?",options:["fl-","fr-"],correct:1},
   {q:"Which blend completes \u2018__ame\u2019 to make \u2018frame\u2019?",options:["fl-","fr-"],correct:1},
   {q:"Which blend completes \u2018__ower\u2019 to make \u2018flower\u2019?",options:["fl-","fr-"],correct:0},
   {q:"Which blend completes \u2018__ies\u2019 to make \u2018fries\u2019?",options:["fl-","fr-"],correct:1},
   {q:"Which blend completes \u2018__iends\u2019 to make \u2018friends\u2019?",options:["fl-","fr-"],correct:1},
   {q:"Which blend completes \u2018__ute\u2019 to make \u2018flute\u2019?",options:["fl-","fr-"],correct:0},
   {q:"Which blend completes \u2018__idge\u2019 to make \u2018fridge\u2019?",options:["fl-","fr-"],correct:1},
   {q:"Which blend completes \u2018__og\u2019 to make \u2018frog\u2019?",options:["fl-","fr-"],correct:1},
   {q:"Which blend completes \u2018__y\u2019 to make \u2018fly\u2019?",options:["fl-","fr-"],correct:0},
   {q:"Which blend completes \u2018__ame\u2019 to make \u2018flame\u2019?",options:["fl-","fr-"],correct:0},
   {q:"Which blend completes \u2018__ag\u2019 to make \u2018flag\u2019?",options:["fl-","fr-"],correct:0}
  ]}},
  {title:"Word Pick and Tell", engine:"flashcard", data:{intro:"Pick a word, sound out the blend, and read it aloud.",
   items:["fruits","frost","fries","floor","flower","flame"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["fly","fries","floor","frost","flower","fruits","flame","fridge","flashlight","frame"]}}
 ]},

 {id:15, title:"Blend gl-, gr-", sub:"Consonant Blend", icon:"\ud83c\udf47", activities:[
  {title:"Fine-Tuning Vocabulary", engine:"quiz", data:{questions:[
   {q:"Which is spelled correctly?",options:["globe","grobe"],correct:0},
   {q:"Which is spelled correctly?",options:["glapes","grapes"],correct:1},
   {q:"Which is spelled correctly?",options:["gloves","groves"],correct:0},
   {q:"Which is spelled correctly?",options:["gleen","green"],correct:1},
   {q:"Which is spelled correctly?",options:["glass","grass"],correct:0},
   {q:"Which is spelled correctly?",options:["graph","glaph"],correct:0},
   {q:"Which is spelled correctly?",options:["grow","glow"],correct:0},
   {q:"Which is spelled correctly?",options:["grill","glill"],correct:0},
   {q:"Which is spelled correctly?",options:["glad","grad"],correct:0},
   {q:"Which is spelled correctly?",options:["graduate","gladuate"],correct:0}
  ]}},
  {title:"Grab and Read!", engine:"flashcard", data:{intro:"Choose your favorite shirt and read the word on it loudly!",
   items:["grammar","grocery","glad","grade","group","greetings","glow","glory","gladiator","green","globe","grey"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["glad","grapes","globe","green","gloves","graph","glass","grill","glue","graduate"]}}
 ]},

 {id:16, title:"Blend pl-, pr-", sub:"Consonant Blend", icon:"\u2708\ufe0f", activities:[
  {title:"Fill-in-the-blank Challenge", engine:"quiz", data:{questions:[
   {q:"Which blend completes \u2018__ay\u2019 to make \u2018play\u2019?",options:["pl-","pr-"],correct:0},
   {q:"Which blend completes \u2018__ince\u2019 to make \u2018prince\u2019?",options:["pl-","pr-"],correct:1},
   {q:"Which blend completes \u2018__ate\u2019 to make \u2018plate\u2019?",options:["pl-","pr-"],correct:0},
   {q:"Which blend completes \u2018__int\u2019 to make \u2018print\u2019?",options:["pl-","pr-"],correct:1},
   {q:"Which blend completes \u2018__ane\u2019 to make \u2018plane\u2019?",options:["pl-","pr-"],correct:0},
   {q:"Which blend completes \u2018__ay\u2019 to make \u2018pray\u2019?",options:["pl-","pr-"],correct:1},
   {q:"Which blend completes \u2018__esent\u2019 to make \u2018present\u2019?",options:["pl-","pr-"],correct:1},
   {q:"Which blend completes \u2018__ice\u2019 to make \u2018price\u2019?",options:["pl-","pr-"],correct:1},
   {q:"Which blend completes \u2018__ier\u2019 to make \u2018plier\u2019?",options:["pl-","pr-"],correct:0},
   {q:"Which blend completes \u2018__us\u2019 to make \u2018plus\u2019?",options:["pl-","pr-"],correct:0}
  ]}},
  {title:"Word Dart Board", engine:"flashcard", data:{intro:"Throw a dart, read the word it lands on, and say it clearly.",
   items:["play","prince","plate","print","plane","pray","present","price","plier","plus"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["plate","pray","plane","print","plus","prince","plier","present","play","price"]}}
 ]},

 {id:17, title:"Blend st-, str-", sub:"Consonant Blend", icon:"\u26c8\ufe0f", activities:[
  {title:"Picture Dart Board", engine:"quiz", data:{questions:
   ["storm","stop","stone","steel","stick"].map(w=>({q:"Does \u2018"+w+"\u2019 begin with st- or str-?",options:["st-","str-"],correct:0}))
   .concat(["strap","street","string","stripes","strawberry"].map(w=>({q:"Does \u2018"+w+"\u2019 begin with st- or str-?",options:["st-","str-"],correct:1})))}},
  {title:"Word-Image Association", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["storm","strap","stop"],correct:0,say:"storm"},
   {q:"Listen: which word do you hear?",options:["strap","storm","street"],correct:0,say:"strap"},
   {q:"Listen: which word do you hear?",options:["stop","stone","strap"],correct:0,say:"stop"},
   {q:"Listen: which word do you hear?",options:["street","strap","stick"],correct:0,say:"street"},
   {q:"Listen: which word do you hear?",options:["stone","stick","stop"],correct:0,say:"stone"},
   {q:"Listen: which word do you hear?",options:["string","strap","street"],correct:0,say:"string"},
   {q:"Listen: which word do you hear?",options:["steel","stone","stop"],correct:0,say:"steel"},
   {q:"Listen: which word do you hear?",options:["stripes","string","strap"],correct:0,say:"stripes"},
   {q:"Listen: which word do you hear?",options:["stick","stone","steel"],correct:0,say:"stick"},
   {q:"Listen: which word do you hear?",options:["strawberry","string","stripes"],correct:0,say:"strawberry"}
  ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["storm","strap","stop","street","stone","string","steel","stripes","stick","strawberry"]}}
 ]},

 {id:18, title:"Blend sh-, sl-", sub:"Consonant Blend", icon:"\ud83d\udebf", activities:[
  {title:"Map-Based Word Search", engine:"quiz", data:{questions:
   ["shower","shine","shake","shovel","shock"].map(w=>({q:"Does \u2018"+w+"\u2019 begin with sh- or sl-?",options:["sh-","sl-"],correct:0}))
   .concat(["slide","slug","slice","slipped","slap"].map(w=>({q:"Does \u2018"+w+"\u2019 begin with sh- or sl-?",options:["sh-","sl-"],correct:1})))}},
  {title:"Word-Image Association", engine:"quiz", data:{questions:[
   {q:"Listen: which word do you hear?",options:["shower","slide","shine"],correct:0,say:"shower"},
   {q:"Listen: which word do you hear?",options:["slide","shine","slug"],correct:0,say:"slide"},
   {q:"Listen: which word do you hear?",options:["shine","shake","shower"],correct:0,say:"shine"},
   {q:"Listen: which word do you hear?",options:["slug","slice","slap"],correct:0,say:"slug"},
   {q:"Listen: which word do you hear?",options:["shake","shine","shovel"],correct:0,say:"shake"},
   {q:"Listen: which word do you hear?",options:["shovel","shock","shake"],correct:0,say:"shovel"},
   {q:"Listen: which word do you hear?",options:["slice","slug","slipped"],correct:0,say:"slice"},
   {q:"Listen: which word do you hear?",options:["slipped","slice","slap"],correct:0,say:"slipped"},
   {q:"Listen: which word do you hear?",options:["shock","shovel","shine"],correct:0,say:"shock"},
   {q:"Listen: which word do you hear?",options:["slap","slug","slide"],correct:0,say:"slap"}
  ]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["shower","slide","shine","slug","shake","slice","shovel","slipped","shock","slap"]}}
 ]},

 {id:19, title:"Blend sp-, spr-, spl-", sub:"Consonant Blend", icon:"\ud83d\udd77\ufe0f", activities:[
  {title:"Fill-in-the-blank Challenge", engine:"quiz", data:{questions:[
   {q:"Which blend completes \u2018__ade\u2019 to make \u2018spade\u2019?",options:["sp-","spr-","spl-"],correct:0},
   {q:"Which blend completes \u2018__ite\u2019 to make \u2018sprite\u2019?",options:["sp-","spr-","spl-"],correct:1},
   {q:"Which blend completes \u2018__ash\u2019 to make \u2018splash\u2019?",options:["sp-","spr-","spl-"],correct:2},
   {q:"Which blend completes \u2018__oon\u2019 to make \u2018spoon\u2019?",options:["sp-","spr-","spl-"],correct:0},
   {q:"Which blend completes \u2018__ain\u2019 to make \u2018sprain\u2019?",options:["sp-","spr-","spl-"],correct:1},
   {q:"Which blend completes \u2018__iral\u2019 to make \u2018spiral\u2019?",options:["sp-","spr-","spl-"],correct:0},
   {q:"Which blend completes \u2018__aghetti\u2019 to make \u2018spaghetti\u2019?",options:["sp-","spr-","spl-"],correct:0},
   {q:"Which blend completes \u2018__ay\u2019 to make \u2018spray\u2019?",options:["sp-","spr-","spl-"],correct:1},
   {q:"Which blend completes \u2018__onge\u2019 to make \u2018sponge\u2019?",options:["sp-","spr-","spl-"],correct:0},
   {q:"Which blend completes \u2018__inkler\u2019 to make \u2018sprinkler\u2019?",options:["sp-","spr-","spl-"],correct:1},
   {q:"Which blend completes \u2018__ider\u2019 to make \u2018spider\u2019?",options:["sp-","spr-","spl-"],correct:0},
   {q:"Which blend completes \u2018__lit\u2019 to make \u2018split\u2019?",options:["sp-","spr-","spl-"],correct:0}
  ]}},
  {title:"Word Pick and Tell", engine:"flashcard", data:{intro:"Pick a word, say the beginning blend, and read the whole word.",
   items:["spade","sprite","splash","spoon","sprain","spiral","spaghetti","spray","sponge","sprinkler","spider","split"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["spear","sprite","spoon","sprain","spider","spring","sponge","spray","spaghetti","sprinkle","splash","split"]}}
 ]},

 {id:20, title:"Blend tr-", sub:"Consonant Blend", icon:"\ud83d\ude82", activities:[
  {title:"Fine-Tuning Vocabulary", engine:"quiz", data:{questions:[
   {q:"Which is spelled correctly?",options:["tractor","fractor"],correct:0},
   {q:"Which is spelled correctly?",options:["priangle","triangle"],correct:1},
   {q:"Which is spelled correctly?",options:["trumpet","crumpet"],correct:0},
   {q:"Which is spelled correctly?",options:["traffic","craffic"],correct:0},
   {q:"Which is spelled correctly?",options:["trash","crash"],correct:0},
   {q:"Which is spelled correctly?",options:["trophy","crophy"],correct:0},
   {q:"Which is spelled correctly?",options:["creasure","treasure"],correct:1},
   {q:"Which is spelled correctly?",options:["tripod","cripod"],correct:0},
   {q:"Which is spelled correctly?",options:["tree","free"],correct:0},
   {q:"Which is spelled correctly?",options:["trolley","prolley"],correct:0}
  ]}},
  {title:"Grab and Read!", engine:"flashcard", data:{intro:"Pick a shirt, say \u2018I love this shirt,\u2019 and read the word aloud.",
   items:["tractor","triangle","trumpet","traffic","trash","trophy","treasure","tripod","tree","trolley"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["tractor","triangle","trumpet","traffic","trash","trophy","treasure","tripod","tree","trolley"]}}
 ]},

 /* ================= LESSONS 21-25: FRY'S BASIC SIGHT WORDS ================= */
 {id:21, title:"Sight Words \u2013 List 1", sub:"Fry\u2019s First 100", icon:"\ud83c\udfa3", activities:[
  {title:"Fishing Sight Words", engine:"flashcard", data:{intro:"Pick a fish from the basin and read the word on it aloud.",
   items:["said","were","one","come","does","two","they","their","what","where"]}},
  {title:"Reading Phrases", engine:"flashcard", data:{intro:"Read each phrase aloud.",
   items:["what they said","where they were","does it come","one or two","why they went"]}},
  {title:"Reading Simple Sentences", engine:"flashcard", data:{intro:"Read each sentence aloud.",
   items:["They said it was fun.","Where were you yesterday?","Does she come every day?","I saw one dog and two cats.","Why is their bag on the floor?"]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["said","were","one","come","does","two","they","their","what","where"]}}
 ]},

 {id:22, title:"Sight Words \u2013 List 2", sub:"Fry\u2019s 2nd 100", icon:"\ud83d\udc0d", activities:[
  {title:"Snake and Ladder Sight Words", engine:"flashcard", data:{intro:"Roll the dice, move to the number, and read the word on that space aloud.",
   items:["again","because","around","every","know","only","people","right","their","would"]}},
  {title:"Reading Phrases", engine:"flashcard", data:{intro:"Read each phrase aloud.",
   items:["again and again","because they know","around the corner","only the right answer","every people\u2019s story"]}},
  {title:"Reading Simple Sentences", engine:"flashcard", data:{intro:"Read each sentence aloud.",
   items:["I would go again tomorrow.","They walked around the park.","She knows every word.","People only want the truth.","Their answer was right."]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["again","because","around","every","know","only","people","right","their","would"]}}
 ]},

 {id:23, title:"Sight Words \u2013 List 3", sub:"Fry\u2019s 3rd 100", icon:"\ud83c\udfa1", activities:[
  {title:"Spin the Wheel of Sight Words", engine:"flashcard", data:{intro:"Spin the wheel and read the word it points to aloud.",
   items:["above","enough","through","though","young","laugh","once","both","always","together"]}},
  {title:"Reading Phrases", engine:"flashcard", data:{intro:"Read each phrase aloud.",
   items:["above the clouds","enough for everyone","through the door","young and strong","always together"]}},
  {title:"Reading Simple Sentences", engine:"flashcard", data:{intro:"Read each sentence aloud.",
   items:["She climbed above the hill.","I have enough food for lunch.","We walked through the park.","The young boy can laugh loudly.","They always work together."]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["above","enough","through","though","young","laugh","once","both","always","together"]}}
 ]},

 {id:24, title:"Sight Words \u2013 List 4", sub:"Fry\u2019s 4th 100", icon:"\ud83d\udce6", activities:[
  {title:"Mystery Box of Sight Words", engine:"flashcard", data:{intro:"Draw a word card from the Mystery Box and read it aloud.",
   items:["beautiful","country","different","important","against","enough","write","without","second","water"]}},
  {title:"Reading Phrases", engine:"flashcard", data:{intro:"Read each phrase aloud.",
   items:["beautiful country","different people","important work","against the wall","second water bottle"]}},
  {title:"Reading Simple Sentences", engine:"flashcard", data:{intro:"Read each sentence aloud.",
   items:["The beautiful flower is in the garden.","Our country is big and strong.","She has a different idea.","It is important to read every day.","He drank enough water after the game."]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["beautiful","country","different","important","against","enough","write","without","second","water"]}}
 ]},

 {id:25, title:"Sight Words \u2013 List 5", sub:"Fry\u2019s 5th 100", icon:"\ud83c\udf42", activities:[
  {title:"Sight Words Hunting", engine:"flashcard", data:{intro:"Find and read aloud each word hidden in the Word Hunt Table.",
   items:["already","certain","early","family","mountain","sentence","special","sure","toward","usually"]}},
  {title:"Reading Phrases", engine:"flashcard", data:{intro:"Read each phrase aloud.",
   items:["already finished work","certain answer","early morning","family dinner","special gift"]}},
  {title:"Reading Simple Sentences", engine:"flashcard", data:{intro:"Read each sentence aloud.",
   items:["She already knows the way.","I am certain this is right.","We woke up early today.","The family went to the park.","He climbed the mountain with friends."]}},
  {title:"Assessment", engine:"flashcard", data:{intro:"Individual Reading -- read each word correctly.",
   items:["already","certain","early","family","mountain","sentence","special","sure","toward","usually"]}}
 ]}
];

/* PRETEST/POSTTEST -- the module's own Level 3A + Level 3B Pre-/Post-
   Assessment Toolkits (4 reading tasks each), combined into one
   Pre-Assessment (start of Level 3) and one Post-Assessment (after all
   25 lessons), matching how Level 1 and Level 2A each run one pre/post
   pair. Every word is copied from the module's own toolkit pages. */
const PRETEST_TASKS = [
 {title:"Task 1: Short Vowels CVC Pattern", items:["bat","den","kid","fog","run","tap","hen","lip","box","gum"]},
 {title:"Task 2: Long Vowels Word Family", items:["meal","food","sack","ball","mask","pail","goat","neck","bell","tank"]},
 {title:"Task 3: Consonant Blends", items:["blue","clap","dress","flower","glass","bread","creep","drop","friend","grape","plan","pray","storm","string","ship","sleep","spoon","spring","split","trash"]},
 {title:"Task 4: Sight Words", items:["they","people","together","beautiful","sentence","come","because","about","enough","usually"]}
];
const POSTTEST_TASKS = [
 {title:"Task 1: Short Vowels CVC Pattern", items:["kid","run","bat","fog","den","lip","gum","tap","box","hen"]},
 {title:"Task 2: Long Vowels Word Family", items:["pail","goat","neck","bell","tank","meal","food","sack","ball","mask"]},
 {title:"Task 3: Consonant Blends", items:["bread","drop","creep","glass","friend","blue","dress","clap","grape","flower","pray","spoon","spring","split","trash","plan","sleep","storm","string","ship"]},
 {title:"Task 4: Sight Words", items:["come","because","about","enough","usually","they","people","together","beautiful","sentence"]}
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
let assessState = null;     // { kind, taskIndex, itemIndex, read:{} }

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
        <div class="l1-ntitle">${escapeHtml(lesson.title)}</div>
        <div class="l1-nsub">${escapeHtml(lesson.sub)}</div>
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
      <div class="stat-label">Level 3 Experience</div>
      <div class="stat-note">Earn 70 XP per lesson, 50 XP per assessment</div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">\ud83d\udcd6</span>
      <div class="stat-value">${PROGRESS.completed.length} / ${LESSONS.length}</div>
      <div class="stat-label">Lessons Completed</div>
      <div class="xp-bar-track"><div class="xp-bar-fill" style="width:${Math.round(PROGRESS.completed.length/LESSONS.length*100)}%"></div></div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">${allDone ? '\ud83c\udfc6' : '\ud83d\udd13'}</span>
      <div class="stat-value">${allDone ? 'Champion!' : (LESSONS.length - PROGRESS.completed.length) + ' to go'}</div>
      <div class="stat-label">${allDone ? 'Level 3 complete' : 'Lessons remaining'}</div>
      <div class="stat-note">${allDone ? "You've unlocked the Post-Assessment!" : 'Keep going -- you can do it!'}</div>
    </div>
  </div>

  <div class="action-grid">
    <a href="javascript:void(0)" class="action-card" onclick="openAssessment('pre')">
      <span class="action-icon">\ud83d\udcdd</span>
      <h3>Pre-Assessment</h3>
      <p>A quick reading check-in before you start -- your teacher will review it.</p>
      <span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.preDone ? 'Review \u2192' : 'Start \u2192'}</span>
    </a>
    <a href="javascript:void(0)" class="action-card ${allDone ? '' : 'is-soon'}" ${allDone ? "onclick=\"openAssessment('post')\"" : ''}>
      <span class="action-icon">${allDone ? '\ud83c\udfc5' : '\ud83d\udd12'}</span>
      <h3>Post-Assessment</h3>
      <p>${allDone ? "Show off everything you've learned!" : 'Unlocks after all 25 lessons are complete.'}</p>
      ${allDone ? `<span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.postDone ? 'Review \u2192' : 'Start \u2192'}</span>` : '<span class="pill-soon">Locked</span>'}
    </a>
  </div>

  <h2 class="section-title">\ud83d\uddfa\ufe0f Your Word Path</h2>
  <div class="l1-path">${nodesHtml}</div>`;
}

function openLesson(id){ openLessonState = { id, activityIndex:0, engineState:{} }; renderLessonOverlay(); }
function closeLesson(){
  window.speechSynthesis && window.speechSynthesis.cancel();
  const overlay = document.getElementById('lessonOverlay');
  if(overlay) overlay.remove();
  openLessonState = null;
  assessState = null;
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
        <div class="l1-ltitle">${lesson.icon} ${escapeHtml(lesson.title)}</div>
        <div class="l1-lsub">${escapeHtml(lesson.sub)} \u00b7 Activity ${openLessonState.activityIndex+1} of ${lesson.activities.length}: ${escapeHtml(activity.title)}</div>
      </div>
    </div>
    <div style="text-align:center;margin:6px 0 12px;">${dots}</div>
    <div class="l1-lesson-body" id="lessonBody"></div>
  `;
  renderEngine(activity);
}
function renderEngine(activity){
  const body = document.getElementById('lessonBody');
  const engines = { sentence: engineSentence, quiz: engineQuiz, sequence: engineSequence, flashcard: engineFlashcard };
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

/* ---- Sentence engine (reused for Spell & Complete worksheets) ---- */
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

/* ---- Quiz engine (reused across most activities) ---- */
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
  const speakText = q.say ? q.say : q.q;
  return `
    <div class="l1-quiz-progress">Question ${es.qIndex+1} of ${total}</div>
    <div class="l1-card">
      <div class="l1-quiz-q">${escapeHtml(q.q)}</div>
      <button class="l1-speak-btn" style="margin-bottom:12px;" onclick="speak(${attrJson(speakText)})">\ud83d\udd0a Read aloud</button>
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

/* ---- Sequence engine (reused for Build a Word / Blend & Say) ---- */
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
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">All built!</h3>
      <p>You built ${rounds.length} words. Great work!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="advanceActivity()">\u2705 Next</button></div>`;
  }
  const round = rounds[es.roundIndex];
  const remaining = round.cards.filter(c => !es.order.includes(c.id));
  const poolHtml = remaining.map(c => `<div class="l1-seq-card" onclick="pickSeq('${c.id}')"><div class="l1-ic">${c.icon}</div>${escapeHtml(c.label)}</div>`).join('') || '<div style="font-size:12px;color:var(--ink-soft);">All letters placed!</div>';
  const slotHtml = es.order.map((id,i) => {
    const c = round.cards.find(x=>x.id===id);
    return `<div class="l1-seq-card" style="background:var(--bulig-gold);">${i+1}. ${escapeHtml(c.label)}</div>`;
  }).join('');
  const complete = es.order.length === round.cards.length;
  let feedback = '';
  if(complete){
    const correct = JSON.stringify(es.order) === JSON.stringify(round.correctOrder);
    feedback = correct
      ? `<div class="l1-card" style="background:#DFF5E4;text-align:center;">\ud83c\udf89 Great job! Together those make "${escapeHtml(round.answerWord)}"!</div>`
      : `<div class="l1-card" style="background:#FBE3DF;text-align:center;">Good try! The order isn't quite right yet -- tap Reset and try again.</div>`;
  }
  return `
    <div class="l1-quiz-progress">Word ${es.roundIndex+1} of ${rounds.length}</div>
    <div class="l1-card"><p>${escapeHtml(activity.data.intro)}</p><button class="l1-speak-btn" onclick="speak(${attrJson(activity.data.intro)},0.85)">\ud83d\udd0a Read aloud</button></div>
    <div class="l1-card">
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Your word:</div>
      <div class="l1-seq-slot-row">${slotHtml}</div>
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Tap in order:</div>
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

/* ---- Flashcard engine (NEW -- reused for Speed Read, Word Dart Board,
   Grab and Read, phrases, sentences, and every lesson's Assessment.
   Reuses the exact same .l1-card / .l1-btn / .l1-speak-btn /
   .l1-quiz-progress classes as the other three engines -- no new CSS. ---- */
function engineFlashcard(activity){
  openLessonState.engineState = { index: 0 };
  return renderFlashcard(activity);
}
function renderFlashcard(activity){
  const es = openLessonState.engineState;
  const items = activity.data.items;
  if(es.index >= items.length){
    return `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">\ud83c\udf89</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">Nice reading!</h3>
      <p>You read all ${items.length}. Great job!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="advanceActivity()">\u2705 Next</button></div>`;
  }
  const item = items[es.index];
  return `
    <div class="l1-quiz-progress">${es.index+1} of ${items.length}</div>
    <div class="l1-card"><p>${escapeHtml(activity.data.intro)}</p></div>
    <div class="l1-card" style="text-align:center;">
      <div style="font-size:30px;font-weight:800;color:var(--bulig-green-dark);font-family:var(--font-display);margin:10px 0 16px;">${escapeHtml(item)}</div>
      <button class="l1-speak-btn" onclick="speak(${attrJson(item)},0.85)">\ud83d\udd0a Read Aloud</button>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="nextFlashcard()">\u2705 Got it!</button></div>`;
}
function nextFlashcard(){
  const activity = currentActivity();
  openLessonState.engineState.index++;
  document.getElementById('lessonBody').innerHTML = renderFlashcard(activity);
}

/* ============ FINISH / CELEBRATE (whole lesson -- all activities done) ============ */
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
    <h2>${allDone ? 'Word Recognition Champion!' : 'Lesson Complete!'}</h2>
    <p>${already ? "Nice replay! Keep practicing " + title + "." : "You finished every activity in " + icon + " " + title + " and earned +" + xpAwarded + " XP!"}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Word Path</button>
  </div>`;
  document.body.appendChild(div);
}
function backToMap(){
  document.querySelectorAll('.l1-celebrate').forEach(e=>e.remove());
  closeLesson();
  renderMap();
}

/* ============ PRE-/POST-ASSESSMENT (module's own Toolkit tasks) ============ */
function openAssessment(kind){
  assessState = { kind, taskIndex: 0 };
  renderAssessmentOverlay();
}
function assessmentTasks(){ return assessState.kind === 'pre' ? PRETEST_TASKS : POSTTEST_TASKS; }
function renderAssessmentOverlay(){
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className='l1-overlay l1-scope'; document.body.appendChild(overlay); }
  const label = assessState.kind === 'pre' ? 'Pre-Assessment' : 'Post-Assessment';
  const tasks = assessmentTasks();
  const dots = tasks.map((t,i) => `<span class="l1-quiz-progress" style="display:inline-block;margin:0 3px;padding:3px 9px;border-radius:20px;${i===assessState.taskIndex ? 'background:var(--bulig-green);color:#fff;' : i<assessState.taskIndex ? 'background:var(--bulig-gold);' : 'background:#eee;'}">${i+1}</span>`).join('');
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeLesson()">\u2715</button>
      <div>
        <div class="l1-ltitle">\ud83d\udcdd ${label}</div>
        <div class="l1-lsub">Individual Reading \u00b7 4 tasks from the Word Recognition Toolkit</div>
      </div>
    </div>
    <div style="text-align:center;margin:6px 0 12px;">${dots}</div>
    <div class="l1-lesson-body" id="lessonBody"></div>`;
  assessState.itemIndex = 0;
  document.getElementById('lessonBody').innerHTML = renderAssessmentTask();
}
function renderAssessmentTask(){
  const tasks = assessmentTasks();
  if(assessState.taskIndex >= tasks.length){
    const already = assessState.kind === 'pre' ? PROGRESS.preDone : PROGRESS.postDone;
    return `<div class="l1-card"><p>${already ? "You've already submitted this -- your teacher can see it. You can submit again to update it." : "Great job! Read every word, phrase, and sentence in each task the best you can -- your teacher will review this with you."}</p></div>
      <div class="l1-footer-actions"><button class="l1-btn" onclick="submitAssessment()">${already ? '\ud83d\udcbe Update' : '\u2705 Submit to Teacher'}</button></div>`;
  }
  const task = tasks[assessState.taskIndex];
  const item = task.items[assessState.itemIndex];
  return `
    <div class="l1-quiz-progress">${escapeHtml(task.title)} \u00b7 ${assessState.itemIndex+1} of ${task.items.length}</div>
    <div class="l1-card"><p>Direction: Read the following correctly.</p></div>
    <div class="l1-card" style="text-align:center;">
      <div style="font-size:${item.length>18?'22px':'30px'};font-weight:800;color:var(--bulig-green-dark);font-family:var(--font-display);margin:10px 0 16px;">${escapeHtml(item)}</div>
      <button class="l1-speak-btn" onclick="speak(${attrJson(item)},0.85)">\ud83d\udd0a Read Aloud</button>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="nextAssessmentItem()">\u2705 Got it!</button></div>`;
}
function nextAssessmentItem(){
  const tasks = assessmentTasks();
  const task = tasks[assessState.taskIndex];
  assessState.itemIndex++;
  if(assessState.itemIndex >= task.items.length){
    assessState.taskIndex++;
    assessState.itemIndex = 0;
  }
  document.getElementById('lessonBody').innerHTML = renderAssessmentTask();
}
async function submitAssessment(){
  const kind = assessState.kind;
  const answers = { completedTasks: assessmentTasks().length, submittedAt: new Date().toISOString() };
  const result = await saveToServer({ type: kind, answers });
  if(!result.ok){ toast('\u26a0\ufe0f Could not save -- check your connection and try again.'); return; }

  if(kind === 'pre'){ PROGRESS.preAnswers = answers; if(!result.already_done){ PROGRESS.preDone = true; PROGRESS.xp += result.xp_awarded; } }
  else { PROGRESS.postAnswers = answers; if(!result.already_done){ PROGRESS.postDone = true; PROGRESS.xp += result.xp_awarded; } }

  window.speechSynthesis && window.speechSynthesis.cancel();
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">\ud83d\udcee</div>
    <h2>${result.already_done ? 'Updated!' : 'Sent to Your Teacher!'}</h2>
    <p>${result.already_done ? 'Your teacher will see your updated reading check-in.' : "Great job! You earned +" + result.xp_awarded + " XP for completing the " + (kind==='pre'?'Pre-Assessment':'Post-Assessment') + "."}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Word Path</button>
  </div>`;
  document.body.appendChild(div);
}

/* ============ BOOT ============ */
renderMap();
