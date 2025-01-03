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

function createTableRows($names, $maxColumns = 5)
{
    $rows = [];
    $chunkedNames = array_chunk($names, $maxColumns);

    foreach ($chunkedNames as $chunk) {
        $row = "<tr>";
        foreach ($chunk as $name) {
            $row .= "<td>$name</td>";
        }
        $row .= "</tr>";
        $rows[] = $row;
    }

    return $rows;
}

function main()
{
    $baseReadme = 'README.md';
    $headReadme = 'head/README.md';

    $baseNames = getNamesFromReadme($baseReadme);
    $headNames = getNamesFromReadme($headReadme);

    // Debugging output for base and head names
    echo "Base Names: " . implode(", ", $baseNames) . "\n";
    echo "Head Names: " . implode(", ", $headNames) . "\n";

    // Ensure no duplicates in head README
    if (count($headNames) !== count(array_unique($headNames))) {
        throw new Exception("Duplicate names found in the head README.");
    }

    // Ensure only one name is added
    $addedNames = array_diff($headNames, $baseNames);
    echo "Added Names: " . implode(", ", $addedNames) . "\n"; // Debugging added names

    if (count($addedNames) !== 1) {
        throw new Exception("Exactly one name should be added.");
    }

    $addedName = reset($addedNames);
    if (end($headNames) !== $addedName) {
        throw new Exception("New name must be added to the end of the table.");
    }

    // Create the table rows with a max of 5 contributors per row
    $rows = createTableRows($headNames);

    // Output the table
    echo "<table>\n";
    echo "<tbody>\n";
    foreach ($rows as $row) {
        echo $row . "\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";

    echo "Validation passed.\n";
    exit(0);
}

try {
    main();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
