const panel=document.getElementById('circuits');
if(panel){
 const clock=new Intl.DateTimeFormat('en-GB',{timeZone:'America/Sao_Paulo',year:'numeric',month:'2-digit',day:'2-digit',hour:'2-digit',minute:'2-digit',second:'2-digit',hourCycle:'h23'});
 const months=['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];
 const labels=['MÊS','DIA','ANO','HORA','MIN','SEG'];
 const rows=[['destination','Destino de estudo',['OUT','21','2125','16','29','00']],['present','Presente · Brasília',['','','','','','']],['past','Última partida',['OUT','26','1985','01','21','00']]];
 let live;
 for(const [cls,title,values] of rows){
  const row=document.createElement('div');row.className='circuit '+cls;
  const heading=document.createElement('strong');heading.textContent=title;row.append(heading);
  const fields=values.map((v,i)=>{const cell=document.createElement('div'),label=document.createElement('small'),digit=document.createElement('b');label.textContent=labels[i];digit.textContent=v;cell.append(label,digit);row.append(cell);return digit;});
  if(cls==='present')live=fields;panel.append(row);
 }
 function tick(){const p=Object.fromEntries(clock.formatToParts(new Date()).map(part=>[part.type,part.value]));[months[Number(p.month)-1],p.day,p.year,p.hour,p.minute,p.second].forEach((v,i)=>{if(live[i].textContent!==v)live[i].textContent=v;});}
 tick();setInterval(tick,1000);
}

const exam = document.querySelector('.admission');
if (exam) {
  const questions = exam.querySelectorAll('fieldset');
  const count = document.getElementById('exam-count');
  function updateCount() {
    const answered = exam.querySelectorAll('input[type="radio"]:checked').length;
    count.textContent = answered + ' de ' + questions.length + ' respondidas';
  }
  exam.addEventListener('change', updateCount);
  updateCount();
}

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const film = document.querySelector('video[autoplay]');
if (film && reducedMotion.matches) film.pause();
reducedMotion.addEventListener('change', event => {
  if (event.matches && film) film.pause();
});

if ('IntersectionObserver' in window && !reducedMotion.matches) {
  const observer = new IntersectionObserver(entries => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        entry.target.classList.add('enter-frame');
        observer.unobserve(entry.target);
      }
    }
  }, { threshold: 0.08 });
  document.querySelectorAll('.field-photo, .mcard, .split, .candidate').forEach(el => observer.observe(el));
}

for (const link of document.querySelectorAll('header nav a')) {
  if (new URL(link.href).pathname === location.pathname) link.setAttribute('aria-current', 'page');
}
