<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");


// ======================================================
// PHISHGUARD AI
// CNN + RANDOM FOREST URL DETECTION
// ======================================================

error_reporting(0);
ini_set("display_errors", 0);


// ======================================================
// DATABASE CONNECTION
// ======================================================

include "db_connection.php";


// ======================================================
// 1. ONLY ALLOW POST REQUEST
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
// 4. URL LENGTH CHECK
// ======================================================

if (strlen($url) > 2048) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "The URL is too long."
    ]);

    exit;
}


// ======================================================
// 5. NORMALIZE URL
// ======================================================

$predictionURL = $url;


if (
    !preg_match(
        '/^https?:\/\//i',
        $predictionURL
    )
) {

    $predictionURL =
        "https://" . $predictionURL;
}


// ======================================================
// 6. VALIDATE URL
// ======================================================

if (
    !filter_var(
        $predictionURL,
        FILTER_VALIDATE_URL
    )
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid URL."
    ]);

    exit;
}


// ======================================================
// 7. PROJECT PATH
// ======================================================
//
// Current file:
//
// backend/php/detect_process.php
//
// dirname(__DIR__, 2)
//
// gives:
//
// AI-Phishing-Detection-System
// ======================================================

$projectRoot =
    dirname(
        __DIR__,
        2
    );


$machineLearningDir =
    $projectRoot
    . DIRECTORY_SEPARATOR
    . "machine_learning";


// ======================================================
// 8. CNN FILES
// ======================================================

$cnnScript =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "predict_url.py";


$cnnModel =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "models"
    . DIRECTORY_SEPARATOR
    . "phishing_cnn.keras";


$cnnTokenizer =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "models"
    . DIRECTORY_SEPARATOR
    . "cnn_tokenizer.json";


// ======================================================
// 9. RANDOM FOREST FILES
// ======================================================

$rfScript =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "predict_rf.py";


$rfModel =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "models"
    . DIRECTORY_SEPARATOR
    . "phishing_model.pkl";


$rfPython =
    $machineLearningDir
    . DIRECTORY_SEPARATOR
    . "rf_env"
    . DIRECTORY_SEPARATOR
    . "Scripts"
    . DIRECTORY_SEPARATOR
    . "python.exe";


// ======================================================
// 10. PYTHON 3.10 FOR CNN
// ======================================================
//
// TensorFlow 2.15.1 is installed in Python 3.10.
//
// DO NOT use Python 3.14 for CNN.
// ======================================================

$cnnPython =
    "C:\\Users\\Dell\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";


// ======================================================
// 11. CHECK CNN PYTHON
// ======================================================

if (!file_exists($cnnPython)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Python 3.10 executable was not found.",
        "path" => $cnnPython
    ]);

    exit;
}


// ======================================================
// 12. CHECK CNN SCRIPT
// ======================================================

if (!file_exists($cnnScript)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "predict_url.py was not found.",
        "path" => $cnnScript
    ]);

    exit;
}


// ======================================================
// 13. CHECK CNN MODEL
// ======================================================

if (!file_exists($cnnModel)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "CNN model was not found.",
        "path" => $cnnModel
    ]);

    exit;
}


// ======================================================
// 14. CHECK CNN TOKENIZER
// ======================================================

if (!file_exists($cnnTokenizer)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "CNN tokenizer was not found.",
        "path" => $cnnTokenizer
    ]);

    exit;
}


// ======================================================
// 15. CHECK RANDOM FOREST PYTHON
// ======================================================

if (!file_exists($rfPython)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Random Forest Python environment was not found.",
        "path" => $rfPython
    ]);

    exit;
}


// ======================================================
// 16. CHECK RANDOM FOREST SCRIPT
// ======================================================

if (!file_exists($rfScript)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "predict_rf.py was not found.",
        "path" => $rfScript
    ]);

    exit;
}


// ======================================================
// 17. CHECK RANDOM FOREST MODEL
// ======================================================

if (!file_exists($rfModel)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Random Forest model was not found.",
        "path" => $rfModel
    ]);

    exit;
}


// ======================================================
// FUNCTION: RUN PYTHON PROCESS
// ======================================================

function runPythonPrediction(
    $pythonExe,
    $script,
    $url,
    $workingDirectory
) {

    $command = [
        $pythonExe,
        $script,
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
        $workingDirectory,
        null,
        [
            "bypass_shell" => true
        ]
    );


    if (!is_resource($process)) {

        return [
            "success" => false,
            "message" => "Unable to start Python prediction process.",
            "python_error" => ""
        ];
    }


    // No STDIN required

    fclose(
        $pipes[0]
    );


    // Read standard output

    $stdout =
        stream_get_contents(
            $pipes[1]
        );


    fclose(
        $pipes[1]
    );


    // Read Python errors

    $stderr =
        stream_get_contents(
            $pipes[2]
        );


    fclose(
        $pipes[2]
    );


    // Close process

    $exitCode =
        proc_close(
            $process
        );


    $stdout =
        trim($stdout);


    $stderr =
        trim($stderr);


    if ($exitCode !== 0) {

        return [
            "success" => false,
            "message" => "Python prediction failed.",
            "python_error" => $stderr,
            "python_output" => $stdout
        ];
    }


    if ($stdout === "") {

        return [
            "success" => false,
            "message" => "Python prediction returned no result.",
            "python_error" => $stderr
        ];
    }


    // Convert JSON

    $result =
        json_decode(
            $stdout,
            true
        );


    if (!is_array($result)) {

        return [
            "success" => false,
            "message" => "Python returned invalid JSON.",
            "python_output" => $stdout,
            "python_error" => $stderr,
            "json_error" => json_last_error_msg()
        ];
    }


    return $result;
}


// ======================================================
// 18. RUN CNN
// ======================================================

$cnnResult = runPythonPrediction(
    $cnnPython,
    $cnnScript,
    $predictionURL,
    $machineLearningDir
);


// ======================================================
// 19. CHECK CNN RESULT
// ======================================================

if (
    !isset($cnnResult["success"]) ||
    $cnnResult["success"] !== true
) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
        $cnnResult["message"]
            ??
            "CNN prediction failed.",
        "cnn_error" =>
        $cnnResult["python_error"]
            ??
            ""
    ]);

    exit;
}


// ======================================================
// 20. GET CNN DATA
// ======================================================

$cnnPrediction =
    intval(
        $cnnResult["prediction"] ?? 0
    );


$cnnProbability =
    floatval(
        $cnnResult["probability"] ?? 0
    );


$cnnConfidence =
    floatval(
        $cnnResult["confidence"] ?? 0
    );


$cnnRawResult =
    strtoupper(
        trim(
            $cnnResult["result"] ?? ""
        )
    );


// ======================================================
// 21. NORMALIZE CNN RESULT
// ======================================================
//
// CNN may return:
//
// LEGITIMATE
// PHISHING
//
// Website/database uses:
//
// Safe
// Phishing
// ======================================================

if (
    $cnnRawResult === "LEGITIMATE"
) {

    $cnnStatus = "Safe";
} elseif (
    $cnnRawResult === "PHISHING"
) {

    $cnnStatus = "Phishing";
} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "CNN returned an unknown classification.",
        "cnn_result" => $cnnRawResult
    ]);

    exit;
}


// ======================================================
// 22. LIMIT CNN CONFIDENCE
// ======================================================

$cnnConfidence =
    max(
        0,
        min(
            100,
            $cnnConfidence
        )
    );


// ======================================================
// 23. RUN RANDOM FOREST
// ======================================================

$rfResult = runPythonPrediction(
    $rfPython,
    $rfScript,
    $predictionURL,
    $machineLearningDir
);


// ======================================================
// 24. CHECK RANDOM FOREST RESULT
// ======================================================

if (
    !isset($rfResult["success"]) ||
    $rfResult["success"] !== true
) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
        $rfResult["message"]
            ??
            "Random Forest prediction failed.",
        "rf_error" =>
        $rfResult["python_error"]
            ??
            ""
    ]);

    exit;
}


// ======================================================
// 25. GET RANDOM FOREST DATA
// ======================================================

$rfPrediction =
    intval(
        $rfResult["prediction"] ?? 0
    );


$rfProbability =
    floatval(
        $rfResult["probability"] ?? 0
    );


$rfConfidence =
    floatval(
        $rfResult["confidence"] ?? 0
    );


$rfRawResult =
    trim(
        $rfResult["result"] ?? ""
    );


// ======================================================
// 26. NORMALIZE RANDOM FOREST RESULT
// ======================================================

if (
    strtolower($rfRawResult) === "phishing"
) {

    $rfStatus = "Phishing";
} else {

    $rfStatus = "Safe";
}


// ======================================================
// 27. LIMIT RF CONFIDENCE
// ======================================================

$rfConfidence =
    max(
        0,
        min(
            100,
            $rfConfidence
        )
    );


// ======================================================
// 28. MODEL AGREEMENT
// ======================================================

$modelAgreement =
    (
        $cnnStatus === $rfStatus
    );


// ======================================================
// 29. CALCULATE INDIVIDUAL RISK
// ======================================================
//
// If model says Phishing:
//
// risk = confidence
//
// If model says Safe:
//
// risk = 100 - confidence
// ======================================================

if ($cnnStatus === "Phishing") {

    $cnnRisk =
        $cnnConfidence;
} else {

    $cnnRisk =
        100 - $cnnConfidence;
}


if ($rfStatus === "Phishing") {

    $rfRisk =
        $rfConfidence;
} else {

    $rfRisk =
        100 - $rfConfidence;
}


// ======================================================
// 30. COMBINED RISK SCORE
// ======================================================

$riskScore =
    round(
        (
            $cnnRisk +
            $rfRisk
        ) / 2,
        2
    );


// Keep between 0 and 100

$riskScore =
    max(
        0,
        min(
            100,
            $riskScore
        )
    );


// ======================================================
// 31. FINAL STATUS
// ======================================================
//
// If both models agree:
//
// use their common result.
//
// If they disagree:
//
// mark as phishing for caution.
// ======================================================

if ($modelAgreement) {

    $finalStatus =
        $cnnStatus;
} else {

    $finalStatus =
        "Phishing";
}


// ======================================================
// 32. RISK LEVEL
// ======================================================

if ($riskScore < 20) {

    $riskLevel = "LOW RISK";
} elseif ($riskScore < 50) {

    $riskLevel = "MEDIUM RISK";
} elseif ($riskScore < 75) {

    $riskLevel = "HIGH RISK";
} else {

    $riskLevel = "VERY HIGH RISK";
}


// ======================================================
// 33. MAIN MESSAGE
// ======================================================

if ($modelAgreement) {

    if ($finalStatus === "Safe") {

        $message =
            "Both AI models classify this URL as safe. However, users should still verify the website domain before entering sensitive information.";
    } else {

        $message =
            "Both AI models classify this URL as potentially phishing. Avoid entering passwords, banking information or other sensitive information.";
    }
} else {

    $message =
        "The AI models produced different classifications. Additional caution is recommended before visiting or entering sensitive information.";
}


// ======================================================
// 34. DETECTION DETAILS
// ======================================================

$reasons = [];


// CNN reason

if ($cnnStatus === "Safe") {

    $reasons[] =
        "The CNN model classified the URL as Safe.";
} else {

    $reasons[] =
        "The CNN model classified the URL as Phishing.";
}


$reasons[] =
    "CNN confidence: "
    .
    number_format(
        $cnnConfidence,
        2
    )
    .
    "%.";


// RF reason

if ($rfStatus === "Safe") {

    $reasons[] =
        "The Random Forest model classified the URL as Safe.";
} else {

    $reasons[] =
        "The Random Forest model classified the URL as Phishing.";
}


$reasons[] =
    "Random Forest confidence: "
    .
    number_format(
        $rfConfidence,
        2
    )
    .
    "%.";


// Agreement

if ($modelAgreement) {

    $reasons[] =
        "Both AI models agree on the classification.";
} else {

    $reasons[] =
        "The AI models disagree on the classification.";
}


// Security recommendation

if ($finalStatus === "Safe") {

    $reasons[] =
        "Always verify the domain before entering passwords, banking information or personal details.";
} else {

    $reasons[] =
        "Do not enter passwords, banking information or personal details unless the website has been independently verified.";
}


// ======================================================
// 35. SAVE TO url_history
// ======================================================
//
// Existing table:
//
// url_history
//
// Columns:
//
// HistoryID
// UserID
// URL
// PredictionResult
// ConfidenceScore
// CheckedDate
//
// We keep UserID internally because your current
// database table uses UserID.
// ======================================================

if (
    isset($_SESSION["UserID"]) &&
    is_numeric($_SESSION["UserID"])
) {

    $userID =
        intval(
            $_SESSION["UserID"]
        );


    $historyURL =
        $predictionURL;


    // Save the FINAL website classification.
    //
    // Only these values are stored:
    //
    // Safe
    // Phishing

    $predictionForDatabase =
        $finalStatus;


    // Use combined confidence

    $historyConfidence =
        round(
            100 - $riskScore,
            2
        );


    $historySQL = "

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


    if ($historyStmt) {

        $historyStmt->bind_param(

            "issd",

            $userID,

            $historyURL,

            $predictionForDatabase,

            $historyConfidence

        );


        $historyStmt->execute();


        $historyStmt->close();
    }
}


// ======================================================
// 36. RETURN COMPLETE RESULT
// ======================================================

echo json_encode(

    [

        "success" => true,


        // ------------------------------------------------
        // URL
        // ------------------------------------------------

        "url" =>
        $predictionURL,


        // ------------------------------------------------
        // FINAL RESULT
        // ------------------------------------------------

        "status" =>
        strtolower(
            $finalStatus
        ),

        "result" =>
        $finalStatus,

        "risk_score" =>
        $riskScore,

        "risk_level" =>
        $riskLevel,

        "message" =>
        $message,


        // ------------------------------------------------
        // CNN
        // ------------------------------------------------

        "cnn" => [

            "model" =>
            "CNN",

            "prediction" =>
            $cnnPrediction,

            "result" =>
            $cnnStatus,

            "probability" =>
            round(
                $cnnProbability,
                6
            ),

            "confidence" =>
            round(
                $cnnConfidence,
                2
            ),

            "risk_score" =>
            round(
                $cnnRisk,
                2
            )

        ],


        // ------------------------------------------------
        // RANDOM FOREST
        // ------------------------------------------------

        "random_forest" => [

            "model" =>
            "Random Forest",

            "prediction" =>
            $rfPrediction,

            "result" =>
            $rfStatus,

            "probability" =>
            round(
                $rfProbability,
                6
            ),

            "confidence" =>
            round(
                $rfConfidence,
                2
            ),

            "risk_score" =>
            round(
                $rfRisk,
                2
            )

        ],


        // ------------------------------------------------
        // MODEL AGREEMENT
        // ------------------------------------------------

        "model_agreement" =>
        $modelAgreement,


        // ------------------------------------------------
        // DETECTION DETAILS
        // ------------------------------------------------

        "reasons" =>
        $reasons

    ],

    JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE

);


exit;
