<?php

function getNamesFromReadme($readmePath)
{
    if (!file_exists($readmePath)) {
        throw new Exception("File not found: $readmePath");
    }

    $content = file_get_contents($readmePath);
    $dom = new DOMDocument();
    @$dom->loadHTML($content);
    $tables = $dom->getElementsByTagName('table');

    if ($tables->length === 0) {
        throw new Exception("No table found in $readmePath");
    }

    $rows = $tables->item(0)->getElementsByTagName('tr');
    $names = [];

    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');
        if ($cols->length > 0) {
            foreach ($cols as $col) {
                // Look for <b> elements inside <sub> (if present)
                $subElements = $col->getElementsByTagName('sub');
                foreach ($subElements as $subElement) {
                    $bElements = $subElement->getElementsByTagName('b');
                    if ($bElements->length > 0) {
                        $name = trim($bElements->item(0)->textContent);
                        if (!empty($name)) {
                            $names[] = $name;
                        }
                    }
                }
            }
        }
    }

    return $names;
}

function main()
{
    $readmePath = 'README.md';  // The README file to check

    $existingNames = getNamesFromReadme($readmePath);  // Get names from the README

    // Debugging output for existing names
    echo "Existing Names: \n";
    foreach ($existingNames as $name) {
        echo $name . "\n";
    }

    // In this version, new contributors would come dynamically from the PR or input
    // Example: Here we simulate new contributors being added
    $newContributors = [
        'Andrew Lacambra',  // Same name, can be used to check duplicate
        'John Doe',         // Another new name
        // Add more new contributors here
    ];

    // Check if any new contributor's name already exists
    $duplicates = [];
    foreach ($newContributors as $newContributor) {
        if (in_array($newContributor, $existingNames)) {
            $duplicates[] = $newContributor;
        }
    }

    if (!empty($duplicates)) {
        echo "Error: Duplicate contributors found: \n";
        foreach ($duplicates as $duplicate) {
            echo $duplicate . "\n";
        }
        exit(1);
    }

    echo "Validation passed: No duplicates found.\n";
    exit(0);
}

try {
    main();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
