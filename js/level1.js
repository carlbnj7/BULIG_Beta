/* BULIG — Level 1 Oral Language Quest engine
   PHP supplies SERVER_STATE + SAVE_URL before this file loads. */

/* ============ DATA ============ */
const LESSONS = [
 {id:1, title:"Meet & Greet", sub:"Talking About Myself & My Family", icon:"🙋", engine:"sentence",
   data:{ intro:"Let's get to know you! Fill in the blanks about yourself and your family.",
     fields:[
       {label:"My name is...", placeholder:"e.g. Ana"},
       {label:"I am ___ years old", placeholder:"e.g. 8"},
       {label:"My family has...", placeholder:"e.g. 5 members"},
       {label:"We like to...", placeholder:"e.g. eat lunch together"}
     ]}},
 {id:2, title:"Mission Trail", sub:"Follow 1-2 Step Directions", icon:"🕵️", engine:"quiz",
  data:{questions:[
    {q:"Mission: 'Go to the door, then close it.' What do you do FIRST?",options:["Close the door","Go to the door","Sit down","Clap hands"],correct:1},
    {q:"Mission: 'Pick up the book, then give it to me.' What do you do SECOND?",options:["Pick up the book","Give it to me","Run away","Read it"],correct:1},
    {q:"Mission: 'Turn around and clap your hands once.' What comes FIRST?",options:["Clap your hands","Turn around","Jump","Sit down"],correct:1},
    {q:"Mission: 'Run to the whiteboard, then sit in your chair.' What is the LAST step?",options:["Run to the whiteboard","Sit in your chair","Stand up","Wave"],correct:1}
  ]}},
 {id:3, title:"Polite Missions", sub:"Giving Clear, Kind Directions", icon:"🤝", engine:"sentence",
  data:{ intro:"Use words like 'Can you...' and 'please' to ask nicely! Give TWO instructions for each mission.",
  fields:[
   {label:"Your younger sister left her dolls on the bed. What 2 things will you ask her to do?",placeholder:"Can you please... and can you..."},
   {label:"Your brother throws his trash anywhere. What 2 things will you ask him to do?",placeholder:"Can you please... and can you..."},
   {label:"Ask a classmate to clean up their desk. Give 2 instructions.",placeholder:"Can you please... and can you..."}
  ]}},
 {id:4, title:"Describing Word Toolkit", sub:"Color, Size, Shape & Feel", icon:"🎨", engine:"quiz",
  data:{questions:[
   {q:"🐻 A big bear. What SIZE word describes it?",options:["Tiny","Big","Short","Flat"],correct:1},
   {q:"🍎 A red apple. What COLOR is it?",options:["Blue","Green","Red","Purple"],correct:2},
   {q:"⚽ A round ball. What SHAPE is it?",options:["Square","Triangle","Round","Flat"],correct:2},
   {q:"🧸 A soft teddy bear. How does it FEEL?",options:["Hard","Soft","Sticky","Rough"],correct:1},
   {q:"☀️ A bright sun. How does it LOOK?",options:["Dark","Dull","Bright","Dirty"],correct:2},
   {q:"🐌 A tiny snail. What SIZE word fits?",options:["Huge","Tiny","Tall","Long"],correct:1}
  ]}},
 {id:5, title:"Picture Chat", sub:"Talking About Topics of Interest", icon:"💬", engine:"qa",
  data:{ intro:"Use sentence starters like 'I see...', 'I think...', 'I like...' to share your ideas!",
  items:[
   {prompt:"🐶⚽🌳 A dog is playing with a red ball in a sunny park.",question:"What do you think about this picture?"},
   {prompt:"🎂🎈 Children are having a birthday party with balloons and cake.",question:"What do you like about this picture?"},
   {prompt:"🌈💧 A rainbow appears over a lake after the rain.",question:"How do you feel when you see this? Why?"}
  ]}},
 {id:6, title:"Describe & Draw", sub:"Turn Words Into Pictures", icon:"🖍️", engine:"draw",
  data:{ intro:"Draw what you hear! Listen to the description, then draw it below.",
    prompt:"A big, green tree branch in the middle. On top, a small red bird with a yellow beak. Next to the bird, a bright yellow flower with five petals. The sky is light blue."}},
 {id:7, title:"Story Chain", sub:"Put the Story in Order", icon:"🔗", engine:"sequence",
  data:{ intro:"Mimi the cat has a story to tell! Tap the pictures in the correct order.",
   cards:[
    {id:"a",icon:"😴",label:"Mimi wakes up in bed"},
    {id:"b",icon:"🚪",label:"Mimi walks to the door"},
    {id:"c",icon:"🌷",label:"Mimi goes to the garden"},
    {id:"d",icon:"🔴",label:"Mimi finds a red ball"},
    {id:"e",icon:"🏠",label:"Mimi walks back home"},
    {id:"f",icon:"💤",label:"Mimi sleeps again"}
   ],
   correctOrder:["a","b","c","d","e","f"]}},
 {id:8, title:"Picture Talk", sub:"Ask & Answer About a Picture", icon:"🖼️", engine:"qa",
  data:{ intro:"Look at the scene: children are playing at a sunny park with slides, swings, a dog, and an ice cream vendor.",
   items:[
    {prompt:"👧🛝🐕🍦", question:"Describe what the people or animals are doing."},
    {prompt:"☀️☁️", question:"What do you think the weather is like? How do you know?"},
    {prompt:"🤔", question:"Ask a friend: What do you like about this picture? Write what they might say."}
   ]}},
 {id:9, title:"Recite & Shine", sub:"Little Voices Through Poems", icon:"⭐", engine:"poem",
  data:{poems:[
   {title:"Twinkle, Twinkle, Little Star",lines:["Twinkle, twinkle, little star,","How I wonder what you are!","Up above the world so high,","Like a diamond in the sky."]},
   {title:"Baa, Baa, Black Sheep",lines:["Baa, baa, black sheep,","Have you any wool?","Yes sir, yes sir,","Three bags full."]},
   {title:"The Itsy-Bitsy Spider",lines:["The itsy-bitsy spider","climbed up the waterspout.","Down came the rain","and washed the spider out."]}
  ]}},
 {id:10, title:"Talk, Play & Share", sub:"Connecting With Friends", icon:"🎈", engine:"qa",
  data:{ intro:"Imagine children playing together at a picnic in the park.",
   items:[
    {prompt:"🏃‍♀️🤸", question:"Describe what the people in the picture are doing. (e.g. 'The girl is jumping.')"},
    {prompt:"☁️☀️", question:"What do you think the weather is like in the picture? How do you know?"},
    {prompt:"🗣️", question:"Ask a friend: 'What do you like about this picture?' Write what they might say."}
   ]}},
 {id:11, title:"Word Friends", sub:"Word Association", icon:"🔤", engine:"quiz",
  data:{questions:[
   {q:"I say 'cat' — which word is its friend?",options:["Meow","Airplane","Chair","Rain"],correct:0},
   {q:"I say 'sun' — which word is its friend?",options:["Cold","Hot","Book","Shoe"],correct:1},
   {q:"I say 'book' — which word is its friend?",options:["Read","Swim","Drive","Cook"],correct:0},
   {q:"I say 'water' — which word is its friend?",options:["Dry","Drink","Fire","Rock"],correct:1},
   {q:"I say 'friend' — which word is its friend?",options:["Enemy","Play","Alone","Angry"],correct:1}
  ]}},
 {id:12, title:"Tongue Twister Fun", sub:"Say It Slow, Then Fast!", icon:"👅", engine:"tonguetwister",
  data:{twisters:[
   "Red lorry, yellow lorry.",
   "She sells seashells by the seashore.",
   "Six sick sheep shepherds' sheep.",
   "How much wood would a woodchuck chuck?"
  ]}}
];
const PRETEST = [
 {lessonId:1, q:"Tell me your name and age, and one thing you like to do for fun."},
 {lessonId:2, q:"Try this: 'Turn around and clap your hands once.' Describe what you did."},
 {lessonId:3, q:"If you want your mom to give you a snack, what will you say?"},
 {lessonId:4, q:"Look at a red apple. What color is it? What shape is it?"},
 {lessonId:5, q:"What is your favorite toy? Tell one thing you like about it."},
 {lessonId:6, q:"Describe a picture of a house so someone else could draw it."},
 {lessonId:7, q:"Tell a short story about something you did yesterday, starting with 'I'."},
 {lessonId:8, q:"Point to one thing you can see right now and name it. What color is it?"},
 {lessonId:9, q:"Do you like poems that rhyme? Why or why not?"},
 {lessonId:10, q:"Describe what the people or animals around you are doing right now."},
 {lessonId:11, q:"I say 'sun' — what's one word that goes with 'sun'?"},
 {lessonId:12, q:"Try to say slowly: 'Red lorry, yellow lorry.'"}
];
const POSTTEST = [
 {lessonId:1, q:"Introduce yourself: name, age, one thing that makes you happy. Then describe your family."},
 {lessonId:2, q:"Follow this: 'Close the door gently, and walk back to your seat.' Describe what you did, in order."},
 {lessonId:3, q:"Ask your brother to turn off the TV and come to dinner — say it clearly with two steps."},
 {lessonId:4, q:"Describe a toy near you — tell its color, size, and how it feels."},
 {lessonId:5, q:"Tell me about your favorite game — how to play it and why you like it."},
 {lessonId:6, q:"Describe a picture of a rainbow over a lake — the colors, and where things are."},
 {lessonId:7, q:"Retell a partner's story, using 'he/she/they' as if you heard it from a friend."},
 {lessonId:8, q:"Tell a short story (2-3 sentences) about children playing at a park."},
 {lessonId:9, q:"Recite your favorite poem's first two lines from memory."},
 {lessonId:10, q:"Add one sentence to a group story that starts: 'Once, a dog met a cat...'"},
 {lessonId:11, q:"I say 'food' — tell me two words related to 'food' and explain why."},
 {lessonId:12, q:"Say 'How much wood would a woodchuck chuck?' once, at a steady pace."}
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
let openLessonState = null;

/* ============ TTS ============ */
function speak(text, rate){
  if(!window.speechSynthesis){
    toast("⚠️ This browser doesn't support read-aloud (Web Speech API).");
    return null;
  }
  window.speechSynthesis.cancel();
  const u = new SpeechSynthesisUtterance(text);
  u.rate = rate || 0.95;
  u.pitch = 1.05;
  u.onerror = function(e){
    console.error('Speech synthesis error:', e.error);
    toast('⚠️ Could not play audio (' + e.error + ').');
  };
  // Call speak() immediately in the same click — some browsers (notably
  // iOS Safari) only allow speech synthesis to start synchronously inside
  // a real user tap/click, so any delay here can silently block it.
  window.speechSynthesis.speak(u);
  return u;
}
function testSound(){
  const u = speak('Hello! Can you hear me? This is a sound test.', 1);
  if(u) toast('🔊 Playing test sound now...');
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
/* Safely embed a JS string literal inside an HTML attribute like onclick="...".
   JSON.stringify() wraps the value in double quotes, which would otherwise
   collide with the attribute's own double quotes — escapeHtml() turns those
   into &quot;/&#39; entities so the browser decodes them back correctly
   before running the click handler. */
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
        ${done ? '<div class="l1-check">✓</div>' : ''}
        <div class="l1-nicon">${lesson.icon}</div>
        <div class="l1-ntitle">${lesson.title}</div>
        <div class="l1-nsub">${lesson.sub}</div>
        <button ${unlocked ? `onclick="openLesson(${lesson.id})"` : 'disabled'}>${done ? 'Replay' : unlocked ? 'Play' : '🔒 Locked'}</button>
      </div>
    </div>`;
  });

  root.innerHTML = `
  <div style="text-align:center;margin-bottom:14px;">
    <button class="l1-pill" onclick="testSound()">🔊 Test My Sound</button>
    <div style="font-size:11.5px;color:var(--ink-soft);margin-top:4px;">Tap this first if "Read Aloud" buttons seem silent.</div>
  </div>
  <div class="stat-grid">
    <div class="stat-card">
      <span class="stat-icon">⭐</span>
      <div class="stat-value">${PROGRESS.xp} XP</div>
      <div class="stat-label">Level 1 Experience</div>
      <div class="stat-note">Earn 50 XP per quest, 40 XP per assessment</div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">📘</span>
      <div class="stat-value">${PROGRESS.completed.length} / ${LESSONS.length}</div>
      <div class="stat-label">Quests Completed</div>
      <div class="xp-bar-track"><div class="xp-bar-fill" style="width:${Math.round(PROGRESS.completed.length/LESSONS.length*100)}%"></div></div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">${allDone ? '🏆' : '🔓'}</span>
      <div class="stat-value">${allDone ? 'Champion!' : (LESSONS.length - PROGRESS.completed.length) + ' to go'}</div>
      <div class="stat-label">${allDone ? 'Level 1 complete' : 'Quests remaining'}</div>
      <div class="stat-note">${allDone ? "You've unlocked the Post-Assessment!" : 'Keep going — you can do it!'}</div>
    </div>
  </div>

  <div class="action-grid">
    <a href="javascript:void(0)" class="action-card" onclick="openAssessment('pre')">
      <span class="action-icon">📝</span>
      <h3>Pre-Assessment</h3>
      <p>A quick check-in before you start — your teacher will review it.</p>
      <span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.preDone ? 'Review your answers →' : 'Start →'}</span>
    </a>
    <a href="javascript:void(0)" class="action-card ${allDone ? '' : 'is-soon'}" ${allDone ? "onclick=\"openAssessment('post')\"" : ''}>
      <span class="action-icon">${allDone ? '🏅' : '🔒'}</span>
      <h3>Post-Assessment</h3>
      <p>${allDone ? "Show off everything you've learned!" : 'Unlocks after all 12 quests are complete.'}</p>
      ${allDone ? `<span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.postDone ? 'Review your answers →' : 'Start →'}</span>` : '<span class="pill-soon">Locked</span>'}
    </a>
  </div>

  <h2 class="section-title">🗺️ Your Quest Path</h2>
  <div class="l1-path">${nodesHtml}</div>`;
}

function openLesson(id){ openLessonState = { id, engineState:{} }; renderLessonOverlay(); }
function closeLesson(){
  window.speechSynthesis && window.speechSynthesis.cancel();
  const overlay = document.getElementById('lessonOverlay');
  if(overlay) overlay.remove();
  openLessonState = null;
}

/* ============ LESSON OVERLAY ============ */
function renderLessonOverlay(){
  const lesson = LESSONS.find(l => l.id === openLessonState.id);
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className='l1-overlay l1-scope'; document.body.appendChild(overlay); }
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeLesson()">✕</button>
      <div>
        <div class="l1-ltitle">${lesson.icon} ${lesson.title}</div>
        <div class="l1-lsub">${lesson.sub}</div>
      </div>
    </div>
    <div class="l1-lesson-body" id="lessonBody"></div>
  `;
  renderEngine(lesson);
}
function renderEngine(lesson){
  const body = document.getElementById('lessonBody');
  const engines = { sentence: engineSentence, quiz: engineQuiz, qa: engineQA, draw: engineDraw, sequence: engineSequence, poem: enginePoem, tonguetwister: engineTwister };
  body.innerHTML = engines[lesson.engine](lesson);
  if(lesson.engine === 'draw') setupCanvas();
}

/* ---- Sentence engine ---- */
function engineSentence(lesson){
  const fieldsHtml = lesson.data.fields.map((f,i) => `
    <div class="l1-field">
      <label>${escapeHtml(f.label)}</label>
      <input id="sf_${i}" placeholder="${escapeHtml(f.placeholder)}" oninput="checkReady(${lesson.data.fields.length})">
    </div>`).join('');
  return `
    <div class="l1-card">
      <p>${escapeHtml(lesson.data.intro)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(lesson.data.intro)})">🔊 Read aloud</button>
    </div>
    <div class="l1-card">${fieldsHtml}</div>
    <div class="l1-footer-actions"><button class="l1-btn l1-disabled" id="finishBtn" onclick="finishLesson(${lesson.id})">✅ Finish Quest</button></div>`;
}

/* ---- QA engine ---- */
function engineQA(lesson){
  const itemsHtml = lesson.data.items.map((it,i) => `
    <div class="l1-card">
      <div style="font-size:30px;text-align:center;margin-bottom:6px;">${it.prompt}</div>
      <div class="l1-field"><label>${escapeHtml(it.question)}</label>
        <textarea id="qa_${i}" rows="2" placeholder="Type your answer..." oninput="checkReady(${lesson.data.items.length})"></textarea>
      </div>
      <button class="l1-speak-btn" onclick="speak(${attrJson(it.question)})">🔊 Read question</button>
    </div>`).join('');
  return `
    <div class="l1-card"><p>${escapeHtml(lesson.data.intro || 'Answer the questions below.')}</p></div>
    ${itemsHtml}
    <div class="l1-footer-actions"><button class="l1-btn l1-disabled" id="finishBtn" onclick="finishLesson(${lesson.id})">✅ Finish Quest</button></div>`;
}
function checkReady(count){
  let ready = true;
  for(let i=0;i<count;i++){
    const el = document.getElementById('sf_'+i) || document.getElementById('qa_'+i);
    if(!el || !el.value.trim()) ready = false;
  }
  const btn = document.getElementById('finishBtn');
  if(btn){ if(ready) btn.classList.remove('l1-disabled'); else btn.classList.add('l1-disabled'); }
}

/* ---- Quiz engine ---- */
function engineQuiz(lesson){
  openLessonState.engineState = { qIndex:0, score:0, answered:false };
  return renderQuizQuestion(lesson);
}
function renderQuizQuestion(lesson){
  const es = openLessonState.engineState;
  const total = lesson.data.questions.length;
  if(es.qIndex >= total){
    return `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">🎉</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">Great effort!</h3>
      <p>You got ${es.score} out of ${total} correct. Every try helps you learn!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="finishLesson(${lesson.id})">✅ Finish Quest</button></div>`;
  }
  const q = lesson.data.questions[es.qIndex];
  const optsHtml = q.options.map((opt,i) => `<button class="l1-quiz-opt" id="opt_${i}" onclick="answerQuiz(${lesson.id},${i})">${escapeHtml(opt)}</button>`).join('');
  return `
    <div class="l1-quiz-progress">Question ${es.qIndex+1} of ${total}</div>
    <div class="l1-card">
      <div class="l1-quiz-q">${escapeHtml(q.q)}</div>
      <button class="l1-speak-btn" style="margin-bottom:12px;" onclick="speak(${attrJson(q.q)})">🔊 Read aloud</button>
      <div>${optsHtml}</div>
    </div>
    <div class="l1-footer-actions" id="quizNextWrap"></div>`;
}
function answerQuiz(lessonId, idx){
  const lesson = LESSONS.find(l=>l.id===lessonId);
  const es = openLessonState.engineState;
  if(es.answered) return;
  es.answered = true;
  const q = lesson.data.questions[es.qIndex];
  document.getElementById('opt_'+idx).classList.add(idx===q.correct?'l1-correct':'l1-wrong');
  if(idx===q.correct){ es.score++; } else { document.getElementById('opt_'+q.correct).classList.add('l1-correct'); }
  document.getElementById('quizNextWrap').innerHTML = `<button class="l1-btn" onclick="nextQuiz(${lessonId})">Next ➜</button>`;
}
function nextQuiz(lessonId){
  const lesson = LESSONS.find(l=>l.id===lessonId);
  openLessonState.engineState.qIndex++;
  openLessonState.engineState.answered = false;
  document.getElementById('lessonBody').innerHTML = renderQuizQuestion(lesson);
}

/* ---- Sequence engine ---- */
function engineSequence(lesson){
  openLessonState.engineState = { order: [] };
  return renderSequence(lesson);
}
function renderSequence(lesson){
  const es = openLessonState.engineState;
  const remaining = lesson.data.cards.filter(c => !es.order.includes(c.id));
  const poolHtml = remaining.map(c => `<div class="l1-seq-card" onclick="pickSeq(${lesson.id},'${c.id}')"><div class="l1-ic">${c.icon}</div>${escapeHtml(c.label)}</div>`).join('') || '<div style="font-size:12px;color:var(--ink-soft);">All cards placed!</div>';
  const slotHtml = es.order.map((id,i) => {
    const c = lesson.data.cards.find(x=>x.id===id);
    return `<div class="l1-seq-card" style="background:var(--bulig-gold);">${i+1}. ${c.icon} ${escapeHtml(c.label)}</div>`;
  }).join('');
  const complete = es.order.length === lesson.data.cards.length;
  let feedback = '';
  if(complete){
    const correct = JSON.stringify(es.order) === JSON.stringify(lesson.data.correctOrder);
    feedback = correct
      ? `<div class="l1-card" style="background:#DFF5E4;text-align:center;">🎉 Perfect order! Great sequencing!</div>`
      : `<div class="l1-card" style="background:#FBE3DF;text-align:center;">Good try! The order isn't quite right yet — tap Reset and try again.</div>`;
  }
  return `
    <div class="l1-card"><p>${escapeHtml(lesson.data.intro)}</p><button class="l1-speak-btn" onclick="speak(${attrJson(lesson.data.intro)})">🔊 Read aloud</button></div>
    <div class="l1-card">
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Your story order:</div>
      <div class="l1-seq-slot-row">${slotHtml}</div>
      <div style="font-weight:800;color:var(--bulig-green-dark);margin-bottom:8px;">Tap in order:</div>
      <div class="l1-seq-pool">${poolHtml}</div>
      <button class="l1-pill" onclick="resetSeq(${lesson.id})">↺ Reset</button>
    </div>
    ${feedback}
    <div class="l1-footer-actions"><button class="l1-btn ${complete?'':'l1-disabled'}" onclick="finishLesson(${lesson.id})">✅ Finish Quest</button></div>`;
}
function pickSeq(lessonId, cardId){
  openLessonState.engineState.order.push(cardId);
  document.getElementById('lessonBody').innerHTML = renderSequence(LESSONS.find(l=>l.id===lessonId));
}
function resetSeq(lessonId){
  openLessonState.engineState.order = [];
  document.getElementById('lessonBody').innerHTML = renderSequence(LESSONS.find(l=>l.id===lessonId));
}

/* ---- Draw engine ---- */
function engineDraw(lesson){
  return `
    <div class="l1-card"><p>${escapeHtml(lesson.data.intro)}</p></div>
    <div class="l1-card">
      <p style="font-weight:700;color:var(--bulig-green-dark);">${escapeHtml(lesson.data.prompt)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(lesson.data.prompt)}, 0.85)">🔊 Read description</button>
    </div>
    <div class="l1-card">
      <canvas id="drawCanvas" width="480" height="280"></canvas>
      <div class="l1-palette">
        ${['#2A2318','#E8432F','#1E7A46','#2352A3','#FFC42D'].map(c=>`<div class="l1-swatch" style="background:${c}" onclick="setDrawColor('${c}',this)"></div>`).join('')}
      </div>
      <div style="text-align:center;"><button class="l1-pill" onclick="clearCanvas()">🧹 Clear</button></div>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="finishLesson(${lesson.id})">✅ I'm Done Drawing!</button></div>`;
}
let drawCtx=null, drawing=false, drawColor='#2A2318';
function setupCanvas(){
  const canvas = document.getElementById('drawCanvas');
  drawCtx = canvas.getContext('2d');
  drawCtx.lineWidth = 4; drawCtx.lineCap='round'; drawCtx.strokeStyle = drawColor;
  const pos = (e) => {
    const rect = canvas.getBoundingClientRect();
    const cx = canvas.width / rect.width, cy = canvas.height / rect.height;
    const t = e.touches ? e.touches[0] : e;
    return { x:(t.clientX-rect.left)*cx, y:(t.clientY-rect.top)*cy };
  };
  const start = (e) => { drawing = true; const p = pos(e); drawCtx.beginPath(); drawCtx.moveTo(p.x,p.y); e.preventDefault(); };
  const move = (e) => { if(!drawing) return; const p = pos(e); drawCtx.lineTo(p.x,p.y); drawCtx.stroke(); e.preventDefault(); };
  const end = () => { drawing = false; };
  canvas.addEventListener('mousedown', start); canvas.addEventListener('mousemove', move); window.addEventListener('mouseup', end);
  canvas.addEventListener('touchstart', start, {passive:false}); canvas.addEventListener('touchmove', move, {passive:false}); canvas.addEventListener('touchend', end);
}
function setDrawColor(c, el){ drawColor = c; if(drawCtx) drawCtx.strokeStyle = c; document.querySelectorAll('.l1-swatch').forEach(s=>s.classList.remove('l1-active')); el.classList.add('l1-active'); }
function clearCanvas(){ if(drawCtx) drawCtx.clearRect(0,0,480,280); }

/* ---- Poem engine ---- */
function enginePoem(lesson){
  openLessonState.engineState = { poemIdx: 0 };
  return renderPoem(lesson);
}
function renderPoem(lesson){
  const es = openLessonState.engineState;
  const poemTabs = lesson.data.poems.map((p,i)=>`<button class="l1-rate-btn ${i===es.poemIdx?'l1-active':''}" onclick="switchPoem(${lesson.id},${i})">${escapeHtml(p.title)}</button>`).join('');
  const poem = lesson.data.poems[es.poemIdx];
  const linesHtml = poem.lines.map((line,i)=>`<div class="l1-poem-line" id="pline_${i}" onclick="speakPoemLine(${i},${attrJson(line)})">${escapeHtml(line)}</div>`).join('');
  const fullText = poem.lines.join(' ');
  return `
    <div class="l1-card"><p>Pick a poem, then tap "Read Whole Poem" or tap any line to hear it alone. Practice reciting it yourself!</p></div>
    <div class="l1-rate-row">${poemTabs}</div>
    <div class="l1-card">
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);margin-top:0;">${escapeHtml(poem.title)}</h3>
      ${linesHtml}
      <button class="l1-speak-btn" style="margin-top:10px;" onclick="speak(${attrJson(fullText)},0.9)">🔊 Read Whole Poem</button>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="finishLesson(${lesson.id})">✅ I Recited It!</button></div>`;
}
function switchPoem(lessonId, idx){
  openLessonState.engineState.poemIdx = idx;
  document.getElementById('lessonBody').innerHTML = renderPoem(LESSONS.find(l=>l.id===lessonId));
}
function speakPoemLine(i, text){
  document.querySelectorAll('.l1-poem-line').forEach(el=>el.classList.remove('l1-active'));
  const el = document.getElementById('pline_'+i); if(el) el.classList.add('l1-active');
  const u = speak(text, 0.85);
  if(u) u.onend = () => { if(el) el.classList.remove('l1-active'); };
}

/* ---- Tongue twister engine ---- */
function engineTwister(lesson){
  openLessonState.engineState = { practiced: new Set() };
  return renderTwisters(lesson);
}
function renderTwisters(lesson){
  const es = openLessonState.engineState;
  const itemsHtml = lesson.data.twisters.map((t,i)=>`
    <div class="l1-twister-item">
      <div class="l1-twister-text">${escapeHtml(t)}</div>
      <div class="l1-rate-row">
        <button class="l1-rate-btn" onclick="speak(${attrJson(t)},0.55)">🐢 Slow</button>
        <button class="l1-rate-btn" onclick="speak(${attrJson(t)},1)">🚶 Normal</button>
        <button class="l1-rate-btn" onclick="speak(${attrJson(t)},1.5)">🐇 Fast</button>
        <button class="l1-rate-btn ${es.practiced.has(i)?'l1-active':''}" onclick="markPracticed(${lesson.id},${i})">${es.practiced.has(i)?'✅ Practiced!':'I said it!'}</button>
      </div>
    </div>`).join('');
  const allDone = es.practiced.size === lesson.data.twisters.length;
  return `
    <div class="l1-card"><p>Listen slow, then fast! Try saying each tongue twister out loud, then tap "I said it!"</p></div>
    ${itemsHtml}
    <div class="l1-footer-actions"><button class="l1-btn ${allDone?'':'l1-disabled'}" onclick="finishLesson(${lesson.id})">✅ Finish Quest</button></div>`;
}
function markPracticed(lessonId, idx){
  openLessonState.engineState.practiced.add(idx);
  document.getElementById('lessonBody').innerHTML = renderTwisters(LESSONS.find(l=>l.id===lessonId));
}

/* ============ FINISH / CELEBRATE ============ */
async function finishLesson(lessonId){
  const lesson = LESSONS.find(l=>l.id===lessonId);
  const result = await saveToServer({ type:'lesson', lesson_id: lessonId });
  if(!result.ok){ toast('⚠️ Could not save — check your connection and try again.'); return; }
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
    <div class="l1-cel-big">${allDone ? '🏆' : '🎉'}</div>
    <h2>${allDone ? 'Oral Language Champion!' : 'Quest Complete!'}</h2>
    <p>${already ? "Nice replay! Keep practicing " + title + "." : "You earned the " + icon + " " + title + " badge and +" + xpAwarded + " XP!"}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Quest Map</button>
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
      <div style="font-size:11.5px;font-weight:800;color:var(--bulig-green);text-transform:uppercase;letter-spacing:.03em;">${lesson.icon} Lesson ${item.lessonId} · ${escapeHtml(lesson.title)}</div>
      <div class="l1-field" style="margin-top:6px;">
        <label>${escapeHtml(item.q)}</label>
        <textarea id="as_${i}" rows="2" placeholder="Type or say your answer, then write it here...">${escapeHtml(val)}</textarea>
      </div>
      <button class="l1-speak-btn" onclick="speak(${attrJson(item.q)})">🔊 Read aloud</button>
    </div>`;
  }).join('');
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeLesson()">✕</button>
      <div>
        <div class="l1-ltitle">📝 ${label}</div>
        <div class="l1-lsub">12 quick questions, one per lesson</div>
      </div>
    </div>
    <div class="l1-lesson-body">
      <div class="l1-card"><p>${done ? "You've already submitted this — your teacher can see your answers. You can still update them below." : "Answer each question the best you can. There's no wrong answer here — this just helps your teacher see where to help you!"}</p></div>
      ${itemsHtml}
      <div class="l1-footer-actions"><button class="l1-btn" onclick="submitAssessment('${kind}',${set.length})">${done ? '💾 Update Answers' : '✅ Submit to Teacher'}</button></div>
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
  if(!result.ok){ toast('⚠️ Could not save — check your connection and try again.'); return; }

  if(kind === 'pre'){ PROGRESS.preAnswers = target; if(!result.already_done){ PROGRESS.preDone = true; PROGRESS.xp += result.xp_awarded; } }
  else { PROGRESS.postAnswers = target; if(!result.already_done){ PROGRESS.postDone = true; PROGRESS.xp += result.xp_awarded; } }

  window.speechSynthesis && window.speechSynthesis.cancel();
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">📮</div>
    <h2>${result.already_done ? 'Answers Updated!' : 'Sent to Your Teacher!'}</h2>
    <p>${result.already_done ? 'Your teacher will see your updated answers.' : "Great job! You earned +" + result.xp_awarded + " XP for completing the " + (kind==='pre'?'Pre-Assessment':'Post-Assessment') + "."}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Quest Map</button>
  </div>`;
  document.body.appendChild(div);
}

/* ============ BOOT ============ */
renderMap();