<?php  /* Template Name:搜索页 */  ?>
<?php  include $this->GetTemplate('header');  ?>
<body class="multi <?php  echo $type;  ?>">
<div id="divAll">
	<div id="divPage">
	<div id="divMiddle">
		<div id="divTop">
			<h1 id="BlogTitle"><a href="<?php  echo $host;  ?>"><?php  echo $name;  ?></a></h1>
			<h3 id="BlogSubTitle"><?php  echo $subname;  ?></h3>
		</div>
		<div id="divNavBar">
<ul>
<?php  if(isset($modules['navbar'])){echo $modules['navbar']->Content;}  ?>
</ul>
		</div>
		<div id="divMain">
<div class="post istop istop-post">
	<h2 class="post-title"><?php  echo $article->Title;  ?></h2>
</div>
<?php  foreach ( $articles as $article) { ?>
<?php  include $this->GetTemplate('post-search');  ?>
<?php }   ?>
<?php if (count($articles)>0) { ?>
<div class="pagebar"><?php  include $this->GetTemplate('pagebar');  ?></div>
<?php } ?>
		</div>
		<div id="divSidebar">
<?php  include $this->GetTemplate('sidebar');  ?>
		</div>
<?php  include $this->GetTemplate('footer');  ?>