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
        if ($cols->length === 5) { // Ensure 5 columns only
            foreach ($cols as $col) {
                $bElements = $col->getElementsByTagName('b');
                if ($bElements->length > 0) {
                    $name = trim($bElements->item(0)->textContent);
                    if (!empty($name)) {
                        $names[] = $name;
                    }
                }
            }
        } else {
            throw new Exception("Table rows must have exactly 5 columns.");
        }
    }

    return $names;
}

function main()
{
    $baseReadme = 'README.md';
    $headReadme = 'head/README.md';

    $baseNames = getNamesFromReadme($baseReadme);
    $headNames = getNamesFromReadme($headReadme);

    echo "Base names: " . implode(', ', $baseNames) . "\n";
    echo "Head names: " . implode(', ', $headNames) . "\n";

    // Check for duplicates in the head
    if (count($headNames) !== count(array_unique($headNames))) {
        throw new Exception("Duplicate names found in the head README.");
    }

    // Ensure one new name is added
    $addedNames = array_diff($headNames, $baseNames);
    if (count($addedNames) !== 1) {
        throw new Exception("Exactly one name should be added.");
    }

    $addedName = reset($addedNames);
    if (end($headNames) !== $addedName) {
        throw new Exception("New name must be added to the end of the table.");
    }

    echo "Validation passed.\n";
    exit(0);
}

try {
    main();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
