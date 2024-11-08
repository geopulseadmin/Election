<?php
session_start();
if (!isset($_SESSION['from_index']) || $_SESSION['from_index'] !== true) {
    header('Location: index.php');
    exit();
}
// Clear the session variable after validating
unset($_SESSION['from_index']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoPulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pompiere&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
            overflow: hidden;
            /*padding: 10px;*/
            width: 100%;
            height: 100%;
            position: relative;
            background: #3B82F6;
            margin: 0;
            /*overflow: hidden; */
        }

        /* Define the keyframes for the upward animation */
        @keyframes moveUp {
            0% {
                transform: translateY(100vh);
                /* Start from bottom off-screen */
            }

            100% {
                transform: translateY(0);
                /* End at the actual position */
            }
        }

        /* Apply the moveUp animation to the body */
        body {
            animation: moveUp 2.5s ease-in-out;
            /* Increased duration to 2.5s */
        }

        .outer-circle {
            width: 100vw;
            position: relative;
            box-shadow: 0px -4px 6px rgba(0, 0, 0, 0.25);
            background-color: #fff;
            height: 95vh;
            top: 93px;
            border-start-start-radius: 100px;
            border-start-end-radius: 100px;
            animation: moveUp 2.5s ease-in-out;
            /* Increased duration */
        }

        .geopulse-container {
            left: 84px;
            top: 80px;
            position: absolute;
            justify-content: flex-start;
            align-items: center;
            gap: 12px;
            display: inline-flex;
            animation: moveUp 2.5s ease-in-out;
            /* Increased duration */
        }

        .geopulse-text {
            color: white;
            font-size: 27px;
            text-align: center;
            font-family: Plus Jakarta Sans;
            font-weight: 700;
            word-wrap: break-word;
            margin-top: -56px;
            animation: moveUp 3s ease-in-out;
            /* Increased duration */
        }

        .geopulse-text img {
            width: 26px;
        }

        .subtext {
            left: 69px;
            position: absolute;
            top: 89vh;
            text-align: center;
            color: black;
            font-size: 16px;
            font-family: Montserrat;
            font-weight: 500;
            word-wrap: break-word;
            animation: moveUp 4s ease-in-out;
            /* Increased duration */
        }

        .get-started-button {
            width: 90vw;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 8px;
            padding-bottom: 8px;
            left: 2vw;
            top: 83vh;
            position: absolute;
            opacity: 1;
            background: #3B82F6;
            box-shadow: 0px 2px 6px 2px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            justify-content: center;
            align-items: center;
            gap: 10px;
            display: inline-flex;
            z-index: 99999;
            font-size: 10px;
            animation: moveUp 4s ease-in-out;
            /* Increased duration */
        }

        .get-started-text {
            text-align: center;
            color: white;
            font-size: 14px;
            font-family: Montserrat;
            font-weight: 500;
            text-transform: uppercase;
            word-wrap: break-word;
            text-decoration: none;
        }

        .left-shape {
            position: absolute;
            top: 20vh;
            left: 30vw;
            animation: moveUp 3s ease-in-out;
            /* Increased duration */
        }

        .left-shape img {
            width: 113px;
            height: 317px;
        }

        .center-shape img,
        .right-shape img {
            width: 80px;
        }

        .center-shape {
            position: absolute;
            top: 46vh;
            left: 8vw;
            animation: moveUp 3.5s ease-in-out;
            /* Increased duration */
        }

        .right-shape {
            position: absolute;
            top: 46vh;
            left: 66vw;
            animation: moveUp 3.5s ease-in-out;
            /* Increased duration */
        }

        .vote-india {
            height: 120px;
            left: 20vh;
            top: 69vh;
            position: absolute;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            gap: 9px;
            display: inline-flex;
            animation: moveUp 4s ease-in-out;
            /* Increased duration */
        }

        .vote-india>.vote,
        .vote-india>.india {
            align-self: stretch;
            text-align: center;
            font-size: 30px;
            font-family: Pompiere sans-serif;
            font-weight: 400;
            word-wrap: break-word;
        }

        .vote-india>.vote {
            color: #FF7300;
        }

        .vote-india>.india {
            color: #004AAD;
        }
    </style>
</head>

<body>
    <div class="outer-circle"></div>

    <div class="geopulse-container">
        <div class="geopulse-logo">

        </div>
        <div class="geopulse-text"> <img src="image/Clip_path_group.svg" alt=""> GeoPulse</div>
    </div>

    <div class="subtext">Indian Commission of India</div>

    <div class="get-started-button">
        <a href="main.php" class="get-started-text">Get Started</a>
        <!-- <a class="get-started-text">Get Started</a> -->
    </div>

    <div class="left-shape">
        <img src="image/Hand1.png" alt="">
    </div>

    <div class="right-shape">
        <img src="image/Hand2.png" alt="">
    </div>

    <div class="center-shape">
        <img src="image/Hand3.png" alt="">
    </div>

    <div class="vote-india">
        <div class="vote">VOTE</div>
        <div class="india">INDIA</div>
    </div>

</body>

</html>