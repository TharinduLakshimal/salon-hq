@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.75rem;
    }

    .stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.35rem;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .stat-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.03em;
    }

    .stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0.4rem 0;
    }

    .stat-trend { font-size: 0.78rem; font-weight: 500; }
    .trend-up { color: #16a34a; }
    .trend-neutral { color: #64748b; }

    .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    }

    .icon-indigo { background: #eef2ff; color: #4f46e5; }
    .icon-emerald { background: #ecfdf5; color: #10b981; }
    .icon-amber { background: #fffbeb; color: #f59e0b; }
    .icon-rose { background: #fff1f2; color: #f43f5e; }

    .dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    }

    .client-cell { font-weight: 600; color: var(--text-main); }
    .client-sub { font-size: 0.76rem; color: var(--text-muted); }

    .side-column { display: flex; flex-direction: column; gap: 1.5rem; }

    .quick-actions {
    padding: 1.25rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.85rem;
    }

    .action-tile {
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 1rem;
    text-decoration: none;
    color: var(--text-main);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.15s ease;
    }

    .action-tile:hover {
    border-color: var(--primary);
    background: #eef2ff;
    color: var(--primary);
    }

    .staff-list {
    padding: 1rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    }

    .staff-item { display: flex; align-items: center; justify-content: space-between; }
    .staff-meta { display: flex; align-items: center; gap: 0.75rem; }
    .staff-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #334155;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    }

    @media (max-width: 1100px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .dashboard-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
    .search-box { display: none; }
    }
@endsection

@section('header-left')
    <div class="page-heading">
        <h1>Dashboard Overview</h1>
        <p>Welcome back, {{ auth()->user()->name ?? 'Admin' }} — Here is what's happening at your salon today.</p>
    </div>
@endsection

@section('header-right')
    <div class="search-box">
        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" placeholder="Search bookings, clients...">
    </div>

    <a href="#" class="btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>New Appointment</span>
    </a>
@endsection

@section('content')
    <!-- KPI Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <div class="stat-label">Today's Bookings</div>
                <div class="stat-value">18</div>
                <div class="stat-trend trend-up">+14% vs yesterday</div>
            </div>
            <div class="stat-icon icon-indigo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div class="stat-label">Today's Revenue</div>
                <div class="stat-value">Rs. 48,500</div>
                <div class="stat-trend trend-up">+8.2% vs last week</div>
            </div>
            <div class="stat-icon icon-emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div class="stat-label">Active Clients</div>
                <div class="stat-value">342</div>
                <div class="stat-trend trend-up">+24 new this month</div>
            </div>
            <div class="stat-icon icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div class="stat-label">Staff on Duty</div>
                <div class="stat-value">6 / 8</div>
                <div class="stat-trend trend-neutral">4 in active session</div>
            </div>
            <div class="stat-icon icon-rose">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="6" cy="6" r="3"></circle>
                    <circle cx="6" cy="18" r="3"></circle>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                    <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Grid: Appointments Table + Side Widgets -->
    <div class="dashboard-grid">
        <div class="panel">
            <div class="panel-header">
                <h2>Today's Appointments</h2>
                <a href="#" class="panel-link">View All Bookings &rarr;</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Stylist</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="client-cell">Nethmi Perera</div>
                                <div class="client-sub">077 412 8930</div>
                            </td>
                            <td>Bridal Hair & Makeup</td>
                            <td>Kavindi S.</td>
                            <td>09:30 AM</td>
                            <td>Rs. 18,500</td>
                            <td><span class="badge badge-progress">In Progress</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="client-cell">Dilshan Silva</div>
                                <div class="client-sub">071 845 2219</div>
                            </td>
                            <td>Haircut & Beard Styling</td>
                            <td>Roshan M.</td>
                            <td>10:15 AM</td>
                            <td>Rs. 3,200</td>
                            <td><span class="badge badge-progress">In Progress</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="client-cell">Sachini Fernando</div>
                                <div class="client-sub">076 992 1044</div>
                            </td>
                            <td>Keratin Hair Treatment</td>
                            <td>Amaya J.</td>
                            <td>11:00 AM</td>
                            <td>Rs. 14,000</td>
                            <td><span class="badge badge-confirmed">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="client-cell">Tharindu Jayasinghe</div>
                                <div class="client-sub">077 203 5511</div>
                            </td>
                            <td>HydraFacial & Skin Care</td>
                            <td>Nadeesha K.</td>
                            <td>01:30 PM</td>
                            <td>Rs. 7,500</td>
                            <td><span class="badge badge-confirmed">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="client-cell">Hashini Wijeratne</div>
                                <div class="client-sub">070 331 6780</div>
                            </td>
                            <td>Gel Nail Extensions</td>
                            <td>Kavindi S.</td>
                            <td>03:00 PM</td>
                            <td>Rs. 5,300</td>
                            <td><span class="badge badge-pending">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="side-column">
            <div class="panel">
                <div class="panel-header">
                    <h2>Quick Actions</h2>
                </div>
                <div class="quick-actions">
                    <a href="#" class="action-tile">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="12" y1="10" x2="12" y2="16"></line>
                            <line x1="9" y1="13" x2="15" y2="13"></line>
                        </svg>
                        <span>Add Booking</span>
                    </a>
                    <a href="#" class="action-tile">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        <span>New Client</span>
                    </a>
                    <a href="#" class="action-tile">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span>Add Service</span>
                    </a>
                    <a href="#" class="action-tile">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <span>POS Billing</span>
                    </a>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2>Stylist Status</h2>
                    <a href="{{ route('staff.index') }}" class="panel-link">Manage</a>
                </div>
                <div class="staff-list">
                    @foreach ($staffMembers as $member)
                        <a href="{{ route('staff.show', $member->id) }}" class="staff-item" style="text-decoration: none; color: inherit;">
                            <div class="staff-meta">
                                <div class="staff-avatar" style="background: {{ $member->avatar_gradient }}; color: #fff;">{{ $member->initials }}</div>
                                <div>
                                    <div class="client-cell">{{ $member->user?->name }}</div>
                                    <div class="client-sub">{{ $member->job_title }}</div>
                                </div>
                            </div>
                            <span class="status-pill status-{{ $member->status_css }}"><span class="status-dot"></span> {{ $member->status_label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection