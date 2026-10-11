
<?php

// =====================================
// IT03-03 - MODEL AGREEMENT TESTING
// =====================================

// Reproduces the decision logic used
// by detect_process.php.

function testClassification($cnnStatus, $rfStatus)
{
    $modelAgreement = ($cnnStatus === $rfStatus);

    if ($modelAgreement) {
        $finalStatus = $cnnStatus;
    } else {
        $finalStatus = "Phishing";
    }

    return $finalStatus;
}


// =====================================
// TEST CASES
// =====================================

$testCases = [

    [
        "id" => "IT03-03-A",
        "cnn" => "Safe",
        "rf" => "Safe",
        "expected" => "Safe"
    ],

    [
        "id" => "IT03-03-B",
        "cnn" => "Phishing",
        "rf" => "Phishing",
        "expected" => "Phishing"
    ],

    [
        "id" => "IT03-03-C",
        "cnn" => "Safe",
        "rf" => "Phishing",
        "expected" => "Phishing"
    ],

    [
        "id" => "IT03-03-D",
        "cnn" => "Phishing",
        "rf" => "Safe",
        "expected" => "Phishing"
    ]

];


// =====================================
// EXECUTE TESTS
// =====================================

echo "<h2>IT03-03 - Model Agreement Testing</h2>";

foreach ($testCases as $test) {

    $actual = testClassification(
        $test["cnn"],
        $test["rf"]
    );

    $status = ($actual === $test["expected"])
        ? "PASS"
        : "FAIL";

    echo "<p>";

    echo "<strong>" . $test["id"] . "</strong><br>";

    echo "CNN Prediction: " . $test["cnn"] . "<br>";

    echo "Random Forest Prediction: " . $test["rf"] . "<br>";

    echo "Expected Result: " . $test["expected"] . "<br>";

    echo "Actual Result: " . $actual . "<br>";

    echo "Status: <strong>" . $status . "</strong>";

    echo "</p><hr>";
}

?>
