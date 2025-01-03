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
        if ($cols->length === 5) {
            foreach ($cols as $col) {
                $bElements = $col->getElementsByTagName('b');
                if ($bElements->length > 0) {
                    $name = trim($bElements->item(0)->textContent);
                    $lineNum = $col->getLineNo(); // Available in DOMDocument PHP 8.1+
                    $names[] = ['name' => $name, 'line' => $lineNum];
                }
            }
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

    // Debug
    echo "Base names: " . print_r($baseNames, true);
    echo "Head names: " . print_r($headNames, true);

    // Check for duplicates
    $headNameSet = array_column($headNames, 'name');
    if (count($headNameSet) !== count(array_unique($headNameSet))) {
        echo "Error: Duplicate names found.\n";
        exit(1);
    }

    // Check if name is added at the end
    $addedNames = array_diff(array_column($headNames, 'name'), array_column($baseNames, 'name'));
    if (count($addedNames) !== 1) {
        echo "Error: Only one name should be added.\n";
        exit(1);
    }

    $addedName = reset($addedNames);
    if (end($headNames)['name'] !== $addedName) {
        echo "Error: Names should be added at the end of the table.\n";
        exit(1);
    }

    echo "Validation passed.\n";
    exit(0);
}

main();
