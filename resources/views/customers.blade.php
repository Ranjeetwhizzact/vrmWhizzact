@extends('layouts.app')
@section('title', 'Honest | Patients List')
@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100">
        <!-- Sidebar -->
        @include('common.sidenav')

        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 transition-all duration-300">
            <!-- Header -->
            @include('common.header')

            <!-- Table Section -->
            <div class="p-6 bg-gray-50 min-h-screen">
                @if (in_array(strtolower(auth()->user()->role), ['superadmin', 'company_admin', 'company_user']))
                    <!-- Header with Actions -->
                    <div
                        class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-900">
                            <i class="ri-user-heart-line mr-2 text-emerald-600"></i>Patients Management
                        </h5>
                        <div class="flex gap-3">
                            <button type="button" id="bulkUploadBtn"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-all duration-200">
                                <i class="ri-upload-cloud-2-line text-white"></i>
                                <span class="text-white">Bulk Upload</span>
                            </button>

                            <a href="{{ route('patients.export.excel', request()->only(['status', 'insurance_company_name', 'division_code', 'branch_code', 'location', 'date_range', 'proposal_number', 'from_date', 'to_date'])) }}"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-all duration-200">
                                <i class="ri-file-excel-2-line text-white"></i>
                                <span class="text-white">Download Excel</span>
                            </a>

                            <a href="{{ url('createpatient') }}"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-all duration-200">
                                <i class="ri-user-add-line text-white"></i>
                                <span class="text-white">Add Patient</span>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Filters Section (unchanged) -->
                <div class="bg-white rounded-lg shadow-sm p-5 mb-6 border border-gray-200">
                    <form method="GET" action="{{ route('customers') }}" class="space-y-4" id="filterForm">
                        <div class="flex flex-wrap items-end gap-4">
                            <!-- Status Filter -->
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <i class="ri-filter-3-line mr-1 text-gray-500"></i>Status
                                </label>
                                <select name="status" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    <option value="">All Status</option>
                                    <option value="unassigned" {{ request('status') == 'unassigned' ? 'selected' : '' }}>
                                        Unassigned</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                        Scheduled</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="re-scheduled"
                                        {{ request('status') == 're-scheduled' ? 'selected' : '' }}>Re-Scheduled</option>
                                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>
                                        Canceled</option>
                                </select>
                            </div>

                            <!-- Insurance Company -->
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <i class="ri-building-4-line mr-1 text-gray-500"></i>Insurance Company
                                </label>
                                <select name="insurance_company_name" id="company_select"
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    <option value="">Select Insurance Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->company_name }}"
                                            {{ request('insurance_company_name') == $company->company_name ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Division -->
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <i class="ri-grid-line mr-1 text-gray-500"></i>Division
                                </label>
                                <select name="division_code" id="division_select"
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    <option value="">Select Division</option>
                                    @if (isset($divisions) && count($divisions) > 0)
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->division_code }}"
                                                {{ request('division_code') == $division->division_code ? 'selected' : '' }}>
                                                {{ $division->division_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Branch -->
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <i class="ri-store-line mr-1 text-gray-500"></i>Branch
                                </label>
                                <select name="branch_code" id="branch_select"
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    <option value="">Select Branch</option>
                                    @if (isset($branches) && count($branches) > 0)
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->branch_code }}"
                                                data-location="{{ $branch->location }}"
                                                {{ request('branch_code') == $branch->branch_code ? 'selected' : '' }}>
                                                {{ $branch->branch_name }} ({{ $branch->branch_code }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="location" id="location" value="{{ request('location') }}">
                            </div>

                            <!-- Date Range -->
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <i class="ri-calendar-line mr-1 text-gray-500"></i>Date Range
                                </label>
                                <select name="date_range" id="dateRangeSelect"
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today
                                    </option>
                                    <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>
                                        Yesterday</option>
                                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Last 7
                                        Days</option>
                                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Last 30
                                        Days</option>
                                    <option value="3months" {{ request('date_range') == '3months' ? 'selected' : '' }}>Last
                                        3 Months</option>
                                    <option value="6months" {{ request('date_range') == '6months' ? 'selected' : '' }}>Last
                                        6 Months</option>
                                    <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>Last
                                        Year</option>
                                </select>
                            </div>

                            <!-- Proposal Number -->
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <i class="ri-file-copy-line mr-1 text-gray-500"></i>Proposal Number
                                </label>
                                <input type="text" name="proposal_number" value="{{ request('proposal_number') }}"
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm"
                                    placeholder="Search by Proposal No...">
                            </div>

                            <!-- Custom Date Range -->
                            <div class="flex-1 min-w-[320px]" id="customDateRange" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Custom Dates</label>
                                <div class="flex gap-2">
                                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                                        class="w-1/2 border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                                        class="w-1/2 border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                </div>
                            </div>

                            <div>
                                <button type="submit"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
                                    <i class="ri-search-line text-white"></i>
                                    <span>Apply Filters</span>
                                </button>
                            </div>

                            @if (request()->anyFilled([
                                    'status',
                                    'insurance_company_name',
                                    'division_code',
                                    'branch_code',
                                    'proposal_number',
                                    'date_range',
                                    'from_date',
                                ]))
                                <div>
                                    <a href="{{ route('customers') }}"
                                        class="text-gray-600 hover:text-gray-800 text-sm font-medium flex items-center gap-1">
                                        <i class="ri-close-line text-gray-500"></i>
                                        <span>Clear Filters</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Patients Table (unchanged) -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Sr.no</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Patient Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Date of Birth</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Location</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Created Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                            </thead>
                            <tbody>
                                @if ($patients->count())
                                    @php $startNumber = ($patients->currentPage() - 1) * $patients->perPage(); @endphp
                                    @foreach ($patients as $i => $p)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $startNumber + $i + 1 }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $p->full_name }}</td>
                                            <td class="px-4 py-3">{{ $p->email ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">{{ $p->phone ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">
                                                {{ $p->dob ? \Carbon\Carbon::parse($p->dob)->format('d M, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3">{{ $p->location->location_name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">
                                                {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusClass = match (strtolower($p->status)) {
                                                        'unassigned' => 'bg-orange-100 text-orange-800',
                                                        'scheduled' => 'bg-yellow-100 text-yellow-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        're-scheduled' => 'bg-blue-100 text-blue-800',
                                                        'canceled' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ ucfirst($p->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('viewpatient', encrypt($p->id)) }}"
                                                        class="p-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{ route('editpatient', ['id' => $p->hashed_id]) }}"
                                                        class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <form action="{{ route('deletepatient', $p->hashed_id) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Are you sure?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                                                            <i class="ri-delete-bin-6-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="px-4 py-8 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="ri-user-search-line text-5xl text-gray-400 mb-3"></i>
                                                <p class="text-lg font-medium text-gray-900">No patients found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if ($patients->hasPages())
                        <div class="px-4 py-3 bg-gray-50 border-t">
                            {{ $patients->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Modal - Enhanced UI -->
    <div id="bulkUploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" id="modalBackdrop"></div>

            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">
                            <i class="ri-upload-cloud-2-line mr-2"></i>
                            Bulk Upload Patients
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 focus:outline-none"
                            id="closeModalIconBtn">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4">
                    <!-- Upload Form Section -->
                    <div id="uploadFormSection">
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start">
                                <i class="ri-information-line text-blue-600 mr-2 mt-0.5"></i>
                                <div class="text-sm text-blue-800">
                                    <strong>Instructions:</strong>
                                    <ul class="mt-1 ml-4 list-disc">
                                        <li>Supported formats: .xlsx, .xls, .csv</li>
                                        <li>Maximum file size: 10MB</li>
                                        <li><span class="font-semibold">Proposal Number</span> is required and must be
                                            unique</li>
                                        <li><span class="font-semibold">Customer Full Name</span> is required</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Choose Excel File
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="ri-upload-cloud-2-line text-3xl text-gray-400 mb-2"></i>
                                            <p class="mb-2 text-sm text-gray-500">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500">Excel or CSV files only</p>
                                        </div>
                                        <input type="file" class="hidden" id="excel_file" name="excel_file"
                                            accept=".xlsx,.xls,.csv" required>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500" id="fileNameDisplay"></p>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-all duration-200"
                                    id="startUploadBtn">
                                    <i class="ri-cloud-upload-line"></i>
                                    <span>Start Upload</span>
                                </button>
                                <a href="{{ route('bulk-upload.sample') }}"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-all duration-200">
                                    <i class="ri-download-line"></i>
                                    <span>Download Sample</span>
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Results Section -->
                    <div id="progressSection" style="display: none;">
                        <div id="progressContent"></div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-2" id="modalFooterSection"
                    style="display: none;">
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                        id="closeFinalBtn">
                        Close
                    </button>
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700"
                        id="uploadAnotherBtn">
                        Upload Another File
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

        #progressContent .failure-item {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        #progressContent .scrollable-errors {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ----- Filters logic (unchanged) -----
            const companySelect = document.getElementById('company_select');
            const divisionSelect = document.getElementById('division_select');
            const branchSelect = document.getElementById('branch_select');
            const locationInput = document.getElementById('location');

            function loadDivisions(companyName) {
                if (!companyName) {
                    divisionSelect.innerHTML = '<option value="">Select Division</option>';
                    branchSelect.innerHTML = '<option value="">Select Branch</option>';
                    return;
                }
                divisionSelect.innerHTML = '<option value="">Loading divisions...</option>';
                fetch(`/get-divisions-by-company/${encodeURIComponent(companyName)}`)
                    .then(response => response.json())
                    .then(divisions => {
                        let options = '<option value="">Select Division</option>';
                        if (divisions && divisions.length) {
                            divisions.forEach(division => {
                                options +=
                                    `<option value="${division.division_code}">${division.division_name} (${division.division_code})</option>`;
                            });
                        }
                        divisionSelect.innerHTML = options;
                        const selectedDivision = "{{ request('division_code') }}";
                        if (selectedDivision && divisions.some(d => d.division_code === selectedDivision)) {
                            divisionSelect.value = selectedDivision;
                            loadBranches(selectedDivision);
                        }
                    })
                    .catch(() => divisionSelect.innerHTML = '<option value="">Error loading divisions</option>');
            }

            function loadBranches(divisionCode) {
                if (!divisionCode) {
                    branchSelect.innerHTML = '<option value="">Select Branch</option>';
                    if (locationInput) locationInput.value = '';
                    return;
                }
                branchSelect.innerHTML = '<option value="">Loading branches...</option>';
                fetch(`/get-branches-by-division/${divisionCode}`)
                    .then(response => response.json())
                    .then(branches => {
                        let options = '<option value="">Select Branch</option>';
                        if (branches && branches.length) {
                            branches.forEach(branch => {
                                options +=
                                    `<option value="${branch.branch_code}" data-location="${branch.location || ''}">${branch.branch_name} (${branch.branch_code}) - ${branch.location || ''}</option>`;
                            });
                        }
                        branchSelect.innerHTML = options;
                        const selectedBranch = "{{ request('branch_code') }}";
                        if (selectedBranch) {
                            branchSelect.value = selectedBranch;
                            const opt = branchSelect.options[branchSelect.selectedIndex];
                            if (opt && locationInput) locationInput.value = opt.dataset.location || '';
                        }
                    })
                    .catch(() => branchSelect.innerHTML = '<option value="">Error loading branches</option>');
            }

            if (companySelect) {
                companySelect.addEventListener('change', function() {
                    loadDivisions(this.value);
                    branchSelect.innerHTML = '<option value="">Select Branch</option>';
                    if (locationInput) locationInput.value = '';
                });
            }
            if (divisionSelect) divisionSelect.addEventListener('change', function() {
                loadBranches(this.value);
                if (locationInput) locationInput.value = '';
            });
            if (branchSelect) branchSelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (opt && locationInput) locationInput.value = opt.dataset.location || '';
            });

            const initialCompany = "{{ request('insurance_company_name') }}";
            if (initialCompany) loadDivisions(initialCompany);
            else if ("{{ request('division_code') }}") loadBranches("{{ request('division_code') }}");

            const dateRangeSelect = document.getElementById('dateRangeSelect');
            const customDateRange = document.getElementById('customDateRange');
            if (dateRangeSelect && customDateRange) {
                function toggleCustomDateRange() {
                    customDateRange.style.display = dateRangeSelect.value === 'custom' ? 'flex' : 'none';
                }
                dateRangeSelect.addEventListener('change', toggleCustomDateRange);
                toggleCustomDateRange();
            }

            // ----- Bulk Upload Modal Logic -----
            const modal = document.getElementById('bulkUploadModal');
            const bulkUploadBtn = document.getElementById('bulkUploadBtn');
            const closeIconBtn = document.getElementById('closeModalIconBtn');
            const closeFinalBtn = document.getElementById('closeFinalBtn');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const fileInput = document.getElementById('excel_file');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const uploadForm = document.getElementById('uploadForm');
            const startBtn = document.getElementById('startUploadBtn');
            const uploadAnotherBtn = document.getElementById('uploadAnotherBtn');

            // Reset modal to upload form
            function resetModalToUploadForm() {
                document.getElementById('uploadFormSection').style.display = 'block';
                document.getElementById('progressSection').style.display = 'none';
                document.getElementById('modalFooterSection').style.display = 'none';
                if (uploadForm) uploadForm.reset();
                if (fileNameDisplay) fileNameDisplay.textContent = '';
                if (fileInput) fileInput.value = '';
            }

            // Open modal
            if (bulkUploadBtn) {
                bulkUploadBtn.addEventListener('click', function() {
                    resetModalToUploadForm();
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (closeIconBtn) closeIconBtn.addEventListener('click', closeModal);
            if (closeFinalBtn) closeFinalBtn.addEventListener('click', closeModal);
            if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);
            if (uploadAnotherBtn) uploadAnotherBtn.addEventListener('click', resetModalToUploadForm);

            // File name display
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    fileNameDisplay.textContent = this.files && this.files[0] ?
                        `Selected: ${this.files[0].name}` : '';
                });
            }

            // Handle upload
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const file = fileInput.files[0];
                    if (!file) {
                        alert('Please select a file to upload');
                        return;
                    }

                    const formData = new FormData(this);
                    startBtn.disabled = true;
                    startBtn.innerHTML = '<i class="ri-loader-4-line ri-spin mr-2"></i>Uploading...';

                    fetch('{{ route('bulk-upload.upload') }}', {
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
                            if (data.success && data.results) {
                                showResults(data.results);
                                document.getElementById('modalFooterSection').style.display = 'flex';
                            } else {
                                showError(data.message || 'Upload failed');
                                document.getElementById('modalFooterSection').style.display = 'flex';
                            }
                        })
                        .catch(error => {
                            console.error('Upload error:', error);
                            showError('Network error. Please try again.');
                            document.getElementById('modalFooterSection').style.display = 'flex';
                        })
                        .finally(() => {
                            startBtn.disabled = false;
                            startBtn.innerHTML =
                                '<i class="ri-cloud-upload-line mr-2"></i>Start Upload';
                        });
                });
            }

            function showResults(results) {
                document.getElementById('uploadFormSection').style.display = 'none';
                document.getElementById('progressSection').style.display = 'block';

                const failures = results.failures || [];
                const successCount = results.success_rows || 0;
                const failedCount = results.failed_rows || 0;
                const totalCount = results.total_rows || 0;

                let failuresHtml = '';
                if (failures.length > 0) {
                    failuresHtml = `
                <div class="mt-4">
                    <h6 class="text-amber-800 font-semibold mb-3 flex items-center gap-2">
                        <i class="ri-error-warning-line text-amber-600"></i>
                        Failed Records (${failures.length})
                    </h6>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        ${failures.map(failure => `
                                            <div class="bg-amber-50 border-l-4 border-amber-500 p-3 rounded">
                                                <div class="text-sm">
                                                    <div class="font-semibold text-gray-900 mb-1">
                                                        ${failure.proposal_number ? `Proposal: ${failure.proposal_number}` : `Row ${failure.row} (No proposal number)`}
                                                    </div>
                                                    <div class="text-red-600 mt-1">
                                                        ${Array.isArray(failure.errors) ?
                                                            failure.errors.map(err => `<div class="ml-2">• ${err}</div>`).join('') :
                                                            `<div class="ml-2">${failure.errors || 'Unknown error'}</div>`
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        `).join('')}
                    </div>
                </div>
            `;
                }

                let successIcon = '';
                let successMessage = '';
                if (failedCount === 0) {
                    successIcon = '<i class="ri-checkbox-circle-fill text-green-500" style="font-size: 48px;"></i>';
                    successMessage = `<div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                <p class="text-green-800 font-semibold">✓ All ${successCount} records uploaded successfully!</p>
                              </div>`;
                } else if (successCount > 0) {
                    successIcon = '<i class="ri-checkbox-circle-fill text-amber-500" style="font-size: 48px;"></i>';
                    successMessage = `<div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                                <p class="text-amber-800 font-semibold">⚠ ${successCount} records uploaded successfully. ${failedCount} records failed.</p>
                              </div>`;
                } else {
                    successIcon = '<i class="ri-close-circle-fill text-red-500" style="font-size: 48px;"></i>';
                    successMessage = `<div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                <p class="text-red-800 font-semibold">✗ No records were uploaded. Please check the errors below.</p>
                              </div>`;
                }

                document.getElementById('progressContent').innerHTML = `
            <div class="text-center">
                <div class="mb-4">${successIcon}</div>
                ${successMessage}
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg p-3">
                        <div class="text-2xl font-bold">${totalCount}</div>
                        <div class="text-xs">Total Records</div>
                    </div>
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-lg p-3">
                        <div class="text-2xl font-bold">${successCount}</div>
                        <div class="text-xs">Successfully Uploaded</div>
                    </div>
                    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg p-3">
                        <div class="text-2xl font-bold">${failedCount}</div>
                        <div class="text-xs">Failed Records</div>
                    </div>
                </div>
                ${failuresHtml}
            </div>
        `;
            }

            function showError(message) {
                document.getElementById('uploadFormSection').style.display = 'none';
                document.getElementById('progressSection').style.display = 'block';
                document.getElementById('progressContent').innerHTML = `
            <div class="text-center py-8">
                <i class="ri-close-circle-fill text-red-500" style="font-size: 48px;"></i>
                <h4 class="text-lg font-semibold text-red-600 mb-2 mt-4">Upload Failed</h4>
                <p class="text-sm text-gray-600">${message}</p>
            </div>
        `;
                document.getElementById('modalFooterSection').style.display = 'flex';
            }
        });
    </script>
@stop

@endsection
