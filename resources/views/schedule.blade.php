@extends('layouts.app')
@section('title', 'Honest | Schedule Appointment')

@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100 ">
        @include('common.sidenav')

        <div class="main-content flex-1 ml-64 transition-all duration-300">
            @include('common.header')

            <div class="p-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Schedule Appointment</h1>
                    <p class="text-sm text-gray-500">Book a new consultation for a patient</p>
                </div>

                @if (session('status') == 'success')
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="max-w-4xl mx-auto">
                    <form action="{{ url('/schedule') }}" method="POST" enctype="multipart/form-data"
                        class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                        @csrf

                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </span>
                                <h2 class="text-lg font-medium text-gray-700">Patient Selection</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <!-- Division -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Division
                                    </label>

                                    <select id="division_code" name="division_code"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Division</option>

                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->division_code }}">
                                                {{ $division->division_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Branch -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Branch
                                    </label>

                                    <select id="branch_code" name="branch_code"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Branch</option>
                                    </select>
                                </div>

                                <!-- Proposal Number -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Proposal Number
                                    </label>

                                    <input type="text" id="patients-search" name="proposal_number"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none"
                                        placeholder="Search by Proposal Number..." required>

                                    <input type="hidden" id="patient-id" name="client_id">
                                    <input type="hidden" name="email" id="clientemail">
                                </div>
                            </div>

                            <!-- Patient Name -->
                            <div class="mt-4 space-y-1">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Patient Name
                                </label>

                                <input type="text" id="assign_patient_name" name="assign_patient_name"
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-lg bg-gray-50"
                                    placeholder="Auto-fills on search" readonly>
                            </div>
                            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Proposal
                                        Number</label>
                                    <input type="text" id="patients-search" name="proposal_number"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none"
                                        placeholder="Search by ID..." required>
                                    <input type="hidden" id="patient-id" name="client_id">
                                    <input type="hidden" name="email" id="clientemail">
                                </div>

                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient
                                        Name</label>
                                    <input type="text" id="assign_patient_name" name="assign_patient_name"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg bg-gray-50"
                                        placeholder="Auto-fills on search" readonly>
                                </div>
                            </div> --}}
                        </div>

                        <div class="p-6 bg-gray-50/30 border-b border-gray-100">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <h2 class="text-lg font-medium text-gray-700">Schedule Details</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</label>
                                    <input type="text" name="date" id="date"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white cursor-pointer"
                                        placeholder="YYYY-MM-DD" readonly>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Time
                                        (24h)</label>
                                    <input type="time" name="time" id="time_input"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white outline-none">
                                </div>

                                <div class="space-y-1">
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Consultation
                                        Mode</label>
                                    <select name="appointment_type"
                                        class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white outline-none appearance-none">
                                        <option value="Offline">In-Person (Offline)</option>
                                        <option value="Online">Video Call (Online)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-1 max-w-md">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Assign
                                    Medical Professional</label>
                                <select id="doctorSelect" name="doctor_id" required
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white transition-all">
                                    <option value="">Choose Doctor (Select Date & Time First)</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-6 bg-white border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-10 py-3 rounded-lg shadow-md transition-all active:transform active:scale-95">
                                Confirm Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {

            $("#division_code").change(function() {

                let divisionCode = $(this).val();

                $("#branch_code").html('<option value="">Loading...</option>');

                $.ajax({
                    url: "/get-branches",
                    type: "GET",
                    data: {
                        division_code: divisionCode
                    },
                    success: function(data) {

                        let options = '<option value="">Select Branch</option>';

                        $.each(data, function(key, item) {
                            options += `<option value="${item.branch_code}">
                                ${item.branch_code}
                            </option>`;
                        });

                        $("#branch_code").html(options);
                    }
                });
            });

            $("#branch_code").change(function() {
                $("#patients-search").val("");
                $("#patient-id").val("");
                $("#clientemail").val("");
                $("#assign_patient_name").val("");
                $("#date").val("");
            });

            // 1. Patient Auto-complete Logic
            function fetchPatientByProposal(proposalNumber) {

                $.ajax({
                    url: "/searchpatients",
                    type: "GET",
                    data: {
                        proposal_number: proposalNumber,
                        division_code: $("#division_code").val(),
                        branch_code: $("#branch_code").val()
                    },
                    dataType: "json",

                    success: function(data) {

                        if (data) {

                            $("#patient-id").val(data.id);
                            $("#assign_patient_name").val(data.full_name);
                            $("#patients-search").val(data.proposal_number);

                            $('#clientemail').val(data.email);
                            $('#date').val(data.available_date.split('T')[0]);
                            $('#time_input').val(data.start_time.substring(0,5));
                            $('#end_time').val(data.end_time);
                            checkAvailability();
                        }
                    }
                });
            }

            $("#patients-search").autocomplete({

                source: function(request, response) {

                    $.ajax({
                        url: "/searchpatients",
                        type: "GET",
                        data: {
                            term: request.term,
                            division_code: $("#division_code").val(),
                            branch_code: $("#branch_code").val()
                        },
                        dataType: "json",

                        success: function(data) {

                            response($.map(data, function(item) {

                                return {
                                    label: item.proposal_number + " - " + item
                                        .full_name,
                                    value: item.proposal_number
                                };
                            }));
                        }
                    });
                },

                minLength: 1,

                select: function(event, ui) {
                    fetchPatientByProposal(ui.item.value);
                }
            });

            // 2. Datepicker Initialization
            $("#date").datepicker({
                minDate: 0,
                dateFormat: 'yy-mm-dd',
                onSelect: function() {
                    checkAvailability();
                }
            });

            // 3. Availability Logic
            function checkAvailability() {
                let date = $('#date').val();
                let time = $('#time_input').val();

                if (date && time) {
                    $('#doctorSelect').html('<option value="">Searching available doctors...</option>');

                    $.ajax({
                        url: "/getavailabletimes",
                        type: "GET",
                        data: {
                            date: date,
                            time: time
                        },
                        dataType: "json",
                        success: function(response) {
                            let $dropdown = $('#doctorSelect');
                            $dropdown.empty();

                            let doctors = response.doctorList || [];
                            if (doctors.length > 0) {
                                $dropdown.append('<option value="">Select a Doctor</option>');
                                doctors.forEach(function(doc) {
                                    $dropdown.append(
                                        `<option value="${doc.doctor_id}">${doc.first_name} ${doc.last_name}</option>`
                                    );
                                });
                            } else {
                                $dropdown.append(
                                    '<option value="">No doctors available for this slot</option>');
                            }
                        },
                        error: function() {
                            $('#doctorSelect').html('<option value="">Error fetching doctors</option>');
                        }
                    });
                }
            }

            // Trigger check when time input changes
            $('#time_input').on('input change', function() {
                checkAvailability();
            });
        });
    </script>
@endsection
