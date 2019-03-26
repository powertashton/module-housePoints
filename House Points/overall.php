<?php
// manage house point categories
if (isActionAccessible($guid, $connection2,"/modules/House Points/overall.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
            print "You do not have access to this action." ;
    print "</div>" ;
} else {
    
    $page->breadcrumbs->add(__('View points overall'));

    $modpath =  "./modules/".$_SESSION[$guid]["module"];
    include $modpath."/function.php";
    include $modpath."/overall_function.php";
   
    ?>
    <script>
        var modpath = '<?php echo $modpath ?>';
    </script>
    <?php
    
    $over = new over($guid, $connection2);
    $over->modpath = $modpath;
    
    $over->mainform();
}
