<?php
#注册插件
RegisterPlugin("acgTop", "ActivePlugin_acgTop");

function ActivePlugin_acgTop()
{
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'acgTop_BuildTemp');
}
function acgTop_BuildTemp(&$templates)
{
  // global $zbp;
  $templates['header'] = str_replace('{$header}', '{$header}' . '<link rel="stylesheet" href="' . acgTop_Path("w-css", "host") . '" />', $templates['header']);;
  $templates['footer'] = str_replace('{$footer}', '{$footer}' . '<div title="要回到上边么？" class="acgTop fade"></div><script src="' . acgTop_Path("w-js", "host") . '"></script>', $templates['footer']);
}
function acgTop_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/acgTop/';
  switch ($file) {
    case 'w-js':
      return $result . 'common/script.js?v=2020-05-20';
      break;
    case 'w-css':
      return $result . 'common/style.css?v=2020-05-20';
      break;
    case 'usr':
      return $result . 'usr/';
      break;
    case 'var':
      return $result . 'var/';
      break;
    case 'main':
      return $result . 'main.php';
      break;
    default:
      return $result . $file;
  }
}
function InstallPlugin_acgTop()
{
  global $zbp;
  $zbp->BuildTemplate();
}
function UninstallPlugin_acgTop()
{
}
