<?php
require('fpdf/fpdf.php');

if (!isset($_GET['student'])) {
    http_response_code(400);
    echo 'Student not specified';
    exit;
}

$student = htmlspecialchars($_GET['student']);

// Mock data - Replace with actual database or JSON file reading
$data = [
    'john_doe' => [
        ['level' => 'L1', 'comment' => 'Late to class', 'date' => '2024-11-22'],
    ],
    // Add other students
];

if (!isset($data[$student])) {
    http_response_code(404);
    echo 'Student not found';
    exit;
}

$report = $data[$student];

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, "Conduct Report for $student");
$pdf->Ln();

foreach ($report as $entry) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, "Level: {$entry['level']}");
    $pdf->Ln();
    $pdf->Cell(40, 10, "Comment: {$entry['comment']}");
    $pdf->Ln();
    $pdf->Cell(40, 10, "Date: {$entry['date']}");
    $pdf->Ln();
}

$pdf->Output('D', "{$student}_Conduct_Report.pdf");
?>