<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <!--x-application-logo class="w-20 h-20 fill-current text-gray-500" /-->
                <a href="/" class="logo-frame">
                    <img src="/images/Shakys.png" style="width: 128px;" loading="lazy" sizes="(max-width: 767px) 100vw, 53vw" srcset="/images/Shakys-p-500.png 500w, /images/Shakys.png 512w" alt="" class="logo">
                </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div style="text-align: center;
                    font-family: 'Sofia Pro 400';
                    margin-bottom: 30px;
                    color: blue;">
                    THIS IS THE FIRST USER TO BE REGISTERED!<br>
                    IT WILL BE CREATED AS AN "admin" USER.
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Username -->
            <div>
                <x-label for="username" :value="__('Username')" />

                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
            </div>

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')"  />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <!--a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a-->

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
