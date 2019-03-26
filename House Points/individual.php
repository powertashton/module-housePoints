<?php
// manage house point categories
if (isActionAccessible($guid, $connection2,"/modules/House Points/individual.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
            print "You do not have access to this action." ;
    print "</div>" ;
} else {
    
    $page->breadcrumbs->add(__('View points individual'));

    $modpath =  "./modules/".$_SESSION[$guid]["module"];
    include $modpath."/function.php";
    include $modpath."/individual_function.php";
   
    ?>
    <script>
        var modpath = '<?php echo $modpath ?>';
    </script>
    <?php
    
    $ind = new ind($guid, $connection2);
    $ind->modpath = $modpath;
    
    $ind->mainform();
}
