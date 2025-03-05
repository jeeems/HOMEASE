
<template>
    <div class="admin-dashboard">
        <!-- Search and Filter Section -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4"
        >
            <div class="input-group me-sm-3 mb-2 mb-sm-0">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search bookings..."
                    v-model="searchTerm"
                    @input="debouncedSearch"
                />
                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    @click="performSearch"
                >
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <div class="dropdown">
                <button
                    class="btn btn-outline-secondary dropdown-toggle"
                    type="button"
                    id="filterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    {{ getCurrentFilterLabel }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li>
                        <a class="dropdown-item" @click="applyFilter('all')"
                            >All</a
                        >
                    </li>
                    <li>
                        <a class="dropdown-item" @click="applyFilter('pending')"
                            >Pending</a
                        >
                    </li>
                    <li>
                        <a class="dropdown-item" @click="applyFilter('ongoing')"
                            >Ongoing</a
                        >
                    </li>
                    <li>
                        <a
                            class="dropdown-item"
                            @click="applyFilter('completed')"
                            >Completed</a
                        >
                    </li>
                    <li>
                        <a
                            class="dropdown-item"
                            @click="applyFilter('cancelled')"
                            >Cancelled</a
                        >
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Worker</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in bookings" :key="booking.id">
                        <td>{{ booking.id }}</td>
                        <td>{{ booking.client?.full_name || "N/A" }}</td>
                        <td>{{ booking.service_type || "N/A" }}</td>
<td>{{ booking.worker?.full_name || "N/A" }}</td>
<td>{{ booking.scheduled_date || "N/A" }}</td>
<td>₱{{ booking.total_amount || "0" }}</td>
<td>
    <span :class="getStatusBadgeClass(booking.status)">
        {{ booking.status || "N/A" }}
    </span>
</td>

                        <td>
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-outline-secondary"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                >
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            :href="`/admin/bookings/${booking.id}`"
                                            >View</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            :href="`/admin/bookings/${booking.id}/edit`"
                                            >Edit</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ pagination.from }}-{{ pagination.to }} of
                {{ pagination.total }}
            </div>
            <nav>
                <ul class="pagination">
                    <li
                        :class="[
                            'page-item',
                            { disabled: pagination.current_page === 1 },
                        ]"
                    >
                        <a
                            class="page-link"
                            @click="changePage(pagination.current_page - 1)"
                            href="#"
                            >Previous</a
                        >
                    </li>
                    <li
                        v-for="page in pagination.last_page"
                        :key="page"
                        :class="[
                            'page-item',
                            { active: pagination.current_page === page },
                        ]"
                    >
                        <a
                            class="page-link"
                            @click="changePage(page)"
                            href="#"
                            >{{ page }}</a
                        >
                    </li>
                    <li
                        :class="[
                            'page-item',
                            {
                                disabled:
                                    pagination.current_page ===
                                    pagination.last_page,
                            },
                        ]"
                    >
                        <a
                            class="page-link"
                            @click="changePage(pagination.current_page + 1)"
                            href="#"
                            >Next</a
                        >
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';

export default {
    data() {
        return {
            bookings: [],
            searchTerm: "",
            currentFilter: "all",
            pagination: {
                current_page: 1,
                from: 0,
                to: 0,
                total: 0,
                last_page: 0,
            },
            isLoading: false,
            error: null,
        };
    },
    computed: {
        getCurrentFilterLabel() {
            const labels = {
                all: "All",
                pending: "Pending",
                ongoing: "Ongoing",
                completed: "Completed",
                cancelled: "Cancelled",
            };
            return labels[this.currentFilter];
        },
    },
    methods: {
        debouncedSearch: _.debounce(function () {
            this.performSearch();
        }, 500),

        async fetchBookings(page = 1) {
    this.isLoading = true;
    this.error = null;

    try {
        const response = await axios.get("/admin/bookings/ajax", {
            params: {
                page,
                search: this.searchTerm,
                status: this.currentFilter,
            },
        });

        console.log("API Response:", response.data);  // Debugging

        if (!response.data || !Array.isArray(response.data.data)) {
            throw new Error("Invalid data structure");
        }

        this.bookings = response.data.data.map(booking => ({
            id: booking.id,
            client: booking.client || { full_name: "Unknown" },
            service_type: booking.service_type || "N/A",
            worker: booking.worker || { full_name: "Unknown" },
            scheduled_date: booking.scheduled_date || "N/A",
            total_amount: booking.total_amount || "0",
            status: booking.status || "N/A"
        }));

        this.pagination = {
            current_page: response.data.current_page || 1,
            from: response.data.from || 0,
            to: response.data.to || 0,
            total: response.data.total || 0,
            last_page: response.data.last_page || 1,
        };
    } catch (error) {
        console.error("Error fetching bookings:", error);
        this.error = "Failed to fetch bookings. Please try again.";
        this.bookings = [];
    } finally {
        this.isLoading = false;
    }
}


        performSearch() {
            this.fetchBookings();
        },

        applyFilter(status) {
            this.currentFilter = status;
            this.fetchBookings();
        },

        changePage(page) {
            if (page > 0 && page <= this.pagination.last_page) {
                this.fetchBookings(page);
            }
        },

        getStatusBadgeClass(status) {
            const badgeClasses = {
                pending: "badge bg-warning",
                ongoing: "badge bg-info",
                completed: "badge bg-success",
                cancelled: "badge bg-danger",
            };
            return badgeClasses[status] || "badge bg-secondary";
        },

        initializePusherListeners() {
            // Check if Echo is available
            if (window.Echo) {
                window.Echo.channel("bookings").listen(
                    "NewBookingCreated",
                    (event) => {
                        if (this.shouldShowNewBooking(event.booking)) {
                            this.bookings.unshift(event.booking);

                            // Adjust pagination
                            this.pagination.total++;
                            this.pagination.to++;
                        }
                    }
                );
            } else {
                console.warn("Pusher/Echo not initialized");
            }
        },

        shouldShowNewBooking(booking) {
            // Check if the new booking matches current filter and search
            const matchesFilter =
                this.currentFilter === "all" ||
                booking.status === this.currentFilter;
            const matchesSearch =
                !this.searchTerm ||
                booking.id.toString().includes(this.searchTerm) ||
                (booking.client?.full_name || '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (booking.service_type || '').toLowerCase().includes(this.searchTerm.toLowerCase());

            return matchesFilter && matchesSearch;
        },
    },
    mounted() {
        this.fetchBookings();
        this.initializePusherListeners();
    },
};
</script>
