webpackJsonp([30],{"50AQ":function(i,e){},C1qu:function(i,e,t){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=t("IHPB"),s=t.n(n),a={data:function(){return{page:1,pageSize:10,list:[]}},mounted:function(){this.recommend()},methods:{recommend:function(){var i=this;this.$api.recommendList({page:this.page,limit:this.pageSize},function(e){var t=e.data;i.list=[].concat(s()(t)),t.length<i.pageSize&&i.$refs.infinitescrollDemo.$emit("ydui.infinitescroll.loadedDone")})},loadMore:function(){var i=this;this.$api.recommendList({page:++this.page,limit:this.pageSize},function(e){var t=e.data;i.list=[].concat(s()(i.list),s()(t)),t.length<i.pageSize&&i.$refs.infinitescrollDemo.$emit("ydui.infinitescroll.loadedDone"),i.$refs.infinitescrollDemo.$emit("ydui.infinitescroll.finishLoad")})}}},l={render:function(){var i=this,e=i.$createElement,t=i._self._c||e;return t("div",{staticClass:"recommendlist"},[t("yd-infinitescroll",{ref:"infinitescrollDemo",attrs:{callback:i.loadMore}},[t("ul",{staticClass:"recommend",attrs:{slot:"list"},slot:"list"},i._l(i.list,function(e,n){return t("li",{key:n,staticClass:"recommenddetail"},[t("img",{staticClass:"user-img",attrs:{src:e.avatar}}),i._v(" "),t("div",{staticClass:"recommendcontent"},[t("h4",[i._v("昵称："+i._s(e.nickname?e.nickname:"暂无"))]),i._v(" "),t("p",[i._v("手机："+i._s(e.mobile))]),i._v(" "),t("span",[i._v("推荐时间："+i._s(e.ctime))])])])}))])],1)},staticRenderFns:[]};var o=t("C7Lr")(a,l,!1,function(i){t("50AQ")},null,null);e.default=o.exports}});
//# sourceMappingURL=30.c5ad8d213264dcc9e75e.js.map