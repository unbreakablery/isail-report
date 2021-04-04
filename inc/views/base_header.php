<?php
/**
 * base_header.php
 *
 * Author: Anna
 *
 * The header of each page (Backend)
 *
 */
?>

<!-- Header -->
<header id="header-navbar" class="content-mini content-mini-full">
    <!-- Header Navigation Right -->
    <ul class="nav-header pull-right">
        <li>
            <div class="btn-group">
                
                <ul class="nav nav-pills push">
                    <li class="active">
                        <a tabindex="-1" href="/logout.php" id="logout">
                            Log out <i class="si si-logout push-5-l"></i>
                        </a>
                    </li>
                </ul>
                
            </div>
        </li>
    </ul>

    <ul class="nav-header pull-right">
        <li>
            <div class="btn-group">
                
                <ul class="nav nav-pills push">
                    <li class="active">
                        <a tabindex="-1" href="/index.php" id="index" class="btn-header-home">
                            Home <i class="si si-home push-5-l"></i>
                        </a>
                    </li>
                </ul>
                
            </div>
        </li>
    </ul>
    <!-- END Header Navigation Right -->

    <!-- Header Navigation Left -->
    <ul class="nav-header pull-left">
        <li class="hidden-md hidden-lg">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                <i class="fa fa-navicon"></i>
            </button>
        </li>
        <li class="hidden-xs hidden-sm">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                <i class="fa fa-ellipsis-v"></i>
            </button>
        </li>
    </ul>
    <!-- END Header Navigation Left -->

    <!-- Header Logo -->
    <?php if ($common->getModelsShortLabel() === "ACE") { ?>
    <div class="pull-left">
        <div class="row push-30-l push-30-r">
            <a class="h5 text-white" href="http://zombiesalesgame.com/iSAIL/index.php" target="_blank">
                <img src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/img/logos/logo_new.png" width="200px" />
            </a>
        </div>
    </div>
    <?php } ?>
    <!-- END Header Logo -->

</header>
<!-- END Header -->