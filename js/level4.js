/* BULIG — Level 4 Fluency quest engine
   PHP supplies SERVER_STATE + SAVE_URL before this file loads.
   SERVER_STATE.content is this pupil's OWN grade's passages only
   (pupil/level4.php already filtered by grade server-side) — this file
   never sees or renders any other grade's content.

   Unlike Levels 1-3, this is not a self-graded quiz: fluency is measured
   by a teacher listening to the pupil read aloud (see the module's own
   "Marking and Scoring Guide"), so:
     - Pre-test / Post-test: the pupil reads the passage (with Read Aloud
       TTS available as a model) and the score shown here is whatever
       the TEACHER has already entered on teacher/progress.php. The
       pupil cannot self-score these.
     - Intervention (practice) passages: pupil-paced reading practice —
       listen, read along, then tap "Mark as Practiced" to earn XP, same
       gamification pattern (XP, badges, celebration) as every other
       level. */

const content = SERVER_STATE.content;
let PROGRESS = {
  grade: SERVER_STATE.grade,
  xp: SERVER_STATE.xp || 0,
  completed: (SERVER_STATE.completed || []).slice(),
  preDone: !!SERVER_STATE.preDone,
  postDone: !!SERVER_STATE.postDone,
  preScore: SERVER_STATE.preScore || null,
  postScore: SERVER_STATE.postScore || null,
};
let openPassage = null; // {kind:'pre'|'post'|'intervention', index}

/* ============ TTS (same pattern as every other level) ============ */
function speak(text, rate){
  if(!window.speechSynthesis){
    toast("\u26a0\ufe0f This browser doesn't support read-aloud (Web Speech API).");
    return null;
  }
  window.speechSynthesis.cancel();
  const u = new SpeechSynthesisUtterance(text);
  u.rate = rate || 0.92;
  u.pitch = 1.02;
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

async function saveToServer(payload){
  try{
    const res = await fetch(SAVE_URL, { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
    return await res.json();
  }catch(e){ return { ok:false, error:'network' }; }
}
function escapeHtml(str){
  return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}
function attrJson(val){ return escapeHtml(JSON.stringify(val)); }
function toast(msg){
  const t = document.createElement('div');
  t.className = 'l1-toast';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(()=>t.remove(), 2200);
}
function scoreBadgeHtml(score){
  if(!score) return '';
  const levelColor = score.readingLevel === 'Independent' ? 'var(--bulig-green-dark)' : (score.readingLevel === 'Instructional' ? 'var(--bulig-gold)' : 'var(--bulig-red, #d9534f)');
  return `<div class="l1-card" style="background:#F3FBF4;">
    <div style="font-weight:800;color:${levelColor};">Score: ${score.oralReadingScore}% \u00b7 ${escapeHtml(score.readingLevel)}</div>
    <div style="font-size:12.5px;color:var(--ink-soft);margin-top:2px;">${score.wpm} words per minute \u00b7 ${score.totalMiscues} miscue${score.totalMiscues===1?'':'s'} out of ${score.wordCount} words</div>
  </div>`;
}

/* ============ RENDER: QUEST MAP ============ */
function renderMap(){
  const root = document.getElementById('mapRoot');
  const total = content.interventions.length;
  const allPracticed = PROGRESS.completed.length === total;

  let nodesHtml = '';
  content.interventions.forEach((p, idx) => {
    const num = idx + 1;
    const done = PROGRESS.completed.includes(num);
    const unlocked = idx === 0 || PROGRESS.completed.includes(idx);
    const side = idx % 2 === 0 ? 'l1-left' : 'l1-right';
    let cls = 'l1-node';
    if(!unlocked) cls += ' l1-locked'; else if(done) cls += ' l1-done'; else cls += ' l1-current';
    nodesHtml += `<div class="l1-node-row ${side}">
      <div class="${cls}">
        <div class="l1-num">${num}</div>
        ${done ? '<div class="l1-check">\u2713</div>' : ''}
        <div class="l1-nicon">\ud83d\udcd6</div>
        <div class="l1-ntitle">${escapeHtml(p.title)}</div>
        <div class="l1-nsub">${p.word_count} words</div>
        <button ${unlocked ? `onclick="openIntervention(${idx})"` : 'disabled'}>${done ? 'Read Again' : unlocked ? 'Read' : '\ud83d\udd12 Locked'}</button>
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
      <div class="stat-label">Level 4 Experience</div>
      <div class="stat-note">Earn 20 XP per passage practiced</div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">\ud83d\udcd6</span>
      <div class="stat-value">${PROGRESS.completed.length} / ${total}</div>
      <div class="stat-label">Passages Practiced</div>
      <div class="xp-bar-track"><div class="xp-bar-fill" style="width:${Math.round(PROGRESS.completed.length/total*100)}%"></div></div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">${allPracticed ? '\ud83c\udfc6' : '\ud83c\udfaf'}</span>
      <div class="stat-value">${allPracticed ? 'All done!' : (total - PROGRESS.completed.length) + ' to go'}</div>
      <div class="stat-label">${allPracticed ? 'Ready for Post-Test' : 'Passages remaining'}</div>
      <div class="stat-note">Grade ${PROGRESS.grade} \u00b7 keep reading!</div>
    </div>
  </div>

  <div class="action-grid">
    <a href="javascript:void(0)" class="action-card" onclick="openPreOrPost('pre')">
      <span class="action-icon">\ud83d\udcdd</span>
      <h3>Pre-Test Reading</h3>
      <p>Read "${escapeHtml(content.pretest.title)}" to your teacher before you start.</p>
      <span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.preDone ? 'View your score \u2192' : 'View passage \u2192'}</span>
    </a>
    <a href="javascript:void(0)" class="action-card ${allPracticed ? '' : 'is-soon'}" ${allPracticed ? "onclick=\"openPreOrPost('post')\"" : ''}>
      <span class="action-icon">${allPracticed ? '\ud83c\udfc5' : '\ud83d\udd12'}</span>
      <h3>Post-Test Reading</h3>
      <p>${allPracticed ? 'Read the same passage again for your teacher — show your progress!' : 'Unlocks after all passages below are practiced.'}</p>
      ${allPracticed ? `<span class="action-go" style="color:var(--bulig-green-dark);">${PROGRESS.postDone ? 'View your score \u2192' : 'View passage \u2192'}</span>` : '<span class="pill-soon">Locked</span>'}
    </a>
  </div>

  <h2 class="section-title">\ud83d\uddfa\ufe0f Grade ${PROGRESS.grade} Reading Path</h2>
  <div class="l1-path">${nodesHtml}</div>`;
}

/* ============ PASSAGE OVERLAY ============ */
function passageOverlayShell(title, sub){
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className='l1-overlay l1-scope'; document.body.appendChild(overlay); }
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeOverlay()">\u2715</button>
      <div>
        <div class="l1-ltitle">\ud83d\udcd6 ${escapeHtml(title)}</div>
        <div class="l1-lsub">${escapeHtml(sub)}</div>
      </div>
    </div>
    <div class="l1-lesson-body" id="lessonBody"></div>`;
  return overlay;
}
function closeOverlay(){
  window.speechSynthesis && window.speechSynthesis.cancel();
  const overlay = document.getElementById('lessonOverlay');
  if(overlay) overlay.remove();
  openPassage = null;
}

function openIntervention(idx){
  openPassage = { kind:'intervention', index: idx };
  const p = content.interventions[idx];
  passageOverlayShell(p.title, `Passage ${idx+1} of ${content.interventions.length} \u00b7 ${p.word_count} words`);
  const done = PROGRESS.completed.includes(idx+1);
  document.getElementById('lessonBody').innerHTML = `
    <div class="l1-card">
      <p style="font-size:16px;line-height:1.7;">${escapeHtml(p.text)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(p.text)})">\ud83d\udd0a Read Aloud</button>
    </div>
    <div class="l1-card" style="text-align:center;">
      <p>Read this passage out loud, then tap below when you've practiced it.</p>
    </div>
    <div class="l1-footer-actions">
      <button class="l1-btn ${done ? 'l1-btn-secondary' : ''}" onclick="markPracticed(${idx})">${done ? '\u2705 Practiced \u2014 Read Again' : '\u2705 Mark as Practiced'}</button>
    </div>`;
}

async function markPracticed(idx){
  const result = await saveToServer({ type:'passage', passage_num: idx+1 });
  if(!result.ok){ toast('\u26a0\ufe0f Could not save \u2014 check your connection and try again.'); return; }
  if(!result.already_done){
    PROGRESS.completed.push(idx+1);
    PROGRESS.xp += result.xp_awarded;
  }
  window.speechSynthesis && window.speechSynthesis.cancel();
  const total = content.interventions.length;
  const allDone = PROGRESS.completed.length === total;
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">${allDone ? '\ud83c\udfc6' : '\ud83c\udf89'}</div>
    <h2>${allDone ? 'All Passages Practiced!' : 'Great Reading!'}</h2>
    <p>${result.already_done ? "Nice re-read! Keep practicing to build your fluency." : "You practiced \u201c" + content.interventions[idx].title + "\u201d and earned +" + result.xp_awarded + " XP!"}</p>
    ${allDone ? '<p>Your Post-Test reading is now unlocked \u2014 ask your teacher to listen to you read it!</p>' : ''}
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Reading Path</button>
  </div>`;
  document.body.appendChild(div);
}

function openPreOrPost(kind){
  openPassage = { kind, index:0 };
  const passage = kind === 'pre' ? content.pretest : content.posttest;
  const score = kind === 'pre' ? PROGRESS.preScore : PROGRESS.postScore;
  const label = kind === 'pre' ? 'Pre-Test' : 'Post-Test';
  passageOverlayShell(passage.title, `${label} Reading \u00b7 ${passage.word_count} words`);
  document.getElementById('lessonBody').innerHTML = `
    <div class="l1-card">
      <p style="font-size:16px;line-height:1.7;">${escapeHtml(passage.text)}</p>
      <button class="l1-speak-btn" onclick="speak(${attrJson(passage.text)})">\ud83d\udd0a Read Aloud (listen first)</button>
    </div>
    <div class="l1-card" style="text-align:center;">
      <p>${score ? 'Here is the score your teacher recorded:' : 'Read this passage OUT LOUD to your teacher \u2014 they will listen and record your score.'}</p>
    </div>
    ${scoreBadgeHtml(score)}
    <div class="l1-footer-actions"><button class="l1-btn l1-btn-secondary" onclick="closeOverlay(); renderMap();">\u2b05\ufe0f Back</button></div>`;
}

function backToMap(){
  document.querySelectorAll('.l1-celebrate').forEach(e=>e.remove());
  closeOverlay();
  renderMap();
}

/* ============ BOOT ============ */
renderMap();
