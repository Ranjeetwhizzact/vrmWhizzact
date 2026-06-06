<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #zmmtg-root {
            display: block !important;
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            /* Ensure it's on top */
            background-color: white;
            /* Prevent invisible background */
        }

        /* #zmmtg-root {
            display: block !important;
            position: relative !important;
            width: 100vw;
            height: 100vh;
        } */

        #meetingSDKElement {
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>

<body>

    <div id="meetingSDKElement"></div>

    <!-- ✅ Zoom Web Meeting SDK CSS -->
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.11.2/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.11.2/css/react-select.css" />

    <!-- ✅ Zoom Web Meeting SDK JS -->
    <script src="https://source.zoom.us/3.11.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/zoom-meeting-3.11.2.min.js"></script>
    <style>
        .zoom-workplace-logo {
            display: none;
        }

        .meeting-header {
            display: none !important;
        }
    </style>
    @if (request()->has('doctor_access'))
        <style>
            .footer__leave-btn-container {
                display: none;
            }

            #meetingSDKElement,
            #zmmtg-root {
                max-width: 65% !important;
                max-height: 85vh !important;
                margin-top: 100px;
            }

            .single-main-container__main-view {
                height: 400px !important;
            }

            video-player {
                height: 600px !important;
            }
        </style>
    @endif


    {{-- Normal user / customer starts --}}
    @if (!request()->has('doctor_access'))
        <style>
            .footer__leave-btn-container {
                display: none !important;
            }

            .patient-topbar {
                position: fixed;
                top: 8px;
                left: 0;
                width: 97.3%;
                height: 90px;
                background: #f5f5f5;
                z-index: 99999;
                display: flex;
                align-items: center;
                padding: 0 24px;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .patient-end-btn {
                margin-left: 20px;
                padding: 10px 26px;
                border-radius: 999px;
                border: 2px solid #c9ced6;
                background: white;
                color: #2563eb;
                font-size: 15px;
                font-weight: 600;
                text-decoration: none;
                transition: 0.2s ease;
                cursor: pointer;
            }

            .patient-end-btn:hover {
                background: #f8fafc;
            }

            /* Prevent Zoom overlap */
            #meetingSDKElement,
            #zmmtg-root {
                margin-top: 100px !important;
                height: calc(100vh - 100px) !important;
            }

            /* Modal */
            .rating-modal {
                display: none;
                position: fixed;
                z-index: 100000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
            }

            .rating-modal-content {
                background: #fff;
                width: 420px;
                max-width: 90%;
                border-radius: 12px;
                padding: 25px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .rating-modal h3 {
                margin-top: 0;
                margin-bottom: 18px;
                font-size: 22px;
                color: #111827;
            }

            .star-rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: flex-end;
                gap: 5px;
                margin-bottom: 20px;
            }

            .star-rating input {
                display: none;
            }

            .star-rating label {
                font-size: 32px;
                color: #d1d5db;
                cursor: pointer;
                transition: 0.2s;
            }

            .star-rating input:checked~label,
            .star-rating label:hover,
            .star-rating label:hover~label {
                color: #fbbf24;
            }

            .rating-textarea {
                width: 100%;
                min-height: 100px;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                padding: 12px;
                font-size: 14px;
                resize: none;
                outline: none;
                margin-bottom: 20px;
            }

            .rating-actions {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
            }

            .rating-cancel-btn,
            .rating-submit-btn {
                padding: 10px 20px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
            }

            .rating-cancel-btn {
                background: #e5e7eb;
                color: #111827;
            }

            .rating-submit-btn {
                background: #2563eb;
                color: white;
            }

            .rating-submit-btn:hover {
                background: #1d4ed8;
            }
        </style>

        <!-- Topbar -->
        <div class="patient-topbar">

            <button type="button" class="patient-end-btn" id="openRatingModal">
                End Meeting
            </button>

        </div>

        <!-- Rating Modal -->
        <div class="rating-modal" id="ratingModal">

            <div class="rating-modal-content">

                <h3>Rate Your Experience</h3>

                <form id="meetingRatingForm">

                    @csrf

                    <!-- Appointment ID -->
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <!-- Star Rating -->
                    <div class="star-rating" required>

                        <input type="radio" name="rating" id="star5" value="5">
                        <label for="star5">&#9733;</label>

                        <input type="radio" name="rating" id="star4" value="4">
                        <label for="star4">&#9733;</label>

                        <input type="radio" name="rating" id="star3" value="3">
                        <label for="star3">&#9733;</label>

                        <input type="radio" name="rating" id="star2" value="2">
                        <label for="star2">&#9733;</label>

                        <input type="radio" name="rating" id="star1" value="1">
                        <label for="star1">&#9733;</label>

                    </div>

                    <!-- Review -->
                    <textarea name="review" class="rating-textarea" placeholder="Write your feedback here..."></textarea>

                    <!-- Buttons -->
                    <div class="rating-actions">

                        <button type="button" class="rating-cancel-btn" id="closeRatingModal">
                            Cancel
                        </button>

                        <button type="submit" class="rating-submit-btn">
                            Submit & End
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- Script -->
        <script>
            const ratingModal = document.getElementById('ratingModal');
            const openModalBtn = document.getElementById('openRatingModal');
            const closeModalBtn = document.getElementById('closeRatingModal');
            const ratingForm = document.getElementById('meetingRatingForm');

            // Open modal
            openModalBtn.addEventListener('click', function() {
                ratingModal.style.display = 'flex';
            });

            // Close modal
            closeModalBtn.addEventListener('click', function() {
                ratingModal.style.display = 'none';
            });

            // Close on outside click
            window.addEventListener('click', function(e) {
                if (e.target === ratingModal) {
                    ratingModal.style.display = 'none';
                }
            });

            // Submit form
            ratingForm.addEventListener('submit', function(e) {

                e.preventDefault();

                // Check if rating selected
                const selectedRating = document.querySelector('input[name="rating"]:checked');

                if (!selectedRating) {

                    alert('Please leave a rating before ending the meeting.');

                    return;
                }

                let formData = new FormData(ratingForm);

                fetch("{{ route('meeting.rating.store') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async response => {

                        let data = await response.json();

                        console.log('Response:', data);

                        if (response.ok) {

                            alert('Thank you for your feedback!');

                            window.location.href = "/";

                        } else {

                            alert(data.message || 'Something went wrong');

                        }

                    })
                    .catch(error => {

                        console.error('Fetch Error:', error);

                        alert('Server error occurred');

                    });

            });
        </script>
    @endif
    {{-- Normal user / customer ends --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("✅ Checking Zoom SDK...");

            if (typeof ZoomMtg === "undefined") {
                console.error("❌ Error: ZoomMtg is not defined. Check if the Zoom SDK script is loading properly.");
                return;
            }

            ZoomMtg.setZoomJSLib('https://source.zoom.us/3.11.2/lib', '/av');
            // ZoomMtg.preLoadWasm();
            // ZoomMtg.prepareJssdk();
            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareWebSDK();


            ZoomMtg.init({
                leaveUrl: "{{ url('/') }}",
                isSupportAV: true,
                success: function() {
                    console.log("✅ Zoom Initialized Successfully");

                    console.log("{{ $meetingId }}");
                    console.log("{{ $userEmail }}");
                    console.log("{{ $userName }}");
                    console.log("{{ $signature }}");
                    console.log("{{ $sdkKey }}");
                    console.log("{{ $passCode }}");


                    ZoomMtg.join({
                        meetingNumber: "{{ $meetingId }}",
                        userName: "{{ $userName }}",
                        signature: "{{ $signature }}",
                        sdkKey: "{{ $sdkKey }}",
                        passWord: "{{ $passCode }}",
                        // zak: zakToken,

                        success: function() {
                            console.log("✅ Successfully joined the Zoom meeting!");
                        },
                        error: function(err) {
                            console.error("❌ Zoom Join Error:", err);
                        }
                    });

                },
                error: function(err) {
                    console.error("❌ Zoom Init Error:", err);
                }
            });

        });
    </script>
    @if (request()->has('doctor_access'))
        <form action="{{ route('storereport') }}" id="reportdata" method="post" enctype="multipart/form-data"
            accept-charset="UTF-8">
            @csrf

            <!-- Fixed Header -->
            <div
                class="w-[97.3%] mt-[7px] h-[90px] fixed top-0 left-0 z-50 rounded-md flex items-center bg-white shadow-md px-4">

                <img src="{{ url('assests/img/honestlogo.png') }}" class="h-12 hidden sm:block mx-3">

                {{-- <button type="button" id="startRecordingBtn" class="text-red-600 font-semibold ml-4 px-3 py-2">
                    Start Recording
                </button>

                <button type="button" id="stopRecordingBtn" class="text-red-600 font-semibold ml-4 px-3 py-2 hidden">
                    Stop Recording
                </button> --}}
                <!-- Submit Report -->
                <button type="button" id="submitReportBtn" class="text-red-600 font-semibold ml-4">
                    Submit Report
                </button>

                <!-- End Meeting -->
                <a href="#" id="endMeetingBtn"
                    class="ml-6 px-4 py-2 rounded-full font-semibold text-gray-400 border-2 border-gray-300 cursor-not-allowed">
                    End Meeting
                </a>

            </div>
            <!-- Form Panel -->
            <div
                class="w-[35%] mt-[100px] mb-10 h-[calc(100%-120px)] fixed top-0 right-5 z-50 rounded-md overflow-y-auto">
                <div class="bg-white p-6 rounded-lg shadow-xl w-full">
                    <h5 class="text-xl font-bold mb-2 text-center">MEDICAL EXAMINER'S REPORT</h5>
                    <p class="text-center text-sm text-gray-500 mb-4">Form No LIC03-001 (Revised 2020)</p>

                    <input type="hidden" name="meeting_id" value="{{ $meetingId ?? '' }}">
                    <input type="hidden" name="isDoctor" value="1">
                    <input type="hidden" name="client_id" value="{{ $patientId ?? '' }}">


                    <input type="hidden" name="doctor_latitude" id="doctor_latitude">
                    <input type="hidden" name="doctor_longitude" id="doctor_longitude">

                    <input type="hidden" name="customer_latitude" id="customer_latitude">
                    <input type="hidden" name="customer_longitude" id="customer_longitude">
                    <!-- Header Information -->
                    <div class="border-b pb-4 mb-4 bg-gray-50 p-3 rounded">
                        <h6 class="font-semibold text-md mb-2">Branch & Policy Details</h6>

                        <label class="block text-sm font-medium text-gray-700">Branch Code</label>
                        <input type="text" name="branch_code" value="{{ old('branch_code', $branch ?? '') }}"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Proposal/Policy No</label>
                        <input type="text" name="policy_no"
                            value="{{ old('proposal_number', $proposal_number ?? '') }}"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">MSP name/code</label>
                        <input type="text" name="msp_code" value="MSP000021"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Date & Time of Examination</label>
                        <input type="datetime-local" name="exam_datetime"
                            value="{{ now()->setTimezone('Asia/Kolkata')->format('Y-m-d\TH:i') }}"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Medical Diary No & Page No</label>
                        <input type="text" name="medical_diary_no" placeholder="-"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Mobile No of the Proposer/Life to be
                            assured</label>
                        <input type="tel" name="mobile_no" value="{{ old('phone', $phone ?? '') }}"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">MER Done</label>
                        <select name="mer_done" class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                            <option value="Video MER">Video MER</option>
                            <option value="Physical MER">Physical MER</option>
                        </select>

                        <label class="block text-sm font-medium text-gray-700">Identity Proof Verified</label>
                        <select name="identity_proof_type"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                            <option value="Pan Card">Pan Card</option>
                            <option value="Aadhaar Card">Aadhaar Card</option>
                            <option value="Voter ID">Voter ID</option>
                            <option value="Passport">Passport</option>
                        </select>

                        <label class="block text-sm font-medium text-gray-700">ID Proof No</label>
                        <input type="text" name="id_proof_no" placeholder="AIPPD1724M"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500">Note: In Case of Aadhaar Card, please mention only last four
                            digits</p>

                    </div>

                    <!-- Consent Section -->
                    <div class="border-b pb-4 mb-4 bg-yellow-50 p-3 rounded">
                        <h6 class="font-semibold text-md mb-2">Consent Recording</h6>
                        <p class="text-sm italic mb-2">"I would like to inform that this call with/ visit to Dr.
                            ____________________ (Name of the Medical Examiner) is for conducting your Medical
                            Examination through Tele/ Video/ Physical Examination on behalf of LIC of India".</p>

                        <label class="block text-sm font-medium text-gray-700">Medical Examiner Name for
                            Consent</label>
                        <input type="text" name="examiner_consent_name" value="{{ $doctor_name ?? '' }}"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Consent Recording Method</label>
                        <select name="consent_method" class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="email">Email Recording</option>
                            <option value="audio">Audio Message</option>
                            <option value="video">Video Message</option>
                        </select>

                    </div>

                    <!-- Basic Information -->
                    <div class="border-b pb-4 mb-4">
                        <h6 class="font-semibold text-lg mb-3">1. Full name of the life to be assured</h6>
                        <input type="text" name="full_name" value="{{ $full_name ?? '' }}"
                            class="w-full p-2 mt-1 mb-3 border border-gray-300 rounded-md" required>

                        <h6 class="font-semibold text-lg mb-3">Date of Birth</h6>
                        <input type="date" id="dob" name="dob"
                            value="{{ $dob ? \Carbon\Carbon::parse($dob)->format('Y-m-d') : '' }}"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" id="age" name="age" min="0"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select name="gender" class="w-full p-2 mt-1 mb-3 border border-gray-300 rounded-md">
                            <option value="female"
                                {{ strtolower(trim($gender ?? '')) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="male" {{ strtolower(trim($gender ?? '')) == 'male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="other" {{ strtolower(trim($gender ?? '')) == 'other' ? 'selected' : '' }}>
                                Other</option>
                        </select>

                        <h6 class="font-semibold text-lg mb-3">3. Height & Weight</h6>
                        <label class="block text-sm font-medium text-gray-700">Height (In cms)</label>
                        <input type="number" step="0.01" name="height" value="156"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                        <label class="block text-sm font-medium text-gray-700">Weight (kgs)</label>
                        <input type="number" step="0.01" name="weight" value="65"
                            class="w-full p-2 mt-1 mb-3 border border-gray-300 rounded-md">

                        <h6 class="font-semibold text-lg mb-3">4. Required only in case of Physical MER</h6>
                        <label class="block text-sm font-medium text-gray-700">Pulse</label>
                        <input type="text" name="pulse" value="NA"
                            class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                        <label class="block text-sm font-medium text-gray-700">Blood Pressure (1st reading)</label>
                        <div class="flex gap-2">
                            <input type="text" name="bp_systolic_1" value="NA"
                                class="w-1/2 p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                            <input type="text" name="bp_diastolic_1" value="NA"
                                class="w-1/2 p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                        </div>

                        <label class="block text-sm font-medium text-gray-700">Blood Pressure (2nd reading)</label>
                        <div class="flex gap-2">
                            <input type="text" name="bp_systolic_2" value="NA"
                                class="w-1/2 p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                            <input type="text" name="bp_diastolic_2" value="NA"
                                class="w-1/2 p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <!-- Medical History Questions -->
                    <div class="border-b pb-4 mb-4">
                        <h6 class="font-semibold text-lg mb-3">ASCERTAIN THE FOLLOWING FROM THE PERSON BEING EXAMINED
                        </h6>
                        <p class="text-sm text-gray-600 mb-3 italic">If answer/s to any of the following questions is
                            Yes, please give full details and ask life to be assured to submit copies of all treatment
                            papers, investigation reports, histopathology report, discharge card, follow up reports etc.
                            along with the proposal form to the Corporation</p>

                        <!-- Question 5a -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">5a. Whether receiving or ever received any treatment/
                                medication including alternate medicine like Ayurveda, homeopathy etc?</p>
                            <select name="q5a_treatment" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q5a_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q5a_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">i. Date of
                                    surgery/accident/injury/hospitalisation</label>
                                <input type="date" name="q5a_date"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">ii. Nature and cause</label>
                                <input type="text" name="q5a_nature"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">iii. Name of Medicine</label>
                                <input type="text" name="q5a_medicine"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">iv. Degree of impairment if any</label>
                                <input type="text" name="q5a_impairment"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">v. Whether unconscious due to accident, if
                                    yes, give duration</label>
                                <input type="text" name="q5a_unconscious"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium mt-2">Upload Medical Documents</label>
                                <input type="file" name="q5a_documents[]" multiple accept="image/*,.pdf"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Question 5b -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">5b. Undergone any surgery / hospitalized for any medical
                                condition / disability / injury due to accident?</p>
                            <select name="q5b_surgery" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q5b_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q5b_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">i. Date of
                                    surgery/accident/injury/hospitalisation</label>
                                <input type="date" name="q5b_date"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">ii. Nature and cause</label>
                                <input type="text" name="q5b_nature"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">iii. Name of Medicine</label>
                                <input type="text" name="q5b_medicine"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">iv. Degree of impairment if any</label>
                                <input type="text" name="q5b_impairment"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">v. Whether unconscious due to accident, if
                                    yes, give duration</label>
                                <input type="text" name="q5b_unconscious"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium mt-2">Upload Surgery/Hospitalization
                                    Documents</label>
                                <input type="file" name="q5b_documents[]" multiple accept="image/*,.pdf"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Question 5c -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">5c. Whether visited the doctor any time in the last 5 years?
                            </p>
                            <select name="q5c_doctor_visit" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q5c_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q5c_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">i. Date of
                                    surgery/accident/injury/hospitalisation</label>
                                <input type="date" name="q5c_date"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">ii. Nature and cause</label>
                                <input type="text" name="q5c_nature"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">iii. Name of Medicine</label>
                                <input type="text" name="q5c_medicine"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">iv. Degree of impairment if any</label>
                                <input type="text" name="q5c_impairment"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">v. Whether unconscious due to accident, if
                                    yes, give duration</label>
                                <input type="text" name="q5c_unconscious"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">

                            </div>
                        </div>

                        <!-- Question 6 (structured) -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">6. In the last 5 years, if advised to undergo an X-ray/ CT scan
                                / MRI / ECG / TMT / Blood test / Sputum/Throat swab test or any other investigatory or
                                diagnostic tests?</p>
                            <p class="text-xs text-gray-500 mb-2">Please specify date, reason, advised by whom &
                                findings.</p>
                            <select name="q6_diagnostic_tests" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q6_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q6_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Date</label>
                                <input type="date" name="q6_date"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">Reason</label>
                                <input type="text" name="q6_reason"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">Advised by whom</label>
                                <input type="text" name="q6_advised_by"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium">Findings</label>
                                <textarea name="q6_findings" class="w-full p-2 border border-gray-300 rounded-md" rows="2"></textarea>

                            </div>
                        </div>

                        <!-- Question 7 -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">7. Suffering or ever suffered from Novel Coronavirus (Covid-19)
                                or experienced any of the symptoms (for more than 5 days) such as any fever, Cough,
                                Shortness of breath, Malaise (flu-like tiredness), Rhinorrhoea (mucus discharge from the
                                nose), Sore throat, Gastro-intestinal symptoms such as nausea, vomiting and/or
                                diarrhoea, Chills, Repeated shaking with chills, Muscle pain, Headache, Loss of taste or
                                smell within last 14 days.</p>
                            <select name="q7_covid" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q7_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q7_details" class="hidden mt-3">
                                <textarea name="q7_details" placeholder="Please provide details of COVID infection, dates, symptoms, treatment"
                                    class="w-full p-2 border border-gray-300 rounded-md" rows="3"></textarea>
                                <label class="block text-sm font-medium mt-2">Upload COVID Reports</label>
                                {{-- <input type="file" name="q7_documents[]" multiple accept="image/*,.pdf" class="w-full p-2 mt-1 border border-gray-300 rounded-md"> --}}
                            </div>
                        </div>

                        <!-- Question 8 (fixed subfields) -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">8a. Suffering from Hypertension (high blood pressure) or
                                diabetes or blood sugar levels higher than normal or history of sugar /albumin in urine?
                            </p>
                            <select name="q8_hypertension_diabetes"
                                class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q8_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q8_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">b. Since when, any follow up and date and
                                    value of last checked blood pressure and sugar levels?</label>
                                <textarea name="q8b_details" class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md" rows="2"></textarea>

                                <label class="block text-sm font-medium">c. Whether on medication? please give name of
                                    the prescribed medicine and dosage</label>
                                <input type="text" name="q8c_medication"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium">d. Whether developed any complications due to
                                    diabetes?</label>
                                <input type="text" name="q8d_complications"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium">e. Whether suffering from any other endocrine
                                    disorders such as thyroid disorder etc.?</label>
                                <input type="text" name="q8e_endocrine"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium">f. Any weight gain or weight loss in last 12
                                    months (other than by diet control or exercise)?</label>
                                <input type="text" name="q8f_weight_change"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">


                            </div>
                        </div>

                        <!-- Question 9 (unchanged, correct) -->
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">9a. Any history of chest pain, heart attack, palpitations and
                                breathlessness on exertion or irregular heartbeat?</p>
                            <select name="q9a_heart_history" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q9_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q9_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">b. Whether suffering from high
                                    cholesterol?</label>
                                <input type="text" name="q9b_cholesterol"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium">c. Whether on medication for any heart
                                    ailment/ high cholesterol? Please state name of the prescribed medicine and
                                    dosage.</label>
                                <input type="text" name="q9c_medication"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium">d. Whether undergone Surgery such as CABG,
                                    open heart surgery or PTCA?</label>
                                <input type="text" name="q9d_surgery"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">

                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">10a. Suffering or ever suffered from any disease related to
                                kidney
                                such as kidney failure, kidney or ureteral stones, blood or pus
                                in urine or prostate? </p>
                            <select name="q10_kidney" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q10_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q10_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q10_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">11a.Suffering or ever suffered from any Liver disorders like
                                cirrhosis, hepatitis, jaundice, or disorder of the Spleen or from
                                any lung related or respiratory disorders such as Asthma,
                                bronchitis, wheezing, tuberculosis breathing difficulties etc.? </p>
                            <select name="q11_liver_respiratory" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q11_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q11_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q11_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">12a.Suffering or ever suffered from any Blood disorder like
                                anaemia, thalassemia or any Circulatory disorder? </p>
                            <select name="q12_blood_disorders" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q12_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q12_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q12_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">13a.Suffering or ever suffered from any form of cancer,
                                leukaemia,
                                tumor, cyst or growth of any kind or enlarged lymph nodes? </p>
                            <select name="q13_cancer" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q13_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q13_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q13_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">14a. Suffering or ever suffered from Epilepsy, nervous
                                disorder,
                                multiple sclerosis, tremors, numbness, paralysis, brain stroke? </p>
                            <select name="q14_neurological" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q14_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q14_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q14_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">15a. Suffering or ever suffered from any physical impairment/
                                disability /amputation or any congenital disease/abnormality or
                                disorder of back, neck, muscle, joints, bones, arthritis or gout? </p>
                            <select name="q15_physical_impairment"
                                class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q15_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q15_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q15_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">16a. Suffering or ever suffered from Hernia or disorder of the
                                Stomach / intestines, colitis, indigestion, Peptic ulcer, piles, or
                                any other disease of the gall bladder or pancreas? </p>
                            <select name="q16_digestive" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q16_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q16_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q16_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">17a.Suffering from Depression/Stress/ Anxiety/ Psychosis or any
                                other Mental / psychiatric disorder? </p>
                            <select name="q17a_mental" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q17_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q17a_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q17a_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">17b. Whether on treatment or ever taken any treatment, if yes,
                                please give details of treatment, prescribed medicine and
                                dosages </p>
                            <select name="q17b_question" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q17b_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q17b_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q17b_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">18. Is there any abnormality of Eyes (partial/total
                                blindness),Ears
                                (deafness/ discharge from the ears), Nose, Throat or
                                Mouth,teeth, swelling of gums / tongue, tobacco stains or signs
                                of oral cancer? </p>
                            <select name="q18_ent" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q18a_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q18a_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q18a_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">19. Whether person being examined and/ or his/her
                                spouse/partner
                                tested positive or is/ are under treatment for HIV
                                /AIDS/Sexually transmitted diseases (e.g. syphilis,
                                gonorrhea, etc.) </p>
                            <select name="q19_hiv_sti" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q19_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q19_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q19_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">


                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="font-medium mb-2">20. Ascertain if any other condition / disease / adverse habit
                                (such
                                as smoking/ tobacco chewing/ consumption of
                                alcohol/drugs etc) which is relevant in assessment of medical
                                risk of examinee. </p>
                            <select name="q20_other" class="w-full p-2 border border-gray-300 rounded-md"
                                onchange="toggleDetails('q20a_details')">
                                <option value="NO">NO</option>
                                <option value="YES">YES</option>
                            </select>
                            <div id="q20a_details" class="hidden mt-3">
                                <label class="block text-sm font-medium">Remark</label>
                                <input type="text" name="q20a_details"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                        <!-- Questions 10-18,20 (identical to your original, just ensure file inputs are present) -->
                        <!-- For brevity, keep your existing code for q10 to q18 and q20 -->
                        <!-- ... (I'll assume you keep them as they are, but ensure no q19) ... -->

                        <!-- Female Specific Questions -->
                        <div class="border-b pb-4 mb-4">
                            <h6 class="font-semibold text-lg mb-3">For Female Proponents only</h6>
                            <div class="mb-4 p-3 bg-pink-50 rounded">
                                <p class="font-medium mb-2">i. Whether pregnant? If so duration.</p>
                                <input type="text" name="pregnancy_status" value="NA"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                <p class="font-medium mb-2 mt-3">ii. Suffering from any pregnancy related complications
                                </p>
                                <input type="text" name="pregnancy_complications" value="NA"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                <p class="font-medium mb-2 mt-3">iii. Whether consulted a gynaecologist or undergone
                                    any investigation, treatment for any gynaecological such as fibroid, cyst or any
                                    disease of the breasts, uterus, cervix or ovaries etc. or taken / taking any
                                    treatment for the same</p>
                                <input type="text" name="gynecological_issues" value="NA"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                <label class="block text-sm font-medium mt-3">Upload Gynaecological Reports</label>
                                {{-- <input type="file" name="gynae_documents[]" multiple accept="image/*,.pdf" class="w-full p-2 mt-1 border border-gray-300 rounded-md"> --}}
                            </div>
                        </div>

                        <!-- Medical Examiner's Observation -->
                        <div class="border-b pb-4 mb-4">
                            <h6 class="font-semibold text-lg mb-3">FROM MEDICAL EXAMINER'S OBSERVATION/ASSESSMENT</h6>
                            <div class="mb-4 p-3 bg-green-50 rounded">
                                <p class="font-medium mb-2">WHETHER LIFE TO BE ASSURED APPEARS MENTALLY AND PHYSICALLY
                                    HEALTHY</p>
                                <select name="appears_healthy" class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <textarea name="healthy_notes" placeholder="If No, please provide details"
                                    class="w-full p-2 mt-2 border border-gray-300 rounded-md" rows="2"></textarea>
                            </div>
                        </div>

                        <!-- Declaration Section -->
                        <div class="border-b pb-4 mb-4">
                            <h6 class="font-semibold text-lg mb-3">Declaration</h6>
                            <div class="mb-4 p-3 bg-blue-50 rounded">
                                <p class="text-sm mb-3"><strong>Declaration by Life Assured:</strong> I MR./MRS. <input
                                        type="text" name="declaration_name" value="{{ $full_name ?? '' }}"
                                        class="inline-block w-40 border-b border-gray-400"> that you have fully
                                    understood the questions asked to you during the Video call Examination and have
                                    furnished complete, true and accurate information after fully understanding the
                                    same. We thank you for having taken the time to confirm the details. The information
                                    provided will be passed on to Life Insurance Corporation of India for further
                                    processing.</p>

                                {{-- <label class="block text-sm font-medium text-gray-700 mt-3">Signature/Thumb impression of Life to be assured (In case of Physical Examination)</label>
                        <input type="file" name="life_assured_signature" accept="image/*" class="w-full p-2 mt-1 border border-gray-300 rounded-md"> --}}

                                <label class="block text-sm font-medium text-gray-700 mt-3">Upload Declaration
                                    Video/Screenshot</label>
                                <input type="file" name="declaration_proof" accept="image/*,.pdf,.mp4"
                                    class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            </div>

                            <div class="mb-4 p-3 bg-blue-50 rounded">
                                <p class="text-sm mb-3"><strong>Medical Examiner's Certificate:</strong> I hereby
                                    certify that I have assessed/ examined the above life to be assured on the Day
                                    <input type="date" name="certificate_date"
                                        value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                        class="inline-block w-40"> Video call Examination personally and recorded true
                                    and correct findings to the aforesaid questions as ascertained from the life to be
                                    assured.
                                </p>

                                <label class="block text-sm font-medium text-gray-700">Place</label>
                                <input type="text" name="examiner_place" placeholder="Mumbai"
                                    class="w-full p-2 mt-1 mb-3 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="examiner_date"
                                    value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                    class="w-full p-2 mt-1 mb-3 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium text-gray-700">
                                    Signature of Medical Examiner
                                </label>


                                {{-- <!-- ✅ Upload new -->
                                <input type="file" name="examiner_signature" id="examiner_signature"
                                    accept="image/*" class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md"> --}}
                                <input type="hidden" name="examiner_signature"
                                    value="{{ $examiner_signature ?? '' }}">


                                <label class="block text-sm font-medium text-gray-700">Doctor Name</label>
                                <input type="text" name="doctor_name" value="{{ $doctor_name ?? '' }}"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <input type="hidden" name="old_doctor_photo" value="{{ $doctor_photo ?? '' }}">

                                <label class="block text-sm font-medium text-gray-700">Doctor Qualification</label>
                                <input type="text" name="doctor_qualification"
                                    value="{{ $doctor_qualification ?? '' }}"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <label class="block text-sm font-medium text-gray-700">Doctor Regn. No</label>
                                <input type="text" name="doctor_registration_number"
                                    value="{{ $doctor_reg_no ?? '' }}"
                                    class="w-full p-2 mt-1 mb-2 border border-gray-300 rounded-md">

                                <input type="hidden" name="examiner_stamp" value="{{ $examiner_stamp ?? '' }}">
                            </div>
                        </div>

                        <!-- Additional Documents -->
                        <div class="mb-4 p-3 bg-gray-100 rounded">
                            <h6 class="font-semibold text-lg mb-3">Additional Documents</h6>
                            <p class="text-sm text-gray-600 mb-2">Upload any other relevant documents, reports, or
                                screenshots</p>
                            <input type="file" name="additional_documents" multiple
                                accept="image/*,.pdf,.doc,.docx" class="w-full p-2 border border-gray-300 rounded-md">
                            <label class="block text-sm font-medium text-gray-700 mt-2">Upload Consent
                                Screenshot/Recording</label>
                            <input type="file" name="consent_proof" accept="image/*,.pdf,.mp4"
                                class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <label class="block text-sm font-medium mt-2">Upload standing Screen shot</label>
                            <input type="file" name="q5c_documents" accept="image/*,.pdf"
                                class="w-full p-2 mt-1 border border-gray-300 rounded-md">

                            <label class="block text-sm font-medium text-gray-700 mt-2">Upload ID Proof
                                Screenshot</label>
                            <input type="file" name="id_proof_screenshot" accept="image/*,.pdf"
                                class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                        </div>

                        <input type="checkbox" name="final_declaration" value="1" required class="mr-2">
                        <label class="text-sm">I confirm that all information provided is true and accurate to the best
                            of my knowledge</label>
                    </div>
                </div>
            </div>
        </form>

        <script>
            function toggleDetails(detailId) {
                const select = event.target;
                const detailsDiv = document.getElementById(detailId);
                const inputs = detailsDiv.querySelectorAll('input');
                if (select.value === 'YES') {
                    detailsDiv.classList.remove('hidden');
                    inputs.forEach(input => {
                        input.setAttribute('required', 'required');
                    });

                } else {
                    detailsDiv.classList.add('hidden');
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            }
        </script>

        <style>
            .hidden {
                display: none !important;
            }

            input,
            select,
            textarea {
                transition: all 0.3s ease;
            }

            input:focus,
            select:focus,
            textarea:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            }
        </style>
    @endif
</body>
<div id="pdfModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-60"
    style="z-index:999999;">

    <div class="bg-white w-[95%] h-[90%] rounded-lg shadow-lg p-4 relative flex flex-col">

        <!-- Close -->
        <button type="button" onclick="closeModal(event)"
            class="absolute top-2 right-3 text-gray-700 text-2xl font-bold">

            &times;

        </button>

        <!-- Title -->
        <h2 class="text-lg font-semibold mb-3">
            Medical Report Preview
        </h2>

        <!-- PDF -->
        <iframe id="pdfFrame" src="" class="w-full flex-1 border rounded">
        </iframe>

        <!-- Actions -->
        <div class="mt-4 flex justify-end gap-3">

            <a href="" id="downloadPdfBtn" download
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

                Download PDF

            </a>

            <button type="button" onclick="closeModal(event)"
                class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">

                Close

            </button>

        </div>
    </div>
</div>
{{-- <script>
    let isSubmitted = false;
    document.addEventListener("DOMContentLoaded", function() {

        // let modal = document.getElementById('pdfModal');

        // if (modal) {
        //     modal.style.display = 'flex';

        //     // 🔥 Hide Zoom behind modal
        //     let zoom = document.getElementById('zoomContainer');
        //     if (zoom) zoom.style.display = 'none';
        // }

        // ✅ End Meeting Button Logic
        const btn = document.getElementById('endMeetingBtn');

        if (!btn) return;

        // let isSubmitted = {{ session('report_submitted') ? 'true' : 'false' }};

        if (isSubmitted) {
            btn.classList.remove('text-gray-400', 'border-gray-300', 'cursor-not-allowed');
            btn.classList.add('text-blue-600', 'border-blue-600', 'hover:bg-blue-50');
            btn.href = "{{ route('assignpatients') }}";
        }

        btn.addEventListener('click', function(e) {
            if (!isSubmitted) {
                e.preventDefault();
                alert('Please submit report first');
            }
        });

    });


    function closeModal(event) {

        // Prevent form submit
        if (event) {
            event.preventDefault();
        }

        document.getElementById('pdfModal').style.display = 'none';

        // Show Zoom again
        let zoom = document.getElementById('zoomContainer');

        if (zoom) {
            zoom.style.display = 'block';
        }
    }
</script> --}}

<script>
    // GLOBAL
    let isSubmitted = false;

    document.addEventListener("DOMContentLoaded", function() {

        const btn = document.getElementById('endMeetingBtn');

        if (!btn) return;

        btn.addEventListener('click', function(e) {

            if (!isSubmitted) {

                e.preventDefault();

                alert('Please submit report first');
            }
        });

    });

    function closeModal(event) {

        if (event) {
            event.preventDefault();
        }

        let modal = document.getElementById('pdfModal');

        modal.classList.add('hidden');

        modal.classList.remove('flex');
    }
</script>
<script>
    document.getElementById('dob').addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();

        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();

        // Adjust if birthday hasn't occurred yet this year
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        document.getElementById('age').value = age >= 0 ? age : '';
    });

    // 🔥 Trigger on page load (for edit case)
    window.addEventListener('load', function() {
        const dobField = document.getElementById('dob');
        if (dobField.value) {
            dobField.dispatchEvent(new Event('change'));
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (!navigator.geolocation) {
            console.error("Geolocation not supported");
            return;
        }

        navigator.geolocation.getCurrentPosition(

            async function(position) {

                    try {

                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        console.log("Coordinates:", lat, lng);

                        // Reverse Geocode
                        const geoResponse = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`, {
                                headers: {
                                    "Accept-Language": "en"
                                }
                            }
                        );

                        const geoData = await geoResponse.json();

                        console.log("Geo Data 🚀🚀:", geoData);

                        const address = geoData.display_name || null;
                        const pincode = geoData.address?.postcode || null;

                        const response = await fetch(
                            "{{ route('save.meeting.location') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "Accept": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    meeting_id: "{{ $meetingId }}",
                                    latitude: lat,
                                    longitude: lng,
                                    address: address,
                                    pincode: pincode,
                                    is_doctor: @json(request()->has('doctor_access'))
                                })
                            }
                        );

                        const result = await response.json();

                        console.log("Location Saved:", result);

                    } catch (error) {

                        console.error("Location Save Error:", error);
                    }
                },

                function(error) {
                    console.error("Geolocation Error:", error);
                }

        );

    });
</script>
<script>
    document.getElementById('submitReportBtn')
        .addEventListener('click', function() {

            let form = document.getElementById('reportdata');

            let formData = new FormData(form);

            fetch("{{ route('storereport') }}", {

                    method: "POST",

                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    body: formData

                })
                .then(async response => {

                    let data = await response.json();

                    console.log(data);

                    if (data.success) {

                        isSubmitted = true;
                        // Enable End Meeting Button
                        const btn = document.getElementById('endMeetingBtn');

                        btn.classList.remove(
                            'text-gray-400',
                            'border-gray-300',
                            'cursor-not-allowed'
                        );

                        btn.classList.add(
                            'text-blue-600',
                            'border-blue-600',
                            'hover:bg-blue-50'
                        );

                        btn.href = "{{ route('assignpatients') }}";

                        // OPEN MODAL
                        let modal = document.getElementById('pdfModal');

                        modal.classList.remove('hidden');

                        modal.classList.add('flex');

                        // PDF
                        document.getElementById('pdfFrame').src =
                            data.pdf_url;

                        // Download
                        document.getElementById('downloadPdfBtn').href =
                            data.pdf_url;

                        alert('Report submitted successfully');

                    } else {

                        alert(data.message || 'Something went wrong');
                    }

                })
                .catch(error => {

                    console.error(error);

                    alert('Server error occurred');
                });

        });
</script>
<script>
    let mediaRecorder;
    let recordedChunks = [];
    let recordingStream = null;
    let screenStream = null;
    let micStream = null;
    let audioContext = null;

    async function startRecording() {
        try {
            screenStream =
                await navigator.mediaDevices.getDisplayMedia({
                    video: {
                        displaySurface: "browser",
                        frameRate: 30
                    },
                    audio: {
                        suppressLocalAudioPlayback: false
                    },
                    preferCurrentTab: true,
                    selfBrowserSurface: "include",
                    surfaceSwitching: "exclude",
                    systemAudio: "include",
                    monitorTypeSurfaces: "exclude"
                });
            micStream =
                await navigator.mediaDevices.getUserMedia({
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true,
                        sampleRate: 44100
                    }
                });
            audioContext = new AudioContext();
            const destination =
                audioContext.createMediaStreamDestination();
            if (
                screenStream.getAudioTracks().length > 0
            ) {
                const systemSource =
                    audioContext.createMediaStreamSource(
                        screenStream
                    );
                systemSource.connect(destination);
            }

            if (
                micStream.getAudioTracks().length > 0
            ) {
                const micSource =
                    audioContext.createMediaStreamSource(
                        micStream
                    );
                micSource.connect(destination);
            }
            recordingStream = new MediaStream([
                ...screenStream.getVideoTracks(),
                ...destination.stream.getAudioTracks()
            ]);
            mediaRecorder = new MediaRecorder(
                recordingStream, {
                    mimeType: 'video/webm;codecs=vp8,opus'
                }
            );
            recordedChunks = [];
            mediaRecorder.ondataavailable = (
                event
            ) => {
                if (
                    event.data &&
                    event.data.size > 0
                ) {
                    recordedChunks.push(event.data);
                }
            };
            screenStream
                .getVideoTracks()[0]
                .onended = () => {
                    stopRecording();
                };
            mediaRecorder.start();
            document
                .getElementById(
                    'startRecordingBtn'
                )
                .classList.add('hidden');

            document
                .getElementById(
                    'stopRecordingBtn'
                )
                .classList.remove('hidden');

            console.log(
                'Recording started'
            );
        } catch (err) {
            console.error(err);
            alert(
                'Recording permission denied'
            );
        }
    }
    async function stopRecording() {

        try {
            if (
                mediaRecorder &&
                mediaRecorder.state !== 'inactive'
            ) {
                await new Promise((resolve) => {
                    mediaRecorder.onstop =
                        async () => {

                            try {
                                const blob =
                                    new Blob(
                                        recordedChunks, {
                                            type: 'video/webm'
                                        }
                                    );

                                console.log(
                                    'Blob Size:',
                                    blob.size
                                );
                                if (
                                    blob.size === 0
                                ) {
                                    alert(
                                        'Recording failed'
                                    );
                                    resolve();
                                    return;
                                }
                                const formData =
                                    new FormData();

                                formData.append(
                                    'recording',
                                    blob,
                                    'meeting-recording.webm'
                                );

                                formData.append(
                                    'meeting_id',
                                    "{{ $meetingId }}"
                                );

                                const response =
                                    await fetch(
                                        "{{ route('upload.recording') }}", {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                            },
                                            body: formData
                                        }
                                    );

                                const data =
                                    await response.json();
                                console.log(
                                    data
                                );
                                if (
                                    data.success
                                ) {
                                    alert(
                                        'Recording uploaded successfully'
                                    );

                                    console.log(
                                        'Recording URL:',
                                        data.path
                                    );

                                } else {
                                    alert(
                                        'Upload failed'
                                    );
                                }

                            } catch (err) {
                                console.error(
                                    err
                                );

                                alert(
                                    'Upload failed'
                                );
                            }

                            resolve();
                        };

                    mediaRecorder.stop();
                });
            }

            if (recordingStream) {
                recordingStream
                    .getTracks()
                    .forEach(track => {
                        track.stop();
                    });
            }

            if (screenStream) {
                screenStream
                    .getTracks()
                    .forEach(track => {
                        track.stop();
                    });
            }

            if (micStream) {
                micStream
                    .getTracks()
                    .forEach(track => {

                        track.stop();
                    });
            }

            if (
                audioContext &&
                audioContext.state !== 'closed'
            ) {

                await audioContext.close();
            }

            mediaRecorder = null;

            recordedChunks = [];

            recordingStream = null;

            screenStream = null;

            micStream = null;

            audioContext = null;

            document
                .getElementById(
                    'startRecordingBtn'
                )
                .classList.remove('hidden');

            document
                .getElementById(
                    'stopRecordingBtn'
                )
                .classList.add('hidden');

            console.log(
                'Recording stopped'
            );

        } catch (err) {

            console.error(err);
        }
    }

    document
        .getElementById(
            'startRecordingBtn'
        )
        .addEventListener(
            'click',
            startRecording
        );

    document
        .getElementById(
            'stopRecordingBtn'
        )
        .addEventListener(
            'click',
            stopRecording
        );
</script>

</html>
