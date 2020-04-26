<?php echo '来自---人呐！干哈呀！的制作';exit;?>
<div class="comiis_scrollTop_box">
<div class="bg_f b_b">
     <ul class="comiis_flex">
	<li class="flex" $subactives[uselog]><a href="home.php?mod=magic&action=log&operation=uselog">{lang magics_log_use}</a></li>
	<li class="flex" $subactives[buylog]><a href="home.php?mod=magic&action=log&operation=buylog">{lang magics_log_buy}</a></li>
	<li class="flex" $subactives[givelog]><a href="home.php?mod=magic&action=log&operation=givelog">{lang magics_log_present}</a></li>
	<li class="flex" $subactives[receivelog]><a href="home.php?mod=magic&action=log&operation=receivelog">{lang magics_log_receive}</a></li>
</ul></div></div>
<!--{if $operation == 'uselog'}-->
	<!--{if $loglist}-->
		<table summary="{lang magics_log_use}" cellspacing="0" cellpadding="0" class="b_b" width="100%">
			<tr>
				<th class="b_b">{lang magics_name}</th>
				<th class="b_b"width="20%">{lang magics_dateline_use}</th>
				<th class="b_b"width="20%">{lang magics_target_use}</th>
			</tr>
			<!--{loop $loglist $log}-->
			<tr>
				<td class="b_b">$log[name]</td>
				<td class="b_b">$log[dateline]</td>
				<td>
					<!--{if $log[idtype] == 'uid'}-->
						<a href="home.php?mod=space&uid=$log[targetid]" target="_blank">{lang magics_target}</a>
					<!--{elseif $log[idtype] == 'tid'}-->
						<a href="forum.php?mod=viewthread&tid=$log[targetid]" target="_blank">{lang magics_target}</a>
					<!--{elseif $log[idtype] == 'pid'}-->
						<a href="forum.php?mod=redirect&pid=$log[targetid]&goto=findpost" target="_blank">{lang magics_target}</a>
					<!--{elseif $log[idtype] == 'sell'}-->
						{lang magics_operation_sell}
					<!--{elseif $log[idtype] == 'drop'}-->
						{lang magics_operation_drop}
					<!--{/if}-->
				</td>
			</tr>
			<!--{/loop}-->
		</table>
		<!--{if $multipage}--><div class="pgs cl mtm">$multipage</div><!--{/if}-->
	<!--{else}-->
		<p class="emp">{lang data_nonexistence}</p>
	<!--{/if}-->
<!--{elseif $operation == 'buylog'}-->
	<!--{if $loglist}-->
		<table summary="{lang magics_log_buy}" cellspacing="0" cellpadding="0" class="b_b" width="100%">
			<tr>
				<th class="b_b">{lang magics_name}</th>
				<th class="b_b" width="20%">{lang magics_dateline_buy}</th>
				<th class="b_b" width="20%">{lang magics_amount_buy}</th>
				<th class="b_b" width="20%">{lang magics_price_buy}</th>
			</tr>
			<!--{loop $loglist $log}-->
				<tr>
					<td class="b_b">$log[name]</td>
					<td class="b_b">$log[dateline]</td>
					<td class="b_b">$log[amount]</td>
					<td class="b_b">$log[price] {$_G['setting']['extcredits'][$log[credit]][unit]}{$_G['setting']['extcredits'][$log[credit]][title]}</td>
				</tr>
			<!--{/loop}-->
		</table>
		<!--{if $multipage}--><div class="pgs cl mtm">$multipage</div><!--{/if}-->
	<!--{else}-->
		<p class="emp">{lang data_nonexistence}</p>
	<!--{/if}-->
<!--{elseif $operation == 'givelog'}-->
	<!--{if $loglist}-->
		<table summary="{lang magics_log_present}" cellspacing="0" cellpadding="0" class="b_b" width="100%">
			<tr>
				<th class="b_b">{lang magics_name}</td>
				<th class="b_b" width="20%">{lang magics_dateline_present}</th>
				<th class="b_b" width="20%">{lang magics_amount_present}</th>
				<th class="b_b" width="20%">{lang magics_target_present}</th>
			</tr>
			<!--{loop $loglist $log}-->
				<tr>
					<td class="b_b">$log[name]</td>
					<td class="b_b">$log[dateline]</td>
					<td class="b_b">$log[amount]</td>
					<td class="b_b"><a href="home.php?mod=space&uid=$log[targetuid]" target="_blank">$log[username]</a></td>
				</tr>
			<!--{/loop}-->
		</table>
		<!--{if $multipage}--><div class="pgs cl mtm">$multipage</div><!--{/if}-->
	<!--{else}-->
		<p class="emp">{lang data_nonexistence}</p>
	<!--{/if}-->
<!--{elseif $operation == 'receivelog'}-->
	<!--{if $loglist}-->
		<table summary="{lang magics_log_receive}" cellspacing="0" cellpadding="0" class="b_b" width="100%">
			<tr>
				<th class="b_b">{lang magics_name}</th>
				<th class="b_b" width="20%">{lang magics_dateline_receive}</th>
				<th class="b_b" width="20%">{lang magics_amount_receive}</th>
				<th class="b_b" width="20%">{lang magics_target_receive}</th>
			</tr>
			<!--{loop $loglist $log}-->
				<tr>
					<td class="b_b"><a href="home.php?mod=magic&action=index&operation=buy&magicid=$log[magicid]" target="_blank">$log[name]</a></td>
					<td class="b_b">$log[dateline]</td>
					<td class="b_b">$log[amount]</td>
					<td class="b_b"><a href="home.php?mod=space&uid=$log[uid]" target="_blank">$log[username]</a></td>
				</tr>
			<!--{/loop}-->
		</table>
		<!--{if $multipage}--><div class="pgs cl mtm">$multipage</div><!--{/if}-->
	<!--{else}-->
		<p class="emp">{lang data_nonexistence}</p>
	<!--{/if}-->
<!--{/if}-->