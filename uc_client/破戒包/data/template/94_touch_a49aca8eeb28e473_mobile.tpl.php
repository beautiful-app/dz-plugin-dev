<style></style>
				<script>
				function comiis_app_portal_loop(h, speed, delay, sid) {
					var t = null;
					var o = document.getElementById(sid);
					o.innerHTML += o.innerHTML;
					o.scrollTop = 0;
					function start() {
						t = setInterval(scrolling, speed);
						o.scrollTop += 2;
					}
					function scrolling() {
						if(o.scrollTop % h != 0) {
							o.scrollTop += 2;
							if(o.scrollTop >= o.scrollHeight / 2) o.scrollTop = 0;
						} else {
							clearInterval(t);
							setTimeout(start, delay);
						}
					}
					setTimeout(start, delay);
				}
				function comiis_app_portal_swiper(a, b){
					if(typeof(Swiper) == 'undefined') {
						$.getScript("./source/plugin/comiis_app_portal/image/comiis.js").done(function(){
							new Swiper(a, b);
						});
					}else{
						new Swiper(a, b);
					}
				}
				</script><div id="comiis_app_block_123" class="bg_f cl"><style>
.comiis_mh_nav_jd {height:40px;width:100%;overflow:hidden;}
.comiis_mh_nav_jdbox {height:40px;position:relative;}
.comiis_mh_nav_jdsub {height:40px;text-align:center;white-space:nowrap;width:100%;}
.comiis_mh_nav_jdsub li {float:left;width:auto;overflow:hidden;position:relative;}
.comiis_mh_nav_jdsub em {position:absolute;left:50%;bottom:2px;margin-left:-9px;height:4px;width:18px;border-radius:10px;}
.comiis_mh_nav_jdsub a {display:inline-block;font-size:15px;height:40px;line-height:40px;padding:0 12px;}
</style>
<div style="height:40px;"><div class="comiis_scrollTop_box"><div class="comiis_mh_nav_jd bg_f b_b">
<div class="comiis_mh_nav_jdbox">
<div class="comiis_mh_nav_jdsub">
<ul class="comiis_flex">
<li class="flex f_b"><a href="forum.php?forumlist=1">版区</a></li>
<li class="flex f_0"><em class="bg_0"></em><a href="javascript:;">新帖</a></li>
<li class="flex f_b"><a href="plugin.php?id=comiis_app_portal&pid=11">热图</a></li>
<li class="flex f_b"><a href="plugin.php?id=comiis_app_portal&pid=12">精选</a></li></ul>
</div>
</div>
</div>
</div></div></div><div id="comiis_app_block_125" class="bg_f b_b cl"><style>
.comiis_mhswfa {background:#000;overflow:hidden;position:relative;}
.comiis_mhswfa .swiper-slide span {position:absolute;left:0;bottom:36px;float:left;color:#fff;width:auto;padding:2px 10px;font-size:14px;max-height:60px;line-height:30px;background:rgba(0,0,0,0.7);overflow:hidden;}
.comiis_mhswfa_roll {position:absolute;left:0;bottom:0;margin-bottom:18px;height:18px;width:100%;text-align:center;color:#fff;z-index:9;overflow:hidden;}
.comiis_mhswfa_roll .swiper-pagination-bullet {display:inline-block;width:4px;height:4px;margin:0 2px;background-color:rgba(0, 0, 0, 0.35);border-radius:6px;}
.comiis_mhswfa_roll .swiper-pagination-bullet-active {background-color:#fff;width:10px;}
</style>
<div class="comiis_mhswfa comiis_mhswfa125">
<ul class="swiper-wrapper">
    <li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=17514" title="有没有养“六角恐龙”的？">
<img src="data/attachment/block/0a/0aec27fd5b97c76bb3524177225c6c43.jpg" width="100%" class="vm comiis_mhswfa_whb125" alt="有没有养“六角恐龙”的？">
<span>有没有养“六角恐龙”的？</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=127" title="《紫蓝花水泡》金鱼详细介绍">
<img src="data/attachment/block/e8/e84e2a2ad3c02ffcea40ccaa30e52e4c.jpg" width="100%" class="vm comiis_mhswfa_whb125" alt="《紫蓝花水泡》金鱼详细介绍">
<span>《紫蓝花水泡》金鱼详细介绍</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=88" title="锦鲤品种之&lt;银松叶锦鲤&gt;">
<img src="data/attachment/forum/201803/28/154507p9khrj9cmmlr18p8.bmp" width="100%" class="vm comiis_mhswfa_whb125" alt="锦鲤品种之&lt;银松叶锦鲤&gt;">
<span>锦鲤品种之&lt;银松叶锦鲤&gt;</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=70" title="锦鲤品种之&lt;赤别甲锦鲤&gt;">
<img src="data/attachment/block/2b/2b88cde71e80e7aaf895237b3d96b709.jpg" width="100%" class="vm comiis_mhswfa_whb125" alt="锦鲤品种之&lt;赤别甲锦鲤&gt;">
<span>锦鲤品种之&lt;赤别甲锦鲤&gt;</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=92" title="锦鲤品种之&lt;金昭和光写锦鲤&gt;">
<img src="data/attachment/block/6d/6da6053c5334d4357fda23f6805b9d33.jpg" width="100%" class="vm comiis_mhswfa_whb125" alt="锦鲤品种之&lt;金昭和光写锦鲤&gt;">
<span>锦鲤品种之&lt;金昭和光写锦鲤&gt;</span></a>
</li>
</ul>
<div class="comiis_mhswfa_roll comiis_mhswfa_roll125"></div>
<div class="comiis_svg_box"><div class="comiis_svg_a"></div><div class="comiis_svg_b"></div></div>
</div>
<script>
  $('.comiis_mhswfa_whb125').css('height', ($('.comiis_mhswfa_whb125').width() * 0.56) + 'px');
comiis_app_portal_swiper('.comiis_mhswfa125', {
slidesPerView : 'auto',
        pagination: '.comiis_mhswfa_roll125',
loop: true,
autoplay: 5000,
        autoplayDisableOnInteraction: false,
onTouchMove: function(swiper){
Comiis_Touch_on = 0;
},
onTouchEnd: function(swiper){
Comiis_Touch_on = 1;
},
});
</script></div><div id="comiis_app_block_124" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit03 {overflow:hidden;position:relative;}
.comiis_mh_tit03 h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;text-align:center;font-weight:400;overflow:hidden;}
</style>
<div class="comiis_mh_tit03 cl">
<h2 class="pb12 f_g">社区新帖</h2></div></div>