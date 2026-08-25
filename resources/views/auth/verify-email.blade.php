<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('ຂອບໃຈທີ່ລົງທະບຽນ! ກ່ອນເລີ່ມໃຊ້ງານ ກະລຸນາຢືນຢັນອີເມວໂດຍກົດລິ້ງທີ່ພວກເຮົາສົ່ງໃຫ້. ຖ້າບໍ່ໄດ້ຮັບອີເມວ ພວກເຮົາສາມາດສົ່ງໃໝ່ໃຫ້ໄດ້.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('ລິ້ງຢືນຢັນໃໝ່ໄດ້ຖືກສົ່ງໄປຫາອີເມວທີ່ທ່ານໃຫ້ໄວ້ຕອນລົງທະບຽນແລ້ວ.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('ສົ່ງອີເມວຢືນຢັນອີກຄັ້ງ') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('ອອກຈາກລະບົບ') }}
            </button>
        </form>
    </div>
</x-guest-layout>
