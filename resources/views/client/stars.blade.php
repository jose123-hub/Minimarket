<x-client-layout
    title="My Stars"
    active="stars"
    :client="$client"
>
    <x-slot name="styles">
        <style>
            .stars-hero {
                background: linear-gradient(135deg, #080202, #190305, #5a0710);
                border-radius: 16px;
                padding: 34px;
                color: #fff;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 26px;
                overflow: hidden;
            }

            .stars-hero-label {
                color: rgba(255,255,255,0.72);
                font-size: 14px;
                margin-bottom: 8px;
            }

            .stars-count {
                display: flex;
                align-items: center;
                gap: 18px;
                margin-bottom: 12px;
            }

            .stars-count .star-icon {
                font-size: 58px;
                color: #e8192c;
                line-height: 1;
            }

            .stars-count strong {
                font-size: 58px;
                font-weight: 900;
                line-height: 1;
            }

            .stars-description {
                color: rgba(255,255,255,0.78);
                font-size: 15px;
                line-height: 1.6;
                max-width: 540px;
            }

            .hero-decoration {
                font-size: 120px;
                color: rgba(232, 25, 44, 0.22);
                font-weight: 900;
                line-height: 1;
            }

            .rewards-title {
                display: flex;
                align-items: center;
                gap: 9px;
                font-size: 21px;
                font-weight: 900;
                color: #111;
                margin-bottom: 18px;
            }

            .rewards-title svg {
                width: 20px;
                height: 20px;
                stroke: #e8192c;
                fill: none;
                stroke-width: 2;
            }

            .rewards-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
            }

            .reward-card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 14px;
                padding: 22px;
                min-height: 190px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .reward-card.available {
                border-color: #f3a8b0;
            }

            .reward-card.locked {
                opacity: 0.58;
            }

            .reward-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 16px;
            }

            .reward-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #e8192c;
            }

            .reward-card.locked .reward-icon {
                color: #777;
            }

            .reward-icon svg {
                width: 30px;
                height: 30px;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
            }

            .reward-stars {
                background: #fff0f2;
                color: #e8192c;
                font-size: 13px;
                font-weight: 900;
                padding: 6px 11px;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .reward-name {
                font-size: 16px;
                font-weight: 900;
                color: #111;
                margin-bottom: 6px;
            }

            .reward-description {
                font-size: 14px;
                color: #777;
                line-height: 1.5;
                margin-bottom: 10px;
            }

            .reward-benefit {
                font-size: 13px;
                color: #e8192c;
                font-weight: 800;
                margin-bottom: 14px;
            }

            .btn-reward {
                width: 100%;
                height: 38px;
                border: none;
                border-radius: 9px;
                background: #e8192c;
                color: #fff;
                font-weight: 900;
                cursor: pointer;
            }

            .btn-reward.disabled {
                background: #f4f4f4;
                color: #999;
            }

            .empty-rewards {
                background: #fff;
                border: 1px dashed #ddd;
                border-radius: 16px;
                padding: 56px 20px;
                text-align: center;
                color: #777;
            }

            .empty-rewards svg {
                width: 42px;
                height: 42px;
                stroke: #aaa;
                fill: none;
                stroke-width: 1.8;
                margin-bottom: 12px;
            }

            .empty-rewards h3 {
                color: #111;
                font-size: 17px;
                margin-bottom: 6px;
            }
            
            .stars-extra-info {
              display: flex;
              flex-wrap: wrap;
              gap: 10px;
              margin-top: 16px;
            }

             .stars-pill {
              background: rgba(255,255,255,0.12);
              border: 1px solid rgba(255,255,255,0.18);
              border-radius: 999px;
              padding: 8px 12px;
              font-size: 12px;
              font-weight: 800;
              color: rgba(255,255,255,0.9);
            }

            .alert-message {
              border-radius: 12px;
              padding: 13px 15px;
              margin-bottom: 18px;
              font-size: 13px;
              font-weight: 800;
            }

            .alert-message.success {
              background: #ecfdf5;
              color: #15803d;
              border: 1px solid #bbf7d0;
            }

            .alert-message.error {
              background: #fef2f2;
              color: #dc2626;
              border: 1px solid #fecaca;
            }

            .btn-reward {
              cursor: pointer;
            }

            @media (max-width: 1000px) {
                .rewards-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 700px) {
                .stars-hero {
                    align-items: flex-start;
                }

                .hero-decoration {
                    display: none;
                }

                .rewards-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </x-slot>

    <div class="page-title">
        <div class="page-icon">
            <svg viewBox="0 0 24 24">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>

        <div>
            <h1>My stars</h1>
            <p>Check your stars and available rewards.</p>
        </div>
    </div>
    
    @if(session('success'))
     <div class="alert-message success">
        {{ session('success') }}
     </div>
    @endif

    @if($errors->any())
     <div class="alert-message error">
        {{ $errors->first() }}
     </div>
    @endif

    <section class="stars-hero">
        <div>
            <div class="stars-hero-label">Your Express stars</div>

            <div class="stars-count">
                <span class="star-icon">★</span>
                <strong>{{ $client->accumulated_stars }}</strong>
            </div>

            <p class="stars-description">
                You earn 1 star for every S/ 5.00 you spend. Redeemed rewards become store credit for your next online purchase.
            </p>
            <div class="stars-extra-info">
         <div class="stars-pill">
            Progress: S/ {{ number_format($client->star_progress_amount ?? 0, 2) }} / S/ 5.00
         </div>

         <div class="stars-pill">
           Rewards credit: S/ {{ number_format($client->store_credit_balance ?? 0, 2) }}
         </div>
         </div>
        </div>

        <div class="hero-decoration">✦</div>
    </section>

    <section>
        <h2 class="rewards-title">
            <svg viewBox="0 0 24 24">
                <polyline points="20 12 20 22 4 22 4 12"/>
                <rect x="2" y="7" width="20" height="5"/>
                <line x1="12" y1="22" x2="12" y2="7"/>
                <path d="M12 7H7.5a2.5 2.5 0 110-5C11 2 12 7 12 7z"/>
                <path d="M12 7h4.5a2.5 2.5 0 100-5C13 2 12 7 12 7z"/>
            </svg>
            Rewards catalog
        </h2>

        @if($rewards->count() > 0)
            <div class="rewards-grid">
                @foreach($rewards as $reward)
                    @php
                        $canRedeem = $client->accumulated_stars >= $reward->stars_required;
                        $missingStars = max($reward->stars_required - $client->accumulated_stars, 0);
                    @endphp

                    <div class="reward-card {{ $canRedeem ? 'available' : 'locked' }}">
                        <div>
                            <div class="reward-top">
                                <div class="reward-icon">
                                    <svg viewBox="0 0 24 24">
                                        <polyline points="20 12 20 22 4 22 4 12"/>
                                        <rect x="2" y="7" width="20" height="5"/>
                                        <line x1="12" y1="22" x2="12" y2="7"/>
                                        <path d="M12 7H7.5a2.5 2.5 0 110-5C11 2 12 7 12 7z"/>
                                        <path d="M12 7h4.5a2.5 2.5 0 100-5C13 2 12 7 12 7z"/>
                                    </svg>
                                </div>

                                <div class="reward-stars">
                                    ★ {{ $reward->stars_required }}
                                </div>
                            </div>

                            <div class="reward-name">{{ $reward->name }}</div>

                            <div class="reward-description">
                                {{ $reward->description ?: 'Applicable to your next purchase' }}
                            </div>

                            @if($reward->type === 'discount')
                                <div class="reward-benefit">
                                    Store credit S/ {{ number_format($reward->discount_value, 2) }}
                                </div>
                            @else
                                <div class="reward-benefit">
                                    Special reward
                                </div>
                            @endif
                        </div>

                @if($canRedeem)
                  <form method="POST"
                  action="{{ route('client.rewards.redeem', $reward) }}"
                   onsubmit="return confirm('Redeem this reward? The credit will be applied to your next online purchase.');">
                   @csrf
                   <button type="submit" class="btn-reward">
                    Redeem credit
                   </button>
                   </form>
                    @else
                     <button type="button" class="btn-reward disabled" disabled>
                       Missing {{ $missingStars }} stars
                     </button>
                     @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-rewards">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 12 20 22 4 22 4 12"/>
                    <rect x="2" y="7" width="20" height="5"/>
                    <line x1="12" y1="22" x2="12" y2="7"/>
                </svg>

                <h3>There are no rewards available</h3>
                <p>Come back soon to see new promotions and prizes.</p>
            </div>
        @endif
    </section>
</x-client-layout>