<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>

<form action="<?php echo (isset($this->_rootref['S_LOGIN_ACTION'])) ? $this->_rootref['S_LOGIN_ACTION'] : ''; ?>" method="post">

<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; ?>
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; if (! $this->_rootref['S_ADMIN_AUTH']) {  echo ((isset($this->_rootref['L_LOGIN'])) ? $this->_rootref['L_LOGIN'] : ((isset($user->lang['LOGIN'])) ? $user->lang['LOGIN'] : '{ LOGIN }')); } else { echo (isset($this->_rootref['LOGIN_EXPLAIN'])) ? $this->_rootref['LOGIN_EXPLAIN'] : ''; } echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>
<table class="tablebg" width="100%" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>">
<?php if ($this->_rootref['LOGIN_EXPLAIN'] && ! $this->_rootref['S_ADMIN_AUTH']) {  ?>
	<tr>
		<td class="row3" colspan="2" align="center"><span class="gensmall"><?php echo (isset($this->_rootref['LOGIN_EXPLAIN'])) ? $this->_rootref['LOGIN_EXPLAIN'] : ''; ?></span></td>
	</tr>
<?php } ?>
<tr><?php if (! $this->_rootref['S_ADMIN_AUTH'] && $this->_rootref['S_REGISTER_ENABLED']) {  ?>
	<td class="row1" width="50%">
		<p class="genmed"><?php echo ((isset($this->_rootref['L_LOGIN_INFO'])) ? $this->_rootref['L_LOGIN_INFO'] : ((isset($user->lang['LOGIN_INFO'])) ? $user->lang['LOGIN_INFO'] : '{ LOGIN_INFO }')); ?></p>

		<p class="genmed" align="center">
			<a href="<?php echo (isset($this->_rootref['U_TERMS_USE'])) ? $this->_rootref['U_TERMS_USE'] : ''; ?>"><?php echo ((isset($this->_rootref['L_TERMS_USE'])) ? $this->_rootref['L_TERMS_USE'] : ((isset($user->lang['TERMS_USE'])) ? $user->lang['TERMS_USE'] : '{ TERMS_USE }')); ?></a> | <a href="<?php echo (isset($this->_rootref['U_PRIVACY'])) ? $this->_rootref['U_PRIVACY'] : ''; ?>"><?php echo ((isset($this->_rootref['L_PRIVACY'])) ? $this->_rootref['L_PRIVACY'] : ((isset($user->lang['PRIVACY'])) ? $user->lang['PRIVACY'] : '{ PRIVACY }')); ?></a>
		</p>
	</td>
	<?php } ?>
	<td <?php if (! $this->_rootref['S_ADMIN_AUTH']) {  ?>class="row2"<?php } else { ?>class="row1"<?php } ?>>
	
		<table align="center" cellspacing="1" cellpadding="4" style="width: 100%;">
		<?php if ($this->_rootref['LOGIN_ERROR']) {  ?>
			<tr>
				<td class="gensmall" colspan="2" align="center"><span class="error"><?php echo (isset($this->_rootref['LOGIN_ERROR'])) ? $this->_rootref['LOGIN_ERROR'] : ''; ?></span></td>
			</tr>
		<?php } ?>

		<tr>
			<td valign="top" <?php if ($this->_rootref['S_ADMIN_AUTH']) {  ?>style="width: 50%; text-align: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;"<?php } ?>><b class="gensmall"><?php echo ((isset($this->_rootref['L_USERNAME'])) ? $this->_rootref['L_USERNAME'] : ((isset($user->lang['USERNAME'])) ? $user->lang['USERNAME'] : '{ USERNAME }')); ?>:</b></td>
			<td><input class="post" type="text" name="<?php echo (isset($this->_rootref['USERNAME_CREDENTIAL'])) ? $this->_rootref['USERNAME_CREDENTIAL'] : ''; ?>" size="25" value="<?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>" tabindex="1" />
				<?php if (! $this->_rootref['S_ADMIN_AUTH'] && $this->_rootref['S_REGISTER_ENABLED']) {  ?>
					<br /><a class="gensmall" href="<?php echo (isset($this->_rootref['U_REGISTER'])) ? $this->_rootref['U_REGISTER'] : ''; ?>"><?php echo ((isset($this->_rootref['L_REGISTER'])) ? $this->_rootref['L_REGISTER'] : ((isset($user->lang['REGISTER'])) ? $user->lang['REGISTER'] : '{ REGISTER }')); ?></a>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td valign="top" <?php if ($this->_rootref['S_ADMIN_AUTH']) {  ?>style="width: 50%; text-align: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;"<?php } ?>><b class="gensmall"><?php echo ((isset($this->_rootref['L_PASSWORD'])) ? $this->_rootref['L_PASSWORD'] : ((isset($user->lang['PASSWORD'])) ? $user->lang['PASSWORD'] : '{ PASSWORD }')); ?>:</b></td>
			<td>
				<input class="post" type="password" name="<?php echo (isset($this->_rootref['PASSWORD_CREDENTIAL'])) ? $this->_rootref['PASSWORD_CREDENTIAL'] : ''; ?>" size="25" tabindex="2" />
				<?php if ($this->_rootref['U_SEND_PASSWORD']) {  ?><br /><a class="gensmall" href="<?php echo (isset($this->_rootref['U_SEND_PASSWORD'])) ? $this->_rootref['U_SEND_PASSWORD'] : ''; ?>"><?php echo ((isset($this->_rootref['L_FORGOT_PASS'])) ? $this->_rootref['L_FORGOT_PASS'] : ((isset($user->lang['FORGOT_PASS'])) ? $user->lang['FORGOT_PASS'] : '{ FORGOT_PASS }')); ?></a><?php } if ($this->_rootref['U_RESEND_ACTIVATION'] && ! $this->_rootref['S_ADMIN_AUTH']) {  ?><br /><a class="gensmall" href="<?php echo (isset($this->_rootref['U_RESEND_ACTIVATION'])) ? $this->_rootref['U_RESEND_ACTIVATION'] : ''; ?>"><?php echo ((isset($this->_rootref['L_RESEND_ACTIVATION'])) ? $this->_rootref['L_RESEND_ACTIVATION'] : ((isset($user->lang['RESEND_ACTIVATION'])) ? $user->lang['RESEND_ACTIVATION'] : '{ RESEND_ACTIVATION }')); ?></a><?php } ?>
			</td>
		</tr>
		<?php if ($this->_rootref['S_DISPLAY_FULL_LOGIN']) {  if ($this->_rootref['S_AUTOLOGIN_ENABLED']) {  ?>
			<tr>
				<td>&nbsp;</td>
				<td><input type="checkbox" class="radio" name="autologin" tabindex="3" /> <span class="gensmall"><?php echo ((isset($this->_rootref['L_LOG_ME_IN'])) ? $this->_rootref['L_LOG_ME_IN'] : ((isset($user->lang['LOG_ME_IN'])) ? $user->lang['LOG_ME_IN'] : '{ LOG_ME_IN }')); ?></span></td>
			</tr>
			<?php } ?>
			<tr>
				<td>&nbsp;</td>
				<td><input type="checkbox" class="radio" name="viewonline" tabindex="4" /> <span class="gensmall"><?php echo ((isset($this->_rootref['L_HIDE_ME'])) ? $this->_rootref['L_HIDE_ME'] : ((isset($user->lang['HIDE_ME'])) ? $user->lang['HIDE_ME'] : '{ HIDE_ME }')); ?></span></td>
			</tr>
		<?php } ?>
		</table>
	</td>
</tr>

<?php if ($this->_rootref['CAPTCHA_TEMPLATE'] && $this->_rootref['S_CONFIRM_CODE']) {  ?>
</table>
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>
<br />
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; ?>
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; echo ((isset($this->_rootref['L_LOGIN_CONFIRMATION'])) ? $this->_rootref['L_LOGIN_CONFIRMATION'] : ((isset($user->lang['LOGIN_CONFIRMATION'])) ? $user->lang['LOGIN_CONFIRMATION'] : '{ LOGIN_CONFIRMATION }')); echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>
<table class="tablebg" width="100%" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>">
<?php if (isset($this->_rootref['CAPTCHA_TEMPLATE'])) { $this->_tpl_include($this->_rootref['CAPTCHA_TEMPLATE']); } } ?>
<?php echo (isset($this->_rootref['S_LOGIN_REDIRECT'])) ? $this->_rootref['S_LOGIN_REDIRECT'] : ''; ?>
<tr>
	<td class="cat" <?php if (! $this->_rootref['S_ADMIN_AUTH'] || $this->_rootref['S_CONFIRM_CODE']) {  ?>colspan="2"<?php } ?> align="center"><?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?><input type="submit" name="login" class="btnmain" value="<?php echo ((isset($this->_rootref['L_LOGIN'])) ? $this->_rootref['L_LOGIN'] : ((isset($user->lang['LOGIN'])) ? $user->lang['LOGIN'] : '{ LOGIN }')); ?>" tabindex="5" /></td>
</tr>
</table>
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>
<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

</form>

<br clear="all" />

<?php $this->_tpl_include('breadcrumbs.html'); ?>

<br clear="all" />

<div align="<?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>"><?php $this->_tpl_include('jumpbox.html'); ?></div>

<script type="text/javascript">
// <![CDATA[
	(function()
	{
		var elements = document.getElementsByName("<?php echo (isset($this->_rootref['USERNAME_CREDENTIAL'])) ? $this->_rootref['USERNAME_CREDENTIAL'] : ''; ?>");
		for (var i = 0; i < elements.length; ++i)
		{
			if (elements[i].tagName.toLowerCase() == 'input')
			{
				elements[i].focus();
				break;
			}
		}
	})();
// ]]>
</script>

<?php $this->_tpl_include('overall_footer.html'); ?>