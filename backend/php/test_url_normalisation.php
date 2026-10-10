
<?php

// UT02 - URL Normalisation Unit Testing

function normaliseURL($url)
{
    $url = trim($url);

    $predictionURL = $url;

    if (!preg_match('/^https?:\/\//i', $predictionURL)) {
        $predictionURL = "https://" . $predictionURL;
    }

    return $predictionURL;
}

// Test inputs and expected outputs
$testCases = [
    [
        "UT02-01",
        "www.google.com",
        "https://www.google.com"
    ],
    [
        "UT02-02",
        "http://www.google.com",
        "http://www.google.com"
    ],
    [
        "UT02-03",
        "https://www.google.com",
        "https://www.google.com"
    ],
    [
        "UT02-04",
        "GOOGLE.COM",
        "https://GOOGLE.COM"
    ]
];

echo "<h2>UT02 - URL Normalisation Testing</h2>";

foreach ($testCases as $test) {

    [$id, $input, $expected] = $test;

    $actual = normaliseURL($input);

    $status = ($actual === $expected) ? "PASS" : "FAIL";

    echo "<p>";
    echo "Test ID: " . htmlspecialchars($id) . "<br>";
    echo "Input: " . htmlspecialchars($input) . "<br>";
    echo "Expected: " . htmlspecialchars($expected) . "<br>";
    echo "Actual: " . htmlspecialchars($actual) . "<br>";
    echo "Status: " . $status;
    echo "</p><hr>";
}

?>
