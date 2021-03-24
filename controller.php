<?php
// This file contains a bridge between the view and the model and redirects back to the proper page
// with after processing whatever form this code absorbs. This is the C in MVC, the Controller.
//
// Authors: Rick Mercer and Sooyoung Moon
//  
session_start(); // Not needed in Quotes1

require_once './DatabaseAdaptor.php';

$theDBA = new DatabaseAdaptor();

if (isset($_GET['todo']) && $_GET['todo'] === 'getQuotes') {
    $arr = $theDBA->getAllQuotations();
    unset($_GET['todo']);
    echo getQuotesAsHTML($arr);
}


if (isset($_POST['registerUsername']) && isset($_POST['registerPassword'])) {

    $registerUsername = htmlspecialchars($_POST['registerUsername']);
    $registerPassword = htmlspecialchars($_POST['registerPassword']);


    if ($theDBA->checkExistUser($registerUsername)) {
        $_SESSION['registrationError'] = "Account name taken";
        header('Location: register.php');
    } else {
        $theDBA->addUser($registerUsername, $registerPassword);
        unset($_SESSION['user']);
        header('Location: view.php');
    }
}

if (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {

    $loginUsername = htmlspecialchars($_POST['loginUsername']);
    $loginPassword = htmlspecialchars($_POST['loginPassword']);

    if ($theDBA->verifyCredentials($loginUsername, $loginPassword)) {
        $_SESSION['user'] = $loginUsername;
        header('Location: view.php');
    } else {
        $_SESSION['loginError'] = "Invalid credentials.";
        unset($_SESSION['user']);
        header('Location: login.php');
    }
}

if (isset($_POST['logout'])) {
    unset($_SESSION['user']);
    header('Location: view.php');
}


if (isset($_POST['quote']) && isset($_POST['author'])) {
    $quote = htmlspecialchars($_POST['quote']);
    $author = htmlspecialchars($_POST['author']);

    $theDBA->addQuote($quote, $author);
    header('Location: view.php');
}

if (isset($_POST['update'])) {
    if ($_POST['update'] == "delete") {
        $theDBA->deleteQuote($_POST['ID']);
        header('Location: view.php');
    } else if ($_POST['update'] == "increase") {
        $theDBA->increaseRating($_POST['ID']);
        header('Location: view.php');
    } else {
        $theDBA->decreaseRating($_POST['ID']);
        header('Location: view.php');
    }
}

function getQuotesAsHTML($arr)
{
    // TODO 6: Many things. You should have at least two quotes in 
    // table quotes. layout each quote using a combo of PHP and HTML 
    // strings that includes HTML for buttons along with the actual 
    // quote and the author, ~15 PHP statements. This function will 
    // be the most time consuming in Quotes 1. You will
    // need to add css rules to styles.css.  
    $result = '<div class="container-wrapper">';

    if (isset($_SESSION['user'])) {
        $display = '';
    } else {
        $display = 'style = "visibility:hidden"';
    }



    foreach ($arr as $quote) {
        $result .= ' <div class="container"> <div class="quote">';
        $result .= '"' . $quote['quote'] . '" </div> 
        <p class="author"> - ' . $quote['author'] . '<br> </p> 
        <form action="controller.php" method="post">
       
         <input type="hidden" name="ID" value="' . $quote['id'] . '">&nbsp;&nbsp;&nbsp;
       
         <button name="update" value="increase">+</button>
       
         &nbsp;<span id="rating">' . $quote['rating'] . '</span>&nbsp;&nbsp;
       
         <button name="update" value="decrease">-</button>&nbsp;&nbsp;
       
         <button name="update" value="delete"' . $display . '>Delete</button>
       
        </form>
       
       </div>
        ';
        // Add more below


    }
    $result .= '</div>';

    return $result;
}
