<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        main {
            max-width: 640px;
            margin-inline: auto;
        }
    </style>
</head>

<body>
    <main>
        <br>
        <div id="response"></div>
        <script>
            const responseElement = document.getElementById('response');

            // Function to handle SSE
            function handleSSE(event) {
                const data = event.data;
                // Check if the incoming data is a dot
                if (data === '.') {
                    // If it's a dot, append a line break after it
                    responseElement.innerHTML += '.<br><br>';
                } else {
                    // If it's not a dot, append the data as usual
                    responseElement.innerHTML += data;
                }
            }

            // Create EventSource object
            const eventSource = new EventSource('sse.php');

            // Listen for SSE messages
            eventSource.onmessage = handleSSE;

            // Handle errors
            eventSource.onerror = function (event) {
                console.error('EventSource failed:', event);
                eventSource.close();
            };
        </script>




    </main>

</body>

</html>