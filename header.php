<nav class="navbar navbar-inverse navbar-fixed-top">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
            <li> <a href="index.php"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a> </li>
            <li> <a href="about.php"><span class="glyphicon glyphicon-comment"></span>&nbsp;About</a> </li>
            <li> <a href="guide.php"><span class="glyphicon glyphicon-alert"></span>&nbsp;Guide</a> </li>
            <li> <a href="email.php"><span class="glyphicon glyphicon-envelope"></span>&nbsp;Email Writing</a> </li>
        <?php
            if (isset($_SESSION['aotemail_username']) and isset($_SESSION['aotemail_student'])){
        ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SESSION['aotemail_username'];?>
                <span class="caret"></span>&nbsp;&nbsp;</a>
                <ul class="dropdown-menu">
                    <li><a href="#">View Grades</a></li>
                    <li><a href="#">View Profile</a></li>
                    <li><a href="#">Edit Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
        <?php
            }
            elseif(isset($_SESSION['aotemail_username']) and isset($_SESSION['aotemail_admin'])){
        ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-cog"></span>&nbsp;Email Settings
                <span class="caret"></span>&nbsp;&nbsp;</a>
                <ul class="dropdown-menu">
                    <li> <a href="addEmail.php"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add Emails</a> </li>
                    <li> <a href="modifyEmail.php"><span class="glyphicon glyphicon-repeat"></span>&nbsp;Modify Emails</a> </li>
                    <li> <a href="deleteEmail.php"><span class="glyphicon glyphicon-remove"></span>&nbsp;Delete Emails</a> </li>
                    <li> <a href="changeEmailTime.php"><span class="glyphicon glyphicon-time"></span>&nbsp;Change Time Limit</a> </li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SESSION['aotemail_username'];?>
                <span class="caret"></span>&nbsp;&nbsp;</a>
                <ul class="dropdown-menu">
                    <li><a href="addAdmin.php">Add Admin</a></li>
                    <li><a href="editStudent.php">Students</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
        <?php
            }
            else{
        ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="register.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Register</a></li>
            <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Login&nbsp;&nbsp;</a></li>
        </ul>
        <?php
            }
        ?>
    </div>
</nav>