<?php
/**
 * base_head.php
 *
 * Author: Anna
 *
 * The head of each page (Backend)
 *
 */
?>

<!-- Page Container -->
<!--
    Available Classes:

    'enable-cookies'             Remembers active color theme between pages (when set through color theme list)

    'sidebar-l'                  Left Sidebar and right Side Overlay
    'sidebar-r'                  Right Sidebar and left Side Overlay
    'sidebar-mini'               Mini hoverable Sidebar (> 991px)
    'sidebar-o'                  Visible Sidebar by default (> 991px)
    'sidebar-o-xs'               Visible Sidebar by default (< 992px)

    'side-overlay-hover'         Hoverable Side Overlay (> 991px)
    'side-overlay-o'             Visible Side Overlay by default (> 991px)

    'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

    'header-navbar-fixed'        Enables fixed header
-->
<div id="page-container"<?php $iSAIL_UI->page_classes(); ?>>
    <?php if (isset($iSAIL_UI->inc_sidebar) && $iSAIL_UI->inc_sidebar) { include($iSAIL_UI->inc_sidebar); } ?>
    <?php if (isset($iSAIL_UI->inc_header) && $iSAIL_UI->inc_header) { include($iSAIL_UI->inc_header); } ?>

    <!-- Main Container -->
    <main id="main-container">