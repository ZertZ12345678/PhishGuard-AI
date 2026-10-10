
<?php

// UT01 - URL Input Validation Unit Testing

function validateURL($url)
{
    $url = trim($url);

    if ($url === "") {
        return "Please enter a URL.";
    }

    if (strlen($url) > 2048) {
        return "The URL is too long.";
    }

    $predictionURL = $url;

    if (!preg_match('/^https?:\/\//i', $predictionURL)) {
        $predictionURL = "https://" . $predictionURL;
    }

    if (!filter_var($predictionURL, FILTER_VALIDATE_URL)) {
        return "Please enter a valid URL.";
    }

    return "VALID";
}

// Test inputs
$testCases = [
    ["UT01-01", "", "Please enter a URL."],
    ["UT01-02", "https://", "Please enter a valid URL."],
    [
        "UT01-03",
        "https://example.com/" . str_repeat("a", 2050),
        "The URL is too long."
    ],
    [
        "UT01-04",
        "https://example.com/" .
            str_repeat("a", 2048 - strlen("https://example.com/")),
        "VALID"
    ]
];

echo "<h2>UT01 - URL Input Validation Testing</h2>";

foreach ($testCases as $test) {

    [$id, $input, $expected] = $test;

    $actual = validateURL($input);

    $status = ($actual === $expected) ? "PASS" : "FAIL";

    echo "<p>";
    echo "Test ID: " . htmlspecialchars($id) . "<br>";
    echo "Input Length: " . strlen($input) . "<br>";
    echo "Expected: " . htmlspecialchars($expected) . "<br>";
    echo "Actual: " . htmlspecialchars($actual) . "<br>";
    echo "Status: " . $status;
    echo "</p><hr>";
}

?>
