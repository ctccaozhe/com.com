<dl id="<?php  echo $module->HtmlID;  ?>" class="sidebox">
    <?php if ((!$module->IsHideTitle)&&($module->Name)) { ?><dt><?php  echo $module->Name;  ?></dt><?php } ?>
    <dd>
        <?php if ($module->Type=='div') { ?>
		<div><?php  echo $module->Content;  ?></div>
		<?php } ?>
		<?php if ($module->Type=='ul') { ?>
		<ul><?php  echo $module->Content;  ?></ul>
		<?php } ?>
    </dd>
</dl>