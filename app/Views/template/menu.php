  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <i class="nav-icon fas fa-map-marked-alt fa-2x brand-image img-circle elevation-3" style="opacity: .8"></i>
      <span class="brand-text font-weight-light"><?php echo APP_NAME; ?></span>
    </a>
<?php
    $user=json_decode(json_encode($user));
?>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/public/assets/img/avatar.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" id="usr-logged-in"><?php echo "{$user->lastname} {$user->firstname}";?>
        </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php 
            
            $menuItems = [];
            $category_icon = "";
          // Initialize an empty associative array to group items by 'menu_category'
          $menuGroups = [];

          foreach ($user->menus as $menuItem) {
              $category = $menuItem->menu_category;

              if (!isset($menuGroups[$category])) {
                  $menuGroups[$category] = [];
              }

              $menuGroups[$category][] = [
                  'menu_id'    => $menuItem->menu_id,
                  'menu_name'  => $menuItem->menu_name,
                  'url'        => $menuItem->url,
                  'type'       => $menuItem->type,
                  'icon'       => $menuItem->icon,
                  'order'      => $menuItem->order,
                  'cat_icon'   => $menuItem->category_icon,
              ];
          }
          // $menuGroups now contains the menu items grouped by 'menu_category'
          foreach ($menuGroups as $key => $value) { ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas <?php echo $value[0]['cat_icon'] ?? 'fa-folder';?>"></i>
              <p>
                <?php echo $key;?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <?php if(is_array($value) && !empty($value)){ 
              ?>
              <ul class="nav nav-treeview ml-3"><?php
                foreach ($value as $link) { 
                  if($link['type']=='url'){
                  ?>
                  <li class="nav-item">
                    <a href="<?php echo $link['url'];?>" class="nav-link" data-widget="iframe" data-title="<?php echo $link['menu_name'];?>">
                      <i class="<?php echo $link['icon'];?>"></i>
                      <p>
                        <?php echo $link['menu_name'];?>
                      </p>
                    </a>
                  </li>
               <?php  
                  }             
              } ?>
              </ul>

            <?php } ?>
          </li>
         <?php }
            ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
