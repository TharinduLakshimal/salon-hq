@extends('layouts.app')

@section('title', 'Staff & Stylists')

@section('styles')
    .toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-row-second {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.65rem;
        padding-bottom: 1.15rem;
        border-bottom: 1px solid var(--border-color);
    }

    .filter-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
        margin-right: 0.35rem;
    }

    .filter-tabs { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

    .filter-btn {
        background: #ffffff;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        padding: 0.48rem 0.95rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .filter-btn:hover { border-color: #cbd5e1; color: var(--text-main); }
    .filter-btn.active { background: var(--primary); border-color: var(--primary); color: #ffffff; }

    .gender-btn {
        background: #ffffff;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        padding: 0.42rem 0.85rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .gender-btn.active { background: #0f172a; border-color: #0f172a; color: #ffffff; }

    .alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 0.85rem 1.2rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1.35rem;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 0.85rem 1.2rem;
        border-radius: 10px;
        font-size: 0.85rem;
        margin-bottom: 1.35rem;
    }

    .staff-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(335px, 1fr));
        gap: 1.35rem;
    }

    .staff-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.22s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .staff-card:hover {
        transform: translateY(-4px);
        border-color: #c7d2fe;
        box-shadow: 0 12px 28px -6px rgba(79, 70, 229, 0.14);
    }

    .card-top { padding: 1.4rem 1.5rem 1rem; display: flex; align-items: flex-start; gap: 1rem; }

    .avatar-circle {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
    }

    .card-identity { flex: 1; min-width: 0; }
    .card-header-row { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.25rem; }
    .staff-name { font-size: 1.05rem; font-weight: 700; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .staff-role { font-size: 0.82rem; color: var(--primary); font-weight: 600; margin-bottom: 0.45rem; }

    .rating-serves-row { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap; }
    .rating-row { display: flex; align-items: center; gap: 0.3rem; font-size: 0.78rem; color: var(--text-muted); }
    .star-icon { color: #f59e0b; font-weight: 700; }
    .rating-score { font-weight: 700; color: var(--text-main); }

    .specialty-tags { padding: 0 1.5rem 1.15rem; display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .tag { background: #f1f5f9; color: #475569; font-size: 0.73rem; font-weight: 500; padding: 0.25rem 0.6rem; border-radius: 6px; }

    .metrics-strip {
        margin: 0 1.5rem 1.25rem;
        padding: 0.85rem 1rem;
        background: #f8fafc;
        border-radius: 10px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        text-align: center;
        border: 1px solid #f1f5f9;
    }
    .metric-box { border-right: 1px solid #e2e8f0; }
    .metric-box:last-child { border-right: none; }
    .metric-num { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
    .metric-lbl { font-size: 0.7rem; color: var(--text-muted); margin-top: 2px; }

    .card-footer {
        margin-top: auto;
        padding: 0.9rem 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        background: #fcfcfd;
        transition: color 0.15s, background 0.15s;
    }
    .staff-card:hover .card-footer { color: var(--primary); background: #f5f7ff; }

    /* Modal Styles */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 200;
        padding: 1rem;
    }
    .modal-backdrop.show { display: flex; }

    .modal-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 600px;
        border-radius: 16px;
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.22);
        overflow: hidden;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
    }

    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; }
    .modal-header h2 { font-size: 1.1rem; font-weight: 700; }
    .modal-close { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; }
    .modal-body { padding: 1.5rem; overflow-y: auto; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 0.9rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; font-size: 0.8rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 0.6rem 0.85rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: inherit;
        outline: none;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .serves-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
    .serves-radio-label { cursor: pointer; }
    .serves-radio-label input { display: none; }
    .serves-option-box {
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.65rem 0.5rem;
        text-align: center;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.2rem;
    }
    .serves-radio-label input:checked + .serves-option-box {
        border-color: var(--primary);
        background: #eef2ff;
        color: var(--primary);
    }

    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); background: #f8fafc; display: flex; justify-content: flex-end; gap: 0.75rem; }
    .btn-cancel { background: #ffffff; border: 1px solid #cbd5e1; color: #475569; padding: 0.6rem 1.1rem; border-radius: 8px; font-weight: 600; cursor: pointer; }

    @media (max-width: 768px) {
        .form-grid, .serves-options { grid-template-columns: 1fr; }
    }
@endsection

@section('header-left')
    <div class="page-heading">
        <h1>Staff & Stylists (Unisex Salon)</h1>
        <p>Loaded directly from MySQL database ({{ $staffMembers->count() }} specialists).</p>
    </div>
@endsection

@section('header-right')
    <button type="button" class="btn-primary" onclick="openAddStaffModal()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Add New Staff</span>
    </button>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top: 0.35rem; padding-left: 1.25rem;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="toolbar">
        <div class="filter-tabs" id="categoryTabs">
            <button class="filter-btn active" onclick="filterStaffCategory('all', this)">All Staff ({{ $staffMembers->count() }})</button>
            <button class="filter-btn" onclick="filterStaffCategory('Bridal & Makeup', this)">Bridal & Makeup</button>
            <button class="filter-btn" onclick="filterStaffCategory('Hair Stylists', this)">Hair Stylists</button>
            <button class="filter-btn" onclick="filterStaffCategory('Barbers', this)">Barbers</button>
            <button class="filter-btn" onclick="filterStaffCategory('Estheticians & Nails', this)">Estheticians & Nails</button>
        </div>

        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="staffSearchInput" placeholder="Search staff by name or skill..." oninput="applyFilters()">
        </div>
    </div>

    <div class="filter-row-second" id="genderTabs">
        <span class="filter-label">Serves Clients:</span>
        <button class="gender-btn active" onclick="filterStaffGender('all', this)">All Types</button>
        <button class="gender-btn" onclick="filterStaffGender('both', this)">⚥ Both (Unisex)</button>
        <button class="gender-btn" onclick="filterStaffGender('female', this)">♀ Female Only</button>
        <button class="gender-btn" onclick="filterStaffGender('male', this)">♂ Male Only</button>
    </div>

    <div class="staff-grid" id="staffGrid">
        @foreach ($staffMembers as $member)
            @php
                $skills = $member->specialties ?? [];
            @endphp
            <a href="{{ route('staff.show', $member->id) }}"
               class="staff-card"
               data-category="{{ $member->category }}"
               data-serves="{{ $member->serves_gender }}"
               data-search="{{ strtolower(($member->user?->name ?? '') . ' ' . $member->job_title . ' ' . $member->serves_label . ' ' . implode(' ', $skills)) }}">

                <div class="card-top">
                    <div class="avatar-circle" style="background: {{ $member->avatar_gradient }}">
                        {{ $member->initials }}
                    </div>

                    <div class="card-identity">
                        <div class="card-header-row">
                            <span class="staff-name">{{ $member->user?->name }}</span>
                            <span class="status-pill status-{{ $member->status_css }}">
                                <span class="status-dot"></span>
                                {{ $member->status_label }}
                            </span>
                        </div>

                        <div class="staff-role">{{ $member->job_title }}</div>

                        <div class="rating-serves-row">
                            <div class="rating-row">
                                <span class="star-icon">★</span>
                                <span class="rating-score">{{ number_format($member->rating, 1) }}</span>
                                <span>({{ $member->reviews_count }})</span>
                            </div>

                            <span class="serves-badge serves-{{ $member->serves_gender }}">
                                {{ $member->serves_icon }} {{ $member->serves_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="specialty-tags">
                    @foreach (array_slice($skills, 0, 3) as $skill)
                        <span class="tag">{{ $skill }}</span>
                    @endforeach
                    @if (count($skills) > 3)
                        <span class="tag">+{{ count($skills) - 3 }} more</span>
                    @endif
                </div>

                <div class="metrics-strip">
                    <div class="metric-box">
                        <div class="metric-num">{{ $member->experience }}</div>
                        <div class="metric-lbl">Experience</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-num">{{ $member->completed_works }}+</div>
                        <div class="metric-lbl">Works Done</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-num">{{ $member->today_bookings }}</div>
                        <div class="metric-lbl">Today's Jobs</div>
                    </div>
                </div>

                <div class="card-footer">
                    <span>📞 {{ $member->user?->phone_number ?? 'N/A' }}</span>
                    <span>View Profile & Works &rarr;</span>
                </div>
            </a>
        @endforeach
    </div>
@endsection

@section('modals')
    <div class="modal-backdrop {{ $errors->any() ? 'show' : '' }}" id="addStaffModal" onclick="if(event.target === this) closeAddStaffModal()">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>Add New Staff Member</h2>
                <button type="button" class="modal-close" onclick="closeAddStaffModal()">✕</button>
            </div>

            <form method="POST" action="{{ route('staff.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="staffName">Full Name *</label>
                            <input type="text" id="staffName" name="name" value="{{ old('name') }}" placeholder="e.g. Oshadi Perera" required>
                        </div>

                        <div class="form-group">
                            <label for="staffRole">Job Title / Role *</label>
                            <input type="text" id="staffRole" name="job_title" value="{{ old('job_title') }}" placeholder="e.g. Senior Hair Stylist" required>
                        </div>

                        <div class="form-group full">
                            <label>Works For (Client Gender) *</label>
                            <div class="serves-options">
                                <label class="serves-radio-label">
                                    <input type="radio" name="serves_gender" value="both" {{ old('serves_gender', 'both') === 'both' ? 'checked' : '' }}>
                                    <div class="serves-option-box">
                                        <span>⚥ Both (Unisex)</span>
                                        <small style="font-weight: 400; font-size: 0.72rem;">Male & Female Clients</small>
                                    </div>
                                </label>
                                <label class="serves-radio-label">
                                    <input type="radio" name="serves_gender" value="female" {{ old('serves_gender') === 'female' ? 'checked' : '' }}>
                                    <div class="serves-option-box">
                                        <span>♀ Female Only</span>
                                        <small style="font-weight: 400; font-size: 0.72rem;">Ladies' Salon & Bridal</small>
                                    </div>
                                </label>
                                <label class="serves-radio-label">
                                    <input type="radio" name="serves_gender" value="male" {{ old('serves_gender') === 'male' ? 'checked' : '' }}>
                                    <div class="serves-option-box">
                                        <span>♂ Male Only</span>
                                        <small style="font-weight: 400; font-size: 0.72rem;">Gents' Barber & Grooming</small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="staffCategory">Department Category *</label>
                            <select id="staffCategory" name="category" required>
                                <option value="Hair Stylists">Hair Stylists</option>
                                <option value="Bridal & Makeup">Bridal & Makeup</option>
                                <option value="Barbers">Barbers</option>
                                <option value="Estheticians & Nails">Estheticians & Nails</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="staffGender">Staff Member's Gender *</label>
                            <select id="staffGender" name="gender" required>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="staffExperience">Experience *</label>
                            <input type="text" id="staffExperience" name="experience" value="{{ old('experience') }}" placeholder="e.g. 4 Years" required>
                        </div>

                        <div class="form-group">
                            <label for="staffPhone">Phone Number *</label>
                            <input type="text" id="staffPhone" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g. 077 123 4567" required>
                        </div>

                        <div class="form-group full">
                            <label for="staffEmail">Login Email Address * <small style="font-weight: 400; color: #64748b;">(Default password: <code>password</code>)</small></label>
                            <input type="email" id="staffEmail" name="email" value="{{ old('email') }}" placeholder="e.g. oshadi@salonhq.com" required>
                        </div>

                        <div class="form-group full">
                            <label for="staffSkills">Specialties / Skills (comma separated)</label>
                            <input type="text" id="staffSkills" name="specialties" value="{{ old('specialties') }}" placeholder="e.g. Balayage, Haircut, Keratin, Bridal Dressing">
                        </div>

                        <div class="form-group full">
                            <label for="staffBio">Short Bio & Experience Summary</label>
                            <textarea id="staffBio" name="bio" rows="3" placeholder="Write a short introduction...">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddStaffModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save Staff Member</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let activeCategory = 'all';
        let activeGender = 'all';

        function openAddStaffModal() {
            document.getElementById('addStaffModal').classList.add('show');
            document.getElementById('staffName').focus();
        }

        function closeAddStaffModal() {
            document.getElementById('addStaffModal').classList.remove('show');
        }

        function filterStaffCategory(category, btn) {
            activeCategory = category;
            document.querySelectorAll('#categoryTabs .filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyFilters();
        }

        function filterStaffGender(gender, btn) {
            activeGender = gender;
            document.querySelectorAll('#genderTabs .gender-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyFilters();
        }

        function applyFilters() {
            const query = document.getElementById('staffSearchInput').value.toLowerCase().trim();
            document.querySelectorAll('#staffGrid .staff-card').forEach(card => {
                const matchCategory = (activeCategory === 'all') || (card.dataset.category === activeCategory);
                const matchGender = (activeGender === 'all') || (card.dataset.serves === activeGender);
                const matchSearch = !query || card.dataset.search.includes(query);
                card.style.display = (matchCategory && matchGender && matchSearch) ? 'flex' : 'none';
            });
        }
    </script>
@endsection
