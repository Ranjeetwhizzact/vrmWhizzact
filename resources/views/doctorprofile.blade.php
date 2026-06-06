@extends('layouts.app')
@section('title', 'Doctor Profile | Honest')

@section('content')
    <!-- Remix Icon CDN for icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <div class="flex h-screen bg-[#f8fafc]">
        @include('common.sidenav')

        <div class="main-content flex-1 ml-64 transition-all duration-300 overflow-y-auto">
            @include('common.header')

            <div class="p-8">
                <div class="max-w-6xl mx-auto">

                    <!-- Top Navigation & Action Bar -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <nav class="flex mb-2" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                                    <li class="inline-flex items-center text-sm text-slate-500">Doctors</li>
                                    <li><i class="ri-arrow-right-s-line text-slate-400"></i></li>
                                    <li class="text-sm font-bold text-blue-600">Profile Details</li>
                                </ol>
                            </nav>
                            <h1 class="text-2xl font-extrabold text-slate-800">Doctor Information</h1>
                        </div>
                        <div class="flex gap-3">
                            {{-- <a href="{{ route('doctors') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition-all flex items-center gap-2">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a> --}}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                        <!-- LEFT COLUMN: Identity & Credentials -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- Main Profile Header Card -->
                            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                                <div class="p-8">
                                    <div class="flex flex-col md:flex-row gap-8">
                                        <!-- Profile Image -->
                                        <div class="w-40 h-40 flex-shrink-0 mx-auto md:mx-0">
                                            <img src="{{ !empty($doctor->image) ? asset($doctor->image) : asset('assets/img/avatar.png') }}"
                                                class="w-full h-full rounded-2xl object-cover ring-4 ring-slate-50 shadow-lg">
                                        </div>

                                        <!-- Basic Details -->
                                        <div class="flex-1 text-center md:text-left">
                                            <div class="flex items-center justify-center md:justify-start gap-2 mb-3">
                                                <span
                                                    class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider">
                                                    Registered Doctor
                                                </span>
                                                <!-- Gender Badge -->
                                                <span
                                                    class="px-3 py-1 rounded-full {{ $doctor->gender == 'female' ? 'bg-pink-50 text-pink-600' : 'bg-slate-50 text-slate-600' }} text-xs font-bold uppercase tracking-wider">
                                                    <i
                                                        class="{{ $doctor->gender == 'female' ? 'ri-women-line' : 'ri-men-line' }} mr-1"></i>
                                                    {{ $doctor->gender ?? 'N/A' }}
                                                </span>
                                            </div>

                                            <h2 class="text-3xl font-black text-slate-800 mb-2">{{ $doctor->first_name }}
                                                {{ $doctor->last_name }}</h2>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 mt-6">
                                                <div class="flex items-center gap-3 text-slate-600">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-blue-500">
                                                        <i class="ri-mail-line"></i>
                                                    </div>
                                                    <span class="text-sm font-medium">{{ $doctor->email }}</span>
                                                </div>
                                                <div class="flex items-center gap-3 text-slate-600">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-emerald-500">
                                                        <i class="ri-phone-line"></i>
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium">{{ $doctor->phone ?? 'Not Provided' }}</span>
                                                </div>
                                                <div class="flex items-center gap-3 text-slate-600">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-purple-500">
                                                        <i class="ri-medal-line"></i>
                                                    </div>
                                                    <span class="text-sm font-medium">{{ $doctor->education }}</span>
                                                </div>
                                                <div class="flex items-center gap-3 text-slate-600">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-orange-500">
                                                        <i class="ri-translate-2"></i>
                                                    </div>
                                                    <span class="text-sm font-medium">
                                                        {{ !empty($doctor->preferred_language)
                                                            ? implode(', ', explode(',', $doctor->preferred_language))
                                                            : 'Not Specified' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reference Bar -->
                                <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-6">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        Reference ID: <span
                                            class="text-slate-600 ml-1">{{ $doctor->reference_number }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @php
                                    $docs = [
                                        [
                                            'key' => 'degree',
                                            'label' => 'Degree Certificate',
                                            'icon' => 'ri-graduation-cap-line',
                                        ],
                                        [
                                            'key' => 'identity_proof',
                                            'label' => 'Identity Proof',
                                            'icon' => 'ri-shield-user-line',
                                        ],
                                        ['key' => 'license', 'label' => 'Medical License', 'icon' => 'ri-honour-line'],
                                    ];
                                @endphp

                                @foreach ($docs as $doc)
                                    <div
                                        class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-hover hover:shadow-md">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="p-2.5 bg-slate-100 rounded-xl text-slate-600">
                                                <i class="{{ $doc['icon'] }} text-xl"></i>
                                            </div>
                                            <h4 class="text-sm font-bold text-slate-800">{{ $doc['label'] }}</h4>
                                        </div>

                                        @if (!empty($doctor->{$doc['key']}))
                                            <div
                                                class="relative group h-40 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 mb-4">
                                                <img src="{{ asset($doctor->{$doc['key']}) }}"
                                                    class="w-full h-full object-cover">
                                                <div
                                                    class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center backdrop-blur-[2px]">
                                                    <a href="{{ asset($doctor->{$doc['key']}) }}" target="_blank"
                                                        class="px-4 py-2 bg-white rounded-lg text-slate-900 font-bold text-xs flex items-center gap-2">
                                                        <i class="ri-eye-line"></i> View Large
                                                    </a>
                                                </div>
                                            </div>
                                            <a href="{{ asset($doctor->{$doc['key']}) }}" target="_blank"
                                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl transition-colors border border-blue-100">
                                                <i class="ri-external-link-line"></i> Open in New Tab
                                            </a>
                                        @else
                                            <div
                                                class="h-40 bg-slate-50 rounded-xl flex flex-col items-center justify-center border border-dashed border-slate-200">
                                                <i class="ri-file-warning-line text-slate-300 text-2xl mb-2"></i>
                                                <p class="text-xs text-slate-400 font-medium">No File Uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Schedule (S M T W T F S style) -->
                        <div class="lg:col-span-4">
                            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 sticky top-8">
                                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center justify-between">
                                    Availability
                                    <span
                                        class="px-2 py-1 bg-emerald-100 text-emerald-600 text-[10px] rounded-md tracking-widest uppercase">Live
                                        Status</span>
                                </h3>

                                @php
                                    $weekdays = [
                                        'sunday' => 'S',
                                        'monday' => 'M',
                                        'tuesday' => 'T',
                                        'wednesday' => 'W',
                                        'thursday' => 'T',
                                        'friday' => 'F',
                                        'saturday' => 'S',
                                    ];
                                @endphp

                                <!-- Circle Icon Strip -->
                                <ul class="flex justify-between items-center mb-10 pb-6 border-b border-slate-100">
                                    @foreach ($weekdays as $day => $letter)
                                        @php
                                            $isAvailable =
                                                isset($doctor->available_days[$day]) &&
                                                !empty($doctor->available_days[$day]['start_time']);
                                        @endphp
                                        <li class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-black transition-all
                                            {{ $isAvailable
                                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 ring-4 ring-blue-50'
                                                : 'bg-slate-100 text-slate-300' }}">
                                                {{ $letter }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                <!-- Detailed List -->
                                <div class="space-y-4">
                                    @foreach ($weekdays as $day => $letter)
                                        @php
                                            $dayData = $doctor->available_days[$day] ?? null;
                                            $hasTime = !empty($dayData['start_time']);
                                        @endphp

                                        <div class="flex items-center justify-between group">
                                            <span
                                                class="text-sm font-bold {{ $hasTime ? 'text-slate-700' : 'text-slate-400' }} capitalize">
                                                {{ $day }}
                                            </span>

                                            @if ($hasTime)
                                                <div
                                                    class="flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                                    <i class="ri-time-line text-emerald-500 text-sm"></i>
                                                    <span class="text-xs font-black text-emerald-700">
                                                        {{ date('h:i A', strtotime($dayData['start_time'])) }} -
                                                        {{ date('h:i A', strtotime($dayData['end_time'])) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter">Not
                                                    Available</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Digital Signature at Bottom of Sidebar -->
                                <div class="mt-10 pt-8 border-t border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">
                                        Physician's Signature</p>
                                    <div
                                        class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center justify-center h-24">
                                        <img src="{{ !empty($doctor->signature) ? asset($doctor->signature) : asset('assets/img/sine.png') }}"
                                            class="max-h-full opacity-80 mix-blend-multiply">
                                    </div>

                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">
                                        Physician's Stamp</p>
                                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center justify-center h-24">
                                        <img src="{{ !empty($doctor->stamp) ? asset($doctor->stamp) : asset('assets/img/stamp.png') }}"
                                            class="max-h-full opacity-80 mix-blend-multiply">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
