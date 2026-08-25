<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('ລຶບບັນຊີ') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('ເມື່ອລຶບບັນຊີແລ້ວ ຂໍ້ມູນ ແລະຊັບພະຍາກອນທັງໝົດຈະຖືກລຶບຖາວອນ. ກ່ອນລຶບບັນຊີ ກະລຸນາດາວໂຫຼດຂໍ້ມູນທີ່ຕ້ອງການເກັບໄວ້.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('ລຶບບັນຊີ') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('ທ່ານແນ່ໃຈບໍວ່າຕ້ອງການລຶບບັນຊີ?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('ເມື່ອລຶບບັນຊີແລ້ວ ຂໍ້ມູນທັງໝົດຈະຖືກລຶບຖາວອນ. ກະລຸນາປ້ອນລະຫັດຜ່ານເພື່ອຢືນຢັນ.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('ລະຫັດຜ່ານ') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('ລະຫັດຜ່ານ') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('ຍົກເລີກ') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('ລຶບບັນຊີ') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
