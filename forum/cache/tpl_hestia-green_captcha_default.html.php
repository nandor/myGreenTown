<?php if (!defined('IN_PHPBB')) exit; ?><tr>
		<th colspan="2" valign="middle"><?php echo ((isset($this->_rootref['L_CONFIRM_CODE'])) ? $this->_rootref['L_CONFIRM_CODE'] : ((isset($user->lang['CONFIRM_CODE'])) ? $user->lang['CONFIRM_CODE'] : '{ CONFIRM_CODE }')); ?></th>
	</tr>
	<?php if ($this->_rootref['S_TYPE'] == (1)) {  ?>
	<tr>
		<td class="row3" colspan="2"><span class="gensmall"><?php echo ((isset($this->_rootref['L_CONFIRM_EXPLAIN'])) ? $this->_rootref['L_CONFIRM_EXPLAIN'] : ((isset($user->lang['CONFIRM_EXPLAIN'])) ? $user->lang['CONFIRM_EXPLAIN'] : '{ CONFIRM_EXPLAIN }')); ?></span></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="row1" colspan="2" align="center"><img src="<?php echo (isset($this->_rootref['CONFIRM_IMAGE_LINK'])) ? $this->_rootref['CONFIRM_IMAGE_LINK'] : ''; ?>" alt="<?php echo ((isset($this->_rootref['L_CONFIRM_CODE'])) ? $this->_rootref['L_CONFIRM_CODE'] : ((isset($user->lang['CONFIRM_CODE'])) ? $user->lang['CONFIRM_CODE'] : '{ CONFIRM_CODE }')); ?>" />
		<input type="hidden" name="confirm_id" id="confirm_id" value="<?php echo (isset($this->_rootref['CONFIRM_ID'])) ? $this->_rootref['CONFIRM_ID'] : ''; ?>" /></td>
	</tr>
	<tr>
		<td class="row1"><b class="genmed"><?php echo ((isset($this->_rootref['L_CONFIRM_CODE'])) ? $this->_rootref['L_CONFIRM_CODE'] : ((isset($user->lang['CONFIRM_CODE'])) ? $user->lang['CONFIRM_CODE'] : '{ CONFIRM_CODE }')); ?>:</b><br /><span class="gensmall"><?php echo ((isset($this->_rootref['L_CONFIRM_CODE_EXPLAIN'])) ? $this->_rootref['L_CONFIRM_CODE_EXPLAIN'] : ((isset($user->lang['CONFIRM_CODE_EXPLAIN'])) ? $user->lang['CONFIRM_CODE_EXPLAIN'] : '{ CONFIRM_CODE_EXPLAIN }')); ?></span></td>
		<td class="row2"><input class="post" type="text" name="confirm_code" size="8" maxlength="8" />
		<?php if ($this->_rootref['S_CONFIRM_REFRESH']) {  ?><input type="submit" name="refresh_vc" id="refresh_vc" class="btnlite" value="<?php echo ((isset($this->_rootref['L_VC_REFRESH'])) ? $this->_rootref['L_VC_REFRESH'] : ((isset($user->lang['VC_REFRESH'])) ? $user->lang['VC_REFRESH'] : '{ VC_REFRESH }')); ?>" /><?php } ?></td>
	</tr>