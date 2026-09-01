/* BULIG -- Level 2A Phonological Awareness quest engine (v3 -- 20 nodes)
   PHP supplies SERVER_STATE + SAVE_URL before this file loads.

   This build follows the 155-page Level 2A module's exact structure:
   8 categories (Lessons in the module) containing 20 numbered Activities
   in total -- and the quest map now has exactly 20 nodes, one per real
   module Activity, grouped under category headers on the path, in the
   same order the module presents them:

     Phoneme Isolation        -- Activities 1-3   (3 nodes)
     Phoneme Identification   -- Activities 4-6   (3 nodes)
     Phoneme Categorization   -- Activities 7-9   (3 nodes)
     Phoneme Blending         -- Activities 10-12 (3 nodes)
     Phoneme Segmentation     -- Activities 13-14 (2 nodes)
     Phoneme Deletion         -- Activities 15-16 (2 nodes)
     Phoneme Addition         -- Activities 17-18 (2 nodes)
     Phoneme Substitution     -- Activities 19-20 (2 nodes)
     TOTAL = 20 activities, matching the module exactly.

   Every node still runs on one of Level 1's three existing engines --
   "quiz", "sequence" (multi-round), and "sentence" -- so no new
   rendering code, CSS, XP model, or TTS mechanism was invented, only
   content. Each node also carries a "hero" illustration (a large emoji
   graphic standing in for the module's own picture, since the original
   photos are the module's copyrighted artwork) shown at the top of the
   activity and, where useful, inline next to individual questions, to
   keep activities visual the way the printed worksheets are. */

/* ============ DATA ============
   Every word/example below is taken directly from the Level 2A module
   (Activities 1-20, Pretest, Posttest) rather than invented. */
const LESSONS = [

 /* ================= CATEGORY: Phoneme Isolation ================= */
 {id:1, category:"Phoneme Isolation", catIcon:"\ud83d\udd75\ufe0f", moduleRef:"Activity 1",
  title:"Beginning Sounds", hero:"\ud83d\ude97", engine:"quiz", data:{questions:[
   {q:"What is the BEGINNING sound of 'car'?",img:"\ud83d\ude97",options:["/c/","/a/","/r/"],correct:0},
   {q:"What is the BEGINNING sound of 'cat'?",img:"\ud83d\udc31",options:["/c/","/m/","/r/"],correct:0},
   {q:"What is the BEGINNING sound of 'sun'?",img:"\u2600\ufe0f",options:["/m/","/s/","/w/"],correct:1},
   {q:"What is the BEGINNING sound of 'pan'?",img:"\ud83c\udf73",options:["/n/","/k/","/p/"],correct:2},
   {q:"What is the BEGINNING sound of 'ant'?",img:"\ud83d\udc1c",options:["/a/","/n/","/t/"],correct:0},
   {q:"What is the BEGINNING sound of 'bag'?",img:"\ud83c\udf92",options:["/b/","/a/","/g/"],correct:0}
  ]}},
 {id:2, category:"Phoneme Isolation", catIcon:"\ud83d\udd75\ufe0f", moduleRef:"Activity 2",
  title:"Middle Sounds", hero:"\ud83d\udd24", engine:"quiz", data:{questions:[
   {q:"What is the MIDDLE sound of 'car'?",img:"\ud83d\ude97",options:["/c/","/a/","/r/"],correct:1},
   {q:"What is the MIDDLE sound of 'ant'?",img:"\ud83d\udc1c",options:["/a/","/n/","/t/"],correct:1},
   {q:"What is the MIDDLE sound of 'mat'?",img:"\ud83e\uddf6",options:["/m/","/a/","/t/"],correct:1},
   {q:"What is the MIDDLE sound of 'ink'?",img:"\ud83d\udd8b\ufe0f",options:["/i/","/n/","/k/"],correct:0},
   {q:"What is the MIDDLE sound of 'pen'?",img:"\ud83d\udd8a\ufe0f",options:["/p/","/e/","/n/"],correct:1},
   {q:"What is the MIDDLE sound of 'sun'?",img:"\u2600\ufe0f",options:["/s/","/u/","/n/"],correct:1}
  ]}},
 {id:3, category:"Phoneme Isolation", catIcon:"\ud83d\udd75\ufe0f", moduleRef:"Activity 3",
  title:"Ending Sounds", hero:"\ud83d\udebd", engine:"quiz", data:{questions:[
   {q:"What is the ENDING sound of 'tap'?",img:"\ud83d\udebd",options:["/t/","/a/","/p/"],correct:2},
   {q:"What is the ENDING sound of 'ink'?",img:"\ud83d\udd8b\ufe0f",options:["/i/","/n/","/k/"],correct:2},
   {q:"What is the ENDING sound of 'box'?",img:"\ud83d\udce6",options:["/b/","/o/","/x/"],correct:2},
   {q:"What is the ENDING sound of 'cat'?",img:"\ud83d\udc31",options:["/c/","/a/","/t/"],correct:2},
   {q:"What is the ENDING sound of 'sun'?",img:"\u2600\ufe0f",options:["/s/","/u/","/n/"],correct:2},
   {q:"What is the ENDING sound of 'dog'?",img:"\ud83d\udc36",options:["/d/","/o/","/g/"],correct:2}
  ]}},

 /* ================= CATEGORY: Phoneme Identification ================= */
 {id:4, category:"Phoneme Identification", catIcon:"\ud83e\udde9", moduleRef:"Activity 4",
  title:"Same Beginning Sound", hero:"\ud83e\uddf6", engine:"quiz", data:{questions:[
   {q:"Which sound do 'mat', 'milk', and 'moon' all start with?",img:"\ud83e\uddf6 \ud83e\udd5b \ud83c\udf19",options:["/m/","/t/","/s/"],correct:0},
   {q:"Which sound do 'can', 'cut', and 'cow' all start with?",img:"\ud83e\udd6b \u2702\ufe0f \ud83d\udc04",options:["/k/","/n/","/t/"],correct:0},
   {q:"Which sound do 'hat', 'hug', and 'ham' all start with?",img:"\ud83c\udfa9 \ud83e\udd17 \ud83e\udd53",options:["/t/","/h/","/g/"],correct:1},
   {q:"Which sound do 'kid', 'key', and 'kit' all start with?",img:"\ud83e\uddd2 \ud83d\udd11 \ud83e\udea5",options:["/d/","/y/","/k/"],correct:2},
   {q:"Which sound do 'run', 'rat', and 'red' all start with?",img:"\ud83c\udfc3 \ud83d\udc00 \ud83d\udd34",options:["/r/","/n/","/d/"],correct:0}
  ]}},
 {id:5, category:"Phoneme Identification", catIcon:"\ud83e\udde9", moduleRef:"Activity 5",
  title:"Same Middle Sound", hero:"\ud83c\udf92", engine:"quiz", data:{questions:[
   {q:"Which sound do 'can', 'bag', and 'map' all have in the middle?",img:"\ud83e\udd6b \ud83c\udf92 \ud83d\uddfa\ufe0f",options:["/a/","/e/","/i/"],correct:0},
   {q:"Which sound do 'sit', 'pin', and 'six' all have in the middle?",img:"\ud83e\ude91 \ud83d\udccc 6\ufe0f\u20e3",options:["/a/","/i/","/u/"],correct:1},
   {q:"Which word has a DIFFERENT middle sound: 'pig', 'dog', 'mit'?",img:"\ud83d\udc37 \ud83d\udc36 \ud83e\udde4",options:["pig","dog","mit"],correct:1},
   {q:"Which word has the SAME middle sound as 'nun': 'mat', 'tub', 'jog'?",img:"\ud83e\uddd1 \ud83d\uded1",options:["mat","tub","jog"],correct:1}
  ]}},
 {id:6, category:"Phoneme Identification", catIcon:"\ud83e\udde9", moduleRef:"Activity 6",
  title:"Same Ending Sound", hero:"\ud83d\udecf\ufe0f", engine:"quiz", data:{questions:[
   {q:"Which word does NOT share the same ending sound: bed, Ted, mad, bib?",img:"\ud83d\udecf\ufe0f",options:["bed","Ted","mad","bib"],correct:3},
   {q:"Which word does NOT share the same ending sound: mop, lip, pet, map?",img:"\ud83e\uddf9",options:["mop","lip","pet","map"],correct:2},
   {q:"Which word does NOT share the same ending sound: wed, yet, met, set?",img:"\ud83d\udc8d",options:["wed","yet","met","set"],correct:0},
   {q:"Which word does NOT share the same ending sound: cab, ham, lab, nab?",img:"\ud83d\ude95",options:["cab","ham","lab","nab"],correct:1},
   {q:"Which word does NOT share the same ending sound: sip, hip, hat, hop?",img:"\ud83e\udd64",options:["sip","hip","hat","hop"],correct:2}
  ]}},

 /* ================= CATEGORY: Phoneme Categorization ================= */
 {id:7, category:"Phoneme Categorization", catIcon:"\ud83c\udfaf", moduleRef:"Activity 7",
  title:"Different Beginning Sound", hero:"\ud83c\udf0c", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong (BEGINNING sound)? sky, sun, rabbit",img:"\ud83c\udf0c \u2600\ufe0f \ud83d\udc30",options:["sky","sun","rabbit"],correct:2},
   {q:"Which word does NOT belong (BEGINNING sound)? ball, bat, can",img:"\u26bd \ud83e\udd87 \ud83e\udd6b",options:["ball","bat","can"],correct:2},
   {q:"Which word does NOT belong (BEGINNING sound)? cat, mat, carrot",img:"\ud83d\udc31 \ud83e\uddf6 \ud83e\udd55",options:["cat","mat","carrot"],correct:1},
   {q:"Which word does NOT belong (BEGINNING sound)? sun, rain, seed",img:"\u2600\ufe0f \ud83c\udf27\ufe0f \ud83c\udf31",options:["sun","rain","seed"],correct:1},
   {q:"Which word does NOT belong (BEGINNING sound)? table, key, kite",img:"\ud83e\ude91 \ud83d\udd11 \ud83e\ude81",options:["table","key","kite"],correct:0},
   {q:"Which word does NOT belong (BEGINNING sound)? mat, cam, mop",img:"\ud83e\uddf6 \ud83d\udcf7 \ud83e\uddf9",options:["mat","cam","mop"],correct:1}
  ]}},
 {id:8, category:"Phoneme Categorization", catIcon:"\ud83c\udfaf", moduleRef:"Activity 8",
  title:"Different Middle Sound", hero:"\ud83d\udc14", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong (MIDDLE sound)? can, hen, mat",img:"\ud83e\udd6b \ud83d\udc14 \ud83e\uddf6",options:["can","hen","mat"],correct:1},
   {q:"Which word does NOT belong (MIDDLE sound)? pig, dog, mit",img:"\ud83d\udc37 \ud83d\udc36 \ud83e\udde4",options:["pig","dog","mit"],correct:1},
   {q:"Which word does NOT belong (MIDDLE sound)? sun, cup, pig",img:"\u2600\ufe0f \u2615 \ud83d\udc37",options:["sun","cup","pig"],correct:2},
   {q:"Which word does NOT belong (MIDDLE sound)? top, box, web",img:"\ud83e\ude80 \ud83d\udce6 \ud83d\udd78\ufe0f",options:["top","box","web"],correct:2}
  ]}},
 {id:9, category:"Phoneme Categorization", catIcon:"\ud83c\udfaf", moduleRef:"Activity 9",
  title:"Different Ending Sound", hero:"\ud83d\udc31", engine:"quiz", data:{questions:[
   {q:"Which word does NOT belong (ENDING sound)? cat, hat, dog",img:"\ud83d\udc31 \ud83c\udfa9 \ud83d\udc36",options:["cat","hat","dog"],correct:2},
   {q:"Which word does NOT belong (ENDING sound)? sun, fan, cup",img:"\u2600\ufe0f \ud83e\udea9 \u2615",options:["sun","fan","cup"],correct:2},
   {q:"Which word does NOT belong (ENDING sound)? bell, hall, fish",img:"\ud83d\udd14 \ud83c\udfdb\ufe0f \ud83d\udc1f",options:["bell","hall","fish"],correct:2},
   {q:"Which word does NOT belong (ENDING sound)? map, cap, rug",img:"\ud83d\uddfa\ufe0f \ud83e\udde2 \ud83e\udded",options:["map","cap","rug"],correct:2}
  ]}},

 /* ================= CATEGORY: Phoneme Blending ================= */
 {id:10, category:"Phoneme Blending", catIcon:"\ud83e\uddf1", moduleRef:"Activity 10",
  title:"You Put Me Together", hero:"\ud83d\udc31", engine:"sequence", data:{
   intro:"Tap each sound in order to blend it into a word.",
   rounds:[
    {word:"cat", cards:[{id:"a",icon:"\ud83d\udd24",label:"/c/"},{id:"b",icon:"\ud83d\udd24",label:"/a/"},{id:"c",icon:"\ud83d\udd24",label:"/t/"}], correctOrder:["a","b","c"], answerWord:"cat", hero:"\ud83d\udc31"},
    {word:"hat", cards:[{id:"a",icon:"\ud83d\udd24",label:"/h/"},{id:"b",icon:"\ud83d\udd24",label:"/a/"},{id:"c",icon:"\ud83d\udd24",label:"/t/"}], correctOrder:["a","b","c"], answerWord:"hat", hero:"\ud83c\udfa9"},
    {word:"bit", cards:[{id:"a",icon:"\ud83d\udd24",label:"/b/"},{id:"b",icon:"\ud83d\udd24",label:"/i/"},{id:"c",icon:"\ud83d\udd24",label:"/t/"}], correctOrder:["a","b","c"], answerWord:"bit", hero:"\ud83e\udea4"},
    {word:"pet", cards:[{id:"a",icon:"\ud83d\udd24",label:"/p/"},{id:"b",icon:"\ud83d\udd24",label:"/e/"},{id:"c",icon:"\ud83d\udd24",label:"/t/"}], correctOrder:["a","b","c"], answerWord:"pet", hero:"\ud83d\udc15"},
    {word:"sit", cards:[{id:"a",icon:"\ud83d\udd24",label:"/s/"},{id:"b",icon:"\ud83d\udd24",label:"/i/"},{id:"c",icon:"\ud83d\udd24",label:"/t/"}], correctOrder:["a","b","c"], answerWord:"sit", hero:"\ud83e\ude91"}
   ]}},
 {id:11, category:"Phoneme Blending", catIcon:"\ud83e\uddf1", moduleRef:"Activity 11",
  title:"Beginning Consonant Blends", hero:"\ud83d\udc4f", engine:"sequence", data:{
   intro:"These words start with TWO sounds blended together (like /cl/ in clap). Tap them in order.",
   rounds:[
    {word:"clap", cards:[{id:"a",icon:"\ud83d\udd24",label:"/cl/"},{id:"b",icon:"\ud83d\udd24",label:"/a/"},{id:"c",icon:"\ud83d\udd24",label:"/p/"}], correctOrder:["a","b","c"], answerWord:"clap", hero:"\ud83d\udc4f"},
    {word:"stamp", cards:[{id:"a",icon:"\ud83d\udd24",label:"/st/"},{id:"b",icon:"\ud83d\udd24",label:"/a/"},{id:"c",icon:"\ud83d\udd24",label:"/m/"},{id:"d",icon:"\ud83d\udd24",label:"/p/"}], correctOrder:["a","b","c","d"], answerWord:"stamp", hero:"\ud83d\udc63"},
    {word:"twig", cards:[{id:"a",icon:"\ud83d\udd24",label:"/tw/"},{id:"b",icon:"\ud83d\udd24",label:"/i/"},{id:"c",icon:"\ud83d\udd24",label:"/g/"}], correctOrder:["a","b","c"], answerWord:"twig", hero:"\ud83c\udf3f"},
    {word:"ship", cards:[{id:"a",icon:"\ud83d\udd24",label:"/sh/"},{id:"b",icon:"\ud83d\udd24",label:"/i/"},{id:"c",icon:"\ud83d\udd24",label:"/p/"}], correctOrder:["a","b","c"], answerWord:"ship", hero:"\ud83d\udea2"},
    {word:"flag", cards:[{id:"a",icon:"\ud83d\udd24",label:"/fl/"},{id:"b",icon:"\ud83d\udd24",label:"/a/"},{id:"c",icon:"\ud83d\udd24",label:"/g/"}], correctOrder:["a","b","c"], answerWord:"flag", hero:"\ud83c\udfc1"},
    {word:"star", cards:[{id:"a",icon:"\ud83d\udd24",label:"/st/"},{id:"b",icon:"\ud83d\udd24",label:"/a/"},{id:"c",icon:"\ud83d\udd24",label:"/r/"}], correctOrder:["a","b","c"], answerWord:"star", hero:"\u2b50"}
   ]}},
 {id:12, category:"Phoneme Blending", catIcon:"\ud83e\uddf1", moduleRef:"Activity 12",
  title:"Ending Consonant Blends", hero:"\ud83d\udd77\ufe0f", engine:"quiz", data:{questions:[
   {q:"Which blend completes '__ider' to make 'spider'?",img:"\ud83d\udd77\ufe0f",options:["sp","dr","gl"],correct:0},
   {q:"Which blend completes '__apes' to make 'grapes'?",img:"\ud83c\udf47",options:["fr","gr","sn"],correct:1},
   {q:"Which blend completes '__ess' to make 'dress'?",img:"\ud83d\udc57",options:["dr","sh","cr"],correct:0},
   {q:"Which blend completes '__ab' to make 'crab'?",img:"\ud83e\udd80",options:["gl","cr","fl"],correct:1},
   {q:"Which blend completes '__og' to make 'frog'?",img:"\ud83d\udc38",options:["fr","sn","gr"],correct:0},
   {q:"Which blend completes '__ail' to make 'snail'?",img:"\ud83d\udc0c",options:["sp","sn","fl"],correct:1},
   {q:"Which ending blend completes 'sa__' to make 'sack'?",img:"\ud83c\udff1",options:["ck","rn","lt"],correct:0},
   {q:"Which ending blend completes 'mi__' to make 'milk'?",img:"\ud83e\udd5b",options:["rd","lk","sk"],correct:1}
  ]}},

 /* ================= CATEGORY: Phoneme Segmentation ================= */
 {id:13, category:"Phoneme Segmentation", catIcon:"\ud83d\udd22", moduleRef:"Activity 13",
  title:"Count the Sounds", hero:"\ud83d\udc31", engine:"quiz", data:{questions:[
   {q:"How many sounds (phonemes) are in 'cat'?",img:"\ud83d\udc31",options:["2","3","4"],correct:1},
   {q:"How many sounds are in 'leg'?",img:"\ud83e\uddb5",options:["2","3","4"],correct:1},
   {q:"How many sounds are in 'six'?",img:"6\ufe0f\u20e3",options:["2","3","4"],correct:1},
   {q:"How many sounds are in 'log'?",img:"\ud83e\udeb5",options:["2","3","4"],correct:1},
   {q:"How many sounds are in 'mart'?",img:"\ud83c\udfea",options:["3","4","5"],correct:1},
   {q:"How many sounds are in 'sand'?",img:"\ud83c\udfd6\ufe0f",options:["3","4","5"],correct:1},
   {q:"How many sounds are in 'food'?",img:"\ud83c\udf4e",options:["2","3","4"],correct:1},
   {q:"How many sounds are in 'work'?",img:"\ud83d\udcbc",options:["3","4","5"],correct:1}
  ]}},
 {id:14, category:"Phoneme Segmentation", catIcon:"\ud83d\udd22", moduleRef:"Activity 14",
  title:"Which Sounds Make This Word?", hero:"\ud83d\ude97", engine:"quiz", data:{questions:[
   {q:"Which sounds make the word 'car'?",img:"\ud83d\ude97",options:["/c/ /a/ /r/","/w/ /a/ /r/"],correct:0},
   {q:"Which sounds make the word 'pen'?",img:"\ud83d\udd8a\ufe0f",options:["/t/ /e/ /n/","/p/ /e/ /n/"],correct:1},
   {q:"Which sounds make the word 'bin'?",img:"\ud83d\uddd1\ufe0f",options:["/b/ /i/ /n/","/t/ /i/ /n/"],correct:0},
   {q:"Which sounds make the word 'sand' (all 4 sounds)?",img:"\ud83c\udfd6\ufe0f",options:["/s/ /a/ /n/","/s/ /a/ /n/ /d/"],correct:1},
   {q:"Which sounds make the word 'four'?",img:"4\ufe0f\u20e3",options:["/f/ /o/ /u/ /r/","/f/ /o/ /r/"],correct:1}
  ]}},

 /* ================= CATEGORY: Phoneme Deletion ================= */
 {id:15, category:"Phoneme Deletion", catIcon:"\ud83e\ude84", moduleRef:"Activity 15",
  title:"Delete the Beginning Sound", hero:"\ud83c\udf7d\ufe0f", engine:"sentence", data:{
   intro:"Say each word, then take away the BEGINNING sound shown. Type the new word you made!",
   fields:[
    {label:"Say 'meat' without the /m/ sound. What word do you get?", placeholder:"e.g. eat", hero:"\ud83c\udf7d\ufe0f"},
    {label:"Say 'twig' without the /t/ sound. What word do you get?", placeholder:"e.g. wig", hero:"\ud83c\udf3f"},
    {label:"Say 'clock' without the /c/ sound. What word do you get?", placeholder:"e.g. lock", hero:"\ud83d\udd50"},
    {label:"Say 'stop' without the /s/ sound. What word do you get?", placeholder:"e.g. top", hero:"\ud83d\uded1"},
    {label:"Say 'rice' without the /r/ sound. What word do you get?", placeholder:"e.g. ice", hero:"\ud83c\udf5a"},
    {label:"Say 'trip' without the /t/ sound. What word do you get?", placeholder:"e.g. rip", hero:"\u2708\ufe0f"}
   ]}},
 {id:16, category:"Phoneme Deletion", catIcon:"\ud83e\ude84", moduleRef:"Activity 16",
  title:"Delete the Middle/Ending Sound", hero:"\ud83c\udfb8", engine:"sentence", data:{
   intro:"Say each word, then take away the MIDDLE or ENDING sound shown. Type the new word you made!",
   fields:[
    {label:"Say 'band' without the /d/ sound. What word do you get?", placeholder:"e.g. ban", hero:"\ud83c\udfb8"},
    {label:"Say 'band' without the /b/ sound. What word do you get?", placeholder:"e.g. and", hero:"\ud83c\udfb8"},
    {label:"Say 'cart' without the /t/ sound. What word do you get?", placeholder:"e.g. car", hero:"\ud83d\udecd\ufe0f"},
    {label:"Say 'tent' without the /t/ sound (the last one). What word do you get?", placeholder:"e.g. ten", hero:"\u26fa"}
   ]}},

 /* ================= CATEGORY: Phoneme Addition ================= */
 {id:17, category:"Phoneme Addition", catIcon:"\u2795", moduleRef:"Activity 17",
  title:"Add a Sound to the End", hero:"\ud83c\udfe1", engine:"quiz", data:{questions:[
   {q:"Add /r/ to the end of 'fam'. What word do you get?",img:"\ud83c\udfe1",options:["cam","farm"],correct:1},
   {q:"Add /t/ to the end of 'plan'. What word do you get?",img:"\ud83c\udf31",options:["rap","plant"],correct:1},
   {q:"Add /k/ to the end of 'for'. What word do you get?",img:"\ud83c\udf74",options:["for","fork"],correct:1},
   {q:"Add /t/ to the end of 'an'. What word do you get?",img:"\ud83d\udc1c",options:["ant","arm"],correct:0},
   {q:"Add /r/ to the end of 'gapes'. What word do you get?",img:"\ud83c\udf47",options:["apes","grapes"],correct:1}
  ]}},
 {id:18, category:"Phoneme Addition", catIcon:"\u2795", moduleRef:"Activity 18",
  title:"Add a Sound to the Beginning", hero:"\ud83e\udd80", engine:"quiz", data:{questions:[
   {q:"Add /c/ to the beginning of 'rab'. What word do you get?",img:"\ud83e\udd80",options:["crab","drab","frab"],correct:0},
   {q:"Add /c/ to the beginning of 'rib'. What word do you get?",img:"\ud83d\udc76",options:["drib","crib","grib"],correct:1},
   {q:"Add /b/ to the beginning of 'read'. What word do you get?",img:"\ud83c\udf5e",options:["bread","dread","tread"],correct:0},
   {q:"Add /m/ to the beginning of 'eat'. What word do you get?",img:"\ud83e\udd69",options:["seat","meat","beat"],correct:1},
   {q:"Add /c/ to the beginning of 'lamp'. What word do you get?",img:"\ud83d\udea8",options:["clamp","stamp","slamp"],correct:0},
   {q:"Add /s/ to the beginning of 'tar'. What word do you get?",img:"\u2b50",options:["star","scar","spar"],correct:0}
  ]}},

 /* ================= CATEGORY: Phoneme Substitution ================= */
 {id:19, category:"Phoneme Substitution", catIcon:"\ud83d\udd04", moduleRef:"Activity 19",
  title:"Change the Beginning Sound", hero:"\ud83d\udc00", engine:"quiz", data:{questions:[
   {q:"'cat' becomes 'rat'. We changed /c/ to which sound?",img:"\ud83d\udc31 \u2192 \ud83d\udc00",options:["/r/","/m/","/t/"],correct:0},
   {q:"'man' becomes 'can'. We changed /m/ to which sound?",img:"\ud83e\uddd1 \u2192 \ud83e\udd6b",options:["/n/","/c/","/a/"],correct:1},
   {q:"'map' becomes 'cap'. We changed /m/ to which sound?",img:"\ud83d\uddfa\ufe0f \u2192 \ud83e\udde2",options:["/c/","/p/","/a/"],correct:0},
   {q:"'mug' becomes 'bug'. Which sound changed?",img:"\u2615 \u2192 \ud83d\udc1b",options:["Beginning sound","Ending sound"],correct:0},
   {q:"'mat' becomes 'hat'. Which sound changed?",img:"\ud83e\uddf6 \u2192 \ud83c\udfa9",options:["Beginning sound","Ending sound"],correct:0},
   {q:"'can' becomes 'ran'. Which sound changed?",img:"\ud83e\udd6b \u2192 \ud83c\udfc3",options:["Beginning sound","Ending sound"],correct:0}
  ]}},
 {id:20, category:"Phoneme Substitution", catIcon:"\ud83d\udd04", moduleRef:"Activity 20",
  title:"Change the Ending Sound", hero:"\ud83d\udc1b", engine:"quiz", data:{questions:[
   {q:"'bug' becomes 'bun'. We changed /g/ to which sound?",img:"\ud83d\udc1b \u2192 \ud83c\udf5e",options:["/n/","/b/","/u/"],correct:0},
   {q:"'mat' becomes 'man'. We changed /t/ to which sound?",img:"\ud83e\uddf6 \u2192 \ud83e\uddd1",options:["/m/","/n/","/a/"],correct:1},
   {q:"'rat' becomes 'ran'. We changed /t/ to which sound?",img:"\ud83d\udc00 \u2192 \ud83c\udfc3",options:["/r/","/a/","/n/"],correct:2},
   {q:"'hat' becomes 'ham'. We changed /t/ to which sound?",img:"\ud83c\udfa9 \u2192 \ud83e\udd53",options:["/h/","/a/","/m/"],correct:2},
   {q:"'can' becomes 'cam'. Which sound changed?",img:"\ud83e\udd6b \u2192 \ud83d\udcf7",options:["Beginning sound","Ending sound"],correct:1}
  ]}}
];

/* Category display order, used to draw section dividers on the path. */
const CATEGORIES = ["Phoneme Isolation","Phoneme Identification","Phoneme Categorization","Phoneme Blending","Phoneme Segmentation","Phoneme Deletion","Phoneme Addition","Phoneme Substitution"];

/* PRETEST/POSTTEST -- one diagnostic item per CATEGORY (8 items), matching
   the module's own Pretest (Test I) / Posttest (Test II) format. Each
   references the first node id in that category just to fetch an icon --
   the question itself is about the whole category, not one activity. */
const PRETEST = [
 {refId:1,  category:"Phoneme Isolation",       q:"Listen: 'dog'. What sound do you hear at the very beginning?"},
 {refId:4,  category:"Phoneme Identification",  q:"What sound do 'cat', 'cup', and 'cot' all start with?"},
 {refId:7,  category:"Phoneme Categorization",  q:"Which word does not belong: mat, cam, mop? Why?"},
 {refId:10, category:"Phoneme Blending",        q:"Put these sounds together: /k/ /a/ /t/. What word do they make?"},
 {refId:13, category:"Phoneme Segmentation",    q:"How many sounds do you hear in the word 'jam'?"},
 {refId:15, category:"Phoneme Deletion",        q:"Say 'stand' without the /s/ sound. What word do you get?"},
 {refId:17, category:"Phoneme Addition",        q:"Add /c/ to the beginning of 'at'. What word do you get?"},
 {refId:19, category:"Phoneme Substitution",    q:"Change the first sound in 'mug' to /b/. What word do you get?"}
];
const POSTTEST = [
 {refId:1,  category:"Phoneme Isolation",       q:"Listen: 'fan'. What sound do you hear at the very beginning?"},
 {refId:4,  category:"Phoneme Identification",  q:"What sound do 'hat', 'hug', and 'ham' all start with?"},
 {refId:7,  category:"Phoneme Categorization",  q:"Which word does not belong: table, key, kite? Why?"},
 {refId:10, category:"Phoneme Blending",        q:"Put these sounds together: /g/ /r/ /a/ /s/ /s/. What word do they make?"},
 {refId:13, category:"Phoneme Segmentation",    q:"How many sounds do you hear in the word 'card'?"},
 {refId:15, category:"Phoneme Deletion",        q:"Say 'cart' without the /t/ sound. What word do you get?"},
 {refId:17, category:"Phoneme Addition",        q:"Add /r/ to the end of 'fam'. What word do you get?"},
 {refId:19, category:"Phoneme Substitution",    q:"Change the ending sound in 'hat' to /m/. What word do you get?"}
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
let openLessonId = null;
let engineState = {};

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
function lessonById(id){ return LESSONS.find(l => l.id === id); }

/* ============ RENDER: QUEST MAP (20 nodes, grouped under 8 category headers) ============ */
function renderMap(){
  const root = document.getElementById('mapRoot');
  const allDone = PROGRESS.completed.length === LESSONS.length;

  let pathHtml = '';
  let lastCategory = null;
  LESSONS.forEach((lesson, idx) => {
    if(lesson.category !== lastCategory){
      lastCategory = lesson.category;
      const catDoneCount = LESSONS.filter(l => l.category === lesson.category && PROGRESS.completed.includes(l.id)).length;
      const catTotal = LESSONS.filter(l => l.category === lesson.category).length;
      pathHtml += `<div class="l1-cat-divider" style="text-align:center;margin:22px 0 10px;">
        <span style="display:inline-block;background:var(--bulig-green);color:#fff;font-family:var(--font-display);font-weight:800;padding:6px 18px;border-radius:20px;font-size:14px;">
          ${lesson.catIcon} ${escapeHtml(lesson.category)} <span style="opacity:.85;font-weight:600;">(${catDoneCount}/${catTotal})</span>
        </span>
      </div>`;
    }
    const done = PROGRESS.completed.includes(lesson.id);
    const unlocked = idx === 0 || PROGRESS.completed.includes(LESSONS[idx-1].id);
    const side = idx % 2 === 0 ? 'l1-left' : 'l1-right';
    let cls = 'l1-node';
    if(!unlocked) cls += ' l1-locked'; else if(done) cls += ' l1-done'; else cls += ' l1-current';
    pathHtml += `<div class="l1-node-row ${side}">
      <div class="${cls}">
        <div class="l1-num">${lesson.id}</div>
        ${done ? '<div class="l1-check">\u2713</div>' : ''}
        <div class="l1-nicon">${lesson.hero}</div>
        <div class="l1-ntitle">${escapeHtml(lesson.title)}</div>
        <div class="l1-nsub">${escapeHtml(lesson.moduleRef)}</div>
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
      <div class="stat-label">Level 2A Experience</div>
      <div class="stat-note">Earn 50 XP per activity, 40 XP per assessment</div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">\ud83d\udd24</span>
      <div class="stat-value">${PROGRESS.completed.length} / ${LESSONS.length}</div>
      <div class="stat-label">Activities Completed</div>
      <div class="xp-bar-track"><div class="xp-bar-fill" style="width:${Math.round(PROGRESS.completed.length/LESSONS.length*100)}%"></div></div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">${allDone ? '\ud83c\udfc6' : '\ud83d\udd13'}</span>
      <div class="stat-value">${allDone ? 'Champion!' : (LESSONS.length - PROGRESS.completed.length) + ' to go'}</div>
      <div class="stat-label">${allDone ? 'Level 2A complete' : 'Activities remaining'}</div>
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
      <p>${allDone ? "Show off everything you've learned!" : 'Unlocks after all 20 activities are complete.'}</p>
      ${allDone ? `<span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.postDone ? 'Review your answers \u2192' : 'Start \u2192'}</span>` : '<span class="pill-soon">Locked</span>'}
    </a>
  </div>

  <h2 class="section-title">\ud83d\uddfa\ufe0f Your Sound Path (20 Activities)</h2>
  <div class="l1-path">${pathHtml}</div>`;
}

function openLesson(id){
  openLessonId = id;
  engineState = {};
  renderLessonOverlay();
}
function closeLesson(){
  window.speechSynthesis && window.speechSynthesis.cancel();
  const overlay = document.getElementById('lessonOverlay');
  if(overlay) overlay.remove();
  openLessonId = null;
}

/* ============ LESSON OVERLAY ============ */
function renderLessonOverlay(){
  const lesson = lessonById(openLessonId);
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className='l1-overlay l1-scope'; document.body.appendChild(overlay); }
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeLesson()">\u2715</button>
      <div>
        <div class="l1-ltitle">${lesson.hero} ${escapeHtml(lesson.title)}</div>
        <div class="l1-lsub">${lesson.catIcon} ${escapeHtml(lesson.category)} \u00b7 ${escapeHtml(lesson.moduleRef)}</div>
      </div>
    </div>
    <div class="l1-lesson-body" id="lessonBody"></div>
  `;
  renderEngine(lesson);
}
function renderEngine(lesson){
  const body = document.getElementById('lessonBody');
  const engines = { sentence: engineSentence, quiz: engineQuiz, sequence: engineSequence };
  body.innerHTML = engines[lesson.engine](lesson);
}

/* ---- Sentence engine (reused for Phoneme Deletion activities) ---- */
function engineSentence(lesson){
  const fieldsHtml = lesson.data.fields.map((f,i) => `
    <div class="l1-field">
      <div style="font-size:34px;text-align:center;margin-bottom:4px;" aria-hidden="true">${f.hero || ''}</div>
      <label>${escapeHtml(f.label)}</label>
      <input id="sf_${i}" placeholder="${escapeHtml(f.placeholder)}" oninput="checkReady(${lesson.data.fields.length})">
      <button class="l1-speak-btn" style="margin:-8px 0 14px;" onclick="speak(${attrJson(f.label)})">\ud83d\udd0a Read aloud</button>
    </div>`).join('');
  return `
    <div class="l1-card" style="text-align:center;">
      <div style="font-size:44px;">${lesson.hero}</div>
      <p>${escapeHtml(lesson.data.intro)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(lesson.data.intro)})">\ud83d\udd0a Read aloud</button>
    </div>
    <div class="l1-card">${fieldsHtml}</div>
    <div class="l1-footer-actions"><button class="l1-btn l1-disabled" id="finishBtn" onclick="finishLesson(${lesson.id})">\u2705 Finish Activity</button></div>`;
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

/* ---- Quiz engine (reused across most activities). Each question can
   carry an "img" illustration (emoji standing in for the module's own
   picture) shown above the question text. ---- */
function engineQuiz(lesson){
  engineState = { qIndex:0, score:0, answered:false };
  return renderQuizQuestion(lesson);
}
function renderQuizQuestion(lesson){
  const total = lesson.data.questions.length;
  if(engineState.qIndex >= total){
    return `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">\ud83c\udf89</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">Great effort!</h3>
      <p>You got ${engineState.score} out of ${total} correct. Every try helps you learn!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="finishLesson(${lesson.id})">\u2705 Finish Activity</button></div>`;
  }
  const q = lesson.data.questions[engineState.qIndex];
  const optsHtml = q.options.map((opt,i) => `<button class="l1-quiz-opt" id="opt_${i}" onclick="answerQuiz(${lesson.id},${i})">${escapeHtml(opt)}</button>`).join('');
  return `
    <div class="l1-quiz-progress">Question ${engineState.qIndex+1} of ${total}</div>
    <div class="l1-card">
      ${q.img ? `<div style="font-size:40px;text-align:center;margin-bottom:8px;" aria-hidden="true">${q.img}</div>` : ''}
      <div class="l1-quiz-q">${escapeHtml(q.q)}</div>
      <button class="l1-speak-btn" style="margin-bottom:12px;" onclick="speak(${attrJson(q.q)})">\ud83d\udd0a Read aloud</button>
      <div>${optsHtml}</div>
    </div>
    <div class="l1-footer-actions" id="quizNextWrap"></div>`;
}
function answerQuiz(lessonId, idx){
  const lesson = lessonById(lessonId);
  if(engineState.answered) return;
  engineState.answered = true;
  const q = lesson.data.questions[engineState.qIndex];
  document.getElementById('opt_'+idx).classList.add(idx===q.correct?'l1-correct':'l1-wrong');
  if(idx===q.correct){ engineState.score++; toast('\u2705 +1 point!'); } else { document.getElementById('opt_'+q.correct).classList.add('l1-correct'); }
  document.getElementById('quizNextWrap').innerHTML = `<button class="l1-btn" onclick="nextQuiz(${lessonId})">Next \u279c</button>`;
}
function nextQuiz(lessonId){
  const lesson = lessonById(lessonId);
  engineState.qIndex++;
  engineState.answered = false;
  document.getElementById('lessonBody').innerHTML = renderQuizQuestion(lesson);
}

/* ---- Sequence engine (reused for Phoneme Blending -- tap the sounds in
   order to "blend" them into the word). Supports multiple rounds within
   one activity. Each round shows a hero illustration for the target word
   and each tap also plays that sound aloud via speak(). ---- */
function engineSequence(lesson){
  engineState = { roundIndex: 0, order: [] };
  return renderSequence(lesson);
}
function renderSequence(lesson){
  const rounds = lesson.data.rounds;
  if(engineState.roundIndex >= rounds.length){
    return `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">\ud83c\udf89</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">All blended!</h3>
      <p>You blended ${rounds.length} words. Great listening!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="finishLesson(${lesson.id})">\u2705 Finish Activity</button></div>`;
  }
  const round = rounds[engineState.roundIndex];
  const remaining = round.cards.filter(c => !engineState.order.includes(c.id));
  const poolHtml = remaining.map(c => `<div class="l1-seq-card" onclick="pickSeq(${lesson.id},'${c.id}')"><div class="l1-ic">${c.icon}</div>${escapeHtml(c.label)}</div>`).join('') || '<div style="font-size:12px;color:var(--ink-soft);">All sounds placed!</div>';
  const slotHtml = engineState.order.map((id,i) => {
    const c = round.cards.find(x=>x.id===id);
    return `<div class="l1-seq-card" style="background:var(--bulig-gold);">${i+1}. ${c.icon} ${escapeHtml(c.label)}</div>`;
  }).join('');
  const complete = engineState.order.length === round.cards.length;
  let feedback = '';
  if(complete){
    const correct = JSON.stringify(engineState.order) === JSON.stringify(round.correctOrder);
    feedback = correct
      ? `<div class="l1-card" style="background:#DFF5E4;text-align:center;">${round.hero || '\ud83c\udf89'} Perfect blend! Together those sounds make "${escapeHtml(round.answerWord)}"!</div>`
      : `<div class="l1-card" style="background:#FBE3DF;text-align:center;">Good try! The order isn't quite right yet -- tap Reset and try again.</div>`;
  }
  return `
    <div class="l1-quiz-progress">Word ${engineState.roundIndex+1} of ${rounds.length}</div>
    <div class="l1-card" style="text-align:center;">
      <div style="font-size:44px;">${round.hero || ''}</div>
      <p>${escapeHtml(lesson.data.intro)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(lesson.data.intro)},0.85)">\ud83d\udd0a Read aloud</button>
    </div>
    <div class="l1-card">
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Your blended word:</div>
      <div class="l1-seq-slot-row">${slotHtml}</div>
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Tap each sound in order:</div>
      <div class="l1-seq-pool">${poolHtml}</div>
      <button class="l1-pill" onclick="resetSeq(${lesson.id})">\u21ba Reset</button>
    </div>
    ${feedback}
    <div class="l1-footer-actions"><button class="l1-btn ${complete?'':'l1-disabled'}" onclick="nextSeqRound(${lesson.id})">${engineState.roundIndex === rounds.length-1 ? '\u2705 Finish' : 'Next word \u279c'}</button></div>`;
}
function pickSeq(lessonId, cardId){
  const lesson = lessonById(lessonId);
  const round = lesson.data.rounds[engineState.roundIndex];
  const card = round.cards.find(c=>c.id===cardId);
  if(card) speak(card.label, 0.8);
  engineState.order.push(cardId);
  document.getElementById('lessonBody').innerHTML = renderSequence(lesson);
}
function resetSeq(lessonId){
  engineState.order = [];
  document.getElementById('lessonBody').innerHTML = renderSequence(lessonById(lessonId));
}
function nextSeqRound(lessonId){
  const lesson = lessonById(lessonId);
  if(engineState.order.length !== lesson.data.rounds[engineState.roundIndex].cards.length) return;
  engineState.roundIndex++;
  engineState.order = [];
  document.getElementById('lessonBody').innerHTML = renderSequence(lesson);
}

/* ============ FINISH / CELEBRATE (one activity) ============ */
async function finishLesson(lessonId){
  const lesson = lessonById(lessonId);
  const result = await saveToServer({ type:'lesson', lesson_id: lessonId });
  if(!result.ok){ toast('\u26a0\ufe0f Could not save -- check your connection and try again.'); return; }
  if(!result.already_done){
    PROGRESS.completed.push(lessonId);
    PROGRESS.xp += result.xp_awarded;
  }
  window.speechSynthesis && window.speechSynthesis.cancel();
  showCelebration(lesson.hero, lesson.title, result.already_done, result.xp_awarded);
}
function showCelebration(icon, title, already, xpAwarded){
  const allDone = PROGRESS.completed.length === LESSONS.length;
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">${allDone ? '\ud83c\udfc6' : '\ud83c\udf89'}</div>
    <h2>${allDone ? 'Phonological Awareness Champion!' : 'Activity Complete!'}</h2>
    <p>${already ? "Nice replay! Keep practicing " + title + "." : "You earned the " + icon + " " + title + " badge and +" + xpAwarded + " XP!"}</p>
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
    const refLesson = lessonById(item.refId);
    const val = answers[item.refId] || '';
    return `<div class="l1-card">
      <div style="font-size:11.5px;font-weight:800;color:var(--bulig-green);text-transform:uppercase;letter-spacing:.03em;">${refLesson.catIcon} ${escapeHtml(item.category)}</div>
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
        <div class="l1-lsub">8 quick questions, one per category</div>
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
    if(el) target[set[i].refId] = el.value;
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
