<x-client-layout
    title="Profile"
    active="profile"
    :client="$client"
>
    <x-slot name="styles">
        <style>
            .profile-action-btn {
    border: 1px solid #e5e5e5;
    background: #fff;
    color: #111;
    border-radius: 10px;
    padding: 11px 16px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.15s ease;
}

.profile-action-btn:hover {
    border-color: #e8192c;
    color: #e8192c;
}

.client-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(17, 17, 17, 0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 18px;
}

.client-modal-overlay.open {
    display: flex;
}

.client-modal {
    width: 520px;
    max-width: 95vw;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 18px 50px rgba(0,0,0,0.18);
    overflow: hidden;
}

.client-modal-header {
    padding: 18px 22px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.client-modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 900;
    color: #111;
}

.client-modal-header button {
    border: none;
    background: transparent;
    font-size: 26px;
    color: #999;
    cursor: pointer;
}

.return-policy-content {
    padding: 20px 22px;
}

.policy-item {
    padding: 14px 0;
    border-bottom: 1px solid #f3f3f3;
}

.policy-item:last-child {
    border-bottom: none;
}

.policy-item strong {
    display: block;
    font-size: 14px;
    font-weight: 900;
    color: #111;
    margin-bottom: 5px;
}

.policy-item p {
    margin: 0;
    font-size: 13px;
    color: #777;
    line-height: 1.45;
}

.client-modal-footer {
    padding: 16px 22px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
}

.btn-close-policy {
    background: #e8192c;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 11px 16px;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
}
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
            <button type="button" class="profile-action-btn" onclick="openReturnPolicyModal()">
              Return policy
            </button>
        </div>
    </div>
    <div class="client-modal-overlay" id="return-policy-modal">
    <div class="client-modal">
        <div class="client-modal-header">
            <h3>Return policy</h3>
            <button type="button" onclick="closeReturnPolicyModal()">×</button>
        </div>

        <div class="return-policy-content">
            <div class="policy-item">
                <strong>1. Return period</strong>
                <p>Returns can be requested within 7 days after the purchase date.</p>
            </div>

            <div class="policy-item">
                <strong>2. Proof of purchase</strong>
                <p>The customer must present the receipt or order number.</p>
            </div>

            <div class="policy-item">
                <strong>3. Product condition</strong>
                <p>The product must be in good condition and with its original packaging when applicable.</p>
            </div>

            <div class="policy-item">
                <strong>4. Non-returnable products</strong>
                <p>Used, damaged, expired, or opened personal-use products cannot be returned.</p>
            </div>

            <div class="policy-item">
                <strong>5. Approval process</strong>
                <p>The cashier can register the return request, but only the administrator can approve or reject it.</p>
            </div>

            <div class="policy-item">
                <strong>6. Stock update</strong>
                <p>Returned stock is restored only after the administrator approves the return.</p>
            </div>
        </div>

        <div class="client-modal-footer">
            <button type="button" onclick="closeReturnPolicyModal()" class="btn-close-policy">
                I understand
            </button>
        </div>
    </div>
</div>
<script>
    function openReturnPolicyModal() {
        document.getElementById('return-policy-modal').classList.add('open');
    }

    function closeReturnPolicyModal() {
        document.getElementById('return-policy-modal').classList.remove('open');
    }

    document.getElementById('return-policy-modal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeReturnPolicyModal();
        }
    });
</script>
</x-client-layout>