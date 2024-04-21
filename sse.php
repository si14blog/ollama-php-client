<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Function to send a message to Ollama API and get response
function sendMessageToOllama($prompt)
{
    // API endpoint
    $apiUrl = 'http://127.0.0.1:11434/api/generate';

    // Data to be sent to the API
    $data = array(
        'model' => 'tinyllama',
        'prompt' => $prompt
    );

    // Initialize cURL session
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // Set the callback function for streaming
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) {
        $data = json_decode($chunk, true);
        if ($data !== null && isset ($data['response'])) {
            echo "data: " . $data['response'] . "\n\n";
            ob_flush();
            flush(); // Flush the output buffer to send the message immediately
        }
        return strlen($chunk); // Return the length of the chunk to signal cURL to continue
    });

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo "event: error\n";
        echo "data: " . json_encode(array('error' => curl_error($ch))) . "\n\n";
        flush();
        return;
    }

    // Close cURL session
    curl_close($ch);
}

// Example usage
$prompt = "write me a short and simple blog post about php";

//echo 'Me: ' . $prompt;
sendMessageToOllama($prompt);
?>