<x-client-layout
    title="Profile"
    active="profile"
    :client="$client"
>
    <x-slot name="styles">
        <style>
            .profile-grid {
                display: grid;
                grid-template-columns: 320px 1fr;
                gap: 18px;
            }

            .profile-card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 14px;
                padding: 24px;
            }

            .profile-main {
                text-align: center;
            }

            .profile-avatar {
                width: 86px;
                height: 86px;
                border-radius: 50%;
                background: #fff0f2;
                color: #e8192c;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 36px;
                font-weight: 900;
                margin: 0 auto 16px;
            }

            .profile-name {
                font-size: 20px;
                font-weight: 900;
                color: #111;
                margin-bottom: 4px;
            }

            .profile-email {
                font-size: 14px;
                color: #777;
                margin-bottom: 18px;
            }

            .stars-box {
                background: #111;
                color: #fff;
                border-radius: 13px;
                padding: 18px;
                text-align: left;
            }

            .stars-box span {
                font-size: 13px;
                color: rgba(255,255,255,0.65);
            }

            .stars-box strong {
                display: block;
                font-size: 32px;
                font-weight: 900;
                margin-top: 6px;
            }

            .profile-section-title {
                font-size: 17px;
                font-weight: 900;
                margin-bottom: 16px;
                color: #111;
            }

            .info-list {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }

            .info-item {
                background: #fafafa;
                border: 1px solid #eee;
                border-radius: 12px;
                padding: 16px;
            }

            .info-label {
                font-size: 12px;
                color: #888;
                margin-bottom: 6px;
            }

            .info-value {
                font-size: 15px;
                color: #111;
                font-weight: 800;
            }

            .note-box {
                margin-top: 18px;
                background: #fffbeb;
                border: 1px solid #fde68a;
                border-radius: 12px;
                padding: 15px 17px;
                color: #78350f;
                font-size: 14px;
                line-height: 1.6;
            }

            @media (max-width: 850px) {
                .profile-grid {
                    grid-template-columns: 1fr;
                }

                .info-list {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </x-slot>

    @php
        $clientName = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
        $clientName = $clientName ?: (auth()->user()->name ?? 'Cliente');
        $initial = strtoupper(substr($clientName, 0, 1));
    @endphp

    <div class="page-title">
        <div class="page-icon">
            <svg viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>

        <div>
            <h1>Profile</h1>
            <p>Check your information as an Express customer.</p>
        </div>
    </div>

    <div class="profile-grid">
        <div class="profile-card profile-main">
            <div class="profile-avatar">
                {{ $initial }}
            </div>

            <div class="profile-name">
                {{ $clientName }}
            </div>

            <div class="profile-email">
                {{ $client->email }}
            </div>

            <div class="stars-box">
                <span>Available stars</span>
                <strong>★ {{ $client->accumulated_stars }}</strong>
            </div>
        </div>

        <div class="profile-card">
            <h2 class="profile-section-title">Customer information</h2>

            <div class="info-list">
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $client->first_name ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Last name</div>
                    <div class="info-value">{{ $client->last_name ?: '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Mail</div>
                    <div class="info-value">{{ $client->email ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Type of customer</div>
                    <div class="info-value">{{ ucfirst($client->type ?? 'regular') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Registration date</div>
                    <div class="info-value">{{ $client->created_at?->format('d/m/Y') ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Rule of stars</div>
                    <div class="info-value">1 star for S/ 5.00</div>
                </div>
            </div>

            <div class="note-box">
                Your stars automatically accumulate when you make registered purchases. You can check the available rewards from the section <strong>My stars</strong>.
            </div>
        </div>
    </div>
</x-client-layout>