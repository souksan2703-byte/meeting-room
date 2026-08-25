<nav class="fixed top-0 inset-x-0 h-16 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto h-full px-4 md:px-6 flex items-center justify-between gap-6">

        {{-- Logo + Nav links --}}
        <div class="flex items-center gap-4 md:gap-10 shrink-0">
            <a href="{{ route('dashboard') }}" class="text-lg md:text-xl font-bold text-red-700 whitespace-nowrap transition-transform duration-150 hover:scale-105 inline-block">
            <a href="{{ route('dashboard') }}"
                class="text-lg md:text-xl font-bold text-red-700 whitespace-nowrap transition-transform duration-150 hover:scale-105 inline-block">
                RoomReserve
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'nav-link-active' : '' }}">
                <a href="{{ route('rooms.index') }}"
                    class="nav-link {{ request()->routeIs('rooms.*') ? 'nav-link-active' : '' }}">
                    Rooms
                </a>
                <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'nav-link-active' : '' }}">
                <a href="{{ route('bookings.index') }}"
                    class="nav-link {{ request()->routeIs('bookings.*') ? 'nav-link-active' : '' }}">
                    My Bookings
                </a>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">
                        <a href="{{ route('admin.bookings.index') }}"
                            class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">
                            Approvals
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Right side: icons + profile + logout --}}
        <div class="flex items-center gap-2 md:gap-4 shrink-0">
            <a href="{{ route('notifications.index') }}" title="Notifications"
               class="relative text-gray-400 hover:text-red-700 transition-transform duration-150 hover:scale-110 active:scale-95">
                class="relative text-gray-400 hover:text-red-700 transition-transform duration-150 hover:scale-110 active:scale-95">
                🔔
                @if (auth()->check() && auth()->user()->unreadNotificationsCount() > 0)
                    <span class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center animate-pulse">
                    <span
                        class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center animate-pulse">
                        {{ auth()->user()->unreadNotificationsCount() > 9 ? '9+' : auth()->user()->unreadNotificationsCount() }}
                    </span>
                @endif
            </a>

            

            @auth
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit" class="text-sm border rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-all duration-150 active:scale-95">
                    <button type="submit"
                        class="text-sm border rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-all duration-150 active:scale-95">
                        Logout
                    </button>
                </form>

                <a href="{{ route('profile.edit') }}" title="Profile"
                   class="w-9 h-9 rounded-full bg-red-700 text-white flex items-center justify-center text-sm font-semibold shrink-0 transition-transform duration-150 hover:scale-110
                    class="w-9 h-9 rounded-full bg-red-700 text-white flex items-center justify-center text-sm font-semibold shrink-0 transition-transform duration-150 hover:scale-110
                          {{ request()->routeIs('profile.*') ? 'ring-2 ring-offset-2 ring-red-700' : '' }}">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>

                {{-- Hamburger — เฉพาะจอมือถือ --}}
                <button type="button" onclick="toggleMobileMenu()" id="hamburger-btn"
                        class="md:hidden text-gray-500 text-2xl leading-none px-1 transition-transform duration-200">
                    class="md:hidden text-gray-500 text-2xl leading-none px-1 transition-transform duration-200">
                    ☰
                </button>
            @else
                <a href="{{ route('login') }}" class="text-sm text-red-700 font-medium hover:underline">ເຂົ້າລະບົບ</a>
            @endauth
        </div>

    </div>

    {{-- Mobile dropdown menu — เลื่อนลงแบบนุ่มนวล --}}
    @auth
        <div id="mobile-menu" class="md:hidden bg-white border-b border-gray-200 px-4 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0">
        <div id="mobile-menu"
            class="md:hidden bg-white border-b border-gray-200 px-4 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0">
            <div class="py-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ໜ້າຫຼັກ</a>
                <a href="{{ route('rooms.index') }}" class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('rooms.*') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ຫ້ອງປະຊຸມ</a>
                <a href="{{ route('bookings.index') }}" class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('bookings.*') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ການຈອງຂອງຂ້ອຍ</a>
                <a href="{{ route('dashboard') }}"
                    class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ໜ້າຫຼັກ</a>
                <a href="{{ route('rooms.index') }}"
                    class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('rooms.*') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ຫ້ອງປະຊຸມ</a>
                <a href="{{ route('bookings.index') }}"
                    class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('bookings.*') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ການຈອງຂອງຂ້ອຍ</a>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.bookings.index') }}" class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('admin.*') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ອະນຸມັດການຈອງ</a>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="block py-2 text-sm transition-colors duration-150 {{ request()->routeIs('admin.*') ? 'text-red-700 font-semibold' : 'text-gray-600 hover:text-red-700' }}">ອະນຸມັດການຈອງ</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 text-sm text-gray-600">ອອກຈາກລະບົບ</button>
                </form>
            </div>
        </div>
    @endauth
</nav>

<style>
    /* ขีดเส้นใต้สไลด์เข้าตอน hover เมนู navbar */
    .nav-link {
        position: relative;
        padding-bottom: 4px;
        color: #6b7280;
        transition: color 0.2s ease;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 0;
        background-color: #b91c1c;
        transition: width 0.25s ease;
    }

    .nav-link:hover {
        color: #b91c1c;
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .nav-link-active {
        color: #b91c1c;
        font-weight: 600;
    }

    .nav-link-active::after {
        width: 100%;
    }

    /* ===== Page transition: fade-in ตอนโหลดหน้าเสร็จ =====
       ใช้ CSS animation ล้วนๆ เล่นอัตโนมัติทันทีที่ body render
       (ไม่ตั้ง opacity:0 เป็นค่าเริ่มต้น เพื่อกันไม่ให้ขึ้นจอขาวว่างก่อน) */
    body {
        animation: pageFadeIn 0.3s ease-out;
    }

    @keyframes pageFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    body.page-leaving {
        animation: pageFadeOut 0.12s ease-in forwards;
    }

    @keyframes pageFadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    /* ===== แถบ loading บางๆ ด้านบน (แบบ YouTube/GitHub) ===== */
    #top-loader {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #b91c1c, #ef4444);
        z-index: 100;
        transition: width 0.3s ease-out, opacity 0.2s ease-in;
        opacity: 0;
    }

    #top-loader.loading {
        opacity: 1;
        width: 80%;
    }

    #top-loader.done {
        width: 100%;
        opacity: 0;
    }
</style>

<div id="top-loader"></div>

<script data-persistent="true">
    // ===== ระบบเปลี่ยนหน้าแบบไม่มีจอขาว =====
    // แทนที่จะปล่อยให้เบราว์เซอร์โหลดหน้าใหม่จริง (ซึ่งจะทิ้งหน้าเดิมทันทีแล้วจอว่างระหว่างรอ)
    // เราจะ fetch หน้าใหม่มาด้วย JS ก่อน โดยหน้าเดิมยังค้างอยู่บนจอตลอด แล้วค่อยสลับเข้ามาแบบ fade

    function finishTransition() {
        const loader = document.getElementById('top-loader');
        loader.classList.remove('loading');
        loader.classList.add('done');
        setTimeout(() => loader.classList.remove('done'), 300);
    }

    function restartFadeIn() {
        document.body.classList.remove('page-leaving');
        // force reflow เพื่อให้ CSS animation เล่นใหม่ได้ (ปกติ animation จะเล่นแค่ครั้งเดียวตอน element ถูกสร้าง)
        document.body.style.animation = 'none';
        void document.body.offsetHeight;
        document.body.style.animation = '';
    }

    // เรียก re-execute <script> ทุกตัวในเนื้อหาใหม่ (ยกเว้นตัวนี้เอง เพราะ listener ผูกกับ document อยู่แล้วไม่ต้องผูกซ้ำ)
    function reExecuteScripts(container) {
        container.querySelectorAll('script').forEach(oldScript => {
            if (oldScript.dataset.persistent === 'true') return; // ข้าม script ตัวนี้เอง กันผูก event ซ้ำ
            const newScript = document.createElement('script');
            [...oldScript.attributes].forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.textContent = oldScript.textContent;
            oldScript.replaceWith(newScript);
        });
    }

    async function navigateTo(url) {
        document.getElementById('top-loader').classList.add('loading');
        document.body.classList.add('page-leaving'); // เริ่ม fade-out หน้าเดิม (แต่ยังอยู่บนจอ ไม่ใช่จอขาว)

        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // ถ้าไม่ใช่ 200 (เช่น redirect ไป login, 403, 500) ให้ปล่อยเบราว์เซอร์ไปโหลดจริงแทน เพื่อความถูกต้องของ URL/status
            if (!res.ok || res.redirected) {
                window.location.href = res.url || url;
                return;
            }

            const html = await res.text();
            const newDoc = new DOMParser().parseFromString(html, 'text/html');

            // รอให้ fade-out เล่นจบสั้นๆ ก่อนสลับเนื้อหา (กันกระตุก)
            await new Promise(resolve => setTimeout(resolve, 120));

            document.title = newDoc.title;
            document.body.innerHTML = newDoc.body.innerHTML;
            window.history.pushState({ ajax: true }, '', url);
            window.history.pushState({
                ajax: true
            }, '', url);
            window.scrollTo(0, 0);

            reExecuteScripts(document.body);
            restartFadeIn();
            finishTransition();
        } catch (err) {
            // fetch ล้มเหลว (เช่นเน็ตหลุด) -> fallback ไปโหลดหน้าจริงแบบปกติ
            window.location.href = url;
        }
    }

    document.addEventListener('click', function (e) {
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        const isSameOrigin = link.href.startsWith(window.location.origin);
        const isNewTab = link.target === '_blank';
        const isAnchor = href.startsWith('#');
        const isSpecialClick = e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0;
        const isDownload = link.hasAttribute('download');

        if (!isSameOrigin || isNewTab || isAnchor || isSpecialClick || isDownload) return;

        e.preventDefault();
        navigateTo(link.href);
    });

    // กด back/forward ของเบราว์เซอร์ -> โหลดใหม่แบบปกติ (ปลอดภัยสุด กันสถานะหน้าเพี้ยน)
    window.addEventListener('popstate', function (e) {
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.ajax) {
            window.location.reload();
        }
    });

    // ดักการ submit ฟอร์ม (จองห้อง, login, filter) ให้ fade-out ก่อนเช่นกัน — ฟอร์มยัง submit แบบปกติ
    document.addEventListener('submit', function () {
    document.addEventListener('submit', function() {
        document.getElementById('top-loader').classList.add('loading');
        document.body.classList.add('page-leaving');
    });

    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const btn = document.getElementById('hamburger-btn');
        const isOpen = menu.classList.contains('max-h-96');

        if (isOpen) {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            btn.style.transform = 'rotate(0deg)';
        } else {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            btn.style.transform = 'rotate(90deg)';
        }
    }
</script></script>
</script>
