@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h1 class="display-6 fw-bold text-dark">
                        <i class="fas fa-users-cog me-3 text-primary"></i>User Management
                    </h1>
                    <div class="d-flex align-items-center">
                        <div class="input-group rounded-pill overflow-hidden me-3" style="width: 300px;">
                            <form action="{{ route('admin.users') }}" method="GET" class="d-flex w-100">
                                @foreach (request()->except('search', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input type="text" class="form-control border-0 px-4 py-2" placeholder="Search users..."
                                    id="searchInput" name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <button class="btn btn-primary btn-lg rounded-pill shadow-sm">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-4 border-0">
                        <ul class="nav nav-pills nav-fill nav-segment rounded-pill bg-light p-1" id="userTabs">
                            <li class="nav-item">
                                <a class="nav-link rounded-pill active" data-bs-toggle="tab" href="#clients">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-tie me-2"></i>Clients
                                        <span class="badge bg-primary ms-2">{{ $clients->total() }}</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#workers">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-hard-hat me-2"></i>Workers
                                        <span class="badge bg-success ms-2">{{ $workers->total() }}</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#admins">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-shield me-2"></i>Admins
                                        <span class="badge bg-info ms-2">{{ $admins->total() }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="clients">
                                @include('admin.users.partials.clients-table', ['users' => $clients])
                            </div>
                            <div class="tab-pane fade" id="workers">
                                @include('admin.users.partials.workers-table', ['users' => $workers])
                            </div>
                            <div class="tab-pane fade" id="admins">
                                @include('admin.users.partials.admins-table', ['users' => $admins])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#userTabs .nav-link');
            const searchInput = document.getElementById('userSearch');
            const tables = document.querySelectorAll('.table tbody');

            // Enhanced tab handling with smooth transitions
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up search handlers');

            // Get the search input and button
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            if (!searchInput || !searchButton) {
                console.error('Search input or button not found in the DOM');
                return;
            }

            console.log('Search elements found, adding event listeners');

            // Function to handle search
            function handleSearch() {
                console.log('Search triggered with value:', searchInput.value);

                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = window.location.pathname; // Current URL path

                // Add the search parameter
                const searchParam = document.createElement('input');
                searchParam.type = 'hidden';
                searchParam.name = 'search';
                searchParam.value = searchInput.value.trim();
                form.appendChild(searchParam);

                // Add all other existing query parameters (except search and page)
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.forEach((value, key) => {
                    if (key !== 'search' && key !== 'page') {
                        const param = document.createElement('input');
                        param.type = 'hidden';
                        param.name = key;
                        param.value = value;
                        form.appendChild(param);
                    }
                });

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }

            // Add click event listener to the search button
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Search button clicked');
                handleSearch();
            });

            // Add keypress event listener to the search input for Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    console.log('Enter key pressed in search input');
                    handleSearch();
                }
            });

            console.log('Event listeners added successfully');
        });
    </script>
@endsection
