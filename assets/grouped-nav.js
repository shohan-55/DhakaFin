/* DhakaFin grouped navigation: accessible hover/click menus and mobile accordion. */
(()=>{'use strict';
 for(const header of document.querySelectorAll('[data-df-navigation]')){
  const triggers=[...header.querySelectorAll('[data-df-group]')];
  const panels=[...header.querySelectorAll('[data-df-panel]')];
  const mobileTrigger=header.querySelector('[data-df-mobile-toggle]');
  const mobilePanel=header.querySelector('#df-mobile-nav');
  let current=null,leaveTimer=null,hoveredMenu=null;
  function closeMega(restoreFocus=false){
   const previous=current;
   current=null;
   panels.forEach(p=>p.hidden=true);
   triggers.forEach(t=>t.setAttribute('aria-expanded','false'));
   if(restoreFocus && previous)triggers.find(t=>t.dataset.dfGroup===previous)?.focus();
  }
  function openMega(name){
   if(!window.matchMedia('(min-width:1131px)').matches)return;
   if(leaveTimer){clearTimeout(leaveTimer);leaveTimer=null;}
   current=name;
   panels.forEach(p=>p.hidden=p.dataset.dfPanel!==name);
   triggers.forEach(t=>t.setAttribute('aria-expanded',String(t.dataset.dfGroup===name)));
   closeMobile();
  }
  function closeMobile(){if(!mobilePanel)return;mobilePanel.hidden=true;mobileTrigger?.setAttribute('aria-expanded','false');mobileTrigger?.setAttribute('aria-label','Open navigation')}
  function closeAll(){closeMega();closeMobile()}
  for(const trigger of triggers){
   trigger.addEventListener('click',()=>{const same=current===trigger.dataset.dfGroup; if(same && hoveredMenu!==trigger.dataset.dfGroup)closeMega();else openMega(trigger.dataset.dfGroup); hoveredMenu=null;});
   trigger.addEventListener('pointerenter',e=>{if(e.pointerType==='mouse'){hoveredMenu=trigger.dataset.dfGroup;openMega(trigger.dataset.dfGroup)}});
   trigger.addEventListener('keydown',e=>{if(e.key==='ArrowDown'){e.preventDefault();openMega(trigger.dataset.dfGroup);panels.find(p=>p.dataset.dfPanel===trigger.dataset.dfGroup)?.querySelector('a')?.focus()}});
  }
  header.addEventListener('pointerleave',e=>{hoveredMenu=null;if(e.pointerType==='mouse'&&current){leaveTimer=setTimeout(closeMega,180)}});
  header.addEventListener('pointerenter',()=>{if(leaveTimer){clearTimeout(leaveTimer);leaveTimer=null}});
  header.querySelectorAll('[data-df-close]').forEach(b=>b.addEventListener('click',()=>closeMega(true)));
  mobileTrigger?.addEventListener('click',()=>{
   const open=mobilePanel.hidden;
   closeMega();mobilePanel.hidden=!open;
   mobileTrigger.setAttribute('aria-expanded',String(open));
   mobileTrigger.setAttribute('aria-label',open?'Close navigation':'Open navigation');
  });
  mobilePanel?.querySelectorAll('details').forEach(details=>details.addEventListener('toggle',()=>{
    if(details.open)mobilePanel.querySelectorAll('details').forEach(other=>{if(other!==details)other.open=false});
  }));
  header.querySelectorAll('.df-mega-panel a,.df-mobile-nav a').forEach(a=>a.addEventListener('click',closeAll));
  document.addEventListener('click',e=>{if(!header.contains(e.target))closeAll()});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'){if(current){closeMega(true)}else if(mobilePanel&&!mobilePanel.hidden){closeMobile();mobileTrigger?.focus()}}});
  document.addEventListener('df:close-nav',closeAll);
  window.addEventListener('resize',closeAll,{passive:true});
  window.addEventListener('scroll',()=>{if(current)closeMega()},{passive:true});
 }
})();
