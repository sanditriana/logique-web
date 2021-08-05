<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- First Name -->
            <div>
                <x-label for="name" :value="__('First Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="firstName" :value="old('firstName')" required autofocus />
            </div>

             <!-- Last Name -->
             <div class="mt-4">
                <x-label for="name" :value="__('Last Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="lastName" :value="old('lastName')" />
            </div>


            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Date Of Birth')" />

                <x-input id="dob" class="block mt-1 w-full" type="text" name="dob" :value="old('dob')" required />
            </div>

            <!-- CreditCard -->
            <div class="mt-4">
                <x-label for="creditCard" :value="__('Credit Card')" />

                <x-input id="cc" class="block mt-1 w-full" type="text" name="cc" :value="old('cc')" required />
            </div>

            <!-- Address -->
            <div class="mt-4">
                <x-label for="address" :value="__('Address')" />

                <textarea id="address" class="block mt-1 w-full" name="address" required>{{old('address')}}</textarea>
            </div>

            <!-- MemberShip -->
            <div class="mt-4">
                <x-label for="address" :value="__('MemberShip')" />

                <select name="membershipType" class="block mt-1 w-full MembershipType">
                    @foreach($memberShipTypes as $memberShipType)
                    <option value="{{$memberShipType->type}}">{{$memberShipType->type}}</option> 
                    @endforeach
                <select>
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

            <!-- Term And Condition -->
            <div class="mt-4">
                <input type="checkbox" name="tc" required>
                <label for="checkbox"> I agree with term and condition</label>
            </div>

            <div class="flex items-center justify-end mt-4">
                

                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>