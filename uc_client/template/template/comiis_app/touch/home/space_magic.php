<?php echo '来自---人呐！干哈呀！的制作';exit;?>
<!--{template common/header}-->
<!--{if $comiis_app_switch['comiis_subnv_top'] != 1}--><div style="height:41px;"><div class="comiis_scrollTop_box"><!--{/if}-->
<style>
.comiis_magicbox li p.medal_btn {
    margin: 5px auto 0px;
}
.comiis_magicbox li p em, .comiis_magicbox li p a.f_f {
    display: inline-block;
    width: 100%;
    border-radius: 0px;
}
.comiis_magictip .xi1 {
    color: red;
}
.comiis_magictip strong {
    font-weight: normal;
}
.comiis_magictop {
    text-align: center;
    border-radius: 3px 3px 0 0;
    overflow: hidden;
    position: relative;
}
.comiis_magictop a.ratetop_close {
    position: absolute;
    top: 0px;
    right: 0px;
    padding: 10px 14px;
}
.comiis_magictop .top {
	margin: 15px 10px 0px;
}
.comiis_magictop .img {
    float: left;
	width: 76px;
    height: 76px;	
}
.comiis_magictop .name {
    width: 174px;
	padding-left: 76px;
}
.comiis_tip .cont {
	margin: 15px 20px 0px;
}
.comiis_tip .cont .ft16 {
	font-size: 16px;;
}
.comiis_tip .cont .ft13 {
	color: #999999;
	font-size: 13px;;
}
.comiis_tip .border {
	border-bottom: 1px dashed #CDCDCD;
    margin: 10px 0px 5px;
    width: 100%;
}
.comiis_input_style input {
    display: inline;
    border-radius: 0;
}
.comiis_tip .cont th {
    padding-top: 9px;
    padding-right: 5px;
    width: 65px;
}
.comiis_tip .cont td {
    vertical-align: top;
    padding: 7px 0;
}
</style>
<div class="comiis_topnv bg_f b_b">
    <ul class="comiis_flex">
		<!--{if empty($_GET[action]) || $_GET[action] == 'mybox' || $_GET[action] == 'log'}-->
			<li class="flex{if empty($_GET[action])} f_0{/if}">{if empty($_GET[action])}<em class="bg_0"></em>{/if}<a href="home.php?mod=magic"{if !empty($_GET[action])} class="f_c"{/if}>{lang magics_shop}</a></li>
		<!--{elseif $action == 'shop' || $_GET[action] == 'mybox' || $_GET[action] == 'log'}-->
			<li class="flex{if $_GET[action] == 'shop'} f_0{/if}">{if $_GET[action] == 'shop'}<em class="bg_0"></em>{/if}<a href="home.php?mod=magic&action=shop""{if $_GET[action] != 'shop'} class="f_c"{/if}>{lang magics_shop}</a></li>
		<!--{/if}-->
        <li class="flex{if $_GET[action] == 'mybox'} f_0{/if}">{if $_GET[action] == 'mybox'}<em class="bg_0"></em>{/if}<a href="home.php?mod=magic&action=mybox""{if $_GET[action] != 'mybox'} class="f_c"{/if}>{lang magics_user}</a></li>
		<li class="flex{if $_GET[action] == 'log'} f_0{/if}">{if $_GET[action] == 'log'}<em class="bg_0"></em>{/if}<a href="home.php?mod=magic&action=log&operation=uselog""{if $_GET[action] != 'log'} class="f_c"{/if}>{lang magics_log}</a></li>

	</ul>
</div>
<!--{if $comiis_app_switch['comiis_subnv_top'] != 1}--></div></div><!--{/if}-->
<div class="styli_h10 bg_e b_b"></div>

<div class="comiis_magicbox bg_f cl">
	<!--{if $action == 'shop'}-->
		<!--{subtemplate home/space_magic_shop}-->
	<!--{elseif $action == 'mybox'}-->
		<!--{subtemplate home/space_magic_mybox}-->
	<!--{elseif $action == 'log'}-->
		<!--{subtemplate home/space_magic_log}-->
	<!--{/if}-->
</div>

<!--{eval $comiis_foot = 'yes';}-->
<!--{template common/footer}-->