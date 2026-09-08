<?php

header("Content-Type: application/json; charset=UTF-8");


// ======================================================
// 1. ONLY ALLOW POST REQUESTS
// ======================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit;
}


// ======================================================
// 2. READ JSON FROM detection.php
// ======================================================

$rawInput = file_get_contents("php://input");

$input = json_decode(
    $rawInput,
    true
);


if (!is_array($input)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request data."
    ]);

    exit;
}


// ======================================================
// 3. GET URL
// ======================================================

$url = trim(
    $input["url"] ?? ""
);


if ($url === "") {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Please enter a URL."
    ]);

    exit;
}


// ======================================================
// 4. NORMALIZE URL
// ======================================================

// If user enters:
// google.com
//
// Convert to:
// http://google.com

if (!preg_match('/^https?:\/\//i', $url)) {

    $url = "http://" . $url;
}


// Validate URL

if (!filter_var($url, FILTER_VALIDATE_URL)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid URL."
    ]);

    exit;
}


// ======================================================
// 5. PROJECT PATH
// ======================================================
//
// Current file:
//
// backend/php/detect_process.php
//
// dirname(__DIR__, 2)
//
// goes back to:
//
// AI-Phishing-Detection-System/
// ======================================================

$projectRoot = dirname(__DIR__, 2);


// Machine learning folder

$machineLearningDir =
    $projectRoot
    . DIRECTORY_SEPARATOR
    . "machine_learning";


// predict.py

$predictScript =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "predict.py";


// Model file

$modelPath =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "models"
    . DIRECTORY_SEPARATOR
    . "phishing_model.pkl";


// ======================================================
// 6. YOUR PYTHON PATH
// ======================================================
//
// From your computer:
//
// C:\Users\Dell\AppData\Local\Programs\Python\Python314\python.exe
// ======================================================

$pythonExe =
    "C:\\Users\\Dell\\AppData\\Local\\Programs\\Python\\Python314\\python.exe";


// ======================================================
// 7. CHECK REQUIRED FILES
// ======================================================

if (!file_exists($pythonExe)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Python executable was not found.",
        "path" => $pythonExe
    ]);

    exit;
}


if (!file_exists($predictScript)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "predict.py was not found.",
        "path" => $predictScript
    ]);

    exit;
}


if (!file_exists($modelPath)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "The trained AI model was not found.",
        "path" => $modelPath
    ]);

    exit;
}


// ======================================================
// 8. RUN PYTHON
// ======================================================
//
// Browser
//   ↓
// detection.php
//   ↓
// detect_process.php
//   ↓
// predict.py
//   ↓
// phishing_model.pkl
//
// proc_open lets us capture:
// stdout = JSON result
// stderr = Python errors
// separately.
// ======================================================

$command = [
    $pythonExe,
    $predictScript,
    $url
];


$descriptorSpec = [

    // STDIN
    0 => [
        "pipe",
        "r"
    ],

    // STDOUT
    1 => [
        "pipe",
        "w"
    ],

    // STDERR
    2 => [
        "pipe",
        "w"
    ]

];


$process = proc_open(

    $command,

    $descriptorSpec,

    $pipes,

    $machineLearningDir,

    null,

    [
        "bypass_shell" => true
    ]

);


// ======================================================
// 9. CHECK IF PYTHON STARTED
// ======================================================

if (!is_resource($process)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to start the AI detection engine."
    ]);

    exit;
}


// We don't send anything through STDIN

fclose($pipes[0]);


// Read Python JSON output

$stdout = stream_get_contents(
    $pipes[1]
);

fclose($pipes[1]);


// Read Python errors separately

$stderr = stream_get_contents(
    $pipes[2]
);

fclose($pipes[2]);


// Get Python exit code

$exitCode = proc_close(
    $process
);


// ======================================================
// 10. PYTHON ERROR
// ======================================================

if ($exitCode !== 0) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "The AI detection engine failed.",
        "python_error" => trim($stderr)
    ]);

    exit;
}


// ======================================================
// 11. CHECK EMPTY RESULT
// ======================================================

$stdout = trim($stdout);


if ($stdout === "") {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "The AI detection engine returned no result.",
        "python_error" => trim($stderr)
    ]);

    exit;
}


// ======================================================
// 12. CONVERT PYTHON JSON
// ======================================================

$result = json_decode(
    $stdout,
    true
);


// ======================================================
// 13. INVALID JSON
// ======================================================

if (!is_array($result)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,

        "message" =>
        "The AI detection engine returned an invalid response.",

        "python_output" =>
        $stdout,

        "python_error" =>
        trim($stderr),

        "json_error" =>
        json_last_error_msg()
    ]);

    exit;
}


// ======================================================
// 14. ERROR RETURNED BY predict.py
// ======================================================

if (
    !isset($result["success"]) ||
    $result["success"] !== true
) {

    http_response_code(500);

    echo json_encode([
        "success" => false,

        "message" =>
        $result["message"]
            ?? "AI prediction failed."
    ]);

    exit;
}


// ======================================================
// 15. SUCCESS
// ======================================================
//
// Return the REAL ML result to detection.php
// ======================================================

echo json_encode(
    $result,
    JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE
);

exit;
