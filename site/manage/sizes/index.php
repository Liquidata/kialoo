<?php
	require("../../../admin/start.php");

	require_once(Settings::$location . "site/Cruds.php");

	if (!$loggedUser) {
	    $page->redirect(Settings::$domainAdmin . "dashboard/login.php");
	}
	include Settings::$locationAdmin . "dashboard/blocks/head.php";
?>
<body>
	<?php
	    include Settings::$locationAdmin . "dashboard/blocks/topbar.php";
	?>
		<div class="container-fluid">
		<div class="row-fluid">

		    <?php
        	    include Settings::$locationAdmin . "dashboard/blocks/leftmenu.php";
        	?>

			<div id="content" class="span10">
    			<!-- content starts -->
    			<?php
            	    include Settings::$locationAdmin . "dashboard/blocks/breadcrumb.php";

            	    $crud = $cruds["sizes"];
            	    echo $crud->showTable();

            	?>

    			<!-- content ends -->
			</div><!--/#content.span10-->
		</div><!--/fluid-row-->

		<hr>

        <?php
            include Settings::$locationAdmin . "dashboard/blocks/footer.php";
        ?>
	</div><!--/.fluid-container-->
	<?php
	include Settings::$locationAdmin . "dashboard/blocks/bodyend.php";

	$page->end();
?>