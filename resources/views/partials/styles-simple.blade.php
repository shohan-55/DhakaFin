@verbatim
<style id="df-styles-simple">
/* DhakaFin premium production layer — static public launch. */
:root{--df-capsule:#063d31;--df-capsule-shadow:0 18px 65px rgba(0,39,29,.28)}
html{scroll-padding-top:105px}
/* Both homepage navbar and inner page nav remain visible as a stable capsule while scrolling. */
.nav-wrap{position:sticky!important;top:0!important;z-index:120!important;background:#063e32;transition:background .3s ease,padding .3s ease;}
.nav-wrap>.nav{transition:max-width .42s cubic-bezier(.2,.85,.2,1),min-height .35s ease,height .35s ease,border-radius .4s ease,box-shadow .4s ease,background .35s ease,padding .35s ease}
body.df-scrolled .nav-wrap{background:transparent!important;padding:9px 14px 0;top:0!important;}
body.df-scrolled .nav-wrap>.nav{max-width:1160px;width:min(100%,1160px);height:66px!important;margin:auto;border-radius:999px;background:rgba(3,50,39,.95);border:1px solid rgba(196,246,207,.27);box-shadow:var(--df-capsule-shadow);padding-left:25px;padding-right:25px;backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px)}
.v2-header{position:sticky!important;top:0!important;z-index:120!important;transition:background .35s ease,padding .35s ease}
.v2-nav{transition:max-width .42s cubic-bezier(.2,.85,.2,1),height .35s ease,border-radius .4s ease,box-shadow .4s ease,background .35s ease,padding .35s ease}
body.df-scrolled .v2-header{background:transparent!important;border-color:transparent!important;padding:9px 14px 0}
body.df-scrolled .v2-header>.v2-nav{max-width:1140px;width:min(100%,1140px);height:66px!important;margin:auto;padding-left:26px;padding-right:26px;border-radius:999px;background:rgba(3,50,39,.96);border:1px solid rgba(196,246,207,.25);box-shadow:var(--df-capsule-shadow);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px)}
/* Open mobile dropdown beneath the floating bar, not behind the content. */
body.df-scrolled .v2-header .v2-mobile{background:#063e32;border:1px solid #ffffff2b;border-radius:18px;margin-top:7px;box-shadow:0 22px 44px rgba(0,29,19,.22)}
body.df-scrolled .nav-wrap .dropdown,body.df-scrolled .nav-wrap .mobile-panel{background:#063c30;border-radius:20px;box-shadow:0 22px 48px rgba(0,26,17,.23)}
body.df-scrolled .v2-brand-mark{width:40px;height:40px;font-size:27px}
body.df-scrolled .v2-brand b{font-size:22px}
body.df-scrolled .brand-icon{width:40px;height:40px;font-size:27px}
body.df-scrolled .brand-name{font-size:22px}
/* Clear, credible language on pages that are still building toward online delivery */
.df-enquiry-note{color:#46665b;font-size:12px;line-height:1.65;margin-top:9px}
.df-service-notice{margin:15px 0 25px;background:#e8f4eb;border-left:3px solid #059669;border-radius:11px;padding:15px 19px;color:#0c4c3a;font-size:13px;line-height:1.65}
.df-client-service{margin:0;background:#f6f8f3;color:#12382b;font-family:Arial,'Segoe UI',sans-serif}
.df-simple-nav{display:flex;gap:22px;align-items:center;justify-content:space-between;padding:20px max(25px,calc((100vw - 1240px)/2));background:#063e32;color:#fff;position:sticky;top:0;z-index:120}
.df-simple-nav nav{display:flex;gap:26px}.df-simple-nav a,.df-client-service a{text-decoration:none;color:inherit}
.df-simple-brand{font-size:29px;font-family:Georgia,serif}.df-simple-brand strong{font-family:Arial,sans-serif;font-size:25px}.df-simple-cta{display:inline-block;background:#c6edcb;color:#063c30!important;border-radius:999px;padding:14px 21px;font-weight:700;font-size:14px}
.df-client-head{text-align:center;background:#063e32;color:#f6fff4;padding:110px 24px 125px}.df-client-head>span{color:#b6efbd;font-size:12px;letter-spacing:.15em}.df-client-head h1{max-width:900px;margin:30px auto;font-size:clamp(39px,6vw,76px);letter-spacing:-.045em;line-height:1.09}.df-client-head em{font-family:Georgia,serif;color:#b8eac4}.df-client-head p{max-width:710px;font-size:18px;line-height:1.8;color:#d8ece1;margin:0 auto 36px}
.df-client-tiles{max-width:1230px;display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin:-65px auto 100px;padding:0 25px;position:relative}.df-client-tiles article{background:white;border:1px solid #e1eae1;border-radius:24px;padding:36px 30px;box-shadow:0 17px 40px #002d1914}.df-client-tiles b{color:#0e8f63}.df-client-tiles h2{font-size:25px}.df-client-tiles p{color:#537062;line-height:1.8}.df-client-tiles a{font-weight:700;color:#047856}.df-simple-footer{text-align:center;background:#022f25;color:#e5f3e6;padding:36px}
@media(max-width:1090px){body.df-scrolled .nav-wrap>.nav{padding-left:15px;padding-right:15px}body.df-scrolled .v2-header>.v2-nav{padding-left:17px;padding-right:17px}.df-simple-nav{flex-wrap:wrap}.df-client-tiles{grid-template-columns:1fr}}
@media(max-width:680px){body.df-scrolled .nav-wrap{padding:7px 10px 0}body.df-scrolled .nav-wrap>.nav{height:64px!important;max-width:100%;border-radius:27px!important;padding:0 13px}.nav-wrap .brand{min-width:0}body.df-scrolled .nav-wrap .brand-name{font-size:20px}body.df-scrolled .nav-wrap .brand-icon{width:37px;height:37px;font-size:24px}body.df-scrolled .v2-header{padding:7px 10px 0}body.df-scrolled .v2-header>.v2-nav{height:64px!important;padding:0 13px;border-radius:27px!important}body.df-scrolled .v2-brand{min-width:0}body.df-scrolled .v2-brand b{font-size:19px}body.df-scrolled .v2-brand-mark{width:35px;height:35px;font-size:24px}.df-simple-nav nav{display:none}.df-simple-cta{font-size:12px}.df-client-head{padding:76px 25px 110px}.df-client-head p{font-size:15px}.df-client-tiles{margin-top:-50px}.df-client-tiles article{padding:26px 22px}}
@media(prefers-reduced-motion:reduce){.nav-wrap,.nav-wrap>.nav,.v2-header,.v2-header>.v2-nav{transition:none!important}}


/* Unified corporate coming-soon notice, intentionally separate from advisory/contact flows. */
.df-av-dialog{position:fixed;inset:0;max-width:min(94vw,550px);max-height:min(90vh,720px);margin:auto;padding:0;border:1px solid #c7e4d4;border-radius:27px;overflow:auto;background:#fafffb;color:#123d2e;box-shadow:0 40px 110px rgba(0,24,18,.37);z-index:2147483000}
.df-av-dialog::backdrop{background:rgba(0,22,16,.7);backdrop-filter:blur(8px)}
.df-av-dialog:not([open]){display:none}
.df-av-shell{padding:clamp(25px,5vw,43px);font-family:Inter,system-ui,Arial,sans-serif}
.df-av-top{display:flex;justify-content:space-between;align-items:center;gap:15px}
.df-av-brand{display:flex;align-items:center;gap:9px;color:#2e6c50;font-size:10px;font-weight:800;letter-spacing:.11em}
.df-av-icon{display:inline-flex;width:34px;height:34px;align-items:center;justify-content:center;background:#064d39;color:#d6f2db;border-radius:10px;font-size:18px;font-weight:900;letter-spacing:-.12em;padding-right:3px}
.df-av-close{width:39px;height:39px;border:1px solid #d8e8de;border-radius:50%;background:white;color:#284c3a;cursor:pointer;font-size:25px;line-height:1}
.df-av-close:hover{background:#eaf5ef}
.df-av-symbol{display:flex;justify-content:center;align-items:center;margin-top:27px;width:70px;height:70px;border-radius:22px;background:linear-gradient(140deg,#064f39,#05a274);color:#ecffe9;font-size:40px;transform:rotate(-8deg);box-shadow:0 14px 25px #0871482e}
.df-av-kicker{display:block;margin-top:28px;color:#078457;font-size:10px;letter-spacing:.18em;font-weight:850}
.df-av-dialog h2{font:700 clamp(29px,5vw,42px)/1.12 Inter,system-ui,sans-serif;letter-spacing:-.055em;color:#062e24;max-width:420px;margin:12px 0 16px}
.df-av-dialog p{font-size:15px;line-height:1.75;color:#526a5f;margin:0 0 21px}
.df-av-availability{display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:14px 15px;background:#ecf5ee;border:1px solid #dbe9dd;border-radius:14px;color:#1d4a35;font-size:13px;font-weight:750}
.df-av-dot{width:7px;height:7px;background:#13a875;border-radius:50%;flex:none}
.df-av-soon{margin-left:auto;font-size:9px;color:#157a58;letter-spacing:.07em}
.df-av-actions{display:flex;align-items:stretch;gap:10px;margin-top:23px;flex-wrap:wrap}
.df-av-primary,.df-av-secondary{border:0;border-radius:12px;min-height:48px;padding:13px 18px;font-size:13px;font-weight:750;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:10px}
.df-av-primary{background:#046847;color:white;flex:1}.df-av-primary:hover{background:#064b37;color:white}
.df-av-secondary{background:#edf2ee;color:#234537}.df-av-secondary:hover{background:#dfece3}
.df-av-more{display:inline-flex;color:#116849;font-weight:750;font-size:12px;text-decoration:none;margin-top:16px;gap:9px;align-items:center}
.df-av-more[hidden]{display:none}.df-av-more:hover{text-decoration:underline}
@media(max-width:520px){.df-av-dialog{max-width:calc(100vw - 24px);border-radius:22px}.df-av-shell{padding:25px 23px 29px}.df-av-actions{flex-direction:column}.df-av-primary,.df-av-secondary{width:100%}.df-av-soon{margin-left:0}.df-av-symbol{width:62px;height:62px;font-size:33px}}
@media(prefers-reduced-motion:reduce){.df-av-dialog::backdrop{backdrop-filter:none}}
/* DhakaFin premium grouped navigation — replaces overcrowded link bar, preserves sticky capsule. */
.df-header{z-index:155!important;overflow:visible!important}
.df-header .df-nav-groups{display:flex;align-items:center;gap:5px;min-width:0;margin-left:auto;margin-right:10px;white-space:nowrap}
.df-header .df-group-trigger{display:inline-flex;align-items:center;gap:7px;border:1px solid transparent;padding:12px 13px;border-radius:999px;background:transparent;color:#e3f5eb;font:650 12px/1.2 Arial,'Segoe UI',sans-serif;white-space:nowrap;cursor:pointer;transition:background .2s,color .2s,border .2s}
.df-header .df-group-trigger svg{transition:transform .23s;opacity:.75}
.df-header .df-group-trigger:hover,.df-header .df-group-trigger[aria-expanded="true"]{background:#ffffff13;color:#d1f8de;border-color:#ffffff1e}
.df-header .df-group-trigger[aria-expanded="true"] svg{transform:rotate(180deg)}
.df-header .df-nav-actions{display:flex;align-items:center;gap:11px;flex-shrink:0}
.df-header .df-client-link{font:650 12px/1.2 Arial,'Segoe UI',sans-serif;color:#def7e7;white-space:nowrap;padding:11px 8px;transition:color .2s}
.df-header .df-client-link:hover{color:#b1f7c8}
.df-header .df-talk-link{min-height:42px;border-radius:999px;padding:0 17px;display:inline-flex;align-items:center;gap:12px;background:#c8eecb;color:#063d2b;font:750 12px/1.2 Arial,'Segoe UI',sans-serif;white-space:nowrap;transition:background .25s,transform .25s}
.df-header .df-talk-link:hover{background:#edffef;transform:translateY(-2px)}
.df-header .df-mobile-trigger{display:none;align-items:center;justify-content:center;width:43px;height:43px;border:1px solid #ffffff58;border-radius:13px;background:#ffffff10;color:#fff;cursor:pointer}
.df-header .df-mega-layer{position:absolute;left:0;top:100%;width:100%;pointer-events:none;z-index:190}
.df-header .df-mega-panel{pointer-events:auto;width:min(930px,calc(100% - 44px));max-height:min(73vh,610px);overflow:auto;margin:7px auto 0;padding:30px 31px 27px;border-radius:23px;border:1px solid #e1eae2;background:#fff;color:#123a2a;box-shadow:0 28px 95px #012b2290,0 3px 14px #06492a26;animation:df-mega-arrive .18s ease-out}
.df-header .df-mega-panel[hidden],.df-header .df-mobile-nav[hidden]{display:none!important}
@keyframes df-mega-arrive{from{opacity:0;transform:translateY(-10px) scale(.985)}to{opacity:1;transform:translateY(0) scale(1)}}
.df-header .df-mega-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;padding-bottom:21px;border-bottom:1px solid #e3eee4}
.df-header .df-menu-eyebrow{color:#008761;letter-spacing:.18em;font-size:9px;font-weight:850}
.df-header .df-mega-heading h2{font-size:29px;letter-spacing:-.055em;color:#073b2a;margin:8px 0 6px;line-height:1.13}
.df-header .df-mega-heading p{font-size:13px;color:#667b6e;line-height:1.6;margin:0}
.df-header .df-mega-close{width:31px;height:31px;border:1px solid #dce8df;border-radius:50%;background:#f4faf4;color:#52705c;flex-shrink:0;font-size:20px;cursor:pointer;line-height:1}
.df-header .df-mega-body{display:grid;grid-template-columns:1.65fr 1fr;gap:25px;margin-top:21px}
.df-header .df-mega-links{display:grid;grid-template-columns:1fr 1fr;gap:8px;align-content:start}
.df-header .df-mega-link{display:flex;align-items:center;justify-content:space-between;gap:13px;padding:13px 12px;border:1px solid transparent;background:transparent;border-radius:12px;color:#24483b;font:650 13px/1.35 Arial,'Segoe UI',sans-serif;transition:background .2s,border-color .2s}
.df-header .df-mega-link svg{width:15px;flex-shrink:0;color:#13916b}
.df-header .df-mega-link:hover,.df-header .df-mega-link:focus-visible{background:#eff8ef;border-color:#d9eadc;color:#006347}
.df-header .df-mega-feature{display:flex;flex-direction:column;align-items:flex-start;background:radial-gradient(circle at 88% 0%,#176c50,#074a39 58%,#032c25);border-radius:16px;padding:22px;color:#e9faec;min-height:237px}
.df-header .df-mega-feature-symbol{font:italic 32px Georgia,serif;color:#c6f9d3}
.df-header .df-mega-feature-kicker{font-size:8px;letter-spacing:.17em;color:#a8dcbb;margin:16px 0 11px;font-weight:750}
.df-header .df-mega-feature strong{font-size:22px;letter-spacing:-.055em;line-height:1.2}
.df-header .df-mega-feature a{margin-top:auto;display:flex;align-items:center;gap:8px;color:#c8f5d5;font-size:12px;font-weight:750;padding-top:20px}
.df-header .df-mobile-nav{position:absolute;left:10px;right:10px;top:calc(100% + 7px);z-index:195;max-height:calc(100dvh - 120px);overflow:auto;background:#074434;border:1px solid #d5f6df39;border-radius:19px;padding:13px 14px 17px;color:white;box-shadow:0 20px 65px #032d28a6}
.df-header .df-mobile-nav a{color:#e3f8e9}
.df-header .df-mobile-home{display:flex;justify-content:space-between;align-items:center;padding:13px;font-size:13px;font-weight:750;border-bottom:1px solid #ffffff26}
.df-header .df-mobile-group{border-bottom:1px solid #ffffff25}
.df-header .df-mobile-group summary{list-style:none;cursor:pointer;display:flex;align-items:center;justify-content:space-between;padding:16px 13px;font-size:14px;font-weight:700}
.df-header .df-mobile-group summary::-webkit-details-marker{display:none}
.df-header .df-mobile-group summary svg{transition:transform .23s}
.df-header .df-mobile-group[open] summary svg{transform:rotate(180deg)}
.df-header .df-mobile-entries{padding:0 3px 9px;display:grid;grid-template-columns:1fr 1fr;gap:6px}
.df-header .df-mobile-entries a{display:flex;justify-content:space-between;gap:7px;background:#ffffff0d;border:1px solid #ffffff17;border-radius:8px;padding:12px;font-size:12px;line-height:1.4;min-width:0}
.df-header .df-mobile-entries svg{color:#a5e7bf;flex-shrink:0}
.df-header .df-mobile-entries a:hover{background:#ffffff20}
.df-header .df-mobile-footer{display:grid;gap:9px;padding-top:15px}
.df-header .df-mobile-footer a{padding:14px 16px;border-radius:11px;border:1px solid #ffffff2f;font-size:13px;display:flex;align-items:center;justify-content:space-between;font-weight:700}
.df-header .df-mobile-footer .df-mobile-talk{background:#c8f2d2;border-color:#c8f2d2;color:#0b4a32}
/* Ensure capsule density remains spacious at the user's 1366px desktop viewport. */
.nav-wrap.df-header>.nav,.v2-header.df-header>.v2-nav{gap:8px}
.df-header .brand{min-width:184px}
.df-header .v2-brand{min-width:180px}
@media(max-width:1290px){.df-header .df-nav-groups{gap:0;margin-right:3px}.df-header .df-group-trigger{padding:12px 9px}.df-header .df-nav-actions{gap:5px}.df-header .df-talk-link{padding-inline:12px}.df-header .df-client-link{padding-inline:6px}}
@media(max-width:1130px){.df-header .df-nav-groups,.df-header .df-client-link{display:none!important}.df-header .df-mobile-trigger{display:flex!important}.df-header .df-nav-actions{margin-left:auto;gap:9px}.df-header .df-mega-layer{display:none}.df-header .df-mobile-nav{display:block}}
@media(min-width:1131px){.df-header .df-mobile-nav{display:none!important}}
@media(max-width:590px){.df-header .df-talk-link{display:none}.df-header .df-mobile-entries{grid-template-columns:1fr}.df-header .df-mobile-trigger{width:40px;height:40px}.df-header .df-nav-actions{margin-left:auto}.df-header .df-mobile-nav{left:7px;right:7px;max-height:calc(100dvh - 107px)}}
@media(prefers-reduced-motion:reduce){.df-header .df-group-trigger,.df-header .df-talk-link,.df-header .df-mega-panel,.df-header .df-mega-link,.df-header svg{animation:none!important;transition:none!important}}
</style>
@endverbatim
