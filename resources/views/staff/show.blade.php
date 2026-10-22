@extends('layouts.app')

@section('title', ($staff->user?->name ?? 'Staff') . ' - Profile & Works')

@section('styles')
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 0.9rem;
        background: #f1f5f9;
        color: #334155;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: background 0.15s;
    }
    .back-btn:hover { background: #e2e8f0; color: var(--text-main); }

    .profile-hero {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .profile-main { display: flex; align-items: flex-start; gap: 1.5rem; }

    .hero-avatar {
        width: 88px;
        height: 88px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.85rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.14);
    }

    .hero-info h1 {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .hero-role { font-size: 0.95rem; color: var(--primary); font-weight: 600; margin: 0.3rem 0 0.75rem; }
    .hero-bio { font-size: 0.88rem; line-height: 1.55; color: #475569; margin-bottom: 1rem; }

    .skill-badges { display: flex; flex-wrap: wrap; gap: 0.45rem; }
    .skill-badge { background: #eef2ff; color: #4338ca; padding: 0.3rem 0.7rem; border-radius: 8px; font-size: 0.76rem; font-weight: 600; }

    .profile-meta-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .meta-row { display: flex; justify-content: space-between; font-size: 0.83rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.55rem; }
    .meta-row:last-child { border-bottom: none; padding-bottom: 0; }
    .meta-label { color: var(--text-muted); font-weight: 500; }
    .meta-val { color: var(--text-main); font-weight: 600; }

    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.75rem; }
    .kpi-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; }
    .kpi-lbl { font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted); font-weight: 600; }
    .kpi-val { font-size: 1.55rem; font-weight: 700; color: var(--text-main); margin-top: 0.35rem; }

    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .section-header h2 { font-size: 1.15rem; font-weight: 700; }

    .works-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
    .work-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .work-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0, 0, 0, 0.07); }
    .work-banner { height: 8px; width: 100%; }
    .work-body { padding: 1.35rem; flex: 1; display: flex; flex-direction: column; }
    .work-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.6rem; }
    .work-cat { background: #f1f5f9; color: #334155; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 6px; }
    .work-rating { font-size: 0.8rem; font-weight: 700; color: #d97706; }
    .work-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.45rem; }
    .work-desc { font-size: 0.83rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.1rem; }
    .work-meta { margin-top: auto; padding-top: 0.85rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); }
    .work-price { font-weight: 700; color: var(--primary); font-size: 0.9rem; }

    @media (max-width: 1024px) {
        .profile-hero { grid-template-columns: 1fr; }
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .profile-main { flex-direction: column; }
        .kpi-grid { grid-template-columns: 1fr; }
    }
@endsection

@section('header-left')
    <a href="{{ route('staff.index') }}" class="back-btn">
        &larr; Back to Staff & Stylists
    </a>
@endsection

@section('content')
    <!-- Hero Profile Section -->
    <div class="profile-hero">
        <div class="profile-main">
            <div class="hero-avatar" style="background: {{ $staff->avatar_gradient }}">
                {{ $staff->initials }}
            </div>

            <div class="hero-info">
                <h1>
                    {{ $staff->user?->name }}
                    <span class="status-pill status-{{ $staff->status_css }}">
                        <span class="status-dot"></span> {{ $staff->status_label }}
                    </span>
                    <span class="serves-badge serves-{{ $staff->serves_gender }}">
                        {{ $staff->serves_icon }} {{ $staff->serves_label }}
                    </span>
                </h1>
                <div class="hero-role">{{ $staff->job_title }}</div>
                <p class="hero-bio">{{ $staff->bio }}</p>

                <div class="skill-badges">
                    @foreach ($staff->specialties ?? [] as $skill)
                        <span class="skill-badge">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Contact & Schedule Info -->
        <div class="profile-meta-box">
            <div class="meta-row">
                <span class="meta-label">Serves Clients</span>
                <span class="meta-val">{{ $staff->serves_icon }} {{ $staff->serves_label }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Phone</span>
                <span class="meta-val">{{ $staff->user?->phone_number ?? 'N/A' }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Email</span>
                <span class="meta-val">{{ $staff->user?->email }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Experience</span>
                <span class="meta-val">{{ $staff->experience }} (Joined {{ $staff->joined_date }})</span>
            </div>
            @foreach ($staff->schedule ?? [] as $days => $hours)
                <div class="meta-row">
                    <span class="meta-label">{{ $days }}</span>
                    <span class="meta-val">{{ $hours }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Stylist Performance Metrics -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-lbl">Total Works Completed</div>
            <div class="kpi-val">{{ $staff->completed_works }}+</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-lbl">Client Rating</div>
            <div class="kpi-val" style="color: #d97706;">★ {{ number_format($staff->rating, 1) }} <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">({{ $staff->reviews_count }} reviews)</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-lbl">Monthly Revenue</div>
            <div class="kpi-val" style="color: #10b981;">{{ $staff->monthly_revenue }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-lbl">Repeat Client Rate</div>
            <div class="kpi-val" style="color: #4f46e5;">{{ $staff->repeat_clients }}</div>
        </div>
    </div>

    <!-- Portfolio & Works Showcase -->
    <div class="section-header">
        <h2>Featured Works & Portfolio ({{ $staff->works->count() }})</h2>
    </div>

    @if ($staff->works->isEmpty())
        <div class="panel" style="padding: 2rem; text-align: center; color: #64748b; margin-bottom: 2rem;">
            No portfolio works recorded for this staff member yet.
        </div>
    @else
        <div class="works-grid">
            @foreach ($staff->works as $work)
                <div class="work-card">
                    <div class="work-banner" style="background: {{ $work->accent_color }}"></div>
                    <div class="work-body">
                        <div class="work-top">
                            <span class="work-cat">{{ $work->category }}</span>
                            <span class="work-rating">★ {{ number_format($work->rating, 1) }}</span>
                        </div>

                        <div class="work-title">{{ $work->title }}</div>
                        <p class="work-desc">{{ $work->description }}</p>

                        <div class="work-meta">
                            <div>
                                <div><strong>Client:</strong> {{ $work->client_name }}</div>
                                <div>{{ $work->performed_date }} • {{ $work->duration }}</div>
                            </div>
                            <div class="work-price">{{ $work->price }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
