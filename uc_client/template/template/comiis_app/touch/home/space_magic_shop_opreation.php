<?php echo '来自---人呐！干哈呀！的制作';exit;?>
<!--{eval
	$_G['home_tpl_titles'] = array('{lang magic}');
}-->
<!--{template common/header}-->
<div class="comiis_tip comiis_input_style bg_f cl">
<form id="magicform" method="post" action="home.php?mod=magic&action=shop&infloat=yes"{if $_G[inajax]} onsubmit="ajaxpost('magicform', 'return_$_GET[handlekey]', 'return_$_GET[handlekey]', 'onerror');return false;"{/if}>
		<input type="hidden" name="formhash" value="{FORMHASH}" />
		<!--{if !empty($_GET['infloat'])}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
		<input type="hidden" name="operation" value="$operation" />
		<input type="hidden" name="mid" value="$_GET['mid']" />
		<!--{if !empty($_GET['idtype']) && !empty($_GET['id'])}-->
			<input type="hidden" name="idtype" value="$_GET[idtype]" />
			<input type="hidden" name="id" value="$_GET[id]" />
		<!--{/if}-->
		<!--{if $operation == 'buy'}-->

				<div class="comiis_magictop comiis_p5 bg_e b_b cl">
					<a href="javascript:;" onclick="popup.close();" class="ratetop_close f_d"><i class="comiis_font">&#xe639</i></a>
					<div class="top">
						<div class="img">
							<img src="$magic[pic]" alt="$magic[name]"/>
						</div>
						<div class="name">
							$magic[name]
							<p class="f_c" style="padding: 10px 0px 0px;">$magic[description]</p>					
						</div>
					</div>	
				</div>
				<div class="cont">
					<p class="ft16">					
						{lang magics_price}: <span{if $magic[discountprice] && $magic[price] != $magic[discountprice]} style="text-decoration:line-through;"{/if}>{$_G['setting']['extcredits'][$magic[credit]][title]} <span class="f_a" id="magicprice">$magic[price]</span> {$_G['setting']['extcredits'][$magic[credit]][unit]}</span>
						<!--{if $magic[discountprice] && $magic[price] != $magic[discountprice]}-->
							&nbsp;&nbsp;{lang magics_discountprice}: {$_G['setting']['extcredits'][$magic[credit]][title]} <span class="f_ok" id="discountprice">$magic[discountprice]</span> $_G['setting']['extcredits'][$magic[credit]][unit]
						<!--{/if}-->
					</p>
					<p class="ft13">
						{lang magics_yourcredit}&nbsp;&nbsp;<span class="f_ok" id="discountprice"><!--{echo getuserprofile('extcredits'.$magic[credit])}--> {$_G['setting']['extcredits'][$magic[credit]][unit]}</span>
					</p>
					<div class="border"></div>
					<p class="ft16">
						{lang magics_weight}:&nbsp;&nbsp;<span class="f_ok" id="magicweight">$magic[weight]</span>
					</p>
					<p class="ft13">
					{lang my_magic_volume}<span class="f_ok">&nbsp;&nbsp;$allowweight</span>
					</p>
					<div class="border"></div>
						<div class="ft16">{lang stock}: <span class="f_ok">$magic[num]</span> {lang magics_unit}
							<!--{if $useperoid !== true}-->
								<!--{if $magic['useperoid'] == 1}-->{lang magics_outofperoid_1}<!--{elseif $magic['useperoid'] == 2}-->{lang magics_outofperoid_2}<!--{elseif $magic['useperoid'] == 3}-->{lang magics_outofperoid_3}<!--{elseif $magic['useperoid'] == 4}-->{lang magics_outofperoid_4}<!--{/if}--><!--{if $useperoid > 0}-->{lang magics_outofperoid_value}<!--{else}-->{lang magics_outofperoid_noperm}<!--{/if}-->
							<!--{/if}-->
							<!--{if !$useperm}-->{lang magics_permission_no}<!--{/if}-->
							{lang memcp_usergroups_buy} <input id="magicnum" name="magicnum" type="text" size="2" autocomplete="off" value="1" class="px pxs" onkeyup="compute();" /> {lang magics_unit}
						</div>
				</div>
				
			
			<input type="hidden" name="operatesubmit" value="yes" />
		<!--{elseif $operation == 'give'}-->
				<div class="comiis_magictop comiis_p5 bg_e b_b cl">
					<a href="javascript:;" onclick="popup.close();" class="ratetop_close f_d"><i class="comiis_font">&#xe639</i></a>
					<div class="top">
						<div class="img">
							<img src="$magic[pic]" alt="$magic[name]"/>
						</div>
						<div class="name">
							{lang magics_operation_present}"$magic[name]"
							<p class="f_c" style="padding: 10px 0px 0px;">$magic[description]</p>					
						</div>
					</div>	
				</div>
				
				<div class="cont">
					<table>
						<tr>
							<th>{lang magics_target_present}</th>
							<td>
								<input type="text" list="selectedusername" name="tousername" size="12" autocomplete="off" value="" class="b_ok bg_f comiis_login_select f_d" style="margin-right: 0; width: 150px;"/>
								<!--{if $buddyarray}-->
									<datalist id="selectedusername">
									<!--{loop $buddyarray $buddy}-->
									  <option value="$buddy[fusername]">
									 <!--{/loop}-->
									</datalist>
								<!--{/if}-->
							</td>
						</tr>
						<tr>
							<th>{lang magics_num}</th>
							<td><input name="magicnum" type="text" size="12" autocomplete="off" value="1" pattern="[0-9]" class="b_ok bg_f comiis_login_select f_d" style="width: 150px; "/></td>
						</tr>
						<tr>
							<th>{lang magics_present_message}</th>
							<td><input name="givemessage" rows="3" style="width: 150px; " class="b_ok bg_f comiis_login_select f_d"></td>
						</tr>
					</table>	
				</div>

			<input type="hidden" name="operatesubmit" value="yes" />
		<!--{/if}-->

<!--{if empty($_GET['infloat'])}--><div class="m_c"><!--{/if}-->

	<li class="comiis_stylino mt10 mb12">
		<!--{if $operation == 'buy'}-->
			<button class="comiis_btn bg_c f_f" type="submit" name="operatesubmit" id="operatesubmit" value="true"><span>{lang magics_operation_buy}</span></button>
		<!--{elseif $operation == 'give'}-->
			<button class="comiis_btn bg_c f_f" type="submit" name="operatesubmit" id="operatesubmit" value="true" onclick="return confirmMagicOp(e)"><span>{lang magics_operation_present}</span></button>
		<!--{/if}-->
	 </li>
<!--{if empty($_GET['infloat'])}--></div><!--{/if}-->
</form>

<script type="text/javascript" reload="1">
	function succeedhandle_$_GET[handlekey](url, msg) {
		hideWindow('$_GET[handlekey]');
		<!--{if !$location}-->
			showDialog(msg, 'notice', null, function () { location.href=url; }, 0);
		<!--{else}-->
			showWindow('$_GET[handlekey]', 'home.php?$querystring');
		<!--{/if}-->
		showCreditPrompt();
	}
	function confirmMagicOp(e) {
		e = e ? e : window.event;
		showDialog('{lang magics_confirm}', 'confirm', '', 'ajaxpost(\'magicform\', \'return_magics\', \'return_magics\', \'onerror\');');
		doane(e);
		return false;
	}
	function compute() {
		var totalcredit = <!--{echo getuserprofile('extcredits'.$magic[credit])}-->;
		var totalweight = $allowweight;
		var magicprice = $('magicprice').innerHTML;
		if($('discountprice')) {
			magicprice = $('discountprice').innerHTML;
		}
		if(isNaN(parseInt($('magicnum').value))) {
			$('magicnum').value = 0;
			return;
		}
		if(!$('magicnum').value || totalcredit < 1 || totalweight < 1) {
			$('magicnum').value = 0;
			return;
		}
		var curprice = $('magicnum').value * magicprice;
		var curweight = $('magicnum').value * $('magicweight').innerHTML;
		if(curprice > totalcredit) {
			$('magicnum').value = parseInt(totalcredit / magicprice);
		} else if(curweight > totalweight) {
			$('magicnum').value = parseInt(totalweight / $('magicweight').innerHTML);
		}
		$('magicnum').value = parseInt($('magicnum').value);
	}
</script>

<!--{if empty($_GET['infloat'])}-->
</div></div>

</div>
<!--{/if}-->
<!--{template common/footer}-->