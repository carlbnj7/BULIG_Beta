/* BULIG — Level 5 (Listening Comprehension & Vocabulary Development) quest engine
   PHP supplies SERVER_STATE + SAVE_URL before this file loads.
   SERVER_STATE.activities is this pupil's OWN grade's real activity list
   (pupil/level5.php already filtered by grade server-side) — each entry
   has {num, title, hasContent, data}. Activities without content yet are
   rendered as an honest "Content Coming Soon" node using their REAL
   title — never a fake quiz. Pupil-scored, same XP/celebration pattern
   as Levels 1-3. */

let PROGRESS = {
  grade: SERVER_STATE.grade,
  xp: SERVER_STATE.xp || 0,
  completed: (SERVER_STATE.completed || []).slice(),
};
const ACTIVITIES = SERVER_STATE.activities || [];
let openActivity = null; // {num, title, data, qIndex, score}

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

/* ============ RENDER: QUEST MAP ============ */
function renderMap(){
  const root = document.getElementById('mapRoot');
  const total = ACTIVITIES.length;
  const allDone = PROGRESS.completed.length === total && total > 0;

  let nodesHtml = '';
  ACTIVITIES.forEach((a, idx) => {
    const done = PROGRESS.completed.includes(a.num);
    const unlocked = a.hasContent && (idx === 0 || PROGRESS.completed.includes(ACTIVITIES[idx-1].num) || !ACTIVITIES[idx-1].hasContent);
    const side = idx % 2 === 0 ? 'l1-left' : 'l1-right';
    let cls = 'l1-node';
    if(!a.hasContent){ cls += ' l1-locked'; }
    else if(!unlocked){ cls += ' l1-locked'; }
    else if(done){ cls += ' l1-done'; }
    else { cls += ' l1-current'; }

    let btnHtml;
    if(!a.hasContent){ btnHtml = `<button disabled>\ud83d\udd28 Coming Soon</button>`; }
    else if(!unlocked){ btnHtml = `<button disabled>\ud83d\udd12 Locked</button>`; }
    else { btnHtml = `<button onclick="openActivityNode(${a.num})">${done ? 'Replay' : 'Play'}</button>`; }

    nodesHtml += `<div class="l1-node-row ${side}">
      <div class="${cls}">
        <div class="l1-num">${a.num}</div>
        ${done ? '<div class="l1-check">\u2713</div>' : ''}
        <div class="l1-nicon">${a.hasContent ? '\ud83c\udfa7' : '\ud83d\udd28'}</div>
        <div class="l1-ntitle">${escapeHtml(a.title)}</div>
        <div class="l1-nsub">${a.hasContent ? 'Listen &amp; Answer' : 'Content coming soon'}</div>
        ${btnHtml}
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
      <div class="stat-label">Level 5 Experience</div>
      <div class="stat-note">Earn 30 XP per activity</div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">\ud83c\udfa7</span>
      <div class="stat-value">${PROGRESS.completed.length} / ${total}</div>
      <div class="stat-label">Activities Completed</div>
      <div class="xp-bar-track"><div class="xp-bar-fill" style="width:${total ? Math.round(PROGRESS.completed.length/total*100) : 0}%"></div></div>
    </div>
    <div class="stat-card">
      <span class="stat-icon">${allDone ? '\ud83c\udfc6' : '\ud83c\udf93'}</span>
      <div class="stat-value">Grade ${PROGRESS.grade}</div>
      <div class="stat-label">${allDone ? 'Level 5 complete!' : 'Your assigned grade'}</div>
      <div class="stat-note">${allDone ? 'Amazing listening & vocabulary work!' : 'More activities are still being added'}</div>
    </div>
  </div>
  <h2 class="path-title" style="text-align:center;font-family:var(--font-display);color:var(--bulig-green-dark);margin:22px 0 10px;">\ud83d\uddfa\ufe0f Your Listening &amp; Vocabulary Path</h2>
  <div class="l1-path">${nodesHtml}</div>`;
}

function openActivityNode(num){
  const a = ACTIVITIES.find(x => x.num === num);
  if(!a || !a.hasContent) return;
  openActivity = { num: a.num, title: a.title, data: a.data, qIndex: 0, score: 0, answered: false };
  renderActivityOverlay();
}
function closeActivity(){
  window.speechSynthesis && window.speechSynthesis.cancel();
  const overlay = document.getElementById('lessonOverlay');
  if(overlay) overlay.remove();
  openActivity = null;
}

/* ============ ACTIVITY OVERLAY (quiz engine) ============ */
function renderActivityOverlay(){
  let overlay = document.getElementById('lessonOverlay');
  if(!overlay){ overlay = document.createElement('div'); overlay.id = 'lessonOverlay'; overlay.className = 'l1-overlay l1-scope'; document.body.appendChild(overlay); }
  overlay.innerHTML = `
    <div class="l1-lesson-head">
      <button class="l1-close" onclick="closeActivity()">\u2715</button>
      <div>
        <div class="l1-ltitle">\ud83c\udfa7 ${escapeHtml(openActivity.title)}</div>
        <div class="l1-lsub">Level 5 \u00b7 Grade ${PROGRESS.grade}</div>
      </div>
    </div>
    <div class="l1-lesson-body" id="activityBody"></div>
  `;
  renderQuestion();
}

function renderQuestion(){
  const body = document.getElementById('activityBody');
  const data = openActivity.data;
  const total = data.questions.length;

  if(openActivity.qIndex === 0 && !openActivity.introShown){
    openActivity.introShown = true;
  }

  if(openActivity.qIndex >= total){
    const pct = Math.round((openActivity.score / total) * 100);
    body.innerHTML = `<div class="l1-card" style="text-align:center;">
      <div style="font-size:40px;">\ud83c\udf89</div>
      <h3 style="color:var(--bulig-green-dark);font-family:var(--font-display);">Great listening!</h3>
      <p>You got ${openActivity.score} out of ${total} correct (${pct}%). Every try helps you learn!</p>
    </div>
    <div class="l1-footer-actions"><button class="l1-btn" onclick="finishActivity()">\u2705 Finish Activity</button></div>`;
    return;
  }

  const q = data.questions[openActivity.qIndex];
  const introHtml = openActivity.qIndex === 0
    ? `<div class="l1-card"><p>${escapeHtml(data.intro)}</p><button class="l1-speak-btn" onclick="speak(${attrJson(data.intro)})">\ud83d\udd0a Read aloud</button></div>`
    : '';

  const optsHtml = q.options.map((opt,i) => `<button class="l1-quiz-opt" id="opt_${i}" onclick="answerQuestion(${i})">${escapeHtml(opt)}</button>`).join('');

  body.innerHTML = `
    ${introHtml}
    <div class="l1-quiz-progress">Question ${openActivity.qIndex+1} of ${total}</div>
    <div class="l1-card">
      <div class="l1-quiz-q">${escapeHtml(q.q)}</div>
      <button class="l1-speak-btn" style="margin-bottom:12px;" onclick="speak(${attrJson(q.q)})">\ud83d\udd0a Read aloud</button>
      <div>${optsHtml}</div>
    </div>
    <div class="l1-footer-actions" id="quizNextWrap"></div>`;
}

function answerQuestion(idx){
  if(openActivity.answered) return;
  openActivity.answered = true;
  const q = openActivity.data.questions[openActivity.qIndex];
  document.getElementById('opt_'+idx).classList.add(idx===q.correct ? 'l1-correct' : 'l1-wrong');
  if(idx===q.correct){ openActivity.score++; } else { document.getElementById('opt_'+q.correct).classList.add('l1-correct'); }
  document.getElementById('quizNextWrap').innerHTML = `<button class="l1-btn" onclick="nextQuestion()">Next \u279c</button>`;
}
function nextQuestion(){
  openActivity.qIndex++;
  openActivity.answered = false;
  renderQuestion();
}

async function finishActivity(){
  const num = openActivity.num;
  const title = openActivity.title;
  const result = await saveToServer({ type:'activity', activity_num: num });
  if(!result.ok){ toast('\u26a0\ufe0f Could not save \u2014 check your connection and try again.'); return; }
  if(!result.already_done){
    PROGRESS.completed.push(num);
    PROGRESS.xp += result.xp_awarded;
  }
  window.speechSynthesis && window.speechSynthesis.cancel();
  showCelebration(title, result.already_done, result.xp_awarded);
}
function showCelebration(title, already, xpAwarded){
  const allDone = PROGRESS.completed.length === ACTIVITIES.length;
  const div = document.createElement('div');
  div.className = 'l1-celebrate l1-scope';
  div.innerHTML = `<div class="l1-cel-box">
    <div class="l1-cel-big">${allDone ? '\ud83c\udfc6' : '\ud83c\udf89'}</div>
    <h2>${allDone ? 'Level 5 Complete!' : 'Activity Complete!'}</h2>
    <p>${already ? "Nice replay! Keep practicing " + escapeHtml(title) + "." : "You earned +" + xpAwarded + " XP for " + escapeHtml(title) + "!"}</p>
    <button class="l1-btn l1-btn-secondary" onclick="backToMap()">Back to Quest Map</button>
  </div>`;
  document.body.appendChild(div);
}
function backToMap(){
  document.querySelectorAll('.l1-celebrate').forEach(e=>e.remove());
  closeActivity();
  renderMap();
}

/* ============ BOOT ============ */
renderMap();
