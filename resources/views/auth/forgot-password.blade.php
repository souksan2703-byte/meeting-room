<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('ລືມລະຫັດຜ່ານບໍ? ບໍ່ເປັນຫຍັງ. ກະລຸນາປ້ອນອີເມວຂອງທ່ານ ແລ້ວລະບົບຈະສົ່ງລິ້ງສຳລັບຕັ້ງລະຫັດຜ່ານໃໝ່.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('ອີເມວ')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('ສົ່ງລິ້ງຕັ້ງລະຫັດຜ່ານໃໝ່') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
