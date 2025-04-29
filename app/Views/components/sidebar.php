<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard for admin -->
        <?php if (session()->get('role') == 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'admin') ? "active" : "collapsed" ?>" href="<?= base_url('admin') ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Admin Nav -->
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'users') ? "active" : "collapsed" ?>" href="<?= base_url('users') ?>">
                    <i class="bi bi-person"></i>
                    <span>Users</span>
                </a>
            </li><!-- End Users Nav -->
        <?php } ?>

        <!-- Dashboard for user -->
        <?php if (session()->get('role') == 'user') { ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'user') ? "active" : "collapsed" ?>" href="<?= base_url('user') ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard User Nav -->
        <?php } ?>

        <!-- Users menu for admin -->
        <?php if (session()->get('role') == 'admin') { ?>

        <?php } ?>

    </ul>

</aside><!-- End Sidebar-->
