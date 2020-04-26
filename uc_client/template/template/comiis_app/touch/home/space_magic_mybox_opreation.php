<?php echo '来自---人呐！干哈呀！的制作';exit;?>
<!--{template common/header}-->

<div class="comiis_tip comiis_input_style bg_f cl">
<form id="magicform" method="post" action="home.php?mod=magic&action=mybox&infloat=yes" onsubmit="ajaxpost('magicform', 'return_$_GET[handlekey]', 'return_$_GET[handlekey]', 'onerror');return false;">
	<div class="c" id="hkey_$_GET[handlekey]">
		<div id="return_$_GET[handlekey]"></div>
			<input type="hidden" name="formhash" value="{FORMHASH}" />
			<input type="hidden" name="handlekey" value="$_GET[handlekey]" />
			<input type="hidden" name="operation" value="$operation" />
			<input type="hidden" name="magicid" value="$magicid" />
			<!--{if $operation == 'give'}-->
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
			<!--{elseif $operation == 'use'}-->
				<!--{if $magiclist}-->
				<p class="mtw mbw">
					<select name="magicid" onchange="showWindow('magics', 'home.php?mod=magic&action=mybox&operation=use&&infloat=yes&type=$typeid&pid=$pid&typeid=$typeid&magicid='+this.options[this.selectedIndex].value)" class="dialog">
						<option value="0">{lang magics_select}</option>
						<!--{loop $magiclist $magics}-->
							<option value="$magics[magicid]" $magicselect[$magics[magicid]]>$magics[name] - $magics[description]</option>
						<!--{/loop}-->
					</select>
				</p>
				<!--{/if}-->
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
				<ul class="comiis_p5 cl">	
					<li class="comiis_styli f_a" style="padding-top:5px;padding-bottom:0;text-align:center;">	
							<!--{if method_exists($magicclass, 'show')}-->
								<!--{eval $magicclass->show();}-->
							<!--{/if}-->
							<!--{if $useperoid !== true}-->
								<p class="xi1"><!--{if $magic['useperoid'] == 1}-->{lang magics_outofperoid_1}<!--{elseif $magic['useperoid'] == 2}-->{lang magics_outofperoid_2}<!--{elseif $magic['useperoid'] == 3}-->{lang magics_outofperoid_3}<!--{elseif $magic['useperoid'] == 4}-->{lang magics_outofperoid_4}<!--{/if}--><!--{if $useperoid > 0}-->{lang magics_outofperoid_value}<!--{else}-->{lang magics_outofperoid_noperm}<!--{/if}--></p>
							<!--{/if}-->
					</li>		
				</ul>
				<input type="hidden" name="usesubmit" value="yes" />
				<input type="hidden" name="operation" value="use" />
				<input type="hidden" name="magicid" value="$magicid" />
				<!--{if !empty($_GET['idtype']) && !empty($_GET['id'])}-->
					<input type="hidden" name="idtype" value="$_GET[idtype]" />
					<input type="hidden" name="id" value="$_GET[id]" />
				<!--{/if}-->
			<!--{elseif $operation == 'sell'}-->
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
				<ul class="comiis_p5 cl">	
					<li class="comiis_styli f_a" style="padding-top:5px;padding-bottom:0;text-align:center;">	
						<p class="mtm mbm">{lang magics_operation_sell} <input name="magicnum" type="text" size="2" value="1" class="b_ok bg_f comiis_login_select f_d" />{lang magics_unit} &nbsp;&nbsp"$magic[name]"</p>
						<div class="border"></div>
						<p class="xw0">
							{lang recycling_prices}:
							<!--{if {$_G['setting']['extcredits'][$magic[credit]][unit]}}-->
								{$_G['setting']['extcredits'][$magic[credit]][title]} $discountprice {$_G['setting']['extcredits'][$magic[credit]][unit]}/{lang magics_unit}
							<!--{else}-->
								$discountprice {$_G['setting']['extcredits'][$magic[credit]][title]}/{lang magics_unit}
							<!--{/if}-->
						</p>
					</li>		
				</ul>
				
				<input type="hidden" name="operatesubmit" value="yes" />
			<!--{elseif $operation == 'drop'}-->
				<dl class="xld cl">
					<dd class="m"><img src="$magic[pic]" alt="$magic[name]"></dd>
					<dt class="z">
						<p class="mtm mbm">{lang magics_operation_drop} <input name="magicnum" type="text" size="2" autocomplete="off" value="1" class="px pxs" /> {lang magics_unit}"$magic[name]"</p>
						<p class="xw0">{lang magics_weight}: $magic[weight]</p>
					</dt>
				</dl>
				<input type="hidden" name="operatesubmit" value="yes" />
			<!--{/if}-->


<!--{if empty($_GET['infloat'])}--><div class="m_c"><!--{/if}-->

	<li class="comiis_stylino mt10 mb12">
	<!--{if $operation == 'give'}-->
		<button class="comiis_btn bg_c f_f" type="submit" name="operatesubmit" id="operatesubmit" value="true" onclick="return confirmMagicOp(e)"><span>{lang magics_operation_present}</span></button>
	<!--{elseif $operation == 'use'}-->
		<button class="comiis_btn bg_c f_f" type="submit" name="usesubmit" id="usesubmit" value="true"><span>{lang magics_operation_use}</span></button>
	<!--{elseif $operation == 'sell'}-->
		<button class="comiis_btn bg_c f_f" type="submit" name="operatesubmit" id="operatesubmit" value="true" onclick="return confirmMagicOp(e)"><span>{lang magics_operation_sell}</span></button>
	<!--{elseif $operation == 'drop'}-->
		<button class="comiis_btn bg_c f_f" type="submit" name="operatesubmit" id="operatesubmit" value="true" onclick="return confirmMagicOp(e)"><span>{lang magics_operation_drop}</span></button>
	<!--{/if}-->
	</li>

<!--{if empty($_GET['infloat'])}--></div><!--{/if}-->
</form>

<script type="text/javascript" reload="1">
	function confirmMagicOp(e) {
		e = e ? e : window.event;
		showDialog('{lang magics_confirm}', 'confirm', '', 'ajaxpost(\'magicform\', \'return_magics\', \'return_magics\', \'onerror\');');
		doane(e);
		return false;
	}
	function succeedhandle_$_GET[handlekey](url, msg) {
		hideWindow('$_GET[handlekey]');
		if(arguments[2] && arguments[2]['avatar']) {
			msg += ' <ul class="ml mls cl"><li><a class="avt" target="_blank" href="home.php?mod=space&amp;uid='+arguments[2]['uid']+'"><em class=""></em><img src="{$_G[setting][ucenterurl]}/avatar.php?uid='+arguments[2]['uid']+'&size=small" /></a><p><a title="admin" href="home.php?mod=space&amp;uid='+arguments[2]['uid']+'" target="_blank"><b>'+arguments[2]['username']+'</b></a></p></li></ul>';
		}
		<!--{if !$location}-->
			showDialog(msg, 'notice', null, function () { location.href=url; }, 0);
		<!--{else}-->
			showWindow('$_GET[handlekey]', 'home.php?$querystring');
		<!--{/if}-->
		showCreditPrompt();
	}
</script>

<!--{if empty($_GET['infloat'])}-->
</div></div>

</div>
<!--{/if}-->
<!--{template common/footer}-->