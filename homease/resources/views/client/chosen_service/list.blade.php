@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Available {{ $serviceType }} Workers</h2>

        @foreach ($workers as $worker)
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">{{ $worker->full_name }}</h3>
                        {{-- <p class="text-gray-500">Role: {{ ucfirst($worker->role) }}</p> --}}
                        <p class="text-gray-500">Experience: {{ $worker->workerVerification->experience ?? 'N/A' }} years</p>
                    </div>
                    <div class="text-right">
                        <p class="text-yellow-500 text-lg font-semibold">
                            ⭐ {{ number_format($worker->averageRating(), 1) }}
                        </p>
                        <p class="text-blue-600 font-bold">₱{{ number_format($worker->hourly_rate, 2) }}/hour</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
