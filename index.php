<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP OpenWeather API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        #form-container {
            text-align: center;
            margin-top: 20px;
            width: 100%; /* Full width for the form-container */
        }
        form {
            display: inline-block; /* Center the form horizontally */
            text-align: center;
            margin-top: 20px;
        }
        label {
            font-size: 18px;
        }
        input[type="text"] {
            font-size: 16px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        button[type="submit"] {
            font-size: 16px;
            padding: 8px 16px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .weather-container {
            text-align: center;
            margin: 20px auto; /* Center horizontally and apply margin */
            width: max-content; /* Only as wide as the content */
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .weather-row {
            display: flex;
            justify-content: space-between; /* Create a 2-column layout */
            align-items: center;
            text-align: left; /* Align text in left column */
            width: 100%;
            margin-bottom: 10px;
        }
        .weather-info{
            align-self:flex-start;
        }
        .validity-time {
            background-color: greenyellow;
            padding: 0px 10px;
            margin: 0 auto; /* Center horizontally */
            text-align: center; /* Center the text inside the div */
        }

        h2 {
            font-size: 24px;
        }
        p {
            font-size: 18px;
        }
        img.weather-icon {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body>
    <h1>PHP OpenWeather API</h1>

    <div id="form-container">

    <!-- Form for submitting city name to get weather data -->
    <form method="post">
        <label for="city">Enter City Name:</label>
        <input type="text" id="city" name="city" placeholder="City name">
        <button type="submit">Get Weather</button>
    </form>

    </div>

    <?php
    // Function to generate the URL for weather icons
    function getWeatherIconUrl($iconCode) {
        return "./weather-icons/$iconCode.svg";
    }

    // Check if the form has been submitted using POST method
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the city name from the submitted form
        $cityName = $_POST["city"];

        // Check if the city name is not empty
        if (!empty($cityName)) {
            // OpenWeather API key for authentication
            $apiKey = "c90265758d6d690ed02c2c3f3028ca77";
            
            // API URL for fetching weather data
            $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=$apiKey";

            // Request to the OpenWeather API and get the response
            $response = file_get_contents($apiUrl);

            if ($response) {
                // Parse the JSON response from the API
                $data = json_decode($response);

                if ($data) {
                    // Extract relevant weather information from the API response
                    $temperature = $data->main->temp - 273.15; // Convert from Kelvin to Celsius
                    $weatherDescription = $data->weather[0]->description;
                    $weatherIconCode = $data->weather[0]->icon;
                    $validityTime = date("H:i l d F Y", $data->dt); // Convert timestamp 

                    // Display weather information
                    echo "<div class='weather-container'>";
                    /*echo "<div>";
                    echo "<h2>Weather in $cityName</h2>";
                    echo "<p>Temperature: " . number_format($temperature, 2) . " &deg;C</p>";
                    echo "<p>Description: $weatherDescription</p>";
                    echo "<p>Valid until: $validityTime</p>";
                    echo "</div>";
                    
                    // Display the weather icon
                    echo "<img src='" . getWeatherIconUrl($weatherIconCode) . "' alt='Weather Icon' class='weather-icon'>";
                    echo "</div>";*/

                     // First row, two columns
                     echo "<div class='weather-row'>";
                     echo "<div class='weather-info'>";
                     echo "<h2>Weather in $cityName</h2>";
                     echo "<p>Temperature: " . number_format($temperature, 2) . " &deg;C</p>";
                     echo "<p>Description: $weatherDescription</p>";
                     echo "</div>";
                     echo "<img src='" . getWeatherIconUrl($weatherIconCode) . "' alt='Weather Icon' class='weather-icon'>";
                     echo "</div>";
 
                     // Second row, single column
                     echo "<div class='weather-row'>";
                     echo "<div class='validity-time'>";
                     echo "<p>Valid until: $validityTime</p>";
                     echo "</div>";
                     echo "</div>";
                     
                     echo "</div>";


                } else {
                    echo "<p>Error parsing API response.</p>";
                }
            } else {
                echo "<p>Error fetching data from OpenWeather API.</p>";
            }
        } else {
            echo "<p>Please enter a city name.</p>";
        }
    }
    ?>
</body>
</html>
