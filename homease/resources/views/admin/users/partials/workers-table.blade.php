<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-normal">Name</th>
                        <th class="text-muted fw-normal">Email</th>
                        <th class="text-muted fw-normal">Registered</th>
                        <th class="text-end pe-4 text-muted fw-normal">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="align-middle">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        @if ($user->profile && $user->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                                class="rounded-circle object-fit-cover"
                                                style="width: 45px; height: 45px;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px; font-size: 1.2rem;">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->full_name }}</div>
                                        <small class="text-muted">{{ $user->role }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 pt-0 pb-4">
        {{ $users->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
