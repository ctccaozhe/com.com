<?php  foreach ( $comments as $cmt) { ?>
<li class="discuz"><a href="<?php  echo $cmt->Post->Url;  ?>#cmt<?php  echo $cmt->ID;  ?>" title="<?php  echo $cmt->Name;  ?><?php  echo $msg->discuss;  ?>《<?php  echo $cmt->Post->Title;  ?>》"><img src="<?php  echo $cmt->Author->Avatar;  ?>" alt="<?php  echo $cmt->Name;  ?>"> <b><?php  echo $cmt->Name;  ?></b>：<?php  echo SubstrUTF8(TransferHTML($cmt->Content, '[noenter][nohtml]'),30);  ?> <q><?php  echo SubstrUTF8($cmt->Post->Title,20);  ?></q></a></li>
<?php }   ?>