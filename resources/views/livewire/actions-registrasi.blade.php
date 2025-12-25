<div>
    <form wire:submit.prevent="store" class="flex flex-col gap-6">
        @csrf
        <!-- Name -->
        <flux:input name="name" :label="__('Name')" :value="old('name')" type="text" required autofocus
            autocomplete="name" :placeholder="__('Full name')" />

        <!-- Email Address -->
        <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" required
            autocomplete="email" placeholder="email@example.com" />

        <flux:input name="no_hp" :label="__('Phone number')" :value="old('no_hp')" type="text" required
            autocomplete="no_hp" placeholder="08xxxxxxxx" />
        <!-- Password -->
        <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password"
            :placeholder="__('Password')" viewable />

        <!-- Confirm Password -->
        <flux:input name="password_confirmation" :label="__('Confirm password')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>
</div>
