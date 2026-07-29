{{-- resources/views/auth/confirm-password.blade.php --}}
<layouts.auth>
    {{-- Auth Header --}}
    <x-slot:authHeader>
        Confirm Password
    </x-slot:authHeader>
    
    <x-slot:authSubheader>
        This is a secure area of the application. Please confirm your password before continuing.
    </x-slot:authSubheader>

    {{-- Form --}}
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-forms.input 
                name="password" 
                type="password" 
                label="Password" 
                placeholder="••••••••" 
                required 
                :error="$errors->get('password') ? $errors->first('password') : null"
            />
        </div>

        <div class="flex justify-end">
            <x-ui.button variant="primary" size="lg" type="submit" full-width>
                {{ __('Confirm') }}
            </x-ui.button>
        </div>
    </form>
</layouts.auth>