<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medical Examination Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .section { margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .section-title { font-size: 14px; font-weight: bold; background: #f0f0f0; padding: 5px; margin-bottom: 10px; }
        .row { display: flex; margin-bottom: 5px; }
        .label { font-weight: bold; width: 200px; }
        .value { flex: 1; }
        .bmi-box { background: #e8f4f8; padding: 8px; border-radius: 4px; margin-top: 10px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Medical Examination Report</h2>
        <p>Generated: {{ $generated_date }}</p>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="row"><div class="label">Full Name:</div><div class="value">{{ $report->full_name }}</div></div>
        <div class="row"><div class="label">Date of Birth:</div><div class="value">{{ $report->dob ? $report->dob->format('d-m-Y') : '' }}</div></div>
        <div class="row"><div class="label">Age:</div><div class="value">{{ $report->age }}</div></div>
        <div class="row"><div class="label">Gender:</div><div class="value">{{ $report->gender }}</div></div>
        <div class="row"><div class="label">Height (cm):</div><div class="value">{{ $report->height }}</div></div>
        <div class="row"><div class="label">Weight (kg):</div><div class="value">{{ $report->weight }}</div></div>
        <div class="bmi-box">
            <div class="row"><div class="label">BMI:</div><div class="value">{{ $bmi }} ({{ $bmi_category }})</div></div>
        </div>
        <div class="row"><div class="label">Pulse:</div><div class="value">{{ $report->pulse }}</div></div>
        <div class="row"><div class="label">Blood Pressure:</div><div class="value">{{ $report->bp_systolic_1 }}/{{ $report->bp_diastolic_1 }} , {{ $report->bp_systolic_2 }}/{{ $report->bp_diastolic_2 }}</div></div>
    </div>

    @if(count($positive_answers) > 0)
    <div class="section">
        <div class="section-title">Medical Conditions (Positive Answers)</div>
        @foreach($positive_answers as $key => $item)
        <div class="row"><div class="label">{{ $item['question'] }}:</div><div class="value">{{ is_array($item['details']) ? json_encode($item['details']) : $item['details'] }}</div></div>
        @endforeach
    </div>
    @endif

    <div class="section">
        <div class="section-title">Declaration</div>
        <div class="row"><div class="label">Declaration Name:</div><div class="value">{{ $report->declaration_name }}</div></div>
        <div class="row"><div class="label">Certificate Date:</div><div class="value">{{ $report->certificate_date ? $report->certificate_date->format('d-m-Y') : '' }}</div></div>
        <div class="row"><div class="label">Examiner Place:</div><div class="value">{{ $report->examiner_place }}</div></div>
        <div class="row"><div class="label">Examiner Date:</div><div class="value">{{ $report->examiner_date ? $report->examiner_date->format('d-m-Y') : '' }}</div></div>
        <div class="row"><div class="label">Examiner Name/Code:</div><div class="value">{{ $report->examiner_name_code }}</div></div>
    </div>

    <div class="footer">
        This is a system-generated medical report. Valid with authorized signature.
    </div>
</body>
</html> 