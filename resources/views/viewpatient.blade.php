@extends('layouts.app')
@section('title', 'Honest | Patient Details')
@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100">
        <!-- Sidebar -->
        @include('common.sidenav')

        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 transition-all duration-300 bg-gray-50">
            <!-- Header -->
            @include('common.header')

            <!-- Main Content Area -->
            <div class="p-6">
                @if ($patient)
                    <!-- Patient Header Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                        <!-- Cover Photo with Gradient -->
                        <div class="h-20 bg-green-100 relative">
                            <div class="absolute -bottom-12 left-6 flex items-end z-10">
                                <div
                                    class="w-24 h-24 bg-white rounded-xl shadow-lg flex items-center justify-center border-4 border-white">
                                    <span class="text-3xl font-bold text-emerald-600">
                                        @php
                                            $names = explode(' ', $patient->full_name);
                                            $initials = strtoupper(
                                                substr($names[0] ?? '', 0, 1) . substr($names[1] ?? '', 0, 1),
                                            );
                                        @endphp
                                        {{ $initials }}
                                    </span>
                                </div>
                                <div class="ml-4 mb-12">
                                    <h1 class="text-2xl font-bold text-emerald-600">{{ $patient->full_name }}</h1>
                                    <div class="flex items-center mt-1">
                                        <span
                                            class="px-5 py-1 mb-0 bg-black/20 backdrop-blur-sm rounded-full text-xs font-medium text-white">
                                            Patient ID: #{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <!-- @if ($patient->status)
    <span
                                                        class="ml-2 px-3 py-1 bg-black/20 backdrop-blur-sm rounded-full text-xs font-medium text-white">
                                                        Status: {{ ucfirst($patient->status) }}
                                                    </span>
    @endif -->
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Quick Stats Row -->
                        <div class="pt-24 px-6 pb-4 grid grid-cols-2 md:grid-cols-4 gap-4 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="ri-calendar-line text-emerald-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Age</p>
                                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($patient->dob)->age }}
                                        years</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="ri-drop-line text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Blood Group</p>
                                    <p class="font-semibold text-gray-800">{{ $patient->blood_group ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="ri-phone-line text-purple-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Contact</p>
                                    <p class="font-semibold text-gray-800">+91 {{ $patient->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="ri-mail-line text-amber-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-800 truncate">{{ $patient->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Two Column Layout for Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Personal Information -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Personal Information Card -->
                            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-100">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                                            <i class="ri-user-line text-white"></i>
                                        </div>
                                        <h2 class="text-lg font-semibold text-gray-800 ml-3">Personal Information</h2>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                        <!-- Full Name -->
                                        <div class="col-span-2">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name
                                            </p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">{{ $patient->full_name }}
                                            </p>
                                        </div>

                                        <!-- Gender -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800 capitalize">
                                                {{ $patient->gender ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Marital Status -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Marital
                                                Status</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800 capitalize">
                                                {{ $patient->marital_status ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Date of Birth -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date of
                                                Birth</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d M, Y') : 'N/A' }}
                                            </p>
                                        </div>

                                        <!-- Blood Group -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Blood
                                                Group</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->blood_group ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Phone -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                                                Number</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">+91 {{ $patient->phone }}
                                            </p>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-span-2">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                                                Address</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->email ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Address (Full Width) -->
                                        <div class="col-span-3">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Address
                                            </p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->address ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance Information Card -->
                            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="ri-shield-check-line text-white"></i>
                                        </div>
                                        <h2 class="text-lg font-semibold text-gray-800 ml-3">Insurance Information</h2>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Third Party Administrator -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">TPA Name
                                            </p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->third_party_administrator ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Insurance Company Name -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Insurance
                                                Company</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->insurance_company_name ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Insurance Company Email -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Company
                                                Email</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->insurance_company_email ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Proposal Number -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Proposal
                                                Number</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->proposal_number ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Provide Date -->
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Provided
                                                Date</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->provide_date ? \Carbon\Carbon::parse($patient->provide_date)->format('d M, Y') : 'N/A' }}
                                            </p>
                                        </div>

                                        <!-- Health Problems (Full Width) -->
                                        <div class="col-span-2">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Health
                                                Problems</p>
                                            <p class="mt-2 text-sm font-semibold text-gray-800">
                                                {{ $patient->health_problems ?? 'No health issues reported' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Status & Documents (simplified) -->
                        <div class="space-y-6">
                            <!-- Status Card -->
                            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center">
                                                <i class="ri-information-line text-white"></i>
                                            </div>
                                            <h2 class="text-lg font-semibold text-gray-800 ml-3">Status & Activity</h2>
                                        </div>
                                        <button id="addLogBtn"
                                            class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm flex items-center gap-2 transition">
                                            <i class="ri-add-line"></i> Add Log
                                        </button>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-4">
                                        <!-- Current Status -->
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Current Status</span>
                                            @php
                                                $statusClass = match (strtolower($patient->status ?? '')) {
                                                    'active', 'completed' => 'bg-green-100 text-green-800',
                                                    'pending', 'scheduled' => 'bg-yellow-100 text-yellow-800',
                                                    'inactive', 'canceled' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ ucfirst($patient->status ?? 'N/A') }}
                                            </span>
                                        </div>

                                        <!-- Is Active -->
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Account Status</span>
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-medium {{ ($patient->is_active ?? '') == '1' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ($patient->is_active ?? '0') == '1' ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <!-- Created By -->
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs font-medium text-gray-500">Created By</p>
                                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                                {{ $patient->created_by ?? 'System' }}</p>
                                        </div>

                                        <!-- Updated By -->
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs font-medium text-gray-500">Last Updated By</p>
                                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                                {{ $patient->updated_by ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Card (compact) -->
                            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

                                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-100">

                                    <div class="flex items-center">

                                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                            <i class="ri-file-copy-line text-white"></i>
                                        </div>

                                        <h2 class="text-lg font-semibold text-gray-800 ml-3">
                                            Documents
                                        </h2>

                                    </div>

                                </div>

                                <div class="p-6">

                                    <div class="space-y-4">

                                        <!-- ID Document -->
                                        {{-- <div class="border border-gray-200 rounded-lg p-4">

                                            <div class="flex items-center justify-between mb-2">

                                                <div class="flex items-center">
                                                    <i class="ri-file-text-line text-gray-400 mr-2"></i>

                                                    <span class="text-sm font-medium text-gray-700">
                                                        ID Proof
                                                    </span>
                                                </div>

                                                <span class="text-xs text-gray-500">
                                                    {{ $patient->id_type ?? 'Not specified' }}
                                                </span>

                                            </div>

                                            @if ($patient->id_document && file_exists(public_path($patient->id_document)))
                                                <a href="{{ asset($patient->id_document) }}" target="_blank"
                                                    class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition-colors duration-200 w-full justify-center border border-emerald-200">

                                                    <i class="ri-eye-line mr-2"></i>

                                                    View {{ $patient->id_type ?? 'Document' }}

                                                </a>
                                            @elseif($patient->id_document)
                                                <a href="{{ $patient->id_document }}" target="_blank"
                                                    class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition-colors duration-200 w-full justify-center border border-emerald-200">

                                                    <i class="ri-eye-line mr-2"></i>

                                                    View {{ $patient->id_type ?? 'Document' }}

                                                </a>
                                            @else
                                                <div class="text-center py-3 bg-gray-50 rounded-lg">

                                                    <i class="ri-file-forbid-line text-gray-400 text-2xl mb-1"></i>

                                                    <p class="text-sm text-gray-500">
                                                        No ID document uploaded
                                                    </p>

                                                </div>
                                            @endif

                                        </div> --}}

                                        <!-- Combined Documents & Reports -->
                                        <div class="border border-gray-200 rounded-lg p-4">

                                            <div class="flex items-center mb-4">

                                                <i class="ri-file-pdf-line text-red-400 mr-2"></i>

                                                <span class="text-sm font-medium text-gray-700">
                                                    Patient Documents & Reports
                                                </span>

                                            </div>

                                            <div class="space-y-3">

                                                {{-- Proposal Form --}}
                                                @if ($patient->documents)

                                                    <div
                                                        class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">

                                                        <div class="flex items-center">

                                                            <i class="ri-file-pdf-line text-red-500 mr-2"></i>

                                                            <div>

                                                                <p class="text-sm font-medium text-gray-800">
                                                                    Proposal Form
                                                                </p>

                                                                <p class="text-xs text-gray-500">
                                                                    Patient Document
                                                                </p>

                                                            </div>

                                                        </div>

                                                        @if (file_exists(public_path($patient->documents)))
                                                            <a href="{{ asset($patient->documents) }}" target="_blank"
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">

                                                                <i class="ri-eye-line mr-1"></i>

                                                                View

                                                            </a>
                                                        @else
                                                            <a href="{{ $patient->documents }}" target="_blank"
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">

                                                                <i class="ri-eye-line mr-1"></i>

                                                                View

                                                            </a>
                                                        @endif

                                                    </div>

                                                @endif

                                                {{-- Patient Reports --}}
                                                @if (isset($patientReports) && $patientReports->count())

                                                    @foreach ($patientReports as $report)
                                                        <div
                                                            class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">

                                                            <div class="flex items-center">

                                                                <i class="ri-file-text-line text-blue-500 mr-2"></i>

                                                                <div>

                                                                    <p class="text-sm font-medium text-gray-800">
                                                                        {{ $report->report_name ?? 'Medical Report' }}
                                                                    </p>

                                                                    <p class="text-xs text-gray-500">
                                                                        Patient Report
                                                                    </p>

                                                                </div>

                                                            </div>

                                                            @if ($report->report_file)
                                                                @if (file_exists(public_path($report->report_file)))
                                                                    <a href="{{ asset($report->report_file) }}"
                                                                        target="_blank"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">

                                                                        <i class="ri-eye-line mr-1"></i>

                                                                        View

                                                                    </a>
                                                                @else
                                                                    <a href="{{ $report->report_file }}" target="_blank"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">

                                                                        <i class="ri-eye-line mr-1"></i>

                                                                        View

                                                                    </a>
                                                                @endif
                                                            @endif

                                                        </div>
                                                    @endforeach

                                                @endif

                                                {{-- No Documents --}}
                                                @if (!$patient->documents && (!isset($patientReports) || !$patientReports->count()))
                                                    <div class="text-center py-4 bg-gray-50 rounded-lg">

                                                        <i class="ri-file-forbid-line text-gray-400 text-3xl mb-2"></i>

                                                        <p class="text-sm text-gray-500">
                                                            No documents or reports uploaded
                                                        </p>

                                                    </div>
                                                @endif

                                            </div>

                                        </div>

                                        {{-- ID Number --}}
                                        @if ($patient->id_number)
                                            <div class="bg-gray-50 p-3 rounded-lg">

                                                <p class="text-xs text-gray-500">
                                                    ID Number
                                                </p>

                                                <p class="text-sm font-semibold text-gray-800 mt-1">
                                                    {{ $patient->id_number }}
                                                </p>

                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ==================== FULL WIDTH ACTIVITY LOG SECTION ==================== -->
                    <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                                        <i class="ri-history-line text-white"></i>
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-800 ml-3">Case History</h2>
                                </div>
                                <span class="text-xs text-gray-500">Latest updates first</span>
                            </div>
                        </div>
                        <div class="p-6 max-h-[500px] overflow-y-auto custom-scroll">
                            @if ($logs && $logs->count())
                                <div class="relative space-y-5">
                                    @foreach ($logs as $log)
                                        <div class="relative pl-7 pb-5 border-l-2 border-gray-200 last:border-l-0">
                                            <div
                                                class="absolute left-[-9px] top-0 w-4 h-4 rounded-full bg-emerald-500 ring-2 ring-white">
                                            </div>
                                            <div
                                                class="ml-3 bg-gray-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if ($log->status == 'completed') bg-green-100 text-green-700
                                                @elseif($log->status == 'scheduled') bg-blue-100 text-blue-700
                                                @elseif($log->status == 're-scheduled') bg-yellow-100 text-yellow-700
                                                @elseif($log->status == 'canceled') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700 @endif
                                            ">
                                                        {{ ucfirst($log->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                                        <i class="ri-time-line"></i>
                                                        {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                                        <i class="ri-calendar-line"></i>
                                                        {{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Kolkata')->format('d M Y | h:i A') }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ $log->comment }}</p>
                                                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                                    <i class="ri-user-line"></i> by {{ $log->created_by ?? 'System' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i class="ri-chat-history-line text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 font-medium">No activity logs yet</p>
                                    <p class="text-xs text-gray-400 mt-1">Click the "Add Log" button to record patient
                                        status updates</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- ==================== END ACTIVITY LOG SECTION ==================== -->
                @else
                    <!-- Patient Not Found -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
                        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-user-unfollow-line text-4xl text-red-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Patient Not Found</h3>
                        <p class="text-gray-500 mb-6">The patient you're looking for doesn't exist or has been removed.</p>
                        <a href="{{ url('/patients') }}"
                            class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                            <i class="ri-arrow-left-line mr-2"></i>
                            Return to Patients List
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for Adding Log -->
    <div id="addLogModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" id="modalBackdrop"></div>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-teal-600">
                    <h3 class="text-lg font-medium text-white flex items-center gap-2">
                        <i class="ri-add-circle-line"></i> Add Status Log
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <form id="logForm">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" required class="w-full border border-gray-300 rounded-lg py-2 px-3">
                                <option value="">Select Status</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="completed">Completed</option>
                                <option value="re-scheduled">Re-Scheduled</option>
                                <option value="canceled">Canceled</option>
                                <option value="in-progress">In Progress</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comment / Feedback *</label>
                            <textarea name="comment" rows="3" required class="w-full border border-gray-300 rounded-lg py-2 px-3"
                                placeholder="Enter details..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" id="closeModalBtn"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" id="saveLogBtn"
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">
                        Save Log
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media print {

            .sidebar,
            .header,
            [id*="addLogBtn"],
            [id*="addLogModal"] {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }

        /* Custom scrollbar for log container */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---------- Modal Logic ----------
            const modal = document.getElementById('addLogModal');
            const addLogBtn = document.getElementById('addLogBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const saveLogBtn = document.getElementById('saveLogBtn');
            const logForm = document.getElementById('logForm');

            function openModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                logForm.reset();
            }

            if (addLogBtn) addLogBtn.addEventListener('click', openModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

            // ---------- Save Log via AJAX ----------
            if (saveLogBtn) {
                saveLogBtn.addEventListener('click', function() {
                    const formData = new FormData(logForm);
                    const patientId = formData.get('patient_id');
                    const status = formData.get('status');
                    const comment = formData.get('comment');

                    if (!status || !comment) {
                        alert('Please fill all required fields.');
                        return;
                    }

                    saveLogBtn.disabled = true;
                    saveLogBtn.innerHTML = '<i class="ri-loader-4-line ri-spin mr-1"></i> Saving...';

                    fetch('{{ route('patient.addLog') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload the page to show the new log
                                location.reload();
                            } else {
                                alert('Error: ' + (data.message || 'Failed to save log.'));
                                saveLogBtn.disabled = false;
                                saveLogBtn.innerHTML = 'Save Log';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Network error. Please try again.');
                            saveLogBtn.disabled = false;
                            saveLogBtn.innerHTML = 'Save Log';
                        });
                });
            }
        });
    </script>
@stop

@endsection
