<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

$markdown = file_get_contents(__DIR__ . '/SMS_User_Manual.md');

// Convert Markdown to basic HTML with minimal parsing.
$html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>School Management System User Manual</title>' .
    '<style>' .
    'body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.5; margin: 30px; }' .
    'h1, h2, h3, h4 { color: #1f2d3d; margin-bottom: 0.25em; }' .
    'h1 { font-size: 24px; }' .
    'h2 { font-size: 18px; }' .
    'h3 { font-size: 14px; }' .
    'p { margin: 0.5em 0; }' .
    'ul { margin: 0.2em 0 0.8em 1.2em; }' .
    'li { margin: 0.25em 0; }' .
    'hr { border: none; border-top: 1px solid #ddd; margin: 1.3em 0; }' .
    '</style></head><body>';

$lines = explode("\n", $markdown);
foreach ($lines as $line) {
    if (preg_match('/^#\s+(.*)$/', $line, $matches)) {
        $html .= '<h1>' . htmlspecialchars($matches[1]) . '</h1>';
        continue;
    }
    if (preg_match('/^##\s+(.*)$/', $line, $matches)) {
        $html .= '<h2>' . htmlspecialchars($matches[1]) . '</h2>';
        continue;
    }
    if (preg_match('/^###\s+(.*)$/', $line, $matches)) {
        $html .= '<h3>' . htmlspecialchars($matches[1]) . '</h3>';
        continue;
    }
    if (preg_match('/^####\s+(.*)$/', $line, $matches)) {
        $html .= '<h4>' . htmlspecialchars($matches[1]) . '</h4>';
        continue;
    }
    if (preg_match('/^\-\s+(.*)$/', $line, $matches)) {
        if (!isset($listOpen) || !$listOpen) {
            $html .= '<ul>';
            $listOpen = true;
        }
        $html .= '<li>' . htmlspecialchars($matches[1]) . '</li>';
        continue;
    }
    if (trim($line) === '') {
        if (isset($listOpen) && $listOpen) {
            $html .= '</ul>';
            $listOpen = false;
        }
        $html .= '<p></p>';
        continue;
    }
    $html .= '<p>' . htmlspecialchars($line) . '</p>';
}
if (isset($listOpen) && $listOpen) {
    $html .= '</ul>';
}
$html .= '</body></html>';

$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'portrait');
$dompdf->loadHtml($html);
$dompdf->render();
file_put_contents(__DIR__ . '/SMS_User_Manual.pdf', $dompdf->output());

echo "PDF created: SMS_User_Manual.pdf\n";
