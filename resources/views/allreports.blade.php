@extends('layouts.app')

@section('title', 'All Reports')

@section('content')

    <div class="flex h-screen divide-x-2 divide-gray-100">

        @include('common.sidenav')

        <div class="main-content flex-1 ml-64 transition-all duration-300">

            @include('common.header')

            <div class="p-5 bg-white">

                <h2 class="text-xl font-semibold mb-5">
                    All Patient Reports
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm border">

                        <thead class="bg-emerald-100">

                            <tr>
                                <th class="p-3 border text-left">Sr.no</th>
                                <th class="p-3 border text-left">Patient</th>
                                <th class="p-3 border text-left">Doctor</th>
                                <th class="p-3 border text-left">Report Name</th>
                                <th class="p-3 border text-left">Remarks</th>
                                {{-- <th class="p-3 border text-left">Uploaded At</th> --}}
                                <th class="p-3 border text-left">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($reports as $report)
                                <tr>

                                    <td class="p-3 border">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="p-3 border">
                                        {{ $report->patient_name }}
                                    </td>

                                    <td class="p-3 border">
                                        {{ $report->doctor_name }}
                                    </td>

                                    <td class="p-3 border">
                                        {{ $report->report_name }}
                                    </td>

                                    <td class="p-3 border">
                                        {{ $report->remarks }}
                                    </td>
{{--
                                    <td class="p-3 border">
                                        {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y h:i A') }}
                                    </td> --}}

                                    <td class="p-3 border">

                                        <a href="{{ asset($report->report_file) }}" target="_blank"
                                            class="px-3 py-1 bg-blue-500 text-white rounded-lg">

                                            View Report

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center p-5">
                                        No Reports Found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-5">
                    {{ $reports->links() }}
                </div>

            </div>

        </div>

    </div>

@endsection
