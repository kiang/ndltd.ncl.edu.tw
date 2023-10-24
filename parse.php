<?php
$oFh = fopen(__DIR__ . '/list.csv', 'w');
fputcsv($oFh, ['student', 'topic', 'meta', 'teachers']);
foreach (glob(__DIR__ . '/raw/*/*.html') as $htmlFile) {
    $html = file_get_contents($htmlFile);
    $html = substr($html, strpos($html, '<table cellspacing=0 cellpadding=1 align=center id="tablefmt1">'));
    $lines = explode('<td class="tdfmt1-first">', $html);
    foreach ($lines as $line) {
        $cols = explode('</td>', $line);
        foreach ($cols as $k => $v) {
            $cols[$k] = trim(strip_tags($v));
            $cols[$k] = str_replace('&nbsp;', '', $cols[$k]);
        }
        if (isset($cols[2]) && $cols[2] === 'simplefmt2td') {
            $cols[5] = explode(':', $cols[5])[1];
            $cols[6] = explode(':', $cols[6])[1];
            $cols[6] = implode(',', explode('／', $cols[6]));
            fputcsv($oFh, [$cols[5], $cols[3], $cols[4], $cols[6]]);
        }
    }
}
