/* DhakaFin Motion Edition — progressive, dependency-free, page-wide enhancement.
   No data sent anywhere. Existing links/forms/calculators are untouched. */
(()=>{
  'use strict';
  const doc=document, body=doc.body;
  if(!body)return;
  const reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  body.classList.add('df-motion');
  const make=(tag,attrs)=>{
    const node=doc.createElement(tag);
    for(const [name,value] of Object.entries(attrs))node.setAttribute(name,value);
    return node;
  };

  // A 3px scroll-reading accent plus accessible return-to-top action.
  const progress=make('div',{'id':'df-reading-progress','aria-hidden':'true'});
  const top=make('button',{'id':'df-return-top','type':'button','aria-label':'Back to top','title':'Back to top'});
  top.innerHTML='<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5m-6 6 6-6 6 6"/></svg>';
  body.append(progress,top);
  top.addEventListener('click',()=>window.scrollTo({top:0,behavior:reduced?'instant':'smooth'}));
  let ticking=false;
  function updateScroll(){
    ticking=false;
    const height=Math.max(1,doc.documentElement.scrollHeight-window.innerHeight);
    const amount=Math.min(1,Math.max(0,window.scrollY/height));
    progress.style.transform=`scaleX(${amount})`;
    top.classList.toggle('df-show',window.scrollY>720);
  }
  window.addEventListener('scroll',()=>{if(!ticking){ticking=true;requestAnimationFrame(updateScroll)}},{passive:true});
  window.addEventListener('resize',()=>{if(!ticking){ticking=true;requestAnimationFrame(updateScroll)}},{passive:true});
  updateScroll();

  // First viewport editorial entrance. All animation is optional.
  const heroSelectors=body.classList.contains('v2-page')
    ? ['.v2-hero-copy .v2-overline','.v2-hero-copy h1','.v2-hero-copy p','.v2-hero-links','.v2-visual']
    : ['.hero-left .eyebrow','.hero-left h1','.hero-left .hero-lead','.hero-left .hero-actions','.hero-left .eight-proof-line','.hero-left .six-footnote','.hero-art'];
  if(!reduced){
    heroSelectors.forEach((sel,index)=>{
      const el=doc.querySelector(sel);if(!el)return;
      el.style.setProperty('--df-intro-delay',`${Math.min(index*105,610)}ms`);
      el.classList.add('df-hero-intro');
    });
  }

  // Reveals target headings and individual cards, never obscure entire sections.
  const selectors=body.classList.contains('v2-page')?[
    '.section-heading','.panel-heading','.directory-card','.detail-feature',
    '.reader-layout','.work-form','.result-panel','.dash-surface',
    '.content-section .pill','.content-section .preview-note',
    '.content-section .section-kicker','.last-cta .site-container',
    '.resource-row','.article-card','.team-card','.portal-card'
  ]:[
    '.scope-item','.advisory .section-title','.advisory .service',
    '.v9-atlas-intro','.v9-service-tile','.v9-cap-spotlight',
    '.tools .section-title','.tools .product',
    '.ai .ai-inner','.workspace .work-grid','.v9-audience-head',
    '.v9-audience-card','.insights .section-title',
    '.insights .insight-card','.v9-why-intro','.v9-why-item',
    '.approach .section-title','.approach .approach-step',
    '.cta .container'
  ];
  if(!reduced && 'IntersectionObserver' in window){
    const elements=[...new Set(selectors.flatMap(sel=>[...doc.querySelectorAll(sel)]))];
    const observer=new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(!entry.isIntersecting)return;
        const el=entry.target;
        el.classList.remove('df-queued');
        el.classList.add('df-visible');
        observer.unobserve(el);
      });
    },{rootMargin:'0px 0px -5% 0px',threshold:.06});
    const perParent=new WeakMap();
    elements.forEach(el=>{
      if(el.closest('.df-hero-intro'))return;
      const rect=el.getBoundingClientRect();
      // Never hide already-visible content or targets that are display:none.
      if(rect.width===0||rect.height===0||rect.top<window.innerHeight+35)return;
      const parent=el.parentElement;
      const index=perParent.get(parent)||0;
      perParent.set(parent,index+1);
      el.style.setProperty('--df-stagger',`${(index%4)*75}ms`);
      el.classList.add('df-reveal','df-queued');
      observer.observe(el);
    });
  }

  // Illustrative, already-existing chart gets a one-time stroke reveal.
  if(!reduced){
    const svg=doc.querySelector('svg.six-chart');
    const path=svg?.querySelector('path[stroke="#0b9e71"]');
    if(path&&typeof path.getTotalLength==='function'){
      const length=path.getTotalLength();
      if(Number.isFinite(length)&&length>0){
        path.style.setProperty('--df-path-length',String(Math.ceil(length)));
        path.classList.add('df-chart-line');
      }
    }
  }
})();
