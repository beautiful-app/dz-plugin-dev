<?php echo '来自---人呐！干哈呀！的制作';exit;?>
<div class="comiis_magictip f_c cl bg_h">
	<!--{if $_G['group']['maxmagicsweight']}-->
		{lang magics_capacity}: <span class="f_a">$totalweight</span>/{$_G['group']['maxmagicsweight']}
	<!--{/if}-->
	<!--{if $magiccredits}-->
			<!--{if $_G['group']['magicsdiscount']}-->&nbsp&nbsp{lang magics_discount}<!--{/if}-->
			<p>
			{lang you_have_now}
			<!--{eval $i = 0;}-->
			<!--{loop $magiccredits $id}-->
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
<!--{if $magiclist}-->
	<ul class="mtm mgcl cl">
	<!--{loop $magiclist $key $magic}-->
		<li class="bg_e">
			<div id="magic_$magic[identifier]_menu" class="tip tip_4" style="display:none">
				<div class="tip_horn"></div>
				<div class="tip_c" style="text-align:left">$magic[description]</div>
			</div>
			<div id="magic_$magic[identifier]" class="mg_img" onmouseover="showMenu({'ctrlid':this.id, 'menuid':'magic_$magic[identifier]_menu', 'pos':'12!'});">
				<img src="$magic[pic]" alt="$magic[name]" />
			</div>
			<p>$magic[name]</p>
			<p>
				<!--{if {$_G['setting']['extcredits'][$magic[credit]][unit]}}-->
					{$_G['setting']['extcredits'][$magic[credit]][title]} <strong class="xi1 xw1 xs2">$magic[price]</strong> {$_G['setting']['extcredits'][$magic[credit]][unit]}/{lang magics_unit}
				<!--{else}-->
					<strong class="xi1 xw1 xs2">$magic[price]</strong> {$_G['setting']['extcredits'][$magic[credit]][title]}/{lang magics_unit}
				<!--{/if}-->
				<!--{if $operation == 'hot'}--><em class="xg1">({lang sold} $magic[salevolume] {lang magics_unit})</em><!--{/if}-->
			</p>

			<p class="mtn bg_c f_f ">
				<!--{if $magic['num'] > 0}-->
					<a href="home.php?mod=magic&action=shop&operation=buy&mid=$magic[identifier]" onclick="showWindow('magics', this.href);return false;" class="dialog">{lang magics_operation_buy}</a>
					<!--{if $_G['group']['allowmagics'] > 1}-->
					<span class="f_f">&nbsp;/&nbsp;</span>
					<a href="home.php?mod=magic&action=shop&operation=give&mid=$magic[identifier]" onclick="showWindow('magics', this.href);return false;" class="dialog">{lang magics_operation_present}</a>
					<!--{/if}-->
				<!--{else}-->
					<span class="xg1">{lang magic_empty}</span>
				<!--{/if}-->
			</p>
		</li>
	<!--{/loop}-->
	</ul>
	<!--{if $multipage}--><div class="comiis_pgs cl">$multipage</div><!--{/if}-->
<!--{else}-->
	<div class="comiis_notip comiis_sofa mt15 cl">
        <i class="comiis_font f_e cl">&#xe613;</i>
        <span class="f_d">{lang data_nonexistence}</span>
    </div>
<!--{/if}-->