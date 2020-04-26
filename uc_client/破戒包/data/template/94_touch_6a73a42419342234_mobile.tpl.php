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
				</script><div id="comiis_app_block_134" class="bg_f cl"><style>
.comiis_showimg12 {display:none;position:fixed;top:-100%;left:0;z-index:200}
.comiis_showimg12 span {position:absolute;right:0;top:-35px;width:26px;height:26px;line-height:26px;text-align:center;background:rgba(0, 0, 0, 0.6);box-shadow:0 0 3px #888;border-radius:50%;}
.comiis_showimg12 span i {font-size:12px;}
.comiis_showimg12 img {box-shadow:0 0 5px #888;border-radius:4px;width:260px;}
</style>
<div class="comiis_showimg12 comiis_showimg12_134">
    <span class="f_f"><i class="comiis_mhfont">&#xe639;</i></span>
    <a href="http://www.ylqsz.cn"><img src=" http://www.ylqsz.cn/source/plugin/comiis_app_portal/comiis/showimg12/img/001.jpg"></a></div>
<script>
$(document).ready(function() {
if (getcookie('comiis_showimg12_134') != 1) {
var comiis_showimg12_134_id = $('.comiis_showimg12_134');
comiis_showimg12_134_id.css({'left' : (($(window).width() - comiis_showimg12_134_id.outerWidth()) / 2), 'display' : 'block'}).animate({"top" : (($(window).height() - comiis_showimg12_134_id.outerHeight()) / 2)}, 800);
$('.comiis_showimg12_134 i.comiis_mhfont').click(function(){
comiis_showimg12_134_id.fadeOut(400);
setcookie('comiis_showimg12_134', '1', comiis_showimg12_time, '', '', '');
});

}
});
</script>
</div><div id="comiis_app_block_8" class="bg_f cl"><style>
.comiis_mh_navs {height:40px;width:100%;overflow:hidden;}
.comiis_mh_subbox {height:40px;position:relative;}
.comiis_mh_sub {height:40px;text-align:center;white-space:nowrap;width:100%;}
.comiis_mh_sub li {float:left;width:auto;overflow:hidden;position:relative;}
.comiis_mh_sub em {position:absolute;left:50%;bottom:2px;margin-left:-9px;height:4px;width:18px;border-radius:10px;}
.comiis_mh_sub a {display:inline-block;font-size:15px;height:40px;line-height:40px;padding:0 12px;}
</style>
<div style="height:40px;"><div class="comiis_scrollTop_box"><div class="comiis_mh_navs bg_f b_b">
<div class="comiis_mh_subbox">
<div id="comiis_mh_sub8" class="comiis_mh_sub">
<ul class="swiper-wrapper">
<li class="swiper-slide kmon b_0 f_0"><a href="./">热点</a></li>
<li class="swiper-slide f_b"><a href="forum.php?forumlist=1" class="f_b">社区</a></li>
<li class="swiper-slide f_b"><a href="forum-41-1.html" class="f_b">看新闻</a></li>
<li class="swiper-slide f_b"><a href="forum-39-1.html" class="f_b">要出售</a></li>
<li class="swiper-slide f_b"><a href="forum-40-1.html" class="f_b">找商品</a></li>
<li class="swiper-slide f_b"><a href="group.php?mod=index&mobile=2" class="f_b">部落</a></li>
<li class="swiper-slide f_b"><a href="forum-38-1.html" class="f_b">视频</a></li>
<li class="swiper-slide f_b"><a href="plugin.php?id=comiis_app_activity" class="f_b">社区活动</a></li></ul>
</div>
</div>
</div>
</div></div><script>
if($("#comiis_mh_sub8 li.f_0").length > 0) {
var comiis_index = $("#comiis_mh_sub8 li.f_0").offset().left + $("#comiis_mh_sub8 li.f_0").width() >= $(window).width() ? $("#comiis_mh_sub8 li.f_0").index() : 0;
}else{
var comiis_index = 0;
}
comiis_app_portal_swiper('#comiis_mh_sub8', {
freeMode : true,
slidesPerView : 'auto',
initialSlide : comiis_index,
onTouchMove: function(swiper){
Comiis_Touch_on = 0;
},
onTouchEnd: function(swiper){
Comiis_Touch_on = 1;
},
});
</script></div><div id="comiis_app_block_17" class="bg_f b_t b_b cl"><style>
.comiis_mhswfa {background:#000;overflow:hidden;position:relative;}
.comiis_mhswfa .swiper-slide span {position:absolute;left:0;bottom:36px;float:left;color:#fff;width:auto;padding:2px 10px;font-size:14px;max-height:60px;line-height:30px;background:rgba(0,0,0,0.7);overflow:hidden;}
.comiis_mhswfa_roll {position:absolute;left:0;bottom:0;margin-bottom:18px;height:18px;width:100%;text-align:center;color:#fff;z-index:9;overflow:hidden;}
.comiis_mhswfa_roll .swiper-pagination-bullet {display:inline-block;width:4px;height:4px;margin:0 2px;background-color:rgba(0, 0, 0, 0.35);border-radius:6px;}
.comiis_mhswfa_roll .swiper-pagination-bullet-active {background-color:#fff;width:10px;}
</style>
<div class="comiis_mhswfa comiis_mhswfa17">
<ul class="swiper-wrapper">
    <li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=17514" title="有没有养“六角恐龙”的？">
<img src="data/attachment/block/e4/e4dd986c882e7beb2b4dbeb475a9cbb8.jpg" width="100%" class="vm comiis_mhswfa_whb17" alt="有没有养“六角恐龙”的？">
<span>有没有养“六角恐龙”的？</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=17513" title="养鱼的20问，肯定有你需要的！">
<img src="data/attachment/block/41/4154ede6f7895e056979b89369e94231.jpg" width="100%" class="vm comiis_mhswfa_whb17" alt="养鱼的20问，肯定有你需要的！">
<span>养鱼的20问，肯定有你需要的！</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=17512" title="闯缸鱼，也许和你想的不一样！">
<img src="data/attachment/block/bf/bf0318aa452bcdbbd8c5c4d6ebfe7b9b.jpg" width="100%" class="vm comiis_mhswfa_whb17" alt="闯缸鱼，也许和你想的不一样！">
<span>闯缸鱼，也许和你想的不一样！</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=17508" title="读懂龙鱼的肢体语言，了解它内心的痛苦">
<img src="data/attachment/block/83/833b1790005f946a8cfc81789d5f3d36.jpg" width="100%" class="vm comiis_mhswfa_whb17" alt="读懂龙鱼的肢体语言，了解它内心的痛苦">
<span>读懂龙鱼的肢体语言，了解它内心的痛苦</span></a>
</li>
<li class="swiper-slide">
            <a href="forum.php?mod=viewthread&tid=17506" title="给懵懂的金龙鱼友普及一下知识，谨防受骗">
<img src="data/attachment/block/82/825df15d70b7c4d9281d5c054f116119.jpg" width="100%" class="vm comiis_mhswfa_whb17" alt="给懵懂的金龙鱼友普及一下知识，谨防受骗">
<span>给懵懂的金龙鱼友普及一下知识，谨防受骗</span></a>
</li>
</ul>
<div class="comiis_mhswfa_roll comiis_mhswfa_roll17"></div>
<div class="comiis_svg_box"><div class="comiis_svg_a"></div><div class="comiis_svg_b"></div></div>
</div>
<script>
  $('.comiis_mhswfa_whb17').css('height', ($('.comiis_mhswfa_whb17').width() * 0.5) + 'px');
comiis_app_portal_swiper('.comiis_mhswfa17', {
slidesPerView : 'auto',
        pagination: '.comiis_mhswfa_roll17',
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
</script></div><div id="comiis_app_block_11" class="bg_f b_b cl"><style>
.comiis_mh_gz02 {overflow:hidden;}
.comiis_mh_gz02 a {float:left;width:33.33%;line-height:20px;padding:10px 12px;font-size:13px;text-align:center;box-sizing:border-box;overflow:hidden;}
.comiis_mh_gz02 a h2 {height:24px;line-height:24px;font-size:18px;font-weight:400;margin-top:2px;overflow:hidden;}
</style>
<div class="comiis_mh_gz02 cl">
<a href="plugin.php?id=k_misign:sign" class="b_r"><h2 style="color:#ff9900">每日签到</h2><span class="f_c">赚金币赢大礼</span></a>
<a href="home.php?mod=space&do=doing&view=all" class="b_r"><h2 style="color:#20b4ff">心情墙</h2><span class="f_c">此时此刻想啥呢</span></a>
<a href="plugin.php?id=comiis_app_find"><h2 style="color:#FF5F45">发现 +</h2><span class="f_c">发现更多好玩的</span></a>
</div></div><div id="comiis_app_block_7" class="bg_f b_b cl"><style>
.comiis_mh_kxtxt {padding:10px 12px;height:22px;line-height:22px;overflow:hidden;}
.comiis_mh_kxtxt span.kxtit {float:left;height:18px;line-height:18px;padding:0 3px;margin-top:2px;margin-right:8px;overflow:hidden;border-radius:1.5px;}
.comiis_mh_kxtxt li, .comiis_mh_kxtxt li a {display:block;font-size:14px;height:22px;line-height:22px;overflow:hidden;}
</style>
<div class="comiis_mh_kxtxt cl">
  <span class="kxtit bg_del f_f">早知道</span>
<div id="comiis_mh_kxtxt7" style="height:22px;line-height:22px;overflow:hidden;">
<ul>
    <li><a href="forum.php?mod=viewthread&tid=17514" title="有没有养“六角恐龙”的？">有没有养“六角恐龙”的？</a></li>
    <li><a href="forum.php?mod=viewthread&tid=127" title="《紫蓝花水泡》金鱼详细介绍">《紫蓝花水泡》金鱼详细介绍</a></li>
    <li><a href="forum.php?mod=viewthread&tid=88" title="锦鲤品种之&lt;银松叶锦鲤&gt;">锦鲤品种之&lt;银松叶锦鲤&gt;</a></li>
    <li><a href="forum.php?mod=viewthread&tid=70" title="锦鲤品种之&lt;赤别甲锦鲤&gt;">锦鲤品种之&lt;赤别甲锦鲤&gt;</a></li>
    <li><a href="forum.php?mod=viewthread&tid=92" title="锦鲤品种之&lt;金昭和光写锦鲤&gt;">锦鲤品种之&lt;金昭和光写锦鲤&gt;</a></li>
    </ul>
</div>
</div>
<script>comiis_app_portal_loop(22, 30, 5000, 'comiis_mh_kxtxt7');</script></div><div id="comiis_app_block_21" class="bg_f cl"><style>
.comiis_mh_user02 {padding:0 6px;overflow:hidden;}
.comiis_mh_user02 li {float:left;width:auto;min-width:70px;margin:0 6px;padding:13px 4px 12px;text-align:center;overflow:hidden;}
.comiis_mh_user02 li a {display:block;}
.comiis_mh_user02 li .kmimg {margin:0 auto;width:58px;height:58px;padding:2px;border-radius:100%;background:#bbb;position:relative;}
.comiis_mh_user02 li .kmimg img {width:58px;height:58px;border-radius:100%;overflow:hidden;}
.comiis_mh_user02 li .kmimg span {position:absolute;left:50%;bottom:-5px;margin-left:-17px;display:inline-block;height:14px;line-height:14px;width:34px;text-align:center;background:#bbb;color:#fff;border-radius:2px;overflow:hidden;}
.comiis_mh_user02 li h2 {margin-top:12px;text-align:center;height:24px;line-height:24px;font-size:15px;font-weight:400;overflow:hidden;}
.comiis_mh_user02 li p.kmtxt {height:18px;line-height:18px;font-size:12px;overflow:hidden;}
.comiis_mh_user02 li .km01, .comiis_mh_user02 li .km01 span {background:#FF705E;}
.comiis_mh_user02 li .km02, .comiis_mh_user02 li .km02 span {background:#FFB900;}
.comiis_mh_user02 li .km03, .comiis_mh_user02 li .km03 span {background:#A8C500;}
</style>
<div id="comiis_mh_usergo21" class="comiis_mh_user02 cl">
<ul class="swiper-wrapper">            <li class="swiper-slide">					
<a href="home.php?mod=space&uid=1&do=profile" title="鱼乐圈">
                <div class="kmimg km01"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=1&size=middle" class="vm"><span>No.1</span></div>					
                <h2>鱼乐圈</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=1125&do=profile" title="顾忌太多">
                <div class="kmimg km02"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=1125&size=middle" class="vm"><span>No.2</span></div>					
                <h2>顾忌太多</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=41&do=profile" title="xiaoyu">
                <div class="kmimg km03"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=41&size=middle" class="vm"><span>No.3</span></div>					
                <h2>xiaoyu</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=16&do=profile" title="玩乐mz94">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=16&size=middle" class="vm"><span>No.4</span></div>					
                <h2>玩乐mz94</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=49&do=profile" title="激动32">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=49&size=middle" class="vm"><span>No.5</span></div>					
                <h2>激动32</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=12&do=profile" title="uu新天地53">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=12&size=middle" class="vm"><span>No.6</span></div>					
                <h2>uu新天地53</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=70&do=profile" title="兼职妹子44">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=70&size=middle" class="vm"><span>No.7</span></div>					
                <h2>兼职妹子44</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=206&do=profile" title="幼乐善馁jm">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=206&size=middle" class="vm"><span>No.8</span></div>					
                <h2>幼乐善馁jm</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=10&do=profile" title="宛如天堂03">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=10&size=middle" class="vm"><span>No.9</span></div>					
                <h2>宛如天堂03</h2>
</a>
</li>
        <li class="swiper-slide">					
<a href="home.php?mod=space&uid=205&do=profile" title="幼乐善馁fc">
                <div class="kmimg"><img src="http://www.ylqsz.cn/uc_server/avatar.php?uid=205&size=middle" class="vm"><span>No.10</span></div>					
                <h2>幼乐善馁fc</h2>
</a>
</li>
</ul>
</div>
<script>
comiis_app_portal_swiper('#comiis_mh_usergo21', {
freeMode : true,
freeModeMomentumRatio : 0.5,
slidesPerView : 'auto',
onTouchMove: function(swiper){
Comiis_Touch_on = 0;
},
onTouchEnd: function(swiper){
Comiis_Touch_on = 1;
},
});
</script></div><div id="comiis_app_block_15" class="bg_f mt10 b_t cl"><style>
.comiis_mh_gz03 {overflow:hidden;}
.comiis_mh_gz03 a {float:left;width:50%;height:76px;line-height:16px;padding:12px;box-sizing:border-box;overflow:hidden;}
.comiis_mh_gz03 a h2 {height:24px;line-height:24px;font-size:20px;font-weight:400;margin-top:2px;margin-bottom:6px;overflow:hidden;}
.comiis_mh_gz03 a img {float:right;width:52px;height:52px;border-radius:50%;}
</style>
<div class="comiis_mh_gz03 cl">
<a href="page-2.html" class="b_r b_b"><img src="chunjie/ico01.png" class="vm"><h2 style="color:#FF9900">社区新帖</h2><span class="f_c">汇聚每日最新贴</span></a>
<a href="plugin.php?id=comiis_app_activity" class="b_b"><img src="chunjie/ico02.png" class="vm"><h2 style="color:#87D140">福利活动</h2><span class="f_c">最多的优惠福利</span></a>
<a href="forum-41-1.html" class="b_r b_b"><img src="chunjie/ico03.png" class="vm"><h2 style="color:#20b4ff">新闻资讯</h2><span class="f_c">头条早知道</span></a>
<a href="page-3.html" class="b_b"><img src="chunjie/ico04.png" class="vm"><h2 style="color:#FF5F45">美图欣赏</h2><span class="f_c">社区最热门的图片</span></a>
</div></div><div id="comiis_app_block_16" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_ge5 {width:100%;padding:10px 6px;border-collapse:inherit;box-sizing:border-box;overflow:hidden;}
.comiis_mh_ge5 li {float:left;text-align:center;width:20%;box-sizing:border-box;}
.comiis_mh_ge5 li a {display:block;padding:5px;}
.comiis_mh_ge5 li img {width:46px;height:46px;margin-bottom:8px;border-radius:3px;}
.comiis_mh_ge5 li p {height:14px;line-height:14px;font-size:13px;}
</style>
<div class="comiis_mh_hotbk5 cl"><ul>
<li><a href="thread-1-1-1.html"><img src="logo/jsq1.png" class="vm"><p>鱼缸计算</p></a></li>
<li><a href="thread-2-1-1.html"><img src="logo/jsq2.png" class="vm"><p>药盐计算</p></a></li>
<li><a href="thread-3-1-1.html"><img src="logo/jsq3.png" class="vm"><p>玻璃厚度</p></a></li>
<li><a href="thread-4-1-1.html"><img src="logo/jsq4.png" class="vm"><p>玻璃裁切</p></a></li>
<li><a href="thread-5-1-1.html"><img src="logo/jsq5.png" class="vm"><p>电量补水</p></a></li>
</ul></div></div><div id="comiis_app_block_20" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit {overflow:hidden;position:relative;}
.comiis_mh_tit_more {position:absolute;right:12px;top:8px;width:16px;height:22px;z-index:50;overflow:hidden;}
.comiis_mh_tit .mh_tit_morea {display:block;position:absolute;right:5px;top:8px;width:40px;height:22px;z-index:60;text-indent:-999px;overflow:hidden;}
.comiis_mh_tit h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;font-weight:400;overflow:hidden;}
</style>
<div class="comiis_mh_tit cl">
    <i class="comiis_mhfont comiis_mh_tit_more f_d">&#xe601;</i>
    <a href="forum.php?mod=forumdisplay&fid=38" class="mh_tit_morea">更多</a><h2 class="pb10">最新音乐</h2></div></div><div id="comiis_app_block_19" class="bg_f b_t b_b cl"><style>
.comis_mhimg_music {padding:3px 12px 10px 0;overflow:hidden;}
.comis_mhimg_music li {float:left;width:calc(33.333% - 12px);margin-left:12px;margin-top:10px;}
.comis_mhimg_music li img {width:70%;}
.comis_mhimg_music .yymk_mkbg {background:url(./source/plugin/comiis_app_portal/image/mhimg_musicbg.png) no-repeat;background-position:right;background-size:60% auto;}
.comis_mhimg_music h2 {margin-top:6px;font-size:14px;height:18px;line-height:18px;overflow:hidden;}
</style>
<div class="comis_mhimg_music cl">
    <ul>
            <li>
            <a href="forum.php?mod=viewthread&tid=17514" title="有没有养“六角恐龙”的？">
            <div class="yymk_mkbg cl"><img src="data/attachment/block/f9/f9961d6fd19796338d17c865647c3b4c.jpg" alt="有没有养“六角恐龙”的？" class="bg_f b_ok vm comiis_img_whb19" alt="有没有养“六角恐龙”的？" /></div>
            <h2>有没有养“六角恐</h2>
            </a>
        </li>
            <li>
            <a href="forum.php?mod=viewthread&tid=17513" title="养鱼的20问，肯定有你需要的！">
            <div class="yymk_mkbg cl"><img src="data/attachment/block/18/188792f03939281e74813e96087b4342.jpg" alt="养鱼的20问，肯定有你需要的！" class="bg_f b_ok vm comiis_img_whb19" alt="养鱼的20问，肯定有你需要的！" /></div>
            <h2>养鱼的20问，肯定</h2>
            </a>
        </li>
            <li>
            <a href="forum.php?mod=viewthread&tid=17512" title="闯缸鱼，也许和你想的不一样！">
            <div class="yymk_mkbg cl"><img src="data/attachment/block/63/6351fb3e30608bc7b8cafcc5975a243f.jpg" alt="闯缸鱼，也许和你想的不一样！" class="bg_f b_ok vm comiis_img_whb19" alt="闯缸鱼，也许和你想的不一样！" /></div>
            <h2>闯缸鱼，也许和你</h2>
            </a>
        </li>
            <li>
            <a href="forum.php?mod=viewthread&tid=17508" title="读懂龙鱼的肢体语言，了解它内心的痛苦">
            <div class="yymk_mkbg cl"><img src="data/attachment/block/cc/ccc7f119bfa376e0025971eee349c0b5.jpg" alt="读懂龙鱼的肢体语言，了解它内心的痛苦" class="bg_f b_ok vm comiis_img_whb19" alt="读懂龙鱼的肢体语言，了解它内心的痛苦" /></div>
            <h2>读懂龙鱼的肢体语</h2>
            </a>
        </li>
            <li>
            <a href="forum.php?mod=viewthread&tid=17506" title="给懵懂的金龙鱼友普及一下知识，谨防受骗">
            <div class="yymk_mkbg cl"><img src="data/attachment/block/c2/c209ba53721c8b0dcbd8b85530a86565.jpg" alt="给懵懂的金龙鱼友普及一下知识，谨防受骗" class="bg_f b_ok vm comiis_img_whb19" alt="给懵懂的金龙鱼友普及一下知识，谨防受骗" /></div>
            <h2>给懵懂的金龙鱼友</h2>
            </a>
        </li>
            <li>
            <a href="forum.php?mod=viewthread&tid=131" title="你与我的旅程 将从这里开始!!!!!!!">
            <div class="yymk_mkbg cl"><img src="data/attachment/block/f1/f1fef1e6be5596ba347dcf9ccdeee6db.jpg" alt="你与我的旅程 将从这里开始!!!!!!!" class="bg_f b_ok vm comiis_img_whb19" alt="你与我的旅程 将从这里开始!!!!!!!" /></div>
            <h2>你与我的旅程 将</h2>
            </a>
        </li>
        </ul>
</div>
<script>$('.comiis_img_whb19').css('height', ($('.comiis_img_whb19').width() * 1) + 'px');</script></div><div id="comiis_app_block_6" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit {overflow:hidden;position:relative;}
.comiis_mh_tit_more {position:absolute;right:12px;top:8px;width:16px;height:22px;z-index:50;overflow:hidden;}
.comiis_mh_tit .mh_tit_morea {display:block;position:absolute;right:5px;top:8px;width:40px;height:22px;z-index:60;text-indent:-999px;overflow:hidden;}
.comiis_mh_tit h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;font-weight:400;overflow:hidden;}
</style>
<div class="comiis_mh_tit cl">
    <i class="comiis_mhfont comiis_mh_tit_more f_d">&#xe601;</i>
    <a href="forum.php?mod=forumdisplay&fid=41" class="mh_tit_morea">更多</a><h2 class="pb10">新闻资讯</h2></div></div><div id="comiis_app_block_5" class="bg_f mb10 b_b cl"><style>
.comiis_mh_twlist {overflow:hidden;}
.comiis_mh_twlist ul {margin:0 12px;overflow:hidden;}
.comiis_mh_twlist .twlist_li:first-child,.comiis_mh_twlist .twlist_li:first-child a {border-top:none !important;}
.comiis_mh_twlist .twlist_li a {display:block;width:100%;padding:12px 0;overflow:hidden;}
.comiis_mh_twlist .twlist_li a.twlist_noimg {padding:8px 0;}
.comiis_mh_twlist .twlist_img {float:left;width:30%;height:85px;overflow:hidden;margin-right:8px;}
.comiis_mh_twlist .twlist_img img {width:100%;}
.comiis_mh_twlist .twlist_info {height:85px;overflow:hidden;}
.comiis_mh_twlist .twlist_info strong {font-weight:400;}
.comiis_mh_twlist .twlist_info p,.comiis_mh_twlist .twlist_info span {display:block;overflow:hidden;}
.comiis_mh_twlist .twlist_info p {height:52px;line-height:26px;font-size:17px;}
.comiis_mh_twlist .twlist_info span {height:20px;line-height:20px;margin-top:14px;font-size:12px;position:relative;}
.comiis_mh_twlist .twlist_info span em {float:right;text-align:right;display:table-cell;vertical-align:bottom;}
.comiis_mh_twlist .twlist_info span i {float:right;margin-top:1px;margin-left:4px;height:14px;line-height:14px;font-size:12px;border-radius:2px;padding:0 2px;overflow:hidden;}
</style>
<div class="comiis_mh_twlist cl">
<ul><li class="twlist_li b_t">
<a href="forum.php?mod=viewthread&tid=29" title="男子将水族小动物闷死，做成钥匙链销售！">
<div class="twlist_img bg_e"><img src="data/attachment/block/5a/5ac6d88b8b26b8c5ba6ce9065d4dd8b7.jpg" width="" height="" alt="男子将水族小动物闷死，做成钥匙链销售！"></div>
<div class="twlist_info">
<p>男子将水族小动物闷死，做成钥匙链销售！</p>
<span class="f_d"><em><i class="b_ok b_i f_g">热</i>844阅读</em>2018-03-14</span>
</div>
</a>
</li>
<li class="twlist_li b_t">
<a href="forum.php?mod=viewthread&tid=28" title="我国水族馆数量规模世界第一,官方称技术等要跟上!">
<div class="twlist_img bg_e"><img src="data/attachment/block/a2/a2fd8cc3e5faa692f703dccdead157ec.jpg" width="" height="" alt="我国水族馆数量规模世界第一,官方称技术等要跟上!"></div>
<div class="twlist_info">
<p>我国水族馆数量规模世界第一,官方称技术等</p>
<span class="f_d"><em>365阅读</em>2018-03-11</span>
</div>
</a>
</li>
<li class="twlist_li b_t">
<a href="forum.php?mod=viewthread&tid=27" title="韩水族馆潜水员变美人鱼水下起舞">
<div class="twlist_img bg_e"><img src="data/attachment/block/db/dbe9846a26516f819af8b16d98cc132d.jpg" width="" height="" alt="韩水族馆潜水员变美人鱼水下起舞"></div>
<div class="twlist_info">
<p>韩水族馆潜水员变美人鱼水下起舞</p>
<span class="f_d"><em>230阅读</em>2018-03-11</span>
</div>
</a>
</li>
<li class="twlist_li b_t">
<a href="forum.php?mod=viewthread&tid=26" title="新加坡水族馆表演水下舞龙贺新春">
<div class="twlist_img bg_e"><img src="data/attachment/block/5e/5e3c290af7a1903ff301c1267feed55c.jpg" width="" height="" alt="新加坡水族馆表演水下舞龙贺新春"></div>
<div class="twlist_info">
<p>新加坡水族馆表演水下舞龙贺新春</p>
<span class="f_d"><em>184阅读</em>2018-03-11</span>
</div>
</a>
</li>
</ul>
</div></div><div id="comiis_app_block_22" class="bg_f mt10 b_t b_b cl"><style>
.comiis_showimg08 {padding:7px;overflow:hidden;}
.comiis_showimg08 a {float:left;margin:5px;width:calc(33% - 10px);height:100px;overflow:hidden;}
.comiis_showimg08 a:nth-child(3n+1) {width:calc(34% - 10px);}
.comiis_showimg08 a img {object-fit:cover;width:100%;height:100%;vertical-align:middle;border-radius:4px;}
</style>
<div class="comiis_showimg08 cl"><a href="#"><img src="source/plugin/comiis_app_portal/comiis/showimg08/img/001.jpg" class="vm"></a><a href="#"><img src="source/plugin/comiis_app_portal/comiis/showimg08/img/002.jpg" class="vm"></a><a href="#"><img src="source/plugin/comiis_app_portal/comiis/showimg08/img/003.jpg" class="vm"></a></div></div><div id="comiis_app_block_3" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit {overflow:hidden;position:relative;}
.comiis_mh_tit_more {position:absolute;right:12px;top:8px;width:16px;height:22px;z-index:50;overflow:hidden;}
.comiis_mh_tit .mh_tit_morea {display:block;position:absolute;right:5px;top:8px;width:40px;height:22px;z-index:60;text-indent:-999px;overflow:hidden;}
.comiis_mh_tit h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;font-weight:400;overflow:hidden;}
</style>
<div class="comiis_mh_tit cl">
    <i class="comiis_mhfont comiis_mh_tit_more f_d">&#xe601;</i>
    <a href="page-3.html" class="mh_tit_morea">更多</a><h2 class="pb10">图新鲜</h2></div></div><div id="comiis_app_block_2" class="bg_f b_b cl"><style>
.comiis_mh_img {padding:6px;overflow:hidden;}
.comiis_mh_img li {float:left;width:50%;padding:6px;box-sizing:border-box;}
.comiis_mh_img li a {display:block;width:100%;overflow:hidden;position:relative;}
.comiis_mh_img li img {width:100%;}
.comiis_mh_img li .album_tit {display:block;width:100%;position:absolute;left:0;bottom:0;background:rgba(0,0,0,0.3);font-size:14px;text-align:center;color:#fff;padding-top:1px;height:26px;line-height:26px;overflow:hidden;}
.comiis_mh_img li .album_tit em {display:block;text-align:center;padding:0 5px;}
</style>
<div class="comiis_mh_img cl">
<ul>
    <li><a href="forum.php?mod=viewthread&tid=17514" title="有没有养“六角恐龙”的？"><img src="data/attachment/block/9c/9cefba12a313c86c0500541fc2938ad3.jpg" alt="有没有养“六角恐龙”的？" class="vm comiis_img_whb2"><span class="album_tit"><em>有没有养“六角恐龙”的？</em></span></a></li>
    <li><a href="forum.php?mod=viewthread&tid=17513" title="养鱼的20问，肯定有你需要的！"><img src="data/attachment/block/c4/c40bc6da78fc4c186e024809e94cdd26.jpg" alt="养鱼的20问，肯定有你需要的！" class="vm comiis_img_whb2"><span class="album_tit"><em>养鱼的20问，肯定有你需要的！</em></span></a></li>
    <li><a href="forum.php?mod=viewthread&tid=17512" title="闯缸鱼，也许和你想的不一样！"><img src="data/attachment/block/24/24c1d357e0dc21b3896f29ecbdeb5027.jpg" alt="闯缸鱼，也许和你想的不一样！" class="vm comiis_img_whb2"><span class="album_tit"><em>闯缸鱼，也许和你想的不一样！</em></span></a></li>
    <li><a href="forum.php?mod=viewthread&tid=17508" title="读懂龙鱼的肢体语言，了解它内心的痛苦"><img src="data/attachment/block/91/9167908ca7b78869da53f7687e990ddd.jpg" alt="读懂龙鱼的肢体语言，了解它内心的痛苦" class="vm comiis_img_whb2"><span class="album_tit"><em>读懂龙鱼的肢体语言，了解它内心的痛苦</em></span></a></li>
    </ul>
</div>
<script>$('.comiis_img_whb2').css('height', ($('.comiis_img_whb2').width() * 0.75) + 'px');</script></div><div id="comiis_app_block_4" class="bg_f mt10 b_t b_b cl"><style>
.comiis_guanggao_tit {overflow:hidden;}
.comiis_guanggao_tit a {display:block;margin:7px 12px 12px;overflow:hidden;}
.comiis_guanggao_tit h2 {font-size:16px;line-height:28px;font-weight:400;}
.comiis_guanggao_tit h2 span {float:left;height:18px;line-height:18px;padding:0 4px;font-size:12px;margin-top:5px;margin-right:5px;overflow:hidden;border-radius:1.5px;}
.comiis_guanggao_tit img {margin-top:5px;width:100%;height:auto;vertical-align:middle;border-radius:4px;}
</style>
<div class="comiis_guanggao_tit cl"><a href="#"><h2><span class="bg_del f_f">推广</span>这里是自定义广告推广文字</h2><img src="source/plugin/comiis_app_portal/image/temp01.jpg"></a></div></div><div id="comiis_app_block_1" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit {overflow:hidden;position:relative;}
.comiis_mh_tit_more {position:absolute;right:12px;top:8px;width:16px;height:22px;z-index:50;overflow:hidden;}
.comiis_mh_tit .mh_tit_morea {display:block;position:absolute;right:5px;top:8px;width:40px;height:22px;z-index:60;text-indent:-999px;overflow:hidden;}
.comiis_mh_tit h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;font-weight:400;overflow:hidden;}
</style>
<div class="comiis_mh_tit cl">
    <i class="comiis_mhfont comiis_mh_tit_more f_d">&#xe601;</i>
    <a href="forum.php?forumlist=1" class="mh_tit_morea">更多</a><h2 class="pb10">热帖排行</h2></div></div><div id="comiis_app_block_13" class="bg_f b_b cl"><style>
.comiis_mh_txtlist_phb li {margin:0 12px;height:40px;line-height:40px;font-size:15px;overflow:hidden;}
.comiis_mh_txtlist_phb li:first-child {border-top:none !important;}
.comiis_mh_txtlist_phb li a {display:block;}
.comiis_mh_txtlist_phb li i {font-size:12px;margin-right:4px;}
.comiis_mh_txtlist_phb li span {font-size:13px;padding-left:8px;}
.comiis_mh_txtlist_phb li em {float:left;margin-top:11px;margin-right:8px;font-size:12px;width:18px;height:18px;line-height:18px;text-align:center;border-radius:0 4px 4px 4px;}
.comiis_mh_txtlist_phb li em.ibg01 {background:#FF705E;}
.comiis_mh_txtlist_phb li em.ibg02 {background:#FFB900;}
.comiis_mh_txtlist_phb li em.ibg03 {background:#A8C500;}
</style>
<div class="comiis_mh_txtlist_phb cl">
<ul>
            <li class="b_t"><a href="forum.php?mod=viewthread&tid=11" title="鱼乐圈水族总版规"><em class="ibg01 f_f">1</em>鱼乐圈水族总版规</a></li>
    <li class="b_t"><a href="forum.php?mod=viewthread&tid=1" title="鱼缸计算器，容量，照明，过滤，加温，饲养计算器"><em class="ibg02 f_f">2</em>鱼缸计算器，容量，照明，过滤，加温，饲养计算器</a></li>
    <li class="b_t"><a href="forum.php?mod=viewthread&tid=2" title="鱼缸盐度|鱼药用量计算器"><em class="ibg03 f_f">3</em>鱼缸盐度|鱼药用量计算器</a></li>
    <li class="b_t"><a href="forum.php?mod=viewthread&tid=29" title="男子将水族小动物闷死，做成钥匙链销售！"><em class="bg_hs f_f">4</em>男子将水族小动物闷死，做成钥匙链销售！</a></li>
    <li class="b_t"><a href="forum.php?mod=viewthread&tid=4" title="鱼缸玻璃裁切尺寸计算器"><em class="bg_hs f_f">5</em>鱼缸玻璃裁切尺寸计算器</a></li>
    <li class="b_t"><a href="forum.php?mod=viewthread&tid=8" title="鱼缸背过滤(背滤)的计算方法"><em class="bg_hs f_f">6</em>鱼缸背过滤(背滤)的计算方法</a></li>
</ul>
</div></div><div id="comiis_app_block_12" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit02 {overflow:hidden;position:relative;}
.comiis_mh_tit02 h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;font-weight:400;overflow:hidden;}
.comiis_mh_tit02 h2 span {font-size:12px;}
.comiis_mh_tit02 h2 span.mh_tit {font-size:14px;}
.comiis_mh_tit02 h2 span.mh_tit span {font-size:14px;padding:0 5px;}
</style>
<div class="comiis_mh_tit02 cl">
<h2 class="pb10"><span class="mh_tit y"><a href="home.php?mod=space&do=album&view=all"  style="color:#FF705E">+ 更多</a></span>网友相册</h2></div></div><div id="comiis_app_block_14" class="bg_f b_b cl"><style>
.comiis_mhalbum_list {padding:7px 6px 6px;overflow:hidden;}
.comiis_mhalbum_list li {float:left;width:33.33%;padding:6px;box-sizing:border-box;}
.comiis_mhalbum_list li a {display:block;width:100%;overflow:hidden;position:relative;border-radius:2px;}
.comiis_mhalbum_list li img {width:100%;}
.comiis_mhalbum_list li .mhalbum_tit {display:block;width:100%;position:absolute;left:0;bottom:0;background:rgba(0,0,0,0.4);text-align:center;color:#fff;height:24px;line-height:24px;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.comiis_mhalbum_list li .mhalbum_tit strong {font-weight:400;}
.comiis_mhalbum_list li .mhalbum_num {position:absolute;top:5px;right:5px;background:rgba(0,0,0,0.3);height:16px;line-height:16px;padding:0 5px;font-size:12px;border-radius:12px;}
.comiis_mhalbum_list li .mhalbum_num i {float:left;margin-right:3px;font-size:14px;}
</style>
<div class="comiis_mhalbum_list cl">
<ul>
        
<li>
          <a href="home.php?mod=space&uid=1&do=album&id=3">
            <img src="data/attachment/album/cover/ec/3.jpg" class="vm">
            <span class="mhalbum_tit">原生鱼类</span>
            <span class="mhalbum_num f_f"><i class="comiis_mhfont">&#xe627;</i>8</span>
          </a>
</li>
    
<li>
          <a href="home.php?mod=space&uid=1&do=album&id=2">
            <img src="data/attachment/album/cover/c8/2.jpg" class="vm">
            <span class="mhalbum_tit">金鱼图片</span>
            <span class="mhalbum_num f_f"><i class="comiis_mhfont">&#xe627;</i>3</span>
          </a>
</li>
    
<li>
          <a href="home.php?mod=space&uid=1&do=album&id=1">
            <img src="data/attachment/album/cover/c4/1.jpg" class="vm">
            <span class="mhalbum_tit">锦鲤图片</span>
            <span class="mhalbum_num f_f"><i class="comiis_mhfont">&#xe627;</i>4</span>
          </a>
</li>
</ul>
</div></div><div id="comiis_app_block_9" class="bg_f mt10 b_t b_b cl"><style>
.comiis_mh_tit {overflow:hidden;position:relative;}
.comiis_mh_tit_more {position:absolute;right:12px;top:8px;width:16px;height:22px;z-index:50;overflow:hidden;}
.comiis_mh_tit .mh_tit_morea {display:block;position:absolute;right:5px;top:8px;width:40px;height:22px;z-index:60;text-indent:-999px;overflow:hidden;}
.comiis_mh_tit h2 {height:18px;line-height:18px;margin:0 12px;padding-top:12px;font-size:16px;font-weight:400;overflow:hidden;}
</style>
<div class="comiis_mh_tit cl">
    <i class="comiis_mhfont comiis_mh_tit_more f_d">&#xe601;</i>
    <a href="forum.php?forumlist=1" class="mh_tit_morea">更多</a><h2 class="pb10">热版推荐</h2></div></div><div id="comiis_app_block_10" class="bg_f b_b cl"><style>
.comiis_mh_hotbk {width:100%;padding:5px 0;border-collapse:inherit;overflow:hidden;}
.comiis_mh_hotbk li {float:left;text-align:center;width:25%;box-sizing:border-box;}
.comiis_mh_hotbk li a {display:block;padding:10px;}
.comiis_mh_hotbk li img {width:46px;height:46px;margin-bottom:8px;border-radius:3px;}
.comiis_mh_hotbk li p {height:14px;line-height:14px;font-size:13px;}
</style>
<div class="comiis_mh_hotbk cl">
<ul>
    <li><a href="forum.php?mod=forumdisplay&fid=64"><img src="http://www.ylqsz.cn/data/attachment/common/logo/28.png" class="vm"><p>小型鱼综合</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=54"><img src="http://www.ylqsz.cn/data/attachment/common/logo/18.png" class="vm"><p>罗汉专区</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=40"><img src="http://www.ylqsz.cn/data/attachment/common/logo/06.png" class="vm"><p>商品活动</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=53"><img src="http://www.ylqsz.cn/data/attachment/common/logo/17.png" class="vm"><p>魟鱼专区</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=39"><img src="http://www.ylqsz.cn/data/attachment/common/logo/05.png" class="vm"><p>鱼友转手</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=79"><img src="http://www.ylqsz.cn/data/attachment/common/logo/40.png" class="vm"><p>宣传推广</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=74"><img src="http://www.ylqsz.cn/data/attachment/common/logo/36.png" class="vm"><p>海缸欣赏</p></a></li>
<li><a href="forum.php?mod=forumdisplay&fid=69"><img src="http://www.ylqsz.cn/data/attachment/common/logo/32.png" class="vm"><p>布景欣赏</p></a></li>
</ul>
</div></div>