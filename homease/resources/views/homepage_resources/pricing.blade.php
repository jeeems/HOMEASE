@extends('layouts.app')

@section('content')
    <div class="container px-3">
        <h1 class="text-center fw-bold mt-4">DETAILED PRICING</h1>

        @php
            $currentService = request()->get('service', 'cleaning');
        @endphp

        <!-- Service Navigation - Grid on mobile, Row on desktop -->
        <div class="service-nav my-4">
            <!-- Desktop View (single row) -->
            <div class="d-none d-md-flex justify-content-center gap-2">
                <a href="?service=cleaning"
                    class="btn {{ $currentService == 'cleaning' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Cleaning</a>
                <a href="?service=daycare"
                    class="btn {{ $currentService == 'daycare' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Daycare</a>
                <a href="?service=carpentry"
                    class="btn {{ $currentService == 'carpentry' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Carpentry</a>
                <a href="?service=electrical"
                    class="btn {{ $currentService == 'electrical' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Electrical</a>
                <a href="?service=plumbing"
                    class="btn {{ $currentService == 'plumbing' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Plumbing</a>
            </div>

            <!-- Mobile View (grid layout) -->
            <div class="d-md-none">
                <div class="row g-2 justify-content-center">
                    <!-- Top row - 3 buttons -->
                    <div class="col-4">
                        <a href="?service=cleaning"
                            class="btn w-100 {{ $currentService == 'cleaning' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Cleaning</a>
                    </div>
                    <div class="col-4">
                        <a href="?service=daycare"
                            class="btn w-100 {{ $currentService == 'daycare' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Daycare</a>
                    </div>
                    <div class="col-4">
                        <a href="?service=carpentry"
                            class="btn w-100 {{ $currentService == 'carpentry' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Carpentry</a>
                    </div>

                    <!-- Bottom row - 2 buttons -->
                    <div class="col-6">
                        <a href="?service=electrical"
                            class="btn w-100 {{ $currentService == 'electrical' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Electrical</a>
                    </div>
                    <div class="col-6">
                        <a href="?service=plumbing"
                            class="btn w-100 {{ $currentService == 'plumbing' ? 'btn-primary text-white' : 'btn-outline-primary' }}">Plumbing</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Content -->
        @php
            $service = request()->get('service', 'cleaning');
        @endphp

        @if ($service == 'daycare')
            <div class="pricing-container border p-3 p-md-4 bg-light">
                <h2 class="fw-bold h3 h2-md">HOUSEHOLD DAYCARE SERVICES <span
                        class="text-primary d-block d-md-inline mt-2 mt-md-0">₱299 - ₱499</span></h2>
                <h4 class="fw-bold mt-4">HOURLY RATES:</h4>
                <ul class="list-unstyled">
                    <li class="mb-1">• Basic care: PHP 299 per hour per child</li>
                    <li class="mb-1">• Specialized care (e.g., special needs): PHP 299 - 499 per hour per child</li>
                </ul>
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                    <a href="{{ route('reviews.all-reviews', ['service_type' => 'Daycare']) }}"
                        class="btn btn-secondary w-100 w-md-auto">CHECK REVIEWS</a>
                    <a href="{{ auth()->check() ? route('workers.list', ['service_type' => 'Daycare']) : route('login') }}"
                        class="btn btn-primary w-100 w-md-auto">
                        BOOK NOW
                    </a>
                </div>
            </div>
        @elseif($service == 'carpentry')
            <div class="pricing-container border p-3 p-md-4 bg-light">
                <h2 class="fw-bold h3 h2-md">CARPENTRY <span class="text-primary d-block d-md-inline mt-2 mt-md-0">₱499 -
                        ₱2599+</span></h2>
                <ul class="list-unstyled mt-4">
                    <li class="mb-1">• INSPECTION CHARGE: PHP 499 (per visit)</li>
                    <li class="mb-1">• Furniture Repair (Small to medium): 499 – 1200 (per furniture)</li>
                    <li class="mb-1">• Small projects (custom chairs): 499 – 1500 (per furniture)</li>
                    <li class="mb-1">• Medium projects (e.g., custom cabinets): PHP 499 – 2599 (per furniture)</li>
                </ul>
                <p class="text-muted small mt-4">
                    <strong>Note:</strong> The inspection charge is 499 (if the client won't pursue such
                    activity, it will automatically charge inspection charge of 499)

                    Note that common tools for carpenter are already provided by
                    the carpenter for the client.

                    Note that major materials are not carried out by the carpenter
                    such as wood/wood glue and other major materials for the
                    possible furniture.
                    the service.
                </p>
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                    <a href='{{ route('reviews.all-reviews', ['service_type' => 'Carpentry']) }}'
                        class="btn btn-secondary w-100 w-md-auto">CHECK REVIEWS</a>
                    <a href="{{ auth()->check() ? route('workers.list', ['service_type' => 'Carpentry']) : route('login') }}"
                        class="btn btn-primary w-100 w-md-auto">
                        BOOK NOW
                    </a>
                </div>
            </div>
        @elseif($service == 'electrical')
            <div class="pricing-container border p-3 p-md-4 bg-light">
                <h2 class="fw-bold h3 h2-md">ELECTRICAL SERVICES <span
                        class="text-primary d-block d-md-inline mt-2 mt-md-0">₱499 - ₱2599</span></h2>

                <h4 class="fw-bold mt-4">RATES:</h4>
                <ul class="list-unstyled">
                    <li class="mb-1">• <strong>INSPECTION CHARGE:</strong> PHP 499 (per visit)</li>
                    <li class="mb-1">• Faucet Repair/Replacement: PHP 500-1,500 per visit (up to 3 faucets)</li>
                    <li class="mb-1">• Toilet Repair/Replacement: PHP 1,000-2,500 per toilet</li>
                    <li class="mb-1">• Sink Repair/Replacement: PHP 500-1,500 per sink</li>
                    <li class="mb-1">• Drain Cleaning: PHP 500-1,500 per drain</li>
                    <li class="mb-1">• Pipe Repair/Replacement: PHP 1,000-5,000 per repair or replacement</li>
                    <li class="mb-1">• Water Heater Installation/Repair: PHP 1,500-2,599+ (varies by size and complexity)
                    </li>
                </ul>

                <p class="text-muted small mt-4">
                    <strong>Note:</strong> Inspection charge is PHP 499 (if the client won't pursue the activity, the charge
                    still applies).
                    Actual appliances, faucet, toilet, or sink are not shouldered by the plumber and should be provided by
                    the client.
                </p>

                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                    <a href='{{ route('reviews.all-reviews', ['service_type' => 'Electrician']) }}'
                        class="btn btn-secondary w-100 w-md-auto">CHECK REVIEWS</a>
                    <a href="{{ auth()->check() ? route('workers.list', ['service_type' => 'Electrician']) : route('login') }}"
                        class="btn btn-primary w-100 w-md-auto">
                        BOOK NOW
                    </a>
                </div>
            </div>
        @elseif($service == 'plumbing')
            <div class="pricing-container border p-3 p-md-4 bg-light">
                <h2 class="fw-bold h3 h2-md">PLUMBING SERVICES <span
                        class="text-primary d-block d-md-inline mt-2 mt-md-0">₱499 - ₱2599</span></h2>

                <h4 class="fw-bold mt-4">RATES:</h4>
                <ul class="list-unstyled">
                    <li class="mb-1">• <strong>INSPECTION CHARGE:</strong> PHP 499 (per visit)</li>
                    <li class="mb-1">• Outlet Installation/Replacement: PHP 500-1,000 per outlet</li>
                    <li class="mb-1">• Switch Installation/Replacement: PHP 300-500 per switch</li>
                    <li class="mb-1">• Light Fixture Installation: PHP 500-1,500 per fixture (varies by complexity)</li>
                    <li class="mb-1">• Circuit Breaker Installation: PHP 750 - 2,000 per breaker</li>
                    <li class="mb-1">• Wiring Repairs: PHP 599-2,599 per hour (varies by complexity)</li>
                    <li class="mb-1">• Electrical Panel Upgrades: PHP 1,500 – 2599 + (varies by scope of work)</li>
                </ul>

                <p class="text-muted small mt-4">
                    <strong>Note:</strong> Inspection charge is PHP 499 (if the client won't pursue such activity,
                    it will automatically charge inspection charge of 499).
                    Note that actual appliances/wires/bulbs/switch and other materials are
                    not shoulder by the electrician, thus it should be provided by the client.
                </p>

                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                    <a href='{{ route('reviews.all-reviews', ['service_type' => 'Plumbing']) }}'
                        class="btn btn-secondary w-100 w-md-auto">CHECK REVIEWS</a>
                    <a href="{{ auth()->check() ? route('workers.list', ['service_type' => 'Plumbing']) : route('login') }}"
                        class="btn btn-primary w-100 w-md-auto">
                        BOOK NOW
                    </a>
                </div>
            </div>
        @else
            <!-- Pricing Section for Condominium & Apartment -->
            <div class="pricing-container border p-3 p-md-4 bg-light mb-4">
                <div class="row g-4">
                    <!-- Left Column (Service Info) -->
                    <div class="col-12 col-md-6 border-end">
                        <h2 class="fw-bold h3 h2-md">Home Cleaning <span
                                class="text-primary d-block d-md-inline mt-2 mt-md-0">₱600 - ₱2999</span></h2>
                        <p>Standard Cleaning for Condominium and Apartment (20 - 60 sqm)</p>

                        <h4 class="fw-bold mt-4">RATES:</h4>
                        <ul class="list-unstyled">
                            <li class="mb-1">• Studio room with 1 bathroom = ₱600 (1 cleaner for 1 hour)</li>
                            <li class="mb-1">• 1 Bedroom with 1 bathroom = ₱700 (1 cleaner for 1 hour 30 mins)</li>
                            <li class="mb-1">• 2 Bedroom with 1 bathroom = ₱850 (1 cleaner for 2 hours)</li>
                            <li class="mb-1">• 3 Bedroom with 1 bathroom = ₱1,050 (2 cleaners 3 hours)</li>
                            <li class="mb-1">• 4 Bedroom with 1 bathroom = ₱1,250 (2 cleaners 4 hours)</li>
                        </ul>

                        <h4 class="fw-bold mt-4">INCLUSIONS:</h4>
                        <ul class="list-unstyled">
                            <li class="mb-1">• Vacuum and wiping of general surface.</li>
                            <li class="mb-1">• Standard cleaning for living space, kitchen (small), and bathroom.</li>
                            <li class="mb-1">• Free Disposal of 1 garbage bag (Large plastic Bag).</li>
                            <li class="mb-1">• Small Balcony cleaning is included.</li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-6 d-flex flex-column">
                        <div>
                            <h4 class="fw-bold">ADD ONS</h4>
                            <ul class="list-unstyled">
                                <li class="mb-1">• Additional 400php per sofa cleaning (30 minutes)</li>
                                <li class="mb-1">• Additional 100 php per Electric Fan cleaning (30 minutes)</li>
                                <li class="mb-1">• Additional 200 php per Disinfectant Spraying</li>
                                <li class="mb-1">• Additional 400 php per Dishwashing and Clothes folding (45 minutes)
                                </li>
                                <li class="mb-1">• Additional 150 php per Ironing of clothes (30 clothes, 30 minutes)
                                </li>
                                <li class="mb-1">• Additional 150php per Bathroom (30 minutes)</li>
                                <li class="mb-1">• Additional 150php per hour of cleaning</li>
                                <li class="mb-1">• Additional 400php per 1 cleaner (Must request early)</li>
                                <li class="mb-1">• Additional 30php per 1 Garbage Disposal (Large Plastic Bag)</li>
                            </ul>
                        </div>


                    </div>
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                        <a href='{{ route('reviews.all-reviews', ['service_type' => 'Home Cleaning']) }}'
                            class="btn btn-secondary w-100 w-md-auto">CHECK REVIEWS</a>
                        <a href="{{ auth()->check() ? route('workers.list', ['service_type' => 'Home Cleaning']) : route('login') }}"
                            class="btn btn-primary w-100 w-md-auto">
                            BOOK NOW
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pricing Section for House Cleaning -->
            <div class="pricing-container border p-3 p-md-4 bg-light">
                <div class="row g-4">
                    <!-- Left Column (Service Info) -->
                    <div class="col-12 col-md-6 border-end">
                        <h2 class="fw-bold h3 h2-md">Home Cleaning <span
                                class="text-primary d-block d-md-inline mt-2 mt-md-0">₱600 - ₱2999</span></h2>
                        <p>Standard Cleaning for Houses (>61 sqm)</p>

                        <h4 class="fw-bold mt-4">RATES:</h4>
                        <ul class="list-unstyled">
                            <li class="mb-1">• 1 Bedroom with 1 bathroom = ₱1,100 (2 cleaners 2 hours)</li>
                            <li class="mb-1">• 2 Bedroom with 1 bathroom = ₱1,270 (2 cleaners 3 hours)</li>
                            <li class="mb-1">• 3 Bedroom with 1 bathroom = ₱1,450 (2 cleaners 4 hours)</li>
                            <li class="mb-1">• 4 Bedroom with 1 bathroom = ₱1,700 (2 cleaners 5 hours)</li>
                        </ul>

                        <h4 class="fw-bold mt-4">INCLUSIONS:</h4>
                        <ul class="list-unstyled">
                            <li class="mb-1">• Vacuum and wiping of general surface.</li>
                            <li class="mb-1">• Standard cleaning for living space, kitchen (small), and bathroom.</li>
                            <li class="mb-1">• Free Disposal of 1 garbage bag (Large plastic Bag).</li>
                        </ul>
                    </div>

                    <!-- Right Column (Add-ons & Buttons) -->
                    <div class="col-12 col-md-6">
                        <h4 class="fw-bold">ADD ONS</h4>
                        <ul class="list-unstyled">
                            <li class="mb-1">• Additional 150php per Bathroom (30 minutes)</li>
                            <li class="mb-1">• Additional 150php per hour of cleaning</li>
                            <li class="mb-1">• Additional 400php per 1 cleaner (Must request early)</li>
                            <li class="mb-1">• Additional 30php per 1 Garbage Disposal (Large Plastic Bag)</li>
                            <li class="mb-1">• Additional 250php per Balcony Cleaning (30 minutes)</li>
                            <li class="mb-1">• Additional 400php per 1 cleaner (Must request early)</li>
                            <li class="mb-1">• Additional 30php per 1 Garbage Disposal (Large Plastic Bag)</li>
                            <li class="mb-1">• Additional 250php per Balcony Cleaning (30 minutes)</li>
                            <li class="mb-1">• Additional 400php per sofa cleaning (30 minutes)</li>
                            <li class="mb-1">• Additional 100 php per Electric Fan cleaning (30 minutes)</li>
                            <li class="mb-1">• Additional 200 php per Disinfectant Spraying</li>
                            <li class="mb-1">• Additional 400 php per Dishwashing and Clothes folding (45 minutes)</li>
                            <li class="mb-1">• Additional 150 php per Ironing of clothes (30 clothes, 30 minutes)</li>
                            <li class="mb-1">• Additional 100php per floor level of surface area of the house cleaning
                                (30 minutes)</li>
                        </ul>
                    </div>
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                        <a href='{{ route('reviews.all-reviews', ['service_type' => 'Home Cleaning']) }}'
                            class="btn btn-secondary w-100 w-md-auto">CHECK REVIEWS</a>
                        <a href="{{ auth()->check() ? route('workers.list', ['service_type' => 'Home Cleaning']) : route('login') }}"
                            class="btn btn-primary w-100 w-md-auto">
                            BOOK NOW
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
