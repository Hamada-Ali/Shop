<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><?php echo lang('@brand'); ?></a>
        </div>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav">
                <li><a href="categories.php" class="hovering"><?php echo lang('@categories'); ?></a></li>
                <li><a href="items.php" class="hovering"><?php echo lang('@items'); ?></a></li>
                <li><a href="members.php" class="hovering"><?php echo lang('@members'); ?></a></li>
                <li><a href="comments.php" class="hovering"><?php echo lang('@comments'); ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php fetchuser();?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../index.php">Visit Shop</a></li>
                        <li><a href=<?php echo "members.php?action=edit&id=" . $_SESSION['ID']; ?>><?php echo lang('@edit'); ?></a></li>
                        <li><a href="#"><?php echo lang('@setting'); ?></a></li>
                        <li><a href="logout.php"><?php echo lang('@logout'); ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>