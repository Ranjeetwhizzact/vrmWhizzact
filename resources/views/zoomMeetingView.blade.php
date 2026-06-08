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
        html, body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            min-height: 100vh;
            overflow-y: auto !important;
        }

        /* Hide Zoom SDK default branding & headers */
        .zoom-workplace-logo,
        .meeting-header,
        .footer__leave-btn-container {
            display: none !important;
        }

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

        /* --- DOCTOR VIEW STYLING --- */
        @if (request()->has('doctor_access'))
            .doctor-controls-capsule {
                position: fixed !important;
                z-index: 10002 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                background-color: #1a1a1a !important; /* Dark Zoom theme background */
                border: 1px solid #2d2d2d !important;
                border-radius: 6px !important; /* Matches Zoom control bar corners */
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2) !important;
            }

            @media (min-width: 768px) {
                .doctor-controls-capsule {
                    top: 20px !important;
                    left: 20px !important;
                    padding: 8px 16px !important;
                    gap: 12px !important;
                }
            }

            @media (max-width: 767px) {
                .doctor-controls-capsule {
                    top: 10px !important;
                    left: 2% !important;
                    width: 96% !important;
                    height: 50px !important;
                    padding: 0 16px !important;
                }
            }

            /* Minimized Form Panel State - Form Icon only */
            .doctor-form-panel.minimized {
                position: fixed !important;
                bottom: 24px !important;
                right: 24px !important;
                left: auto !important;
                top: auto !important;
                width: 60px !important;
                height: 60px !important;
                border-radius: 50% !important;
                background-color: #1e293b !important; /* Premium Slate-800 dark theme */
                border: 2px solid #ffffff !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4) !important;
                cursor: pointer !important;
                overflow: hidden !important;
                z-index: 10005 !important;
                transition: transform 0.2s ease, background-color 0.2s ease !important;
            }
            .doctor-form-panel.minimized:hover {
                transform: scale(1.08);
                background-color: #0f172a !important; /* Premium hover color Slate-900 */
            }

            /* Animation transition when minimizing/maximizing */
            .doctor-form-panel.animating {
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                            height 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                            border-radius 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                            box-shadow 0.3s ease !important;
            }

            .doctor-form-panel.minimized #formDragHandle {
                width: 100% !important;
                height: 100% !important;
                background: transparent !important;
                padding: 0 !important;
                border-radius: 50% !important;
                justify-content: center !important;
                align-items: center !important;
                display: flex !important;
            }

            .doctor-form-panel.minimized #formResizeHandle {
                display: none !important;
            }

            /* Submit & End buttons styled to match Zoom SDK Theme */
            #submitReportBtn {
                background-color: #0E71EB !important;
                color: #ffffff !important;
                border: none !important;
                font-size: 13px !important;
                padding: 6px 14px !important;
                border-radius: 4px !important;
                font-weight: 600 !important;
                cursor: pointer !important;
                transition: background-color 0.2s !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            }
            #submitReportBtn:hover {
                background-color: #0c62cc !important;
            }
            #submitReportBtn:disabled {
                background-color: #2d2d2d !important;
                color: #555555 !important;
                cursor: not-allowed !important;
            }

            #endMeetingBtn {
                background-color: #2d2d2d !important;
                color: #777777 !important;
                border: none !important;
                font-size: 13px !important;
                padding: 6px 14px !important;
                border-radius: 4px !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                display: inline-block !important;
                cursor: not-allowed !important;
                transition: all 0.2s !important;
            }

            #endMeetingBtn.active-btn {
                background-color: #DE2828 !important;
                color: #ffffff !important;
                cursor: pointer !important;
            }
            #endMeetingBtn.active-btn:hover {
                background-color: #c02222 !important;
            }

            /* Main Zoom Video */
            #sv-active-video,
            #sv-active-speaker-view,
            .active-main,
            .main-layout,
            .single-main-container__main-view {
                width: 100% !important;
                height: 100% !important;
            }

            /* Floating Self Preview - Put on the left side of the screen */
            .suspension-window {
                width: 220px !important;
                height: 160px !important;
                left: 20px !important;
                right: auto !important;
                border-radius: 10px !important;
                overflow: hidden !important;
                z-index: 10000 !important;
            }

            /* Prevent multiple preview positions and stack them on the left */
            .suspension-window:nth-of-type(1) {
                bottom: 100px !important;
                top: auto !important;
            }
            .suspension-window:nth-of-type(2) {
                top: 120px !important;
                bottom: auto !important;
            }
            .suspension-window:nth-of-type(3) {
                top: 300px !important;
                bottom: auto !important;
            }

            /* Video Fit */
            video {
                object-fit: cover !important;
            }

            /* Desktop/Tablet Layout (768px and up) */
            @media (min-width: 768px) {
                #meetingSDKElement,
                #zmmtg-root {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100vw !important;
                    height: 100vh !important;
                    z-index: 9999 !important;
                    overflow: hidden !important;
                    border-radius: 0px !important;
                }

                .doctor-form-panel {
                    position: fixed !important;
                    top: 100px; /* Allowed override by JS */
                    right: 20px; /* Allowed override by JS */
                    width: 400px !important;
                    height: calc(100vh - 180px);
                    z-index: 10001 !important; /* Floats above full-screen Zoom video */
                    display: flex !important;
                    flex-direction: column !important;
                    overflow: hidden !important;
                    border: 1px solid #e2e8f0;
                    background-color: white !important;
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                }

                #formDragHandle {
                    display: flex !important;
                    cursor: grab !important;
                }
                #formDragHandle:active {
                    cursor: grabbing !important;
                }

                #formResizeHandle {
                    display: block !important;
                }
            }

            /* Tablet Specific Refinements (768px to 1024px) */
            @media (min-width: 768px) and (max-width: 1024px) {
                .doctor-form-panel:not(.minimized) {
                    width: 340px !important;
                    right: 15px !important;
                    top: 90px !important;
                    height: calc(100vh - 150px) !important;
                }
            }

            /* Mobile Layout (below 768px) */
            @media (max-width: 767px) {
                #meetingSDKElement,
                #zmmtg-root {
                    position: fixed !important;
                    top: 70px !important;
                    left: 2% !important;
                    width: 96% !important;
                    height: 45vh !important;
                    z-index: 9999 !important;
                    overflow: hidden !important;
                    border-radius: 10px !important;
                }

                .doctor-form-panel {
                    position: absolute !important;
                    top: calc(85px + 45vh) !important;
                    left: 2% !important;
                    right: auto !important; /* Forces reset of desktop right positioning */
                    width: 96% !important;
                    height: auto !important;
                    margin-bottom: 40px !important;
                    z-index: 9998 !important;
                    overflow-y: visible !important;
                }

                #formDragHandle {
                    display: flex !important;
                    cursor: default !important;
                }

                #formResizeHandle {
                    display: none !important;
                }
            }
        @endif

        /* --- PATIENT/CUSTOMER VIEW STYLING --- */
        @if (!request()->has('doctor_access'))
            .patient-topbar {
                position: fixed !important;
                z-index: 10002 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                background-color: #1a1a1a !important; /* Dark Zoom theme background */
                border: 1px solid #2d2d2d !important;
                border-radius: 6px !important; /* Matches Zoom control bar corners */
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2) !important;
            }

            .patient-end-btn {
                background-color: #DE2828 !important;
                color: #ffffff !important;
                border: none !important;
                font-size: 13px !important;
                padding: 6px 14px !important;
                border-radius: 4px !important;
                font-weight: 600 !important;
                cursor: pointer !important;
                transition: background-color 0.2s !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            }

            .patient-end-btn:hover {
                background-color: #c02222 !important;
            }

            /* Responsive Topbar, Logo, End Button and Zoom Container */
            @media (min-width: 768px) {
                .patient-topbar {
                    top: 20px !important;
                    left: 20px !important;
                    padding: 8px 12px !important;
                }
            }

            @media (max-width: 767px) {
                .patient-topbar {
                    top: 10px !important;
                    left: 10px !important;
                    padding: 6px 10px !important;
                }
            }

            #meetingSDKElement,
            #zmmtg-root {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                z-index: 9999 !important;
                overflow: hidden !important;
                border-radius: 0px !important;
            }

            /* Main Zoom Video inside patient view */
            #sv-active-video,
            #sv-active-speaker-view,
            .active-main,
            .main-layout,
            .single-main-container__main-view {
                width: 100% !important;
                height: 100% !important;
            }

            /* Floating Self Preview for Patient */
            .suspension-window {
                width: 150px !important;
                height: 110px !important;
                right: 15px !important;
                top: 15px !important;
                left: auto !important;
                bottom: auto !important;
                border-radius: 8px !important;
                overflow: hidden !important;
                z-index: 10000 !important;
            }

            @media (min-width: 640px) {
                .suspension-window {
                    width: 220px !important;
                    height: 160px !important;
                }
            }

            /* Prevent multiple preview positions */
            .suspension-window:nth-of-type(1),
            .suspension-window:nth-of-type(2),
            .suspension-window:nth-of-type(3) {
                top: 15px !important;
            }

            /* Video Fit */
            video {
                object-fit: cover !important;
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
        @endif
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
    {{-- Scattered styles consolidated to head style block --}}

    @if (!request()->has('doctor_access'))
        <!-- Topbar -->
        <div class="patient-topbar shadow-md">
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

            <!-- Floating Controls Capsule -->
            <div class="doctor-controls-capsule">
                <!-- Submit Report -->
                <button type="button" id="submitReportBtn" class="px-4 py-2 rounded-full text-xs sm:text-sm font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 hover:text-red-700 transition duration-200 cursor-pointer shadow-sm">
                    Submit Report
                </button>

                <!-- End Meeting -->
                <a href="#" id="endMeetingBtn"
                    class="px-4 py-2 rounded-full text-xs sm:text-sm font-bold text-gray-400 border border-gray-300 bg-gray-50/50 cursor-not-allowed transition duration-200 shadow-sm">
                    End Meeting
                </a>
            </div>
            <!-- Form Panel -->
            <div class="doctor-form-panel bg-white shadow-xl rounded-lg">
                <!-- Drag Handle Header (Desktop only) -->
                <div id="formDragHandle" class="w-full bg-[#1e293b] text-white px-4 py-3 rounded-t-lg flex justify-between items-center select-none">
                    <!-- Maximized State Title -->
                    <div id="dragTitle" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="font-bold text-sm tracking-wide">MEDICAL EXAMINER'S REPORT</span>
                    </div>
                    <!-- Maximized State Controls -->
                    <div id="dragControls" class="flex items-center gap-3">
                        <span class="hidden md:inline-block text-[10px] bg-[#334155] border border-slate-600 px-2 py-1 rounded text-slate-200 font-semibold uppercase tracking-wider">Drag to Move</span>
                        <button type="button" id="minimizeFormBtn" onclick="toggleMinimizeForm(event)" class="text-white hover:bg-[#334155] focus:outline-none rounded p-1 flex items-center justify-center transition-colors duration-200" title="Minimize Form" style="width: 24px; height: 24px; cursor: pointer;">
                            <svg id="minimizeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                            </svg>
                            <svg id="maximizeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <!-- Minimized State Document Icon -->
                    <svg id="clipboardIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2 2 4-4" />
                    </svg>
                </div>
                <!-- Scrollable Form Body -->
                <div id="doctorFormBody" class="p-6 overflow-y-auto w-full flex-1">
                    <h5 class="text-xl font-bold mb-2 text-center">MEDICAL EXAMINER'S REPORT</h5>
                    <p class="text-center text-sm text-gray-500 mb-4">Form No LIC03-001 (Revised 2020)</p>

                    <input type="hidden" name="meeting_id" value="{{ $meetingId ?? '' }}">
                    <input type="hidden" name="isDoctor" value="1">
                    <input type="hidden" name="client_id" value="{{ $patientId ?? '' }}">
                    @if (request()->has('doctor_access'))
                        <input type="hidden" name="doctor_access" value="{{ request('doctor_access') }}">
                    @endif


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
                                <input type="text" name="examiner_place" value ="Mumbai - 400071"
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
                <!-- Custom Resize Handle (Tablet/Desktop only) -->
                <div id="formResizeHandle" class="absolute bottom-0 right-0 w-6 h-6 cursor-se-resize flex items-end justify-end p-0.5 z-[10003]" style="touch-action: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 19h-6M19 19v-6M19 13l-6 6" />
                    </svg>
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

            // Draggable Form Panel logic for Desktop/Tablet (screens >= 768px)
            function makeElementDraggable(elmnt, dragHandle) {
                let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                let isDragging = false;
                let startX = 0, startY = 0;

                dragHandle.addEventListener('mousedown', dragMouseDown);
                dragHandle.addEventListener('touchstart', dragTouchStart, { passive: false });

                function dragMouseDown(e) {
                    if (e.button !== 0) return; // Only left click

                    // Do not drag if clicking on minimize button inside maximized state
                    if (e.target.closest('#minimizeFormBtn')) return;

                    isDragging = false;
                    startX = e.clientX;
                    startY = e.clientY;

                    pos3 = e.clientX;
                    pos4 = e.clientY;

                    if (window.innerWidth >= 768) {
                        // Avoid Zoom SDK swallowing events
                        const zmmtgRoot = document.getElementById('zmmtg-root');
                        if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'none';
                        const meetingSDK = document.getElementById('meetingSDKElement');
                        if (meetingSDK) meetingSDK.style.pointerEvents = 'none';

                        document.body.style.userSelect = 'none';

                        document.addEventListener('mousemove', elementDrag);
                    }
                    document.addEventListener('mouseup', closeDragElement);
                }

                function dragTouchStart(e) {
                    if (e.target.closest('#minimizeFormBtn')) return;

                    isDragging = false;
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;

                    pos3 = e.touches[0].clientX;
                    pos4 = e.touches[0].clientY;

                    if (window.innerWidth >= 768) {
                        const zmmtgRoot = document.getElementById('zmmtg-root');
                        if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'none';
                        const meetingSDK = document.getElementById('meetingSDKElement');
                        if (meetingSDK) meetingSDK.style.pointerEvents = 'none';

                        document.body.style.userSelect = 'none';

                        document.addEventListener('touchmove', elementTouchDrag, { passive: false });
                    }
                    document.addEventListener('touchend', closeTouchDragElement);
                }

                function elementDrag(e) {
                    if (Math.abs(e.clientX - startX) > 4 || Math.abs(e.clientY - startY) > 4) {
                        isDragging = true;
                    }

                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;

                    const rect = elmnt.getBoundingClientRect();
                    let newTop = rect.top - pos2;
                    let newLeft = rect.left - pos1;

                    if (newTop < 0) newTop = 0;
                    if (newLeft < 0) newLeft = 0;
                    if (newLeft > window.innerWidth - rect.width) newLeft = window.innerWidth - rect.width;
                    if (newTop > window.innerHeight - rect.height) newTop = window.innerHeight - rect.height;

                    elmnt.style.setProperty('top', newTop + 'px', 'important');
                    elmnt.style.setProperty('left', newLeft + 'px', 'important');
                    elmnt.style.setProperty('right', 'auto', 'important');
                    elmnt.style.setProperty('bottom', 'auto', 'important');
                }

                function elementTouchDrag(e) {
                    if (e.cancelable) e.preventDefault();
                    if (Math.abs(e.touches[0].clientX - startX) > 4 || Math.abs(e.touches[0].clientY - startY) > 4) {
                        isDragging = true;
                    }

                    pos1 = pos3 - e.touches[0].clientX;
                    pos2 = pos4 - e.touches[0].clientY;
                    pos3 = e.touches[0].clientX;
                    pos4 = e.touches[0].clientY;

                    const rect = elmnt.getBoundingClientRect();
                    let newTop = rect.top - pos2;
                    let newLeft = rect.left - pos1;

                    if (newTop < 0) newTop = 0;
                    if (newLeft < 0) newLeft = 0;
                    if (newLeft > window.innerWidth - rect.width) newLeft = window.innerWidth - rect.width;
                    if (newTop > window.innerHeight - rect.height) newTop = window.innerHeight - rect.height;

                    elmnt.style.setProperty('top', newTop + 'px', 'important');
                    elmnt.style.setProperty('left', newLeft + 'px', 'important');
                    elmnt.style.setProperty('right', 'auto', 'important');
                    elmnt.style.setProperty('bottom', 'auto', 'important');
                }

                function closeDragElement() {
                    document.removeEventListener('mouseup', closeDragElement);
                    document.removeEventListener('mousemove', elementDrag);

                    const zmmtgRoot = document.getElementById('zmmtg-root');
                    if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'auto';
                    const meetingSDK = document.getElementById('meetingSDKElement');
                    if (meetingSDK) meetingSDK.style.pointerEvents = 'auto';

                    document.body.style.userSelect = 'auto';

                    // If it was a simple click on the minimized icon, maximize it!
                    if (!isDragging && elmnt.classList.contains('minimized')) {
                        toggleMinimizeForm();
                    }
                }

                function closeTouchDragElement() {
                    document.removeEventListener('touchend', closeTouchDragElement);
                    document.removeEventListener('touchmove', elementTouchDrag);

                    const zmmtgRoot = document.getElementById('zmmtg-root');
                    if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'auto';
                    const meetingSDK = document.getElementById('meetingSDKElement');
                    if (meetingSDK) meetingSDK.style.pointerEvents = 'auto';

                    document.body.style.userSelect = 'auto';

                    if (!isDragging && elmnt.classList.contains('minimized')) {
                        toggleMinimizeForm();
                    }
                }
            }

            // Resizable Form Panel logic for Desktop/Tablet (screens >= 768px)
            function makeElementResizable(elmnt, resizeHandle) {
                let startWidth, startHeight, startX, startY;

                resizeHandle.addEventListener('mousedown', initResize);
                resizeHandle.addEventListener('touchstart', initTouchResize, { passive: false });

                function initResize(e) {
                    if (e.button !== 0) return;
                    e.preventDefault();
                    e.stopPropagation();

                    startX = e.clientX;
                    startY = e.clientY;

                    const rect = elmnt.getBoundingClientRect();
                    startWidth = rect.width;
                    startHeight = rect.height;

                    const zmmtgRoot = document.getElementById('zmmtg-root');
                    if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'none';
                    const meetingSDK = document.getElementById('meetingSDKElement');
                    if (meetingSDK) meetingSDK.style.pointerEvents = 'none';

                    document.body.style.userSelect = 'none';

                    document.addEventListener('mousemove', resizeElement);
                    document.addEventListener('mouseup', stopResize);
                }

                function initTouchResize(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;

                    const rect = elmnt.getBoundingClientRect();
                    startWidth = rect.width;
                    startHeight = rect.height;

                    const zmmtgRoot = document.getElementById('zmmtg-root');
                    if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'none';
                    const meetingSDK = document.getElementById('meetingSDKElement');
                    if (meetingSDK) meetingSDK.style.pointerEvents = 'none';

                    document.body.style.userSelect = 'none';

                    document.addEventListener('touchmove', resizeTouchElement, { passive: false });
                    document.addEventListener('touchend', stopResize);
                }

                function resizeElement(e) {
                    const dx = e.clientX - startX;
                    const dy = e.clientY - startY;

                    let newWidth = startWidth + dx;
                    let newHeight = startHeight + dy;

                    if (newWidth < 280) newWidth = 280;
                    if (newWidth > window.innerWidth - 40) newWidth = window.innerWidth - 40;
                    if (newHeight < 150) newHeight = 150;
                    if (newHeight > window.innerHeight - 40) newHeight = window.innerHeight - 40;

                    elmnt.style.setProperty('width', newWidth + 'px', 'important');
                    elmnt.style.setProperty('height', newHeight + 'px', 'important');
                }

                function resizeTouchElement(e) {
                    if (e.cancelable) e.preventDefault();
                    const dx = e.touches[0].clientX - startX;
                    const dy = e.touches[0].clientY - startY;

                    let newWidth = startWidth + dx;
                    let newHeight = startHeight + dy;

                    if (newWidth < 280) newWidth = 280;
                    if (newWidth > window.innerWidth - 40) newWidth = window.innerWidth - 40;
                    if (newHeight < 150) newHeight = 150;
                    if (newHeight > window.innerHeight - 40) newHeight = window.innerHeight - 40;

                    elmnt.style.setProperty('width', newWidth + 'px', 'important');
                    elmnt.style.setProperty('height', newHeight + 'px', 'important');
                }

                function stopResize() {
                    document.removeEventListener('mousemove', resizeElement);
                    document.removeEventListener('mouseup', stopResize);
                    document.removeEventListener('touchmove', resizeTouchElement);
                    document.removeEventListener('touchend', stopResize);

                    const zmmtgRoot = document.getElementById('zmmtg-root');
                    if (zmmtgRoot) zmmtgRoot.style.pointerEvents = 'auto';
                    const meetingSDK = document.getElementById('meetingSDKElement');
                    if (meetingSDK) meetingSDK.style.pointerEvents = 'auto';

                    document.body.style.userSelect = 'auto';
                }
            }

            window.toggleMinimizeForm = function(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                const formPanel = document.querySelector('.doctor-form-panel');
                const formBody = document.getElementById('doctorFormBody');
                const dragHandle = document.getElementById('formDragHandle');
                const dragTitle = document.getElementById('dragTitle');
                const dragControls = document.getElementById('dragControls');
                const clipboardIcon = document.getElementById('clipboardIcon');

                if (formPanel && formBody) {
                    const isMinimized = formPanel.classList.contains('minimized');

                    formPanel.classList.add('animating');

                    if (!isMinimized) {
                        // Save current inline styles before removing them for minimized view
                        if (formPanel.style.width) formPanel.setAttribute('data-saved-width', formPanel.style.width);
                        if (formPanel.style.height) formPanel.setAttribute('data-saved-height', formPanel.style.height);
                        if (formPanel.style.top) formPanel.setAttribute('data-saved-top', formPanel.style.top);
                        if (formPanel.style.left) formPanel.setAttribute('data-saved-left', formPanel.style.left);

                        // Remove inline styles to let CSS circle/fixed styles apply
                        formPanel.style.removeProperty('width');
                        formPanel.style.removeProperty('height');
                        formPanel.style.removeProperty('top');
                        formPanel.style.removeProperty('left');
                        formPanel.style.removeProperty('right');
                        formPanel.style.removeProperty('bottom');

                        formPanel.classList.add('minimized');
                        formBody.classList.add('hidden');
                        if (dragTitle) dragTitle.classList.add('hidden');
                        if (dragControls) dragControls.classList.add('hidden');
                        if (clipboardIcon) clipboardIcon.classList.remove('hidden');
                        if (dragHandle) {
                            dragHandle.title = "Click to Expand Form / Drag to Move";
                        }
                    } else {
                        formPanel.classList.remove('minimized');
                        formBody.classList.remove('hidden');
                        if (dragTitle) dragTitle.classList.remove('hidden');
                        if (dragControls) dragControls.classList.remove('hidden');
                        if (clipboardIcon) clipboardIcon.classList.add('hidden');
                        if (dragHandle) {
                            dragHandle.removeAttribute('title');
                        }

                        // Restore inline styles saved before minimizing
                        const savedWidth = formPanel.getAttribute('data-saved-width');
                        const savedHeight = formPanel.getAttribute('data-saved-height');
                        const savedTop = formPanel.getAttribute('data-saved-top');
                        const savedLeft = formPanel.getAttribute('data-saved-left');

                        if (savedWidth) formPanel.style.setProperty('width', savedWidth, 'important');
                        if (savedHeight) formPanel.style.setProperty('height', savedHeight, 'important');
                        if (savedTop) formPanel.style.setProperty('top', savedTop, 'important');
                        if (savedLeft) formPanel.style.setProperty('left', savedLeft, 'important');
                        if (savedTop || savedLeft) {
                            formPanel.style.setProperty('right', 'auto', 'important');
                            formPanel.style.setProperty('bottom', 'auto', 'important');
                        }

                        // Clean up data attributes
                        formPanel.removeAttribute('data-saved-width');
                        formPanel.removeAttribute('data-saved-height');
                        formPanel.removeAttribute('data-saved-top');
                        formPanel.removeAttribute('data-saved-left');
                    }

                    setTimeout(() => {
                        formPanel.classList.remove('animating');
                    }, 300);
                }
            };

            function initDoctorFormControls() {
                const formPanel = document.querySelector('.doctor-form-panel');
                const dragHandle = document.getElementById('formDragHandle');
                const resizeHandle = document.getElementById('formResizeHandle');
                if (formPanel) {
                    if (dragHandle) {
                        makeElementDraggable(formPanel, dragHandle);
                    }
                    if (resizeHandle && window.innerWidth >= 768) {
                        makeElementResizable(formPanel, resizeHandle);
                    }
                }
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", initDoctorFormControls);
            } else {
                initDoctorFormControls();
            }
        </script>

        {{-- Input transitions consolidated to head style block --}}
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
    let hasSubmittedAtLeastOnce = false;

    document.addEventListener("DOMContentLoaded", function() {

        const btn = document.getElementById('endMeetingBtn');

        if (!btn) return;

        btn.addEventListener('click', function(e) {

            if (!hasSubmittedAtLeastOnce) {

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

        // Re-enable the Submit Report button and reset submission status
        const submitBtn = document.getElementById('submitReportBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Submit Report';
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }

        // Keep End Meeting button enabled after submission
        isSubmitted = false;
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
            const submitBtn = document.getElementById('submitReportBtn');

            // If already successfully submitted, prevent double submission
            if (isSubmitted) {
                alert('Report has already been submitted successfully.');
                return;
            }

            // Disable button & change text to prevent duplicate clicks during loading
            submitBtn.disabled = true;
            submitBtn.innerText = 'Submitting...';
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';

            let form = document.getElementById('reportdata');

            let formData = new FormData(form);

            fetch("{{ route('storereport') }}", {

                    method: "POST",

                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    body: formData

                })
                .then(async response => {

                    let data = await response.json();

                    console.log(data);

                    if (data.success) {

                        isSubmitted = true;
                        hasSubmittedAtLeastOnce = true;
                        submitBtn.innerText = 'Report Submitted';

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
                            'hover:bg-blue-50',
                            'active-btn'
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


                    } else {
                        // Re-enable button on logic error
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Submit Report';
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';

                        if (data.errors) {
                            let errorMsg = 'Validation errors occurred:\n';
                            for (let key in data.errors) {
                                errorMsg += '- ' + data.errors[key].join('\n- ') + '\n';
                            }
                            alert(errorMsg);
                        } else {
                            alert(data.message || 'Something went wrong');
                        }
                    }

                })
                .catch(error => {
                    // Re-enable button on connection error
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Submit Report';
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';

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
