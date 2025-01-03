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
                $bElements = $col->getElementsByTagName('b');
                if ($bElements->length > 0) {
                    $name = trim($bElements->item(0)->textContent);
                    if (!empty($name)) {
                        $names[] = $name;
                    }
                }
            }
        }
    }

    return $names;
}

function main()
{
    $readmePath = 'README.md';  // The single README file to check

    $existingNames = getNamesFromReadme($readmePath);  // Names already listed in the README

    // Debugging output for existing names
    echo "Existing Names: " . implode(", ", $existingNames) . "\n";

    // In this version, new names would come dynamically from the PR or input
    // Example: Here we simulate new names coming from a PR or a list.
    $newNames = [];  // This would be populated with new names to check against the README

    // Check if any new name already exists in the README
    $duplicateNames = array_intersect($newNames, $existingNames);

    if (!empty($duplicateNames)) {
        echo "Error: Duplicate names found: " . implode(", ", $duplicateNames) . "\n";
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
