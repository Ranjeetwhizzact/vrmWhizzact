@extends('layouts.app')
@section('title', 'Whizzact | Add Patient')
@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100">
        <!-- Sidebar -->
        @include('common.sidenav')

        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 transition-all duration-300 bg-gray-50">
            <!-- Header -->
            @include('common.header')

            <div class="p-6">
                <!-- Form Container -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="ri-user-add-line mr-2 text-white"></i>
                                    {{ isset($patient->id) ? 'Edit Patient Details' : 'Register New Patient' }}
                                </h2>
                                <p class="text-emerald-100 text-sm mt-1">
                                    {{ isset($patient->id) ? 'Update the patient information below' : 'Fill in the patient information below to create a new record' }}
                                </p>
                            </div>
                            <div class="bg-white/20 rounded-lg px-4 py-2">
                                <span class="text-white text-sm font-medium">Patient Registration</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ url('/storepatient') }}" method="POST" enctype="multipart/form-data"
                        accept-charset="UTF-8" class="p-6">
                        @csrf

                        <!-- Progress Steps (Optional - shows form completion) -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex items-center text-emerald-600 relative">
                                        <div
                                            class="rounded-full bg-emerald-100 w-8 h-8 flex items-center justify-center border-2 border-emerald-600">
                                            <span class="text-emerald-600 font-semibold">1</span>
                                        </div>
                                        <div class="ml-2 text-sm font-medium text-emerald-600">Personal Info</div>
                                    </div>
                                    <div class="w-12 h-0.5 bg-gray-200 mx-2"></div>
                                    <div class="flex items-center text-gray-400">
                                        <div
                                            class="rounded-full bg-gray-100 w-8 h-8 flex items-center justify-center border-2 border-gray-300">
                                            <span class="text-gray-500 font-semibold">2</span>
                                        </div>
                                        <div class="ml-2 text-sm font-medium text-gray-500">Insurance Details</div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <i class="ri-information-line mr-1"></i>All fields marked with <span
                                        class="text-red-500">*</span> are required
                                </div>
                            </div>
                        </div>

                        <!-- Patient ID (Hidden) -->
                        <input type="hidden" name="id" value="{{ isset($patient->id) ? $patient->id : '' }}">

                        <!-- Personal Information Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="ri-user-line text-emerald-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 ml-3">Personal Information</h3>
                                <div class="flex-1 ml-4">
                                    <div class="h-0.5 bg-gradient-to-r from-emerald-200 to-transparent"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Case Registration Date
                                    </label>

                                    <div class="relative">

                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-calendar-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>

                                        <input type="datetime-local" name="case_registration_datetime"
                                            id="customer_case_registration_date"
                                            value="{{ isset($patient->case_registration_datetime) ? \Carbon\Carbon::parse($patient->case_registration_datetime)->format('Y-m-d\TH:i') : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white">

                                    </div>
                                </div>
                                <!-- First Name -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">

                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-user-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>

                                        <input type="text" name="first_name"
                                            value="{{ isset($patient->full_name) ? explode(' ', $patient->full_name)[0] : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white"
                                            placeholder="John" required>

                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">

                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-user-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>

                                        <input type="text" name="last_name"
                                            value="{{ isset($patient->full_name) ? implode(' ', array_slice(explode(' ', $patient->full_name), 1)) : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white"
                                            placeholder="Doe" required>

                                    </div>
                                </div>


                                <!-- Gender -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-men-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <select
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            name="gender">
                                            <option value="male"
                                                {{ isset($patient->gender) && $patient->gender == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female"
                                                {{ isset($patient->gender) && $patient->gender == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other"
                                                {{ isset($patient->gender) && $patient->gender == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Marital Status -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-heart-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <select
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            name="marital_status">
                                            <option value="single"
                                                {{ isset($patient->marital_status) && $patient->marital_status == 'single' ? 'selected' : '' }}>
                                                Single</option>
                                            <option value="married"
                                                {{ isset($patient->marital_status) && $patient->marital_status == 'married' ? 'selected' : '' }}>
                                                Married</option>
                                            <option value="divorced"
                                                {{ isset($patient->marital_status) && $patient->marital_status == 'divorced' ? 'selected' : '' }}>
                                                Divorced</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Profile</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-heart-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <select
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            name="customer_profile">
                                            <option value="Normal"
                                                {{ isset($patient->customer_profile) && $patient->customer_profile == 'normal' ? 'selected' : '' }}>
                                                Normal</option>
                                            <option value="HNI"
                                                {{ isset($patient->customer_profile) && $patient->customer_profile == 'HNI' ? 'selected' : '' }}>
                                                HNI</option>
                                            <option value="NRI"
                                                {{ isset($patient->customer_profile) && $patient->customer_profile == 'NRI' ? 'selected' : '' }}>
                                                NRI</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>


                                <!-- DOB -->





                                <!-- DOB -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-calendar-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <input type="date" name="dob"
                                            value="{{ isset($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : '' }}"
                                            id="customer_dob"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white">
                                    </div>
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2"> MER Type</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-heart-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <select
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            name="mer_type">
                                            <option value="Video MER"
                                                {{ isset($patient->mer_type) && $patient->mer_type == 'Video MER' ? 'selected' : '' }}>
                                                Video MER</option>
                                            <option value="Physical MER"
                                                {{ isset($patient->mer_type) && $patient->mer_type == 'Physical MER' ? 'selected' : '' }}>
                                                Physical MER</option>

                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-mail-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <input type="email" name="email"
                                            value="{{ isset($patient->email) ? $patient->email : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white"
                                            placeholder="example@email.com">
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-phone-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <input type="tel" name="phone"
                                            value="{{ isset($patient->phone) ? $patient->phone : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white phonenumber"
                                            placeholder="9876543210" pattern="[987]{1}[0-9]{9}" required>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">10 digit mobile number</p>
                                </div>



                                <!-- Blood Group -->
                                {{-- <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-drop-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <select
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            name="blood_group">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'A+' ? 'selected' : '' }}>
                                                A+</option>
                                            <option value="A-"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'A-' ? 'selected' : '' }}>
                                                A-</option>
                                            <option value="B+"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'B+' ? 'selected' : '' }}>
                                                B+</option>
                                            <option value="B-"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'B-' ? 'selected' : '' }}>
                                                B-</option>
                                            <option value="O+"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'O+' ? 'selected' : '' }}>
                                                O+</option>
                                            <option value="O-"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'O-' ? 'selected' : '' }}>
                                                O-</option>
                                            <option value="AB+"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'AB+' ? 'selected' : '' }}>
                                                AB+</option>
                                            <option value="AB-"
                                                {{ isset($patient->blood_group) && $patient->blood_group == 'AB-' ? 'selected' : '' }}>
                                                AB-</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- Document Type -->
                                {{-- <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-file-copy-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <select
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            name="id_type">
                                            <option value="">Select Document Type</option>
                                            <option value="Aadhar Card"
                                                {{ isset($patient->id_type) && $patient->id_type == 'Aadhar Card' ? 'selected' : '' }}>
                                                Aadhar Card</option>
                                            <option value="Pan Card"
                                                {{ isset($patient->id_type) && $patient->id_type == 'Pan Card' ? 'selected' : '' }}>
                                                Pan Card</option>
                                            <option value="Driving Licence"
                                                {{ isset($patient->id_type) && $patient->id_type == 'Driving Licence' ? 'selected' : '' }}>
                                                Driving Licence</option>
                                            <option value="Passport"
                                                {{ isset($patient->id_type) && $patient->id_type == 'Passport' ? 'selected' : '' }}>
                                                Passport</option>
                                            <option value="Voter ID"
                                                {{ isset($patient->id_type) && $patient->id_type == 'Voter ID' ? 'selected' : '' }}>
                                                Voter ID</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Document -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Document</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ri-upload-line text-gray-400"></i>
                                        </div>
                                        <input type="file" name="id_document"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    </div>
                                    @if (isset($patient->id_document) && $patient->id_document)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="ri-file-line mr-1"></i>Current: {{ $patient->id_document }}
                                        </p>
                                    @endif
                                </div> --}}

                                <!-- Patient Address (spans 2 columns) -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Preferred Language <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-phone-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <input type="tel" name="preferred_language"
                                            value="{{ isset($patient->preferred_language) ? $patient->preferred_language : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white phonenumber"
                                            placeholder="Preferred Language" required>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Enter your preferred language</p>
                                </div>
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Sum Assured <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-phone-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <input type="tel" name="sum_assured"
                                            value="{{ isset($patient->sum_assured) ? $patient->sum_assured : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white phonenumber"
                                            placeholder="Sum Assured" required>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Enter the sum assured</p>
                                </div>
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pincode <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-phone-line text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                        </div>
                                        <input type="tel" name="pincode"
                                            value="{{ isset($patient->Pincode) ? $patient->Pincode : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white phonenumber"
                                            placeholder="123456" pattern="[0-9]{6}" required>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">6 digit pincode</p>
                                </div>
                                <div class="lg:col-span-2 group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient Address</label>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 pointer-events-none">
                                            <i class="ri-map-pin-line text-gray-400"></i>
                                        </div>
                                        <textarea
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-gray-50 focus:bg-white"
                                            rows="3" name="address" placeholder="Enter your full address">{{ isset($patient->address) ? $patient->address : '' }}</textarea>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Insurance Company Information Section -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="ri-shield-check-line text-blue-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 ml-3">Insurance Information</h3>
                                <div class="flex-1 ml-4">
                                    <div class="h-0.5 bg-gradient-to-r from-blue-200 to-transparent"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Third-Party Administrator -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">TPA Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-building-line text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="text" name="third_party_administrator"
                                            value="{{ isset($patient->third_party_administrator) ? $patient->third_party_administrator : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white"
                                            placeholder="TPA name">
                                    </div>
                                </div>

                                <!-- Insurance Company Name -->
                                <div class="lg:col-span-3 group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Insurance Company <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-building-4-line text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select name="insurance_company_name"
                                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            id="company_select" required>
                                            <option value="">Select Insurance Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->company_name }}"
                                                    data-company-id="{{ $company->id }}"
                                                    data-company-code="{{ $company->company_code }}"
                                                    {{ isset($patient->insurance_company_name) && $patient->insurance_company_name == $company->company_name ? 'selected' : '' }}>
                                                    {{ $company->company_name }} ({{ $company->company_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="company_id" id="company_id"
                                        value="{{ isset($patient->company_id) ? $patient->company_id : '' }}">
                                </div>

                                <!-- Division Name -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Division <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-grid-line text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select name="division_id"
                                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            id="division_select" required>
                                            <option value="">Select Division</option>
                                            @if (isset($divisions) && count($divisions) > 0)
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->code }}"
                                                        data-division-code="{{ $division->division_Code }}"
                                                        {{ isset($patient->division_id) && $patient->division_id == $division->id ? 'selected' : '' }}>
                                                        {{ $division->division_name }} ({{ $division->division_Code }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="division_code" id="division_code"
                                        value="{{ isset($patient->division_code) ? $patient->division_code : '' }}">
                                </div>

                                <!-- Branch Name -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Branch <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-store-line text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select name="branch_id"
                                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white appearance-none"
                                            id="branch_select" required>
                                            <option value="">Select Branch</option>
                                            @if (isset($branches) && count($branches) > 0)
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->code }}"
                                                        data-branch-code="{{ $branch->branch_code }}"
                                                        data-location="{{ $branch->location }}"
                                                        {{ isset($patient->branch_id) && $patient->branch_id == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->branch_name }} ({{ $branch->branch_code }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="branch_code" id="branch_code"
                                        value="{{ isset($patient->branch_code) ? $patient->branch_code : '' }}">
                                    <input type="hidden" name="location" id="location"
                                        value="{{ isset($patient->location) ? $patient->location : '' }}">
                                </div>

                                <!-- Insurance Company Email -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Insurance Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ri-mail-send-line text-gray-400"></i>
                                        </div>
                                        <input type="email" name="insurance_company_email" id="insurance_company_email"
                                            value="{{ isset($patient->insurance_company_email) ? $patient->insurance_company_email : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                                            placeholder="Auto-filled" readonly>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Auto-filled from company selection</p>
                                </div>

                                <!-- Proposal Number -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Proposal Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ri-file-text-line text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="text" name="proposal_number"
                                            value="{{ isset($patient->proposal_number) ? $patient->proposal_number : '' }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white"
                                            placeholder="Enter proposal number" required>
                                    </div>
                                </div>

                                <!-- Upload PDF -->
                                <div class="group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Proposal PDF</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ri-file-pdf-line text-gray-400"></i>
                                        </div>
                                        <input type="file" name="documents" accept=".pdf"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                    @if (isset($patient->documents) && $patient->documents)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="ri-file-pdf-line mr-1 text-red-500"></i>
                                            Current:

                                            <a href="{{ asset($patient->documents) }}" target="_blank"
                                                class="text-emerald-600 hover:text-emerald-800 underline">
                                                View Document
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ url('/customers') }}"
                                class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:ring-2 focus:ring-gray-200 transition-all flex items-center">
                                <i class="ri-close-line mr-2 text-lg"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-lg hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all flex items-center shadow-lg shadow-emerald-500/20">
                                <i class="ri-save-line mr-2 text-lg"></i>
                                {{ isset($patient->id) ? 'Update Patient' : 'Save Patient' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Section -->
    <section>
        <div id="myModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6 transform transition-all">
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="ri-information-line text-emerald-600 mr-2"></i>
                        Form Status
                    </h2>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="mt-4" id="modalMessage">
                    <!-- Modal content will be inserted here -->
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        // Date of Birth validation (18-70 years)
        const dobInput = document.getElementById('customer_dob');
        if (dobInput) {
            const today = new Date();
            const minDate = new Date(today.getFullYear() - 70, today.getMonth(), today.getDate());
            const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

            dobInput.min = minDate.toISOString().split('T')[0];
            dobInput.max = maxDate.toISOString().split('T')[0];
        }

        // Auto-fill company email and company_id when company is selected
        const companySelect = document.getElementById('company_select');
        const companyEmailInput = document.getElementById('insurance_company_email');
        const companyIdInput = document.getElementById('company_id');

        if (companySelect) {
            companySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                // Set company_id from data attribute
                if (selectedOption.value) {
                    const companyId = selectedOption.getAttribute('data-company-id');
                    const companyEmail = selectedOption.getAttribute('data-company-email');

                    companyIdInput.value = companyId || '';
                    companyEmailInput.value = companyEmail || '';
                } else {
                    companyIdInput.value = '';
                    companyEmailInput.value = '';
                }
            });

            // Trigger change event on page load if a company is already selected
            if (companySelect.value) {
                companySelect.dispatchEvent(new Event('change'));
            }
        }

        // Phone number validation
        const phoneInput = document.querySelector('.phonenumber');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
        }

        // Form validation
        const patientForm = document.querySelector('form');
        if (patientForm) {
            patientForm.addEventListener('submit', function(e) {
                const phone = document.querySelector('input[name="phone"]').value;
                if (phone && phone.length !== 10) {
                    e.preventDefault();
                    showModal('Error', 'Phone number must be 10 digits');
                }

                const dob = document.getElementById('customer_dob').value;
                if (dob) {
                    const birthDate = new Date(dob);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    if (age < 18 || age > 70) {
                        e.preventDefault();
                        showModal('Error', 'Patient age must be between 18 and 70 years');
                    }
                }
            });
        }

        // Modal functionality
        const modal = document.getElementById('myModal');
        const closeModal = document.getElementById('closeModal');

        if (closeModal) {
            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Show success/error messages from session
        @if (session('success'))
            showModal('Success', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showModal('Error', '{{ session('error') }}');
        @endif

        function showModal(title, message) {
            const modal = document.getElementById('myModal');
            const modalMessage = document.getElementById('modalMessage');
            if (modal && modalMessage) {
                const icon = title === 'Success' ? 'ri-checkbox-circle-line text-green-500' :
                    'ri-error-warning-line text-red-500';
                modalMessage.innerHTML = `
                <div class="flex items-start">
                    <i class="${icon} text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-gray-800">${title}</h3>
                        <p class="mt-2 text-gray-600">${message}</p>
                    </div>
                </div>
            `;
                modal.classList.remove('hidden');

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 3000);
            }
        }

        // Company-Division-Branch cascade logic ( OLD )
        // document.addEventListener('DOMContentLoaded', function() {
        //     const companySelect = document.getElementById('company_select');
        //     const divisionSelect = document.getElementById('division_select');
        //     const branchSelect = document.getElementById('branch_select');

        //     const companyIdInput = document.getElementById('company_id');
        //     const divisionCodeInput = document.getElementById('division_code');
        //     const branchCodeInput = document.getElementById('branch_code');
        //     const locationInput = document.getElementById('location');

        //     // Function to load divisions based on company ID
        //     function loadDivisions(companyId, selectedDivisionId = null) {
        //         if (!companyId) {
        //             divisionSelect.innerHTML = '<option value="">Select Division</option>';
        //             branchSelect.innerHTML = '<option value="">Select Branch</option>';
        //             divisionSelect.disabled = false;
        //             branchSelect.disabled = false;
        //             return;
        //         }

        //         divisionSelect.innerHTML = '<option value="">Loading divisions...</option>';
        //         divisionSelect.disabled = true;

        //         branchSelect.innerHTML = '<option value="">Select Branch</option>';
        //         branchSelect.disabled = true;

        //         fetch(`/get-divisions/${companyId}`)
        //             .then(response => response.json())
        //             .then(divisions => {
        //                 let options = '<option value="">Select Division</option>';

        //                 if (divisions.length > 0) {
        //                     divisions.forEach(division => {
        //                         const selected = (selectedDivisionId && division.id == selectedDivisionId) ? 'selected' : '';
        //                         options += `<option value="${division.division_code}"
    //                                     data-division-code="${division.division_code}"
    //                                     ${selected}>
    //                                     ${division.division_name} (${division.division_code})
    //                                 </option>`;
        //                     });
        //                     divisionSelect.disabled = false;
        //                 } else {
        //                     options = '<option value="">No divisions available</option>';
        //                     divisionSelect.disabled = true;
        //                 }

        //                 divisionSelect.innerHTML = options;

        //                 if (selectedDivisionId) {
        //                     loadBranches(selectedDivisionId);
        //                 } else if (divisionSelect.value) {
        //                     divisionSelect.dispatchEvent(new Event('change'));
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error loading divisions:', error);
        //                 divisionSelect.innerHTML = '<option value="">Error loading divisions</option>';
        //                 divisionSelect.disabled = false;
        //             });
        //     }

        //     // Function to load branches based on division ID
        //     function loadBranches(divisionId, selectedBranchId = null) {
        //         if (!divisionId) {
        //             branchSelect.innerHTML = '<option value="">Select Branch</option>';
        //             branchSelect.disabled = false;
        //             return;
        //         }

        //         branchSelect.innerHTML = '<option value="">Loading branches...</option>';
        //         branchSelect.disabled = true;

        //         fetch(`/get-branches/${divisionId}`)
        //             .then(response => response.json())
        //             .then(branches => {
        //                 let options = '<option value="">Select Branch</option>';

        //                 if (branches.length > 0) {
        //                     branches.forEach(branch => {
        //                         const selected = (selectedBranchId && branch.id == selectedBranchId) ? 'selected' : '';
        //                         options += `<option value="${branch.id}"
    //                                     data-branch-code="${branch.branch_code}"
    //                                     data-location="${branch.location}"
    //                                     ${selected}>
    //                                     ${branch.branch_name} (${branch.branch_code}) - ${branch.location}
    //                                 </option>`;
        //                     });
        //                     branchSelect.disabled = false;
        //                 } else {
        //                     options = '<option value="">No branches available</option>';
        //                     branchSelect.disabled = true;
        //                 }

        //                 branchSelect.innerHTML = options;

        //                 if (selectedBranchId) {
        //                     branchSelect.dispatchEvent(new Event('change'));
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error loading branches:', error);
        //                 branchSelect.innerHTML = '<option value="">Error loading branches</option>';
        //                 branchSelect.disabled = false;
        //             });
        //     }

        //     // Event listeners
        //     if (companySelect) {
        //         companySelect.addEventListener('change', function() {
        //             const selectedOption = this.options[this.selectedIndex];
        //             const companyId = selectedOption.getAttribute('data-company-id');
        //             companyIdInput.value = companyId || '';
        //             loadDivisions(companyId);
        //         });

        //         if (companySelect.value) {
        //             companySelect.dispatchEvent(new Event('change'));
        //         }
        //     }

        //     if (divisionSelect) {
        //         divisionSelect.addEventListener('change', function() {
        //             const selectedOption = this.options[this.selectedIndex];
        //             const divisionId = this.value;
        //             const divisionCode = selectedOption.getAttribute('data-division-code');
        //             divisionCodeInput.value = divisionCode || '';
        //             loadBranches(divisionId);
        //         });

        //         if (divisionSelect.value) {
        //             divisionSelect.dispatchEvent(new Event('change'));
        //         }
        //     }

        //     if (branchSelect) {
        //         branchSelect.addEventListener('change', function() {
        //             const selectedOption = this.options[this.selectedIndex];
        //             const branchCode = selectedOption.getAttribute('data-branch-code');
        //             const location = selectedOption.getAttribute('data-location');
        //             branchCodeInput.value = branchCode || '';
        //             locationInput.value = location || '';
        //         });

        //         if (branchSelect.value) {
        //             branchSelect.dispatchEvent(new Event('change'));
        //         }
        //     }
        // });

        document.addEventListener('DOMContentLoaded', function() {

            const companySelect = document.getElementById('company_select');
            const divisionSelect = document.getElementById('division_select');
            const branchSelect = document.getElementById('branch_select');

            const divisionCodeInput = document.getElementById('division_code');
            const branchCodeInput = document.getElementById('branch_code');
            const locationInput = document.getElementById('location');

            const selectedDivisionCode = "{{ $patient->division_Code ?? '' }}";
            const selectedBranchCode = "{{ $patient->branch ?? '' }}";

            console.log("Division Code:", selectedDivisionCode);
            console.log("Branch Code:", selectedBranchCode);

            // ================= DIVISIONS =================
            function loadDivisions(companyId) {

                console.log("Company ID sent:", companyId);

                fetch(`/get-divisions/${companyId}`)
                    .then(res => res.json())
                    .then(divisions => {

                        console.log("Divisions:", divisions);

                        let options = '<option value="">Select Division</option>';

                        divisions.forEach(d => {
                            options += `
                        <option value="${d.division_code}">
                            ${d.division_name} (${d.division_code})
                        </option>
                    `;
                        });

                        divisionSelect.innerHTML = options;

                        // ✅ set selected
                        if (selectedDivisionCode) {
                            divisionSelect.value = selectedDivisionCode;
                            divisionCodeInput.value = selectedDivisionCode;

                            console.log("Selected Division:", divisionSelect.value);

                            loadBranches(selectedDivisionCode);
                        }
                    });
            }

            // ================= BRANCHES =================
            function loadBranches(divisionCode) {

                console.log("Division Code sent:", divisionCode);

                fetch(`/get-branches/${divisionCode}`)
                    .then(res => res.json())
                    .then(branches => {

                        console.log("Branches:", branches);

                        let options = '<option value="">Select Branch</option>';

                        branches.forEach(b => {
                            options += `
                        <option value="${b.branch_code}"
                            data-location="${b.location}">
                           ${b.branch_code} - ${b.location}
                        </option>
                    `;
                        });

                        branchSelect.innerHTML = options;

                        // ✅ set selected
                        if (selectedBranchCode) {
                            branchSelect.value = selectedBranchCode;

                            const selectedOption = branchSelect.options[branchSelect.selectedIndex];

                            if (selectedOption) {
                                branchCodeInput.value = selectedBranchCode;
                                locationInput.value = selectedOption.dataset.location;

                                console.log("Selected Branch:", selectedBranchCode);
                            }
                        }
                    });
            }

            // ================= EVENTS =================
            companySelect.addEventListener('change', function() {
                loadDivisions(this.value); // ✅ company_id
            });

            divisionSelect.addEventListener('change', function() {
                const divisionCode = this.value;

                divisionCodeInput.value = divisionCode;

                loadBranches(divisionCode); // ✅ division_code
            });

            branchSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                branchCodeInput.value = this.value;
                locationInput.value = selectedOption.dataset.location || '';
            });

            // ================= AUTO LOAD =================
            if (companySelect.value) {
                loadDivisions(companySelect.value);
            }

        });
    </script>
@endsection
