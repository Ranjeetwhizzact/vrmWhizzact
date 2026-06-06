@extends('layouts.app')
@section('title', 'Honest | ' . (isset($doctor) ? 'Edit Doctor' : 'Add Doctor'))

@section('content')
    <div class="flex h-screen bg-[#f8fafc]"> <!-- Slightly softer blue-gray background -->
        <!-- Sidebar -->
        @include('common.sidenav')

        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 transition-all duration-300 overflow-y-auto">
            <!-- Header -->
            @include('common.header')

            <div class="p-8">
                <div class="max-w-6xl mx-auto">
                    <!-- Page Title Area -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-slate-800">
                            {{ isset($doctor) ? 'Edit Doctor Profile' : 'Register New Doctor' }}</h1>
                        <p class="text-slate-500 mt-1 text-sm">Manage professional details and clinical availability.</p>
                    </div>

                    <form action="{{ isset($doctor) ? route('update.doctor', $doctor->id) : route('store.doctor') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                            <!-- LEFT COLUMN: Form Fields (8 Cols) -->
                            <div class="lg:col-span-8 space-y-8">

                                <!-- Section: Primary Identity -->
                                <!-- Section: Primary Identity -->
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                        <h3 class="font-semibold text-slate-700 flex items-center">
                                            <i class="ri-user-settings-line mr-2 text-emerald-500"></i> Identity &
                                            Authentication
                                        </h3>
                                    </div>

                                    <div class="p-6 space-y-6">
                                        <!-- Image Upload Row -->
                                        <div class="flex flex-col md:flex-row gap-8 items-start">
                                            <!-- Profile Photo (Existing) -->
                                            <div class="space-y-2">
                                                <label
                                                    class="text-xs font-bold uppercase tracking-wider text-slate-400">Profile
                                                    Photo <span class="text-rose-500">*</span></label>
                                                <div class="relative group w-32 h-32">
                                                    <img id="imagePreview"
                                                        src="{{ isset($doctor->image) ? asset($doctor->image) : asset('assests/img/avatar.png') }}"
                                                        class="w-full h-full rounded-2xl object-cover border-4 border-white shadow-md group-hover:scale-[1.02] transition-transform duration-300">
                                                    <button type="button"
                                                        onclick="document.getElementById('image-input').click();"
                                                        class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                        <i class="ri-camera-switch-line text-2xl"></i>
                                                    </button>
                                                    <input type="file" name="image" id="image-input" class="hidden"
                                                        accept="image/*" onchange="previewImage(event, 'imagePreview')"
                                                        {{ !isset($doctor) ? 'required' : '' }}>
                                                </div>
                                            </div>

                                            <!-- Digital Signature (Existing) -->
                                            <div class="space-y-2">
                                                <label
                                                    class="text-xs font-bold uppercase tracking-wider text-slate-400">Digital
                                                    Signature <span class="text-rose-500">*</span></label>
                                                <div
                                                    class="relative group w-48 h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center overflow-hidden">
                                                    <img id="signaturePreview"
                                                        src="{{ isset($doctor->signature) ? asset($doctor->signature) : asset('assests/img/sine.png') }}"
                                                        class="max-w-[80%] max-h-[80%] object-contain mix-blend-multiply">
                                                    <button type="button"
                                                        onclick="document.getElementById('signature-input').click();"
                                                        class="absolute inset-0 bg-emerald-600/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span
                                                            class="bg-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">Update</span>
                                                    </button>
                                                    <input type="file" name="signature" id="signature-input"
                                                        class="hidden" accept="image/*"
                                                        onchange="previewImage(event, 'signaturePreview')"
                                                        {{ !isset($doctor) ? 'required' : '' }}>
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <label
                                                    class="text-xs font-bold uppercase tracking-wider text-slate-400">Digital
                                                    Stamp <span class="text-rose-500">*</span></label>
                                                <div
                                                    class="relative group w-48 h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center overflow-hidden">
                                                    <img id="stampPreview"
                                                        src="{{ isset($doctor->stamp) ? asset($doctor->stamp) : asset('assests/img/sine.png') }}"
                                                        class="max-w-[80%] max-h-[80%] object-contain mix-blend-multiply">
                                                    <button type="button"
                                                        onclick="document.getElementById('stamp-input').click();"
                                                        class="absolute inset-0 bg-emerald-600/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span
                                                            class="bg-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">Update</span>
                                                    </button>
                                                    <input type="file" name="stamp" id="stamp-input"
                                                        class="hidden" accept="image/*"
                                                        onchange="previewImage(event, 'stampPreview')"
                                                        {{ !isset($doctor) ? 'required' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form Inputs -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="space-y-1">
                                                <label class="text-sm font-semibold text-slate-600 ml-1">First Name <span
                                                        class="text-rose-500">*</span></label>
                                                <input type="text" name="first_name"
                                                    value="{{ old('first_name', $doctor->first_name ?? '') }}" required
                                                    class="w-full p-3 h-12 rounded-md border-gray-200 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-sm font-semibold text-slate-600 ml-1">Last Name <span
                                                        class="text-rose-500">*</span></label>
                                                <input type="text" name="last_name"
                                                    value="{{ old('last_name', $doctor->last_name ?? '') }}" required
                                                    class="w-full p-3 h-12 rounded-md border-gray-200 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                            </div>

                                            <!-- NEW: Gender Field -->
                                            <div class="space-y-1">
                                                <label class="text-sm font-semibold text-slate-600 ml-1">Gender <span
                                                        class="text-rose-500">*</span></label>
                                                <select name="gender" required
                                                    class="w-full p-3 h-12 rounded-md border-gray-200 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all appearance-none">
                                                    <option value="" disabled
                                                        {{ !isset($doctor->gender) ? 'selected' : '' }}>Select Gender
                                                    </option>
                                                    <option value="male"
                                                        {{ old('gender', $doctor->gender ?? '') == 'male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="female"
                                                        {{ old('gender', $doctor->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                        Female</option>
                                                    <option value="other"
                                                        {{ old('gender', $doctor->gender ?? '') == 'other' ? 'selected' : '' }}>
                                                        Other</option>
                                                </select>
                                            </div>

                                            <!-- NEW: Personal Email Field -->
                                            <div class="space-y-1">
                                                <label class="text-sm font-semibold text-slate-600 ml-1">Personal Email
                                                    <span class="text-rose-500">*</span></label>
                                                <input type="email" name="email"
                                                    value="{{ old('email', $doctor->email ?? '') }}" required
                                                    class="w-full p-3 h-12 rounded-md border-gray-200 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                            </div>

                                            <div class="space-y-1">
                                                <label class="text-sm font-semibold text-slate-600 ml-1">Company Email
                                                    <span class="text-rose-500">*</span></label>
                                                <input type="email" name="username"
                                                    value="{{ old('username', $user->email ?? '') }}" required
                                                    class="w-full p-3 h-12 rounded-md border-gray-200 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-sm font-semibold text-slate-600 ml-1">Login Password
                                                    {{ !isset($doctor) ? '*' : '' }}</label>
                                                <input type="password" name="password" placeholder="••••••••"
                                                    {{ !isset($doctor) ? 'required' : '' }}
                                                    class="w-full p-3 h-12 rounded-md border-gray-200 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                                <p class="text-[10px] text-slate-400 mt-1 italic">Leave empty to keep
                                                    current password</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section: Professional Info -->
                                <div
                                    class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="col-span-full mb-2">
                                        <h3 class="font-semibold text-slate-700 flex items-center">
                                            <i class="ri-medal-line mr-2 text-blue-500"></i> Qualifications & Contact
                                        </h3>
                                    </div>
                                    <div class="cal-span-full">
                                        <label class="text-sm font-semibold text-slate-600">
                                            Preferred Language
                                        </label>

                                        @php
                                            $selectedLanguages = old(
                                                'preferred_language',
                                                isset($doctor) ? explode(',', $doctor->preferred_language) : [],
                                            );
                                        @endphp

                                        <div class="flex flex-wrap gap-4">

                                            <label>
                                                <input type="checkbox" name="preferred_language[]" value="ENGLISH"
                                                    {{ in_array('ENGLISH', $selectedLanguages) ? 'checked' : '' }}>
                                                English
                                            </label>

                                            <label>
                                                <input type="checkbox" name="preferred_language[]" value="HINDI"
                                                    {{ in_array('HINDI', $selectedLanguages) ? 'checked' : '' }}>
                                                Hindi
                                            </label>

                                            <label>
                                                <input type="checkbox" name="preferred_language[]" value="MARATHI"
                                                    {{ in_array('MARATHI', $selectedLanguages) ? 'checked' : '' }}>
                                                Marathi
                                            </label>

                                            <label>
                                                <input type="checkbox" name="preferred_language[]" value="GUJARATI"
                                                    {{ in_array('GUJARATI', $selectedLanguages) ? 'checked' : '' }}>
                                                Gujarati
                                            </label>

                                            <label>
                                                <input type="checkbox" name="preferred_language[]" value="OTHER"
                                                    {{ in_array('OTHER', $selectedLanguages) ? 'checked' : '' }}>
                                                Other
                                            </label>

                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-sm font-semibold text-slate-600">Education</label>
                                        <input type="text" name="education"
                                            value="{{ old('education', $doctor->education ?? '') }}"
                                            placeholder="e.g. MBBS, MD"
                                            class="w-full p-3 rounded-md  border-gray-700 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-sm font-semibold text-slate-600">Reference ID</label>
                                        <input type="text" name="reference_number"
                                            value="{{ $doctor->reference_number ?? '' }}" placeholder="REF-12345"
                                            class="w-full p-3 rounded-md  border-gray-700 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-sm font-semibold text-slate-600">Phone Number</label>
                                        <input type="text" name="phone"
                                            value="{{ old('phone', $doctor->phone ?? '') }}"
                                            class="w-full p-3 rounded-md  border-gray-700 bg-slate-100 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                    </div>
                                </div>

                                <!-- Section: Documents -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @php
                                        $docs = [
                                            [
                                                'name' => 'degree',
                                                'label' => 'Degree Certificate',
                                                'icon' => 'ri-school-fill',
                                                'img' => 'degree.png',
                                            ],
                                            [
                                                'name' => 'identity_proof',
                                                'label' => 'ID Proof (Aadhaar/PAN)',
                                                'icon' => 'ri-fingerprint-line',
                                                'img' => 'idproof.png',
                                            ],
                                            [
                                                'name' => 'license',
                                                'label' => 'Medical License',
                                                'icon' => 'ri-id-card-line',
                                                'img' => 'license.png',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($docs as $doc)
                                        <div
                                            class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                            <label
                                                class="text-xs font-bold text-slate-500 uppercase flex items-center mb-3">
                                                <i class="{{ $doc['icon'] }} mr-2"></i> {{ $doc['label'] }}
                                            </label>
                                            <div class="relative h-28 w-full bg-slate-50 rounded-xl overflow-hidden border border-slate-100 cursor-pointer group"
                                                onclick="document.getElementById('{{ $doc['name'] }}-input').click();">
                                                <img id="{{ $doc['name'] }}Preview"
                                                    src="{{ isset($doctor->{$doc['name']}) ? asset($doctor->{$doc['name']}) : asset('assests/img/' . $doc['img']) }}"
                                                    class="w-full h-full object-cover">
                                                <div
                                                    class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                                    <i class="ri-upload-2-line text-white text-xl"></i>
                                                </div>
                                            </div>
                                            <input type="file" name="{{ $doc['name'] }}"
                                                id="{{ $doc['name'] }}-input" class="hidden"
                                                onchange="previewImage(event, '{{ $doc['name'] }}Preview')">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Availability (4 Cols) -->
                            <div class="lg:col-span-4">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-8">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="font-bold text-slate-800">Weekly Schedule</h3>
                                        <span
                                            class="bg-blue-100 text-blue-600 text-[10px] px-2 py-1 rounded-full font-bold uppercase">Clinical
                                            Hours</span>
                                    </div>

                                    <div id="schedule-fields" class="space-y-3">
                                        @php $weekdays = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday']; @endphp
                                        @foreach ($weekdays as $day)
                                            @php
                                                $isAvailable =
                                                    isset($doctor->available_days[$day]) &&
                                                    !empty($doctor->available_days[$day]['start_time']);
                                            @endphp
                                            @if ($isAvailable)
                                                <div class="schedule-day-item group flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100"
                                                    data-day="{{ $day }}">
                                                    <div class="flex-1">
                                                        <span
                                                            class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ $day }}</span>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <input type="time"
                                                                name="available_days[{{ $day }}][start_time]"
                                                                value="{{ $doctor->available_days[$day]['start_time'] }}"
                                                                class="text-xs p-1 border-none bg-transparent focus:ring-0">
                                                            <span class="text-slate-300">-</span>
                                                            <input type="time"
                                                                name="available_days[{{ $day }}][end_time]"
                                                                value="{{ $doctor->available_days[$day]['end_time'] }}"
                                                                class="text-xs p-1 border-none bg-transparent focus:ring-0">
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        class="remove-day-btn text-slate-300 hover:text-rose-500 transition-colors"
                                                        data-day="{{ $day }}">
                                                        <i class="ri-close-circle-fill text-lg"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="mt-6 space-y-3">
                                        <button type="button" id="add-day-btn"
                                            class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                                            <i class="ri-add-circle-line text-lg"></i> Add Availability
                                        </button>
                                        {{-- <button type="button" id="reset-schedule-btn"
                                            class="w-full py-2 text-slate-400 hover:text-rose-500 text-xs font-medium transition-colors">
                                            Reset Schedule
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Action Footer -->
                        <div class="mt-12 sticky bottom-8 flex justify-center">
                            <div
                                class="bg-white/80 backdrop-blur-md px-6 py-4 rounded-2xl shadow-2xl border border-white flex gap-4">
                                <button type="submit"
                                    class="px-10 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all active:scale-95 flex items-center gap-2">
                                    <i class="ri-save-3-line text-lg"></i>
                                    {{ isset($doctor) ? 'Save Changes' : 'Complete Registration' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Image preview function (works for all file inputs)
        function previewImage(event, previewId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Availability schedule logic
        const weekdays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const weekdayNames = {
            sunday: 'Sunday',
            monday: 'Monday',
            tuesday: 'Tuesday',
            wednesday: 'Wednesday',
            thursday: 'Thursday',
            friday: 'Friday',
            saturday: 'Saturday'
        };

        function getUsedDays() {
            const used = [];
            document.querySelectorAll('.schedule-day-item').forEach(item => {
                used.push(item.getAttribute('data-day'));
            });
            return used;
        }

        function addDay(day) {
            if (getUsedDays().includes(day)) return;
            const scheduleFields = document.getElementById('schedule-fields');
            const newDiv = document.createElement('div');
            newDiv.className =
                'schedule-day-item bg-white p-3 rounded-lg shadow-sm border flex items-center justify-between gap-2';
            newDiv.setAttribute('data-day', day);
            newDiv.innerHTML = `
            <span class="capitalize font-medium w-20 text-gray-700">${weekdayNames[day]}</span>
            <input type="time" name="available_days[${day}][start_time]" class="border rounded px-2 py-1 w-24 text-sm" value="09:00">
            <span class="text-gray-500">–</span>
            <input type="time" name="available_days[${day}][end_time]" class="border rounded px-2 py-1 w-24 text-sm" value="17:00">
            <button type="button" class="text-red-500 hover:text-red-700 p-1 remove-day-btn" data-day="${day}">
                <i class="ri-delete-bin-line"></i>
            </button>
        `;
            scheduleFields.appendChild(newDiv);
            attachRemoveEvent(newDiv.querySelector('.remove-day-btn'));
        }

        function attachRemoveEvent(btn) {
            btn.addEventListener('click', function() {
                const item = this.closest('.schedule-day-item');
                if (item) item.remove();
            });
        }

        function resetSchedule() {
            document.getElementById('schedule-fields').innerHTML = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Attach remove events to existing remove buttons
            document.querySelectorAll('.remove-day-btn').forEach(btn => attachRemoveEvent(btn));

            // Add day button
            const addBtn = document.getElementById('add-day-btn');
            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const used = getUsedDays();
                    const available = weekdays.filter(d => !used.includes(d));
                    if (available.length === 0) {
                        alert('All days already added.');
                        return;
                    }
                    const dayToAdd = available[0];
                    addDay(dayToAdd);
                });
            }

            // Reset button
            const resetBtn = document.getElementById('reset-schedule-btn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (confirm('Remove all availability slots? This action cannot be undone.')) {
                        resetSchedule();
                    }
                });
            }
        });
    </script>
@endsection
