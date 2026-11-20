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
