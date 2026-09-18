<?php

session_start();


header(
    "Content-Type: application/json; charset=UTF-8"
);



// ======================================================
// DATABASE CONNECTION
// ======================================================

include "db_connection.php";




// ======================================================
// 1. ONLY ALLOW POST REQUESTS
// ======================================================


if (
    $_SERVER["REQUEST_METHOD"] !== "POST"
) {


    http_response_code(405);


    echo json_encode([

        "success" => false,

        "message" =>
        "Invalid request method."

    ]);


    exit;
}






// ======================================================
// 2. READ JSON FROM detection.php
// ======================================================


$rawInput =
    file_get_contents(
        "php://input"
    );



$input =
    json_decode(
        $rawInput,
        true
    );





if (
    !is_array($input)
) {


    http_response_code(400);


    echo json_encode([

        "success" => false,

        "message" =>
        "Invalid request data."

    ]);


    exit;
}







// ======================================================
// 3. GET URL
// ======================================================


$url =
    trim(
        $input["url"] ?? ""
    );





if (
    $url === ""
) {


    http_response_code(400);


    echo json_encode([

        "success" => false,

        "message" =>
        "Please enter a URL."

    ]);


    exit;
}








// ======================================================
// 4. NORMALIZE URL
// ======================================================


if (
    !preg_match(
        '/^https?:\/\//i',
        $url
    )
) {


    $url =
        "http://" . $url;
}






// Validate URL


if (
    !filter_var(
        $url,
        FILTER_VALIDATE_URL
    )
) {


    http_response_code(400);


    echo json_encode([

        "success" => false,

        "message" =>
        "Please enter a valid URL."

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
// AI-Phishing-Detection-System/
// ======================================================


$projectRoot =
    dirname(
        __DIR__,
        2
    );




// Machine Learning folder

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
// 6. PYTHON PATH
// ======================================================


$pythonExe =

    "C:\\Users\\Dell\\AppData\\Local\\Programs\\Python\\Python314\\python.exe";








// ======================================================
// 7. CHECK REQUIRED FILES
// ======================================================


if (
    !file_exists($pythonExe)
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "Python executable was not found.",

        "path" =>
        $pythonExe

    ]);


    exit;
}





if (
    !file_exists($predictScript)
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "predict.py was not found.",

        "path" =>
        $predictScript

    ]);


    exit;
}





if (
    !file_exists($modelPath)
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "The trained AI model was not found.",

        "path" =>
        $modelPath

    ]);


    exit;
}








// ======================================================
// 8. RUN PYTHON AI MODEL
// ======================================================



$command = [

    $pythonExe,

    $predictScript,

    $url

];





$descriptorSpec = [


    0 => [

        "pipe",

        "r"

    ],


    1 => [

        "pipe",

        "w"

    ],


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






if (
    !is_resource($process)
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "Unable to start AI detection engine."

    ]);


    exit;
}






// No STDIN input

fclose(
    $pipes[0]
);






// Python output

$stdout =
    stream_get_contents(
        $pipes[1]
    );


fclose(
    $pipes[1]
);






// Python errors

$stderr =
    stream_get_contents(
        $pipes[2]
    );


fclose(
    $pipes[2]
);






// Exit code

$exitCode =
    proc_close(
        $process
    );







// ======================================================
// 9. PYTHON ERROR
// ======================================================


if (
    $exitCode !== 0
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "AI detection engine failed.",

        "python_error" =>
        trim($stderr)

    ]);


    exit;
}






// ======================================================
// 10. EMPTY RESULT
// ======================================================


$stdout =
    trim(
        $stdout
    );




if (
    $stdout === ""
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "AI engine returned no result.",

        "python_error" =>
        trim($stderr)

    ]);


    exit;
}

// ======================================================
// 11. CONVERT PYTHON JSON RESULT
// ======================================================


$result = json_decode(

    $stdout,

    true

);







// ======================================================
// 12. INVALID JSON
// ======================================================


if (
    !is_array($result)
) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
        "AI engine returned invalid response.",

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
// 13. CHECK AI RESULT
// ======================================================


if (

    !isset($result["success"])

    ||

    $result["success"] !== true

) {


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>

        $result["message"]

            ??

            "AI prediction failed."

    ]);


    exit;
}








// ======================================================
// 14. SAVE DETECTION HISTORY
// ======================================================



if (

    isset($_SESSION["UserID"])

) {


    $userID =

        $_SESSION["UserID"];




    $detectedURL =

        $result["url"]

        ??

        $url;





    $predictionResult =

        $result["status"]

        ??

        "Unknown";





    $confidenceScore =

        $result["ai_confidence"]

        ??

        0;







    $historySQL =

        "
    INSERT INTO url_history

    (

        UserID,

        URL,

        PredictionResult,

        ConfidenceScore

    )

    VALUES

    (

        ?,

        ?,

        ?,

        ?

    )
    ";






    $historyStmt =

        $conn->prepare(

            $historySQL

        );






    if (

        $historyStmt

    ) {



        $historyStmt->bind_param(

            "issd",

            $userID,

            $detectedURL,

            $predictionResult,

            $confidenceScore

        );





        $historyStmt->execute();





        $historyStmt->close();
    }
}

// ======================================================
// 15. RETURN AI RESULT TO detection.php
// ======================================================


echo json_encode(

    $result,

    JSON_UNESCAPED_SLASHES
        |
        JSON_UNESCAPED_UNICODE

);



exit;
