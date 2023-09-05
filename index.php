<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP OpenWeather API</title>
</head>
<body>
    <h1>PHP OpenWeather API</h1>

    <!-- Form for submitting city name to get weather data -->
    <form method="post">
        <label for="city">Enter City Name:</label>
        <input type="text" id="city" name="city" placeholder="City name">
        <button type="submit">Get Weather</button>
    </form>

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
                    echo "<div>";
                    echo "<h2>Weather in $cityName</h2>";
                    echo "<p>Temperature: " . number_format($temperature, 2) . " &deg;C</p>";
                    echo "<p>Description: $weatherDescription</p>";
                    echo "<p>Valid until: $validityTime</p>";
                    echo "</div>";
                    
                    // Display the weather icon
                    echo "<img src='" . getWeatherIconUrl($weatherIconCode) . "' alt='Weather Icon' class='weather-icon'>";
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
