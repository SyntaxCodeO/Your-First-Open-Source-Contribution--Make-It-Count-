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
    $contributors = [];

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
                        // Assuming the username is embedded as a link in the <a> tag
                        $aElements = $col->getElementsByTagName('a');
                        $username = '';
                        if ($aElements->length > 0) {
                            $username = trim($aElements->item(0)->getAttribute('href'));
                        }
                        if (!empty($name) && !empty($username)) {
                            $contributors[] = ['name' => $name, 'username' => $username];
                        }
                    }
                }
            }
        }
    }

    return $contributors;
}

function main()
{
    $readmePath = 'README.md';  // The README file to check

    $existingContributors = getNamesFromReadme($readmePath);  // Get contributors from the README

    // Debugging output for existing contributors
    echo "Existing Contributors: \n";
    foreach ($existingContributors as $contributor) {
        echo $contributor['name'] . " - " . $contributor['username'] . "\n";
    }

    // In this version, new contributors would come dynamically from the PR or input
    // Example: Here we simulate new contributors being added
    $newContributors = [
        ['name' => 'Andrew Lacambra', 'username' => 'CodeByMoriarty'],
        // Add more new contributors with their usernames here
    ];

    // Check if any new contributor is already in the README (same name + username)
    $duplicates = [];
    foreach ($newContributors as $newContributor) {
        foreach ($existingContributors as $existingContributor) {
            if ($newContributor['name'] === $existingContributor['name'] && $newContributor['username'] === $existingContributor['username']) {
                $duplicates[] = $newContributor;
            }
        }
    }

    if (!empty($duplicates)) {
        echo "Error: Duplicate contributors found: \n";
        foreach ($duplicates as $duplicate) {
            echo $duplicate['name'] . " - " . $duplicate['username'] . "\n";
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
