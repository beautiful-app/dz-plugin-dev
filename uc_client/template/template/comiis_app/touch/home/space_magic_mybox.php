<?php echo '来自---人呐！干哈呀！的制作';exit;?>
<div class="comiis_magictip f_c cl bg_h">
	<!--{if $_G['group']['maxmagicsweight']}-->
		{lang magics_capacity}: <span class="f_a">$totalweight</span>/{$_G['group']['maxmagicsweight']}
	<!--{/if}-->
	<!--{if $mymagiccredits}-->
			<!--{if $_G['group']['magicsdiscount']}-->&nbsp&nbsp{lang magics_discount}<!--{/if}-->
			<p>
			{lang you_have_now}
			<!--{eval $i = 0;}-->
			<!--{loop $mymagiccredits $id}-->
				<!--{if $i != 0}-->, <!--{/if}-->{$_G['setting']['extcredits'][$id][img]} {$_G['setting']['extcredits'][$id][title]} <span class="f_a"><!--{echo getuserprofile('extcredits'.$id);}--></span> {$_G['setting']['extcredits'][$id][unit]}
				<!--{eval $i++;}-->
			<!--{/loop}-->
			</p>
			<!--{if ($_G['setting']['ec_ratio'] && ($_G['setting']['ec_tenpay_opentrans_chnid'] || $_G['setting'][ec_tenpay_bargainor] || $_G['setting']['ec_account'])) || $_G['setting']['card']['open']}-->
				<span class="pipe">|</span><a href="home.php?mod=spacecp&ac=credit&op=buy">{lang buy_credits}</a>
			<!--{/if}-->
			<!--{if $_G[setting][exchangestatus]}-->
				<span class="pipe">|</span><a href="home.php?mod=spacecp&ac=credit&op=exchange">{lang credit_exchange}</a>
			<!--{/if}-->
	<!--{/if}-->
</div>

<!--{if $mymagiclist}-->
	<ul class="mtm mgcl cl">
	<!--{loop $mymagiclist $key $mymagic}-->
		<li class="bg_e">
			<div id="magic_$mymagic[identifier]_menu" class="tip tip_4" style="display:none">
				<div class="tip_horn"></div>
				<div class="tip_c" style="text-align:left">$mymagic[description]</div>
			</div>
			<div id="magic_$mymagic[identifier]" class="mg_img" onmouseover="showMenu({'ctrlid':this.id, 'menuid':'magic_$mymagic[identifier]_menu', 'pos':'12!'});">
				<img src="$mymagic[pic]" alt="$mymagic[name]" />
			</div>
			<p>$mymagic[name]</p>
			<p>
				{lang magics_num}: <font class="xi1 xw1">$mymagic[num]</font>
			</p>
			<p class="mtn bg_c f_f ">
				<!--{if $mymagic['useevent']}-->
					<a href="home.php?mod=magic&action=mybox&operation=use&magicid=$mymagic[magicid]" onclick="showWindow('magics', this.href, 'get', 0);return false;" class="dialog">{lang magics_operation_use}</a>
					<span class="f_f">/</span>
				<!--{/if}-->
				<!--{if $_G['group']['allowmagics'] > 1}-->					
					<a href="home.php?mod=magic&action=mybox&operation=give&magicid=$mymagic[magicid]" onclick="showWindow('magics', this.href, 'get', 0);return false;" class="dialog">{lang magics_operation_present}</a>
				<!--{/if}-->
				<!--{if $_G['setting']['magicdiscount']}-->
					<span class="f_f">/</span>
					<a href="home.php?mod=magic&action=mybox&operation=sell&magicid=$mymagic[magicid]" onclick="showWindow('magics', this.href, 'get', 0);return false;" class="dialog">{lang magics_operation_sell}</a>
				<!--{else}-->
					<span class="f_f">/</span>
					<a href="home.php?mod=magic&action=mybox&operation=drop&magicid=$mymagic[magicid]" onclick="showWindow('magics', this.href, 'get', 0);return false;" class="dialog">{lang magics_operation_drop}</a>
				<!--{/if}-->
			</p>			
		</li>
	<!--{/loop}-->
	</ul>
	<!--{if $multipage}--><div class="pgs cl mtm">$multipage</div><!--{/if}-->
<!--{else}-->
	<p class="emp">{lang data_nonexistence}</p>
<!--{/if}-->
