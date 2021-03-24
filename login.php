<!-- 
This code will appear when the Login menu item is chosen in the Final Exam.

This form will be absorbed by the controller.

Authors: Rick Mercer and Sooyoung Moon
-->
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <?php
    session_start();
    ?>

    <h3>Login</h3>
    <form autocomplete="off" action="controller.php" method="post">
        <div class="loginContainer">
            <input type="text" name="loginUsername" placeholder='Username' required>
            <br>
            <input type="text" name="loginPassword" placeholder='Password' required>
            <br><br>
            <input type="submit" value="login"> <br>
            <?php
            // This is not needed in Quotations 1.  This code will be needed to show
            // errors later in a multi-page website when an account name already exists.
            if (isset($_SESSION['loginError']))
                echo $_SESSION['loginError'];
            unset($_SESSION['loginError']);
            ?>

        </div>

    </form>