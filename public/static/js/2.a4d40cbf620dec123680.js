webpackJsonp([2],{"1tB0":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=a("8VVY"),o=a("hPOA"),n=a("+rOk"),i=n(s.a,o.a,!1,null,null,null);e.default=i.exports},"8VVY":function(t,e,a){"use strict";e.a={data:function(){return{collapse:"closed"===window.localStorage.getItem("wemesh_sidebar"),app:" 胶东医院信息化系统"}},methods:{toggleSidebar:function(){this.collapse=!this.collapse,this.collapse?window.localStorage.setItem("wemesh_sidebar","closed"):window.localStorage.setItem("wemesh_sidebar","open")},goto:function(t){this.$router.push("/dashboard/"+t),window.console.log(t)},showMenu:function(t,e){this.$refs.menuCollapsed.getElementsByClassName("submenu-hook-"+t)[0].style.display=e?"block":"none"},onLogout:function(){this.$store.dispatch("logout")}},computed:{account:function(){return this.$store.state.account},menu:function(){return this.$store.state.account.menus},openeds:function(){return["/"+this.$route.path.split("/")[1],"/"+this.$route.path.split("/")[2]]}},mounted:function(){null===window.localStorage.getItem("wemesh_sidebar")&&window.localStorage.setItem("wemesh_sidebar","open")}}},hPOA:function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"body",class:t.collapse?"collapsed":""},[a("div",{staticClass:"wrapper"},[a("header",{staticClass:"main-header"},[a("router-link",{staticClass:"logo",attrs:{to:"login"}},[t.collapse?a("span",{staticClass:"logo-mini"},[a("i",{staticClass:"el-icon-fa-podcast"})]):a("span",{staticClass:"logo-lg"},[a("i",{staticClass:"el-icon-fa-podcast",staticStyle:{font:"16px"}}),t._v(" "+t._s(t.app))])]),t._v(" "),a("nav",{staticClass:"navbar navbar-static-top"},[a("a",{staticClass:"sidebar-toggle",on:{click:function(e){e.preventDefault(),t.toggleSidebar(e)}}},[a("span",{staticClass:"sr-only"},[t._v("缩小边栏")])]),t._v(" "),a("div",{staticClass:"navbar-custom-menu"},[a("ul",{staticClass:"nav navbar-nav"},[a("li",{staticClass:"dropdown user user-menu"},[a("el-dropdown",{attrs:{trigger:"hover"}},[a("a",{staticClass:"el-dropdown-link userinfo-inner"},[a("img",{staticClass:"user-image",attrs:{src:t.account.avatar}}),t._v(" "+t._s(t.account.name))]),t._v(" "),a("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[a("el-dropdown-item",[a("i",{staticClass:"fa fa-vcard"}),t._v(" "),a("router-link",{attrs:{to:"/home/me/profile"}},[t._v("个人资料")])],1),t._v(" "),a("el-dropdown-item",[a("i",{staticClass:"fa fa-comments"}),t._v(" "),a("router-link",{attrs:{to:"/home/me/event"}},[t._v("通知提醒")])],1),t._v(" "),a("el-dropdown-item",{attrs:{divided:""}},[a("a",{on:{click:function(e){e.preventDefault(),t.onLogout(e)}}},[a("i",{staticClass:"fa fa-window-close"}),t._v(" 退出登录")])])],1)],1)],1)])])])],1),t._v(" "),a("aside",{staticClass:"main-sidebar"},[a("section",{staticClass:"sidebar"},[a("div",{staticClass:"user-panel"},[a("div",{staticClass:"pull-left image"},[a("img",{staticClass:"img-circle",attrs:{src:t.account.avatar,alt:"User Image"}})]),t._v(" "),a("div",{staticClass:"pull-left info"},[a("p",[t._v(t._s(t.account.name))]),t._v(" "),a("a",{attrs:{href:"#"}},[t._v("[ "+t._s(t.account.nickname)+" ]")])])]),t._v(" "),a("el-menu",{attrs:{"default-active":t.$route.path,"default-openeds":t.openeds,"unique-opened":"",router:"",collapse:t.collapse}},[t._l(t.menu,function(e,s){return["children"in e?a("el-submenu",{attrs:{index:e.path}},[a("template",{slot:"title"},[a("i",{class:"el-icon-fa-"+e.icon}),t._v(" "),a("span",{attrs:{slot:"title"},slot:"title"},[t._v(" "+t._s(e.name))])]),t._v(" "),a("el-menu-item-group",t._l(e.children,function(e){return a("el-menu-item",{key:e.path,attrs:{index:e.path}},[a("router-link",{attrs:{to:e.path}},[t._v(t._s(e.name))])],1)}))],2):a("el-menu-item",{attrs:{index:e.path}},[a("i",{class:"el-icon-fa-"+e.icon}),t._v(" "),a("span",{attrs:{slot:"title"},slot:"title"},[t._v(" "+t._s(e.name))])])]})],2)],1)]),t._v(" "),a("div",{staticClass:"content-wrapper"},[a("section",{staticClass:"content-header"},[a("strong",{staticClass:"title"},[t._v(t._s(t.$route.meta.name))]),t._v(" "),a("el-breadcrumb",{staticClass:"breadcrumb-inner",attrs:{separator:"/"}},[a("el-breadcrumb-item",{attrs:{to:{name:"home"}}},[a("i",{staticClass:"el-icon-fa-home"})]),t._v(" "),t._l(t.$route.matched,function(e){return"/home"!=e.path?a("el-breadcrumb-item",{key:e.path,attrs:{to:e.path}},[t._v(t._s(e.meta.name))]):t._e()})],2)],1),t._v(" "),a("el-col",{staticClass:"content",attrs:{span:24}},[a("router-view")],1)],1)])])},o=[],n={render:s,staticRenderFns:o};e.a=n}});
//# sourceMappingURL=2.a4d40cbf620dec123680.js.map