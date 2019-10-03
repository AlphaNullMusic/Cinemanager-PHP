<nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Your Movies</span>
            </h6>
            <ul class="nav flex-column">
            
              <?php if (has_permission('sessions')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="movies.php"){?>active<?php}?>" href="movies.php">Movies and Session Times</a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="labels.php"){?>active<?php}?>" href="labels.php">Label Your Session Times</a>
              </li>
              <?php } ?>
              
              <?php if (has_permission('import_showcase')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="import_showcase.php"){?>active<?php}?>" href="import_showcase.php">Import from Showcase</a>
              </li>
              <?php } ?>
              
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="export_sessions.php"){?>active<?php}?>" href="export_sessions.php">Export Sessions</a>
              </li>
            </ul>
            
            <?php if (has_permission('edit_pages') || has_permission('user_list') || has_permission('newsletters') ||  has_permission('news')) { ?>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Your Website</span>
            </h6>
            
            <ul class="nav flex-column mb-2">
              <?php if (has_permission('edit_pages')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="pages.php"){?>active<?php}?>" href="pages.php">Edit Web Pages</a>
              </li>
              <?php } ?>
              
              <?php if (has_permission('user_list')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="users.php"){?>active<?php}?>" href="users.php">Manage Subscribers</a>
              </li>
              <?php } ?>
              
              <?php if (has_permission('newsletters')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="newsletter_overview.php"){?>active<?php}?>" href="newsletter_overview.php">Manage Newsletters</a>
              </li>
              <?php } ?>
              
              <?php if (has_permission('news')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="news.php"){?>active<?php}?>" href="news.php">Cinema <?php=(!empty($_SESSION['cinema_data']['news_name']))?$_SESSION['cinema_data']['news_name']:'News'?></a>
              </li>
              <?php } ?>
              
              <?php if (has_permission('design_settings')) { ?>
              <li class="nav-item">
                <a class="nav-link <?php if (basename($_SERVER['PHP_SELF'])==="site_settings.php"){?>active<?php}?>" href="site_settings.php">Design Settings</a>
              </li>
              <?php } ?>
              
            </ul>
            <?php } ?>
            
            <?php if (check_cinema() && has_permission('advanced')) { ?>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Advanced Options</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="<?php=$_SERVER['PHP_SELF']?>?clear_cache=true">Clear Cache</a>
              </li>
            </ul>
            <?php } ?>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>General</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link disabled" href="#">Your Details</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.cinemanager.ga/cinema-website-specialists.php">Contact Us</a>
              </li>
            </ul>
          </div>
        </nav>