<?php if (!defined('IN_PHPBB')) exit; if ($this->_tpldata['DEFINE']['.']['CA_COMMENTS']) {  ?>
Explanation of editable variables:

$CA_WIDTH - page width. examples: '100%', '800'
$CA_SPLIT_POSTS - 0 to show posts in one table, 1 to show posts in separate tables

Do not edit variables other than those two.
<?php } $this->_tpldata['DEFINE']['.']['CA_WIDTH'] = '100%'; $this->_tpldata['DEFINE']['.']['CA_SPLIT_POSTS'] = '0'; $this->_tpldata['DEFINE']['.']['CA_SPACING'] = '0'; $this->_tpldata['DEFINE']['.']['CA_SKIP_LAST_SPACER'] = '1'; $this->_tpldata['DEFINE']['.']['CA_CAP_START'] = '<caption><div class="cap-left"><div class="cap-right">'; $this->_tpldata['DEFINE']['.']['CA_CAP_END'] = '&nbsp;</div></div></caption>'; $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] = '<div class="cap-div"><div class="cap-left"><div class="cap-right">'; $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] = '&nbsp;</div></div></div>'; $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] = '<div class="block-start">'; $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] = '<div class="block-end-left"><div class="block-end-right"></div></div></div>'; $this->_tpldata['DEFINE']['.']['CA_SKIP_LAST_SPACER'] = '1'; ?>