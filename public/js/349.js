(self.webpackChunk=self.webpackChunk||[]).push([[349],{64349:(e,n,r)=>{"use strict";r.r(n),r.d(n,{default:()=>f});var s=r(37703),a=r(85893),u=r(67294),o=r(5977),t=r(22405),c=r(24911),d=r(21606);const i=function(){return(0,a.jsx)("div",{children:"Logging out..."})};var l=r(73727);const h=[{name:"Profile",url:"/dashboard"},{name:"Get EventsCountdown Pro",url:"/getpro"},{name:"My Videos",url:"/dashboard/myvideos"},{name:"Logout",url:"/dashboard/logout"}];const x=function(){return(0,a.jsx)("ul",{children:h.map((function(e){return(0,a.jsx)("li",{children:(0,a.jsx)(l.OL,{exact:!0,to:e.url,onMouseOver:function(){return(0,t.A)(e.url)},children:(0,a.jsx)("h5",{children:e.name})})},e.name)}))})};const j=function(e){var n=(0,o.k6)(),s=(0,t.z)((function(){return r.e(658).then(r.bind(r,65658))}));return(0,u.useLayoutEffect)((function(){!e.user.loading&&e.user.error&&n.push(c.Z.signup)}),[e.user]),e.user.loading?(0,a.jsx)(d.Z,{}):(0,a.jsxs)("div",{className:"dashboard",children:[(0,a.jsx)("img",{src:"/images/header.jpg?512421a0ccf5a4a82d03da84cf2aee2c"}),(0,a.jsxs)("div",{className:"dashboard__wrapper",children:[(0,a.jsx)("nav",{children:(0,a.jsx)(x,{})}),(0,a.jsxs)("main",{children:[(0,a.jsx)(o.AW,{exact:!0,path:"/dashboard",children:(0,a.jsx)(s,{user:e.user})}),(0,a.jsx)(o.AW,{exact:!0,path:c.Z.logout,component:i})]})]})]})};const f=(0,s.$j)((function(e){return{user:e.user}}))(j)}}]);