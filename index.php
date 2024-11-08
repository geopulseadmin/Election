<?php
session_start();
$_SESSION['from_index'] = true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election</title>
    <style>
        /* Background styling */
        body {
            font-family: Plus Jakarta Sans;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
            transition: background-color 1s ease;
        }

        /* Animation for logo */
        @keyframes imageAppear {
            0% {
                opacity: 0;
                transform: scale(4);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Animation for fading in and out */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        /* Logo styling */
        .responsive-image {
            max-width: 100%;
            height: auto;
            width: 53%;
            animation: imageAppear 1s ease-in-out 1s forwards;
            opacity: 0;
        }

        /* Loading overlay styling */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
            opacity: 0;
            visibility: hidden;
            /* Hidden initially */
            transition: opacity 1s ease, visibility 0s 1s;
            /* Delay visibility change until fade completes */
        }

        .loader-container {
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Loader icon styling */
        .loader {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        /* Loading text styling */
        .loading-text {
            font-size: 1em;
            color: white;
            font-weight: normal;
        }

        /* Geopulse text styling */
        .geopulse-text {

            color: white;
            font-size: 40px;

            font-weight: 700;
            word-wrap: break-word;
            opacity: 0;
            transition: opacity 1s ease;
            /* Fade in Geopulse text */
        }

        .geopulse-text img {
            width: 32px;
        }

        /* Spin animation for the loader */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Hidden class to fade out logo */
        .fade-out {
            animation: fadeOut 1s forwards;
        }
    </style>
</head>

<body>
    <!-- Logo -->
    <img src="./image/Clip_path_group.svg" alt="Centered Image" class="responsive-image" id="logo">

    <!-- Loading overlay with loader and text -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader-container">
            <div class="loader"></div>
            <div class="loading-text">Loading</div>
        </div>
    </div>

    <!-- Geopulse text hidden initially -->
    <div class="geopulse-text" id="geopulseText"><img src="./image/Clip_path_group.svg" alt=""> Geopulse</div>

    <script>
        // Change background color after a delay
        setTimeout(() => {
            document.body.style.backgroundColor = '#3b82f6';
        }, 200);

        // Fade out the logo and show the loading overlay
        setTimeout(() => {
            const logo = document.getElementById('logo');
            const loadingOverlay = document.getElementById('loadingOverlay');
            logo.classList.add('fade-out'); // Start fading out the logo
            loadingOverlay.style.visibility = 'visible'; // Make loader visible
            loadingOverlay.style.opacity = '1'; // Fade in the overlay

            // Remove the logo from the DOM after it fades out
            setTimeout(() => {
                logo.style.display = 'none';
            }, 1000); // Adjust to match the fade-out duration
        }, 3000); // Adjust time as needed

        // Hide the loader and show Geopulse text after 2 seconds
        setTimeout(() => {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const geopulseText = document.getElementById('geopulseText');
            loadingOverlay.style.opacity = '0'; // Fade out the loader
            loadingOverlay.style.visibility = 'hidden'; // Hide loader after fading out
            geopulseText.style.opacity = '1'; // Fade in Geopulse text
        }, 5000); // 2 seconds after loader appears
        // After 2 seconds, redirect to a new landing page (e.g., "landing-page.html")
        setTimeout(() => {
            window.location.href = 'landing.php'; // Change this URL to your desired landing page
        }, 7000); 
    </script>
</body>

</html>