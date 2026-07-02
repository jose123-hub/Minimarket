<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Express — {{ $title }}</title>

    <style>
        .client-notification-wrapper {
    position: relative;
}

.client-notification-btn {
    width: 38px;
    height: 38px;
    border: none;
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.client-notification-btn:hover {
    background: #f5f5f5;
}

.client-notification-btn svg {
    width: 21px;
    height: 21px;
    stroke: #444;
    fill: none;
    stroke-width: 1.8;
}

.client-notification-dot {
    min-width: 17px;
    height: 17px;
    padding: 0 5px;
    background: #e8192c;
    color: #fff;
    border-radius: 999px;
    position: absolute;
    top: 3px;
    right: 2px;
    border: 2px solid #fff;
    font-size: 10px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
}

.client-notification-dropdown {
    display: none;
    position: absolute;
    top: 46px;
    right: 0;
    width: 320px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 14px;
    box-shadow: 0 14px 40px rgba(0,0,0,0.12);
    z-index: 9999;
    overflow: hidden;
}

.client-notification-dropdown.open {
    display: block;
}

.client-notification-header {
    padding: 15px 18px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.client-notification-header strong {
    font-size: 15px;
    color: #111;
}

.client-notification-header span {
    font-size: 12px;
    color: #999;
}

.client-notification-item {
    padding: 14px 18px;
    border-bottom: 1px solid #f5f5f5;
}

.client-notification-item strong {
    display: block;
    font-size: 13px;
    color: #111;
    margin-bottom: 4px;
}

.client-notification-item p {
    margin: 0;
    font-size: 12px;
    color: #777;
    line-height: 1.4;
}

.client-notification-item.info strong {
    color: #2563eb;
}

.client-notification-item.success strong {
    color: #16a34a;
}

.client-notification-item.reward strong,
.client-notification-item.stars strong {
    color: #f59e0b;
}

.client-notification-empty {
    padding: 24px 18px;
    text-align: center;
    color: #aaa;
    font-size: 13px;
}

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #fafafa;
            color: #111;
            min-height: 100vh;
        }

        .client-navbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 220px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #e8192c;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg {
            width: 19px;
            height: 19px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        .brand-text strong {
            display: block;
            font-size: 20px;
            font-weight: 900;
            line-height: 1;
        }

        .brand-text span {
            font-size: 11px;
            color: #777;
        }

        .client-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .client-nav a {
            height: 38px;
            padding: 0 15px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            color: #555;
            font-size: 14px;
            font-weight: 700;
            transition: 0.18s ease;
        }

        .client-nav a:hover {
            background: #f5f5f5;
            color: #111;
        }

        .client-nav a.active {
            background: #e8192c;
            color: #fff;
        }

        .client-nav svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
        }

        .client-user {
            min-width: 220px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .client-user-info {
            text-align: right;
        }

        .client-user-info strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            color: #111;
        }

        .client-stars {
            font-size: 12px;
            color: #555;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .client-stars span {
            color: #e8192c;
            font-weight: 900;
        }

        .client-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffe2e7;
            color: #e8192c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
        }

        .logout-client-btn {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #777;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-client-btn:hover {
            color: #e8192c;
        }

        .logout-client-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        .client-content {
            max-width: 980px;
            margin: 0 auto;
            padding: 28px 10px 50px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 26px;
        }

        .page-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: #fff0f2;
            color: #e8192c;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-icon svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
        }

        .page-title h1 {
            font-size: 26px;
            font-weight: 900;
            color: #111;
        }

        .page-title p {
            font-size: 14px;
            color: #777;
            margin-top: 3px;
        }

        .toast-message {
            position: fixed;
            top: 76px;
            right: 28px;
            z-index: 9999;
            min-width: 280px;
            max-width: 380px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            animation: slideInToast 0.25s ease;
        }

        .success-toast {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .error-toast {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .toast-message.hide {
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        @keyframes slideInToast {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 900px) {
            .client-navbar {
                height: auto;
                padding: 12px 16px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .brand,
            .client-user {
                min-width: auto;
            }

            .client-nav {
                order: 3;
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .client-content {
                padding: 24px 16px 45px;
            }
        }
            .client-avatar-link {
              text-decoration: none;
              color: inherit;
              display: inline-flex;
            }

            .client-avatar-link:hover {
               opacity: 0.85;
            }
            .client-notification-item {
              display: block;
              text-decoration: none;
              color: inherit;
              cursor: pointer;
            }

            .client-notification-item:hover {
              background: #f9fafb;
            }
    </style>

    {{ $styles ?? '' }}
</head>

<body>
@php
    $user = auth()->user();

    $clientName = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));

    if ($clientName === '') {
        $clientName = $user->name ?? 'Cliente';
    }

    $clientStars = $client->accumulated_stars ?? 0;
    $clientInitial = strtoupper(substr($clientName, 0, 1));
@endphp

<nav class="client-navbar">
    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>

        <div class="brand-text">
            <strong>Express</strong>
            <span>Minimarket · Client</span>
        </div>
    </div>

    <div class="client-nav">
    <a href="{{ url('/client/catalog') }}" class="{{ $active === 'store' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
        </svg>
        Store
    </a>

    <a href="{{ url('/client/orders') }}" class="{{ $active === 'orders' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24">
            <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
        </svg>
        My Orders
    </a>

    <a href="{{ url('/client/stars') }}" class="{{ $active === 'stars' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
        My Stars
    </a>

    <a href="{{ url('/client/profile') }}" class="{{ $active === 'profile' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
        Profile
    </a>
</div>
    
    <div class="client-user">
        <div class="client-user-info">
            <strong>{{ $clientName }}</strong>
            <div class="client-stars">
                <span>★</span>
                {{ $clientStars }} stars
            </div>
        </div>
        <a href="{{ url('/client/profile') }}" class="client-avatar-link">
        <div class="client-avatar">
            {{ $clientInitial }}
        </div>
        </a>

         <div class="client-notification-wrapper">
    <button type="button" class="client-notification-btn" onclick="toggleClientNotifications(event)">
        <svg viewBox="0 0 24 24">
            <path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>

        @if(($ClientNotificationCount ?? 0) > 0)
          <span class="notification-dot" id="portal-notification-count">
        {{ $ClientNotificationCount > 9 ? '9+' : $ClientNotificationCount }}
         </span>
        @endif
    </button>

    <div class="client-notification-dropdown" id="client-notification-dropdown">
        <div class="client-notification-header">
            <strong>Notifications</strong>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>

        @forelse($clientNotifications ?? [] as $notification)
       <a href="{{ $notification['url'] ?? '#' }}"
       class="client-notification-item {{ $notification['type'] ?? '' }}"
       data-client-notification-key="{{ $notification['key'] ?? md5($notification['title']) }}"
       onclick="dismissClientNotification(event, this)">
        <strong>{{ $notification['title'] }}</strong>
        <p>{{ $notification['message'] }}</p>
        </a>
        @empty
         <div class="client-notification-empty">No notifications for now.</div>
        @endforelse

        <div class="client-notification-empty" id="client-notification-empty-js" style="display:none;">
        No notifications for now.
      </div>
     </div>
    </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-client-btn">
                <svg viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>
    </div>
</nav>

<main class="client-content">
    @if(session('success'))
        <div class="toast-message success-toast">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="toast-message error-toast">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="toast-message error-toast">
            {{ $errors->first() }}
        </div>
    @endif

    {{ $slot }}
</main>

<script>
    function toggleClientNotifications(event) {
        event.stopPropagation();

        const dropdown = document.getElementById('client-notification-dropdown');
        if (dropdown) {
            dropdown.classList.toggle('open');
        }
    }

    document.addEventListener('click', function (event) {
        const wrapper = document.querySelector('.client-notification-wrapper');

        if (wrapper && !wrapper.contains(event.target)) {
            document.getElementById('client-notification-dropdown')?.classList.remove('open');
        }
    });

    function getDismissedClientNotifications() {
    const saved = localStorage.getItem('dismissed_client_notifications');

    if (!saved) {
        return {};
    }

    try {
        return JSON.parse(saved);
    } catch (e) {
        return {};
    }
}

function saveDismissedClientNotifications(data) {
    localStorage.setItem('dismissed_client_notifications', JSON.stringify(data));
}

function updateClientNotificationCount() {
    const items = document.querySelectorAll('.client-notification-item');
    let visibleCount = 0;

    for (let i = 0; i < items.length; i++) {
        if (items[i].style.display !== 'none') {
            visibleCount++;
        }
    }

    const countBadge = document.getElementById('client-notification-count');
    const emptyBox = document.getElementById('client-notification-empty-js');

    if (countBadge) {
        if (visibleCount === 0) {
            countBadge.remove();
        } else {
            countBadge.textContent = visibleCount > 9 ? '9+' : visibleCount;
        }
    }

    if (emptyBox) {
        emptyBox.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

function loadDismissedClientNotifications() {
    const dismissed = getDismissedClientNotifications();
    const items = document.querySelectorAll('.client-notification-item');

    for (let i = 0; i < items.length; i++) {
        const key = items[i].dataset.clientNotificationKey;

        if (dismissed[key] === true) {
            items[i].style.display = 'none';
        }
    }

    updateClientNotificationCount();
}

function dismissClientNotification(event, element) {
    event.preventDefault();

    const key = element.dataset.clientNotificationKey;
    const url = element.getAttribute('href');

    const dismissed = getDismissedClientNotifications();
    dismissed[key] = true;
    saveDismissedClientNotifications(dismissed);

    element.style.display = 'none';
    updateClientNotificationCount();

    setTimeout(function () {
        window.location.href = url;
    }, 150);
}

document.addEventListener('DOMContentLoaded', function () {
    loadDismissedClientNotifications();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastMessages = document.querySelectorAll('.toast-message');

    toastMessages.forEach(function (toast) {
        setTimeout(function () {
            toast.classList.add('hide');

            setTimeout(function () {
                toast.remove();
            }, 300);
        }, 3000);
    });
});
</script>

{{ $scripts ?? '' }}

</body>
</html>