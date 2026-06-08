<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LIC Medical Examiner Report</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            background-color: #f0f0f0;
        }

        .page-container {
            width: 702px;
            margin: auto;
            background: #fff;
            padding: 0px;
            border: 0.5px solid #000;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header Layout */
        .header-grid {
            display: flex;
            border: 0.5px solid #000;
        }

        .header-logo {
            width: 50%;
            border-right: 0.5px solid #000;
            display: flex;
            align-items: center;
            padding: 0px;
        }

        .header-title {
            width: 55%;
            border-right: 0.5px solid #000;
            text-align: center;
            padding: 5px;
        }

        .header-meta {
            width: 50%;
        }

        .header-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-meta td {
            border-bottom: 0.5px solid #000;
            padding: 2px 5px;
            border-right: none;
        }

        .header-meta tr:last-child td {
            border-bottom: none;
        }

        /* Main Form Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -1px;
        }

        th,
        td {
            border: 0.5px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .num-col {
            width: 30px;
            padding: 20px 0px;
            text-align: center;
            font-weight: bold;
            background-color: #fff;
        }

        .label-bg {
            background-color: #fdfdfd;
            width: 35%;
            font-size: 14px;
        }

        .font-14 {
            font-size: 14px;
        }

        .consent-box {
            font-size: 10px;
            padding: 8px;
            border: 0.5px solid #000;
            border-top: none;
            line-height: 1.4;
        }

        .section-break {
            background-color: #eeeeee;
            text-align: center;
            font-weight: bold;
            padding: 4px;
            border: 0.5px solid #000;
        }

        p {
            font-size: 16px;
            margin: 10px;
        }

        .input-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 50px;
        }

        .mindcol {
            width: 60%;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="page-container">
        {{-- HEADER --}}
        <table style="width:100%;  border-collapse: collapse;">
            <tr>
                <td style="width:60%; vertical-align: top;">
                    <div style="inline-block inline-block; float: left;">
                        <img src="{{ public_path('assests/img/lic_logo.png') }}" style="width:150px;">

                        <div style="font-weight: bold; font-size: 12px;">
                            MEDICAL EXAMINER'S REPORT
                        </div>
                        <div>Form No LIC03-001 (Revised 2020)</div>
                    </div>
                    <img src="{{ public_path('/assests/img/gowelnext_stamp.png') }}"
                        style="width:110px; inline-block; float: right;">
                </td>

                <td style="width:40%; vertical-align: top;">
                    <table style="width:100%;">
                        <tr>
                            <td>Branch Code:</td>
                            <td>{{ $report->branch_code ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Proposal / Policy No:</td>
                            <td>{{ $report->policy_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>MSP name/code:</td>
                            <td>{{ $report->msp_code ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Date & Time:</td>
                            <td>{{ $report->exam_datetime?->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td>Medical Diary No:</td>
                            <td>{{ $report->medical_diary_no ?? '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- MOBILE & ID --}}
        <table>
            <tr>
                <td colspan="2">
                    <p>Mobile No of the Proposer/Life to be assured:
                        {{ substr($report->mobile_no, 0, 2) . 'XXXXXX' . substr($report->mobile_no, -2) }}</p>
                    <p>MER Done: {{ $report->mer_done ?? 'Video MER' }}</p>
                    <p>Identity Proof: {{ $report->identity_proof_type ?? '' }}</p>
                    <p>Identity Proof No: {{ $report->id_proof_no ?? '' }}</p>
                    <p>(In Case of Aadhaar Card, please mention only last four digits)</p>
                    <p>[Note: Mobile number and identity proof details to be filled in above. For Physical MER, Identity
                        Proof is to be verified and stamped.]</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>For Tele/ Video MER, consent given below is to be recorded either through email or audio/video
                        message. For Physical Examination the below consent is to be obtained before examination.</p>
                    <p>“I would like to inform that this call with/ visit to Dr.
                        {{ $report->examiner_consent_name ?? '_________________' }} (Name of the Medical Examiner) is
                        for conducting your Medical Examination through Tele/ Video/ Physical Examination on behalf of
                        LIC of India”.</p>
                    <p>Signature/ Thumb impression of Life to be assured</p>
                    <p>(In case of Physical Examination)</p>
                </td>
            </tr>
        </table>

        {{-- BASIC DETAILS --}}
        <table>
            <tr>
                <td class="num-col">1</td>
                <td class="label-bg">Full name of the life to be assured:</td>
                <td colspan="3" class="font-14" style="border: 1px solid #000">{{ $report->full_name ?? '' }}</td>
            </tr>
            <tr>
                <td class="num-col">2</td>
                <td class="label-bg">Date of Birth:</td>
                <td style="width:20%;" class="font-14">
                    {{ !empty($report->dob) ? \Carbon\Carbon::parse($report->dob)->format('d-m-Y') : '' }}
                </td>
                <td class="label-bg">Age:</td>
                <td class="font-14">{{ $report->age ?? '' }}</td>
                <td class="label-bg">Gender:</td>
                <td class="font-14">{{ $report->gender ?? '' }}</td>
            </tr>
            <tr>
                <td class="num-col">3</td>
                <td class="label-bg">Height (in cms):</td>
                <td class="font-14">{{ $report->height ?? '' }}</td>
                <td class="label-bg">Weight (in kgs):</td>
                <td colspan="3" class="font-14">{{ $report->weight ?? '' }}</td>
            </tr>
        </table>

        {{-- PHYSICAL MER (PULSE & BP) --}}
        <table>
            <tr style="background-color:#f2f2f2;">
                <td class="num-col">4</td>
                <td colspan="2"><strong>Required only in case of Physical MER</strong></td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td style="width:40%;" class="font-14">Pulse:</td>
                <td class="font-14">{{ $report->pulse ?? '' }}</td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="font-14">Blood Pressure (2 readings):</td>
                <td class="font-14">
                    1. Systolic: {{ $report->bp_systolic_1 ?? '' }} Diastolic: {{ $report->bp_diastolic_1 ?? '' }}<br>
                    2. Systolic: {{ $report->bp_systolic_2 ?? '' }} Diastolic: {{ $report->bp_diastolic_2 ?? '' }}
                </td>
            </tr>
        </table>

        {{-- MEDICAL HISTORY SECTION --}}
        <div class="section-break">
            ASCERTAIN THE FOLLOWING FROM THE PERSON BEING EXAMINED
            <p>If answer/s to any of the following questions is Yes, please give full details and ask life to be assured
                to submit copies of all treatment papers, investigation reports, histopathology report, discharge card,
                follow up reports etc. along with the proposal form to the Corporation.</p>
        </div>

        <table>
            {{-- Header row --}}
            <tr>
                <td class="num-col"></td>
                <td>Question</td>
                <td>Yes</td>
                <td>Remark</td>
            </tr>

            {{-- Helper to show Yes/No and details --}}
            @php
                function showYesNo($value)
                {
                    return $value === 'YES' ? 'Yes' : 'No';
                }
            @endphp

            {{-- Q5a --}}
            <tr>
                <td class="num-col">5</td>
                <td class="mindcol">
                    <p>a. Whether receiving or ever received any treatment/medication including alternate medicine like
                        ayurveda, homeopathy etc ?</p>
                </td>
                <td>{{ showYesNo($report->q5a_treatment ?? 'NO') }}</td>
                <td>
                    @if (($report->q5a_treatment ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            Date: {{ $report->q5a_date ?? '' }}<br>
                            Nature: {{ $report->q5a_nature ?? '' }}<br>
                            Medicine: {{ $report->q5a_medicine ?? '' }}<br>
                            Impairment: {{ $report->q5a_impairment ?? '' }}<br>
                            Unconscious: {{ $report->q5a_unconscious ?? '' }}
                        </div>
                    @endif
                </td>
            </tr>
            {{-- Q5b --}}
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>b. Undergone any surgery / hospitalized for any medical condition / disability / injury due to
                        accident?</p>
                </td>
                <td>{{ showYesNo($report->q5b_surgery ?? 'NO') }}</td>
                <td>
                    @if (($report->q5b_surgery ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            Date: {{ $report->q5b_date ?? '' }}<br>
                            Nature: {{ $report->q5b_nature ?? '' }}<br>
                            Medicine: {{ $report->q5b_medicine ?? '' }}<br>
                            Impairment: {{ $report->q5b_impairment ?? '' }}<br>
                            Unconscious: {{ $report->q5b_unconscious ?? '' }}
                        </div>
                    @endif
                </td>
            </tr>
            {{-- Q5c --}}
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>c. Whether visited the doctor any time in the last 5 years ?</p>
                </td>
                <td>{{ showYesNo($report->q5c_doctor_visit ?? 'NO') }}</td>
                <td>
                    @if (($report->q5c_doctor_visit ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            Date: {{ $report->q5c_date ?? '' }}<br>
                            Nature: {{ $report->q5c_nature ?? '' }}<br>
                            Medicine: {{ $report->q5c_medicine ?? '' }}<br>
                            Impairment: {{ $report->q5c_impairment ?? '' }}<br>
                            Unconscious: {{ $report->q5c_unconscious ?? '' }}
                        </div>
                    @endif
                </td>
            </tr>

            {{-- Q6 --}}
            <tr>
                <td class="num-col">6</td>
                <td class="mindcol">
                    <p>In the last 5 years, if advised to undergo an X-ray/ CT scan / MRI / ECG / TMT / Blood test /
                        Sputum/Throat swab test or any other investigatory or diagnostic tests?</p>
                    <p>Please specify date , reason ,advised by whom & findings.</p>
                </td>
                <td>{{ showYesNo($report->q6_diagnostic_tests ?? 'NO') }}</td>

                <td>
                    @if (($report->q6_diagnostic_tests ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            Date: {{ $report->q6_date ?? '' }}<br>
                            Reason: {{ $report->q6_reason ?? '' }}<br>
                            Advised by: {{ $report->q6_advised_by ?? '' }}<br>
                            Findings: {{ $report->q6_findings ?? '' }}
                        </div>
                    @endif
                </td>
            </tr>

            {{-- Q7 COVID --}}
            <tr>
                <td class="num-col">7</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from Novel Coronavirus (Covid-19) or experienced any of the symptoms
                        (for more than 5 days) ... If yes provide all investigation and treatment reports.</p>
                </td>
                <td>{{ showYesNo($report->q7_covid ?? 'NO') }}</td>
                <td>
                    @if (($report->q7_covid ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q7_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q8 Hypertension/Diabetes --}}
            <tr>
                <td class="num-col">8</td>
                <td class="mindcol">
                    <p>a. Suffering from Hypertension (high blood pressure) or diabetes or blood sugar levels higher
                        than normal or history of sugar /albumin in urine?</p>
                </td>
                <td>{{ showYesNo($report->q8_hypertension_diabetes ?? 'NO') }}</td>
                <td>
                    @if (($report->q8_hypertension_diabetes ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q8_hypertension_diabetesdetails ?? '' }}<br>

                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>b. Since when, any follow up and date and
                        value of last checked blood pressure and sugar levels?</p>
                </td>
                <td>{{ showYesNo($report->q8b_details ?? 'NO') }}</td>
                <td>
                    @if (($report->q8b_details ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q8b_detailsdetails ?? '' }}<br>

                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>c. Whether on medication? please give name of
                        the prescribed medicine and dosage.
                    </p>
                </td>
                <td>{{ showYesNo($report->q8c_medication ?? 'NO') }}</td>
                <td>
                    @if (($report->q8c_medication ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q8c_medicationdetails ?? '' }}<br>

                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>d. Whether undergone any surgery or hospitalization for any medical condition?</p>
                </td>
                <td>{{ showYesNo($report->q8d_hospitalization ?? 'NO') }}</td>
                <td>
                    @if (($report->q8d_hospitalization ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q8d_hospitalizationdetails ?? '' }}<br>

                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>e. Whether suffering from any other endocrine
                        disorders such as thyroid disorder etc.?</p>
                </td>
                <td>{{ showYesNo($report->q8e_complications ?? 'NO') }}</td>
                <td>
                    @if (($report->q8e_complications ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q8e_complicationsdetails ?? '' }}<br>

                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>f. Whether experiencing any weight change (gain or loss) in the last 5 years?</p>
                </td>
                <td>{{ showYesNo($report->q8f_endocrine ?? 'NO') }}</td>
                <td>
                    @if (($report->q8f_endocrine ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q8f_endocrinedetails ?? '' }}<br>

                        </div>
                    @endif
                </td>
            </tr>

            {{-- Q9 Heart --}}
            <tr>
                <td class="num-col">9</td>
                <td class="mindcol">
                    <p>a. Any history of chest pain, heart attack, palpitations and breathlessness on exertion or
                        irregular heartbeat?</p>
                </td>
                <td>{{ showYesNo($report->q9a_heart_history ?? 'NO') }}</td>
                <td>
                    @if (($report->q9a_heart_history ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q9a_heart_historydetails ?? '' }}<br>
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>b. Whether suffering from high
                        cholesterol?</p>
                </td>
                <td>{{ showYesNo($report->q9b_cholesterol ?? 'NO') }}</td>
                <td>
                    @if (($report->q9b_cholesterol ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q9b_cholesteroldetails ?? '' }}<br>
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>c. Whether on medication for any heart
                        ailment/ high cholesterol? Please state name of the prescribed medicine and
                        dosage.</p>
                </td>
                <td>{{ showYesNo($report->q9c_medication ?? 'NO') }}</td>
                <td>
                    @if (($report->q9c_medication ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q9c_medicationdetails ?? '' }}<br>
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num-col"></td>
                <td class="mindcol">
                    <p>d. Whether undergone Surgery such as CABG,
                        open heart surgery or PTCA?</p>
                </td>
                <td>{{ showYesNo($report->q9d_surgery ?? 'NO') }}</td>
                <td>
                    @if (($report->q9d_surgery ?? 'NO') === 'YES')
                        <div class="ans-detail">
                            {{ $report->q9d_surgerydetails ?? '' }}<br>
                        </div>
                    @endif
                </td>
            </tr>

            {{-- Q10 Kidney (assume field names) --}}
            <tr>
                <td class="num-col">10</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from any disease related to kidney such as kidney failure, kidney or
                        ureteral stones, blood or pus in urine or prostate?</p>
                </td>
                <td>{{ showYesNo($report->q10_kidney ?? 'NO') }}</td>
                <td>
                    @if (($report->q10_kidney ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q10_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q11 Liver/Lung --}}
            <tr>
                <td class="num-col">11</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from any Liver disorders like cirrhosis, hepatitis, jaundice, or
                        disorder of the Spleen or from any lung related or respiratory disorders such as Asthma,
                        bronchitis, wheezing, tuberculosis breathing difficulties etc.?</p>
                </td>
                <td>{{ showYesNo($report->q11_liver_respiratory ?? 'NO') }}</td>
                <td>
                    @if (($report->q11_liver_respiratory ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q11_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q12 Blood --}}
            <tr>
                <td class="num-col">12</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from any Blood disorder like anaemia, thalassemia or any Circulatory
                        disorder?</p>
                </td>
                <td>{{ showYesNo($report->q12_blood_disorders ?? 'NO') }}</td>
                <td>
                    @if (($report->q12_blood_disorders ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q12_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q13 Cancer --}}
            <tr>
                <td class="num-col">13</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from any form of cancer, leukaemia, tumor, cyst or growth of any kind
                        or enlarged lymph nodes?</p>
                </td>
                <td>{{ showYesNo($report->q13_cancer ?? 'NO') }}</td>
                <td>
                    @if (($report->q13_cancer ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q13_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q14 Neurological --}}
            <tr>
                <td class="num-col">14</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from Epilepsy, nervous disorder, multiple sclerosis, tremors,
                        numbness, paralysis, brain stroke?</p>
                </td>
                <td>{{ showYesNo($report->q14_neurological ?? 'NO') }}</td>
                <td>
                    @if (($report->q14_neurological ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q14_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q15 Physical impairment --}}
            <tr>
                <td class="num-col">15</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from any physical impairment/disability/amputation or any congenital
                        disease/abnormality or disorder of back, neck, muscle, joints, bones, arthritis or gout</p>
                </td>
                <td>{{ showYesNo($report->q15_physical_impairment ?? 'NO') }}</td>
                <td>
                    @if (($report->q15_physical_impairment ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q15_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q16 Hernia / GI --}}
            <tr>
                <td class="num-col">16</td>
                <td class="mindcol">
                    <p>Suffering or ever suffered from Hernia or disorder of the Stomach / intestines, colitis,
                        indigestion, Peptic ulcer, piles, or any other disease of the gall bladder or pancreas?</p>
                </td>
                <td>{{ showYesNo($report->q16_digestive ?? 'NO') }}</td>
                <td>
                    @if (($report->q16_digestive ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q16_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q17 Mental health --}}
            <tr>
                <td class="num-col">17</td>
                <td class="mindcol">
                    <p>a. Suffering from Depression/Stress/ Anxiety/ Psychosis or any other Mental / psychiatric
                        disorder?</p>
                </td>
                <td>{{ showYesNo($report->q17a_mental ?? 'NO') }}</td>
                <td>
                    @if (($report->q17a_mental ?? 'NO') === 'YES')
                        <div class="ans-detail">b. {{ $report->q17b_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q18 Eyes/ENT --}}
            <tr>
                <td class="num-col">18</td>
                <td class="mindcol">
                    <p>Is there any abnormality of Eyes (partial/total blindness),Ears (deafness/ discharge from the
                        ears), Nose, Throat or Mouth,teeth, swelling of gums / tongue, tobacco stains or signs of oral
                        cancer?</p>
                </td>
                <td>{{ showYesNo($report->q18_ent ?? 'NO') }}</td>
                <td>
                    @if (($report->q18_ent ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q18_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q19 HIV/STD --}}
            <tr>
                <td class="num-col">19</td>
                <td class="mindcol">
                    <p>Whether person being examined and/or his/her spouse/partner tested positive or is/are under
                        treatment for HIV / AIDS / Sexually transmitted diseases (e.g. syphilis, gonorrhea, etc.)?</p>
                </td>
                <td>{{ showYesNo($report->q19_hiv_sti ?? 'NO') }}</td>
                <td>
                    @if (($report->q19_hiv_sti ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q19_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Q20 Habits --}}
            <tr>
                <td class="num-col">20</td>
                <td class="mindcol">
                    <p>Ascertain if any other condition / disease / adverse habit (such as smoking/ tobacco chewing/
                        consumption of alcohol/drugs etc) which is relevant in assessment of medical risk of examinee.
                    </p>
                </td>
                <td>{{ showYesNo($report->q20_other ?? 'NO') }}</td>
                <td>
                    @if (($report->q20_other ?? 'NO') === 'YES')
                        <div class="ans-detail">{{ $report->q20a_details ?? '' }}</div>
                    @endif
                </td>
            </tr>

            {{-- Female section (only if female) --}}
            {{-- @if (isset($report->gender) && strtolower($report->gender) === 'female') --}}
            <tr>
                <td colspan="4">
                    <p>For Female Proponents only</p>
                </td>
            </tr>
            <tr>
                <td class="num-col">i</td>
                <td class="mindcol">
                    <p>Whether pregnant? If so duration.</p>
                </td>
                <td colspan="2">{{ $report->pregnancy_status ?? '' }}</td>
            </tr>
            <tr>
                <td class="num-col">ii</td>
                <td class="mindcol">
                    <p>Suffering from any pregnancy related complications</p>
                </td>
                <td colspan="2">{{ $report->pregnancy_complications ?? '' }}</td>
            </tr>
            <tr>
                <td class="num-col">iii</td>
                <td class="mindcol">
                    <p>Whether consulted a gynaecologist or undergone any investigation, treatment for any
                        gynaecological such as fibroid, cyst or any disease of the breasts, uterus, cervix or ovaries
                        etc. or taken / taking any treatment for the same</p>
                </td>
                <td colspan="2">{{ $report->gynecological_issues ?? '' }}</td>
            </tr>
            {{-- @endif --}}
        </table>

        {{-- OBSERVATION --}}
        <table>
            <tr>
                <td>
                    <p>FROM MEDICAL EXAMINER’S OBSERVATION/ASSESSMENT WHETHER LIFE TO BE ASSURED APPEARS MENTALLY AND
                        PHYSICALLY HEALTHY</p>
                </td>
                <td>{{ ($report->appears_healthy ?? 'Yes') == 'Yes' ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        {{-- DECLARATION & SIGNATURES --}}
        <div style="margin: 50px;">
            <p><u>
                    <center>Declaration</center>
                </u></p>
            <p style="font-style: justify;">I {{ $report->full_name ?? '_________________' }} that you have fully
                understood the questions asked to you during the Video call Examination and have furnished complete,
                true and accurate information after fully understanding the same. We thank you for having taken the time
                to confirm the details. The information provided will be passed on to Life Insurance Corporation of
                India for further processing.</p>
        </div>

        <div style="display: flex; justify-content: end;">
            <div style="width: 300px;">
                <div style="display: flex; justify-content: center;">
                    @if ($report->life_assured_signature)
                        <img src="{{ storage_path('app/public/' . $report->life_assured_signature) }}"
                            alt="Signature" style="max-width: 100%; height: auto; width:200px;">
                    @endif
                </div>
                <p style="font-style: justify;">Signature/ Thumb impression of Life to be assured</p>
                <p>
                    <center>(In case of Physical Examination)</center>
                </p>
            </div>
        </div>

        <div style="margin: 50px;">
            <p style="font-style: justify;">I hereby certify that I have assessed/ examined the above life to be
                assured on the Day {{ $report->exam_datetime?->format('d-m-Y') ?? '____/____/____' }} Video call
                Examination
                personally and recorded true and correct findings to the aforesaid questions as ascertained from the
                life to be assured.</p>
        </div>
        <div style="margin: 50px;">
            <table style="width:100%; border-collapse: collapse;">
                <tr>
                    <!-- LEFT SIDE -->
                    <td style="width:50%; vertical-align: top;">
                        <div>Place: {{ $report->examiner_place ?? '' }}</div>
                        <div>
                            Date: {{ \Carbon\Carbon::parse($report->examiner_date)->format('d M Y') }}
                        </div>
                        <div>
                            Stamp:<br>
                            <img src="file://{{ public_path($report->examiner_stamp) }}" style="width:300px;">
                        </div>
                    </td>

                    <!-- RIGHT SIDE -->
                    <td style="width:50%; vertical-align: top; text-align: right;">
                        <img src="file://{{ public_path($report->examiner_signature) }}" style="width:300px;">

                        <div>Signature of Medical Examiner</div>

                        <div>Doctor Name: {{ $report->doctor_name ?? '' }}</div>
                        <div>Doctor Qualification: {{ $report->doctor_qualification ?? '' }}</div>
                        <div>Doctor Regn. No: {{ $report->doctor_registration_number ?? '' }}</div>

                        @if ($report->examiner_signature)
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding-top: 15px;">
                        <img src="{{ public_path('/assests/img/gowelnext_stamp.png') }}"
                            style="width:150px; display: inline-block;">
                    </td>
                </tr>
            </table>
        </div>
        <table>
            <tr>
                <td>
                    <img src="{{ storage_path('app/public/' . $report->additional_documents) }}"
                        style="width:600px;">

                    <div
                        style="
                            width:600px;
                            background:#f5f5f5;
                            padding:5px;
                            font-size:9px;
                            border:1px solid #ddd;
                        ">
                        <strong>Customer Address:</strong>
                        {{ $report->customer_address ?? 'N/A' }}
                        ({{ $report->customer_pincode ?? 'N/A' }})
                        <br>

                        <strong>Doctor Address:</strong>
                        {{ $report->doctor_address ?? 'N/A' }}
                        ({{ $report->doctor_pincode ?? 'N/A' }})

                        <br>
                        <strong>Customer Coordinates:</strong>
                        {{ $report->customer_latitude ?? 'N/A' }},
                        {{ $report->customer_longitude ?? 'N/A' }}

                        <br>
                        <strong>Doctor Coordinates:</strong>
                        {{ $report->doctor_latitude ?? 'N/A' }},
                        {{ $report->doctor_longitude ?? 'N/A' }}
                    </div>
                </td>
            </tr>
            <tr>

                <td>
                    <img src="{{ storage_path('app/public/' . $report->consent_proof) }}" style="width:600px;">

                    <div
                        style="
                        width:600px;
                        background:#f5f5f5;
                        padding:5px;
                        font-size:9px;
                        border:1px solid #ddd;
                        line-height:1.4;
                        word-wrap:break-word;
                    ">

                        <strong>Customer Address:</strong>
                        {{ $report->customer_address ?? 'N/A' }}
                        <br>

                        <strong>Customer Pincode:</strong>
                        {{ $report->customer_pincode ?? 'N/A' }}

                        <br><br>

                        <strong>Doctor Address:</strong>
                        {{ $report->doctor_address ?? 'N/A' }}
                        <br>

                        <strong>Doctor Pincode:</strong>
                        {{ $report->doctor_pincode ?? 'N/A' }}

                        <br><br>

                        <strong>Customer Coordinates:</strong>
                        {{ $report->customer_latitude ?? 'N/A' }},
                        {{ $report->customer_longitude ?? 'N/A' }}

                        <br>

                        <strong>Doctor Coordinates:</strong>
                        {{ $report->doctor_latitude ?? 'N/A' }},
                        {{ $report->doctor_longitude ?? 'N/A' }}

                    </div>
                </td>

            <tr>
                <td><img src="{{ storage_path('app/public/' . $report->q5c_documents) }}" style="width:600px; ">
                </td>
            </tr>
            <tr>
                <td><img src="{{ storage_path('app/public/' . $report->id_proof_screenshot) }}"
                        style="width:600px; ">
                </td>
            </tr>
        </table>
    </div>
    </div>
</body>

</html>
