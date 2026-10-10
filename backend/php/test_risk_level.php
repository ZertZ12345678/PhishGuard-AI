
<?php

// UT03 - Risk Level Classification Unit Testing

function classifyRiskLevel($riskScore)
{
    // Same classification logic as detect_process.php

    if ($riskScore < 20) {
        $riskLevel = "LOW RISK";
    } elseif ($riskScore < 50) {
        $riskLevel = "MEDIUM RISK";
    } elseif ($riskScore < 75) {
        $riskLevel = "HIGH RISK";
    } else {
        $riskLevel = "VERY HIGH RISK";
    }

    return $riskLevel;
}

// Boundary value test cases
$testCases = [
    ["UT03-01", 0, "LOW RISK"],
    ["UT03-02", 19.99, "LOW RISK"],
    ["UT03-03", 20, "MEDIUM RISK"],
    ["UT03-04", 49.99, "MEDIUM RISK"],
    ["UT03-05", 50, "HIGH RISK"],
    ["UT03-06", 74.99, "HIGH RISK"],
    ["UT03-07", 75, "VERY HIGH RISK"],
    ["UT03-08", 100, "VERY HIGH RISK"]
];

echo "<h2>UT03 - Risk Level Classification Testing</h2>";

foreach ($testCases as $test) {

    [$id, $score, $expected] = $test;

    $actual = classifyRiskLevel($score);

    $status = ($actual === $expected) ? "PASS" : "FAIL";

    echo "<p>";
    echo "Test ID: " . htmlspecialchars($id) . "<br>";
    echo "Risk Score: " . htmlspecialchars((string)$score) . "<br>";
    echo "Expected: " . htmlspecialchars($expected) . "<br>";
    echo "Actual: " . htmlspecialchars($actual) . "<br>";
    echo "Status: " . $status;
    echo "</p><hr>";
}

?>
