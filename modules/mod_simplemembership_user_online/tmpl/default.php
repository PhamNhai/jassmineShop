<?php 
$i=count($ListUser[0]);
$j = $i;
$max_user=$params->get('max_user');
?>
<div class="profileTable<?php echo $sufix;?>">
<table  class="profileTable<?php echo $sufix;?>">
<?php
if($ListUser[0]){
    if(($params->get('view'))=='0'){
        do{			
            if(((count($ListUser[0]))-$i)!=$max_user){    
            }
            else
                break;
            $i--;
            if(($params->get('users_pic'))=='0'){
                ?>
                <tr>
                    <td>
                        <a class="plugOnlineUserText1" href="<?php echo sefRelToAbs('index.php?option=com_simplemembership&Itemid='.
                                    $Itemid_simp.'&task=showUsersProfile&userId='.$ListUser[0][$i].''); ?>">
                            <?php
                            echo ($ListUser[1][$i])."<br>";
                            ?>
                        </a>
                    </td>
                </tr>
                <?php
            }else{
                ?>
                <tr>
                    <td>
                        <?php
                        echo $mas_img[1][$i];
                        ?>
                        <a class="plugOnlineUserText2" href="<?php echo sefRelToAbs('index.php?option=com_simplemembership&Itemid='.
                                      $Itemid_simp.'&task=showUsersProfile&userId='.$ListUser[0][$i].''); ?>">
                            <?php
                            echo ($ListUser[1][$i])."<br>";
                            ?>
                        </a>
                    </td>
                </tr>
                <?php
            }
        }while($i >0);
    }else{ ?>
        <tr>
        <?php
            if(($params->get('users_pic'))=='1'){
                do{
                    if(((count($ListUser[0]))-$i)!=$max_user){
                    }else
                        break;
                    $i--;
                    ?>
                    <td align="center" valign="top"  width="<?php echo $params->get('img_width');?>">
                        <?php echo $mas_img[1][$i]."<br>";?>
                        <a class="plugOnlineUserText1" href="<?php echo sefRelToAbs('index.php?option=com_simplemembership&Itemid='.
                            $Itemid_simp.'&task=showUsersProfile&userId='.$ListUser[0][$i].''); ?>">
                        <?php  echo $ListUser[1][$i]; ?>
                        </a>
                    </td>
                    <?php
                }while($i >0);
            }else{
                do{
                    if(((count($ListUser[0]))-$j)!=$max_user){}
                    else
                        break;
                    $j--;
                    ?>
                    <td>
                        <a class="plugOnlineUserText3" href="<?php echo sefRelToAbs('index.php?option=com_simplemembership&Itemid='.
                                                        $Itemid_simp.'&task=showUsersProfile&userId='.$ListUser[0][$j].''); ?>">
                            <?php  echo $ListUser[1][$j]; ?>
                        </a>
                    </td>
                    <?php
                }while($j >0);
            } ?>
       </tr>
    <?php
    }
} ?>
</table>	
</div> 
<div style="text-align: center;"><a style="font-size: 10px;" href="http://ordasoft.com">Powered by OrdaSoft!</a></div>