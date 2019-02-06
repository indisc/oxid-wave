<?php /* Smarty version 2.6.31, created on 2019-02-06 22:17:32
         compiled from widget/header/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/header/search.tpl', 10, false),)), $this); ?>

    <?php if ($this->_tpl_vars['oView']->showSearch()): ?>
        <form class="form search" role="form" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfActionLink(); ?>
" method="get" name="search">
            <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

            <input type="hidden" name="cl" value="search">

            
                <div class="input-group">
                    
                        <input class="form-control" type="text" id="searchParam" name="searchparam" value="<?php echo $this->_tpl_vars['oView']->getSearchParamForHtml(); ?>
" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'SEARCH'), $this);?>
">
                    

                    
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" title="<?php echo smarty_function_oxmultilang(array('ident' => 'SEARCH_SUBMIT'), $this);?>
"><i class="fas fa-search"></i></button>
                    </div>
                    
                </div>
            
        </form>
    <?php endif; ?>