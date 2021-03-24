<!-- 
This code will appear when the add Quote menu item is chosen in the Final Exam.

This form will be absorbed by the controller.

Authors: Rick Mercer and Sooyoung Moon
-->
<!DOCTYPE html>
<html>

<head>
    <title>Add Quote</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <?php
    session_start();
    ?>

    <h3>Add a Quote</h3>
    <form autocomplete="off" action="controller.php" method="post">
        <div class="registerContainer">
            <textarea name="quote" placeholder='Enter new quote' rows="5" cols="100" required></textarea>
            <br>
            <input type="text" name="author" placeholder='Author' required>
            <br><br>
            <input type="submit" value="Add Quote"> <br>
            <?php
            if (isset($_SESSION['quoteError']))
                echo $_SESSION['quoteError'];
            unset($_SESSION['quoteError']);
            ?>

        </div>

    </form>