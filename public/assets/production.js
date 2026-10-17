/* DhakaFin public launch enhancements; no background network requests. */
(()=>{'use strict';
 const body=document.body; if(!body)return;
 const navbar=document.querySelector('.nav-wrap,.v2-header');
 const update=()=>{body.classList.toggle('df-scrolled',window.scrollY>72);};
 window.addEventListener('scroll',update,{passive:true});update();
 // Contact requests use the visitor's email application; no pretend successful submission.
 document.querySelectorAll('form[data-email-enquiry]').forEach(form=>{
  form.addEventListener('submit',e=>{
   e.preventDefault();
   if(!form.reportValidity())return;
   const get=name=>form.querySelector(`[name="${name}"]`)?.value?.trim()||'';
   const fields=[['Name',get('name')],['Email',get('email')],['Organisation',get('organisation')],['Area of interest',get('service')],['Preferred date',get('date')],['Message',get('message')]];
   const subject=(form.dataset.emailEnquiry==='consultation'?'Consultation request':'Website enquiry')+' — DhakaFin';
   const bodyText=fields.filter(x=>x[1]).map(([k,v])=>k+': '+v).join('\n')+'\n\nSent from the DhakaFin website. Please respond by email.';
   const mailto='mailto:hello.sohan@outlook.com?subject='+encodeURIComponent(subject)+'&body='+encodeURIComponent(bodyText);
   const feedback=form.querySelector('.form-response');
   if(feedback)feedback.textContent='Your email application should open. Please review the enquiry and select Send. Nothing has been submitted yet.';
   window.location.href=mailto;
  });
 });
 // For incomplete online services, send a visitor to appropriate advisory channels.
 document.querySelectorAll('[data-df-enquiry-link]').forEach(el=>{
   el.addEventListener('click',e=>{if(el.tagName==='A')return;e.preventDefault();window.location.href=(location.pathname.includes('/pages/')?'':'pages/')+'consultation.html';});
 });
})();


/* DhakaFin feature availability: browser-only, accessible. No claims of working backend. */
(()=>{'use strict';
 const PENDING={
  'personal-tax.html':'Personal Income Tax Calculator',
  'corporate-tax.html':'Corporate Income Tax Calculator',
  'tds-calculator.html':'TDS Calculator',
  'vds-calculator.html':'VDS Calculator',
  'vat-calculator.html':'VAT Calculator',
  'mushak-6-3.html':'Mushak 6.3 Generator',
  'vat-return.html':'VAT Return Preparation',
  'ai-tax-search.html':'AI Tax Law Search',
  'tax-search-results.html':'Tax Research Search',
  'client-dashboard.html':'Client Portal',
  'documents.html':'Document Vault',
  'reports.html':'Financial Reports',
  'login.html':'Client Portal',
  'register.html':'Client Registration',
  'submissions.html':'Online Submissions',
  'mushak-list.html':'Mushak Records',
  'mushak-detail.html':'Mushak Invoice Details',
  'notifications.html':'Client Notifications'
 };
 let dialog=null;
 function root(){return location.pathname.indexOf('/pages/')>=0?'../':'./';}
 function ensure(){
  if(dialog)return dialog;
  dialog=document.createElement('dialog');dialog.id='df-service-availability';dialog.className='df-av-dialog';
  dialog.setAttribute('aria-labelledby','df-av-heading');dialog.setAttribute('aria-describedby','df-av-description');
  dialog.innerHTML='<div class="df-av-shell">'+
   '<div class="df-av-top"><span class="df-av-brand"><span class="df-av-icon">df.</span> DHAKAFIN / DIGITAL SERVICES</span><button type="button" class="df-av-close" aria-label="Close notice" data-df-close>×</button></div>'+
   '<div class="df-av-symbol" aria-hidden="true">↗</div><span class="df-av-kicker">A BETTER EXPERIENCE IS ON THE WAY</span>'+
   '<h2 id="df-av-heading">This digital service is coming soon.</h2>'+
   '<p id="df-av-description">We are preparing this feature for a secure, reliable online experience.</p>'+
   '<div class="df-av-availability"><span class="df-av-dot"></span><span id="df-av-name"></span><span class="df-av-soon">IN DEVELOPMENT</span></div>'+
   '<div class="df-av-actions"><a class="df-av-primary" id="df-av-advisor" href="pages/consultation.html">Speak with an advisor <span aria-hidden="true">↗</span></a><button type="button" class="df-av-secondary" data-df-close>Continue browsing</button></div>'+
   '<a id="df-av-learn" class="df-av-more" href="pages/tools.html" hidden>Explore this service page <span aria-hidden="true">→</span></a>'+
   '</div>';
  document.body.appendChild(dialog);
  dialog.querySelectorAll('[data-df-close]').forEach(b=>b.addEventListener('click',()=>dialog.close()));
  dialog.addEventListener('click',e=>{if(e.target===dialog)dialog.close()});
  dialog.querySelector('#df-av-advisor').href=root()+'pages/consultation.html';
  return dialog;
 }
 function open(label,url){
  const d=ensure();
  const name=label||'DhakaFin online services';
  d.querySelector('#df-av-name').textContent=name;
  d.querySelector('#df-av-description').textContent='Our '+name.toLowerCase()+' online feature is currently being prepared. For related questions or assistance, our advisory team is available to help.';
  const more=d.querySelector('#df-av-learn');
  if(url && !/\b(admin|login|register|client-dashboard|documents|reports|submissions|notifications)\.html$/i.test(url)){
   more.hidden=false;more.href=url;
  }else{more.hidden=true;more.removeAttribute('href');}
  if(typeof d.showModal==='function'){if(!d.open)d.showModal();}
  else d.setAttribute('open','');
 }
 function getPending(a){
  if(a.dataset.dfComingSoon)return {label:a.dataset.dfComingSoon,url:''};
  if(a.classList.contains('product')&&/document vault/i.test(a.textContent))return {label:'Document Vault',url:''};
  let dest=a.getAttribute('href');if(!dest || dest.startsWith('#'))return null;
  let path;
  try{path=new URL(dest,document.baseURI).pathname.split('/').pop()}catch{return null;}
  if(path in PENDING)return {label:PENDING[path],url:dest};
  return null;
 }
 // Feature cards show availability before opening a page; informational service pages remain directly accessible.
 document.addEventListener('click',e=>{
  const a=e.target.closest?.('a[href],a[data-df-coming-soon]');
  if(a && !e.ctrlKey&&!e.metaKey&&!e.shiftKey&&!e.altKey && e.button===0){
   const p=getPending(a);
   if(p){e.preventDefault();e.stopImmediatePropagation();open(p.label,p.url);return;}
  }
  // Existing sample-input buttons and navigation tabs remain interactive.
  const b=e.target.closest?.('button[data-toast]');
  if(b){e.preventDefault();e.stopImmediatePropagation();open('Online account actions');}
 },true);
 // No fictitious calculations, AI outputs or saved data. Existing mailto enquiries work unchanged.
 document.addEventListener('submit',e=>{
  const f=e.target;
  if(f.matches?.('#aiForm,[data-ai-form],[data-demo-submit]')){
   e.preventDefault();e.stopImmediatePropagation();
   const path=location.pathname.split('/').pop();
   const title=f.id==='aiForm'?'AI Tax Law Search':(PENDING[path]||'Digital Tax Tools');
   open(title);
  }
 },true);
 document.addEventListener('keydown',e=>{if(e.key==='Escape'&&dialog?.open)dialog.close()});
})();
