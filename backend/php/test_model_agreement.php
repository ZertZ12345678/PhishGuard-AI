
<?php

// WB03 - Structural Testing
// Model Agreement and Disagreement Logic

function testClassification($cnnStatus, $rfStatus)
{
    $modelAgreement = ($cnnStatus === $rfStatus);

    // Same decision logic as detect_process.php
    if ($modelAgreement) {
        $finalStatus = $cnnStatus;
    } else {
        $finalStatus = "Phishing";
    }

    return $finalStatus;
}

// Controlled test cases
$testCases = [
    ["WB03-03", "Safe", "Phishing", "Phishing"],
    ["WB03-04", "Phishing", "Safe", "Phishing"]
];

echo "<h2>WB03 - Model Disagreement Testing</h2>";

foreach ($testCases as $test) {

    [$id, $cnn, $rf, $expected] = $test;

    $actual = testClassification($cnn, $rf);

    $status = ($actual === $expected) ? "PASS" : "FAIL";

    echo "<p>";
    echo "Test ID: " . htmlspecialchars($id) . "<br>";
    echo "CNN: " . htmlspecialchars($cnn) . "<br>";
    echo "Random Forest: " . htmlspecialchars($rf) . "<br>";
    echo "Expected: " . htmlspecialchars($expected) . "<br>";
    echo "Actual: " . htmlspecialchars($actual) . "<br>";
    echo "Status: " . $status;
    echo "</p><hr>";
}

?>
