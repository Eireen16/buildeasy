<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center">Customer Registration</h2>

        <form method="POST" action="{{ url('register/customer') }}" class="mt-4 space-y-4">
            @csrf

            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="username" required />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="email" required />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="password" required />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="password_confirmation" required />
            </div>

            <div class="flex justify-between items-center">
                <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-primary-button>
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

