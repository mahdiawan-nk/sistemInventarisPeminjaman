<div>
    <form wire:submit.prevent="store" class="flex flex-col gap-3">
        <!-- Name -->
        <flux:input name="name" :label="__('Name')" :value="old('name')" type="text" autofocus
            autocomplete="name" :placeholder="__('Full name')" wire:model="name"/>

        <!-- Email Address -->
        <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" 
            autocomplete="email" placeholder="email@example.com" wire:model="email"/>

        <flux:input name="no_hp" :label="__('Phone number')" :value="old('no_hp')" type="text" 
            autocomplete="no_hp" placeholder="08xxxxxxxx" wire:model="no_hp"/>
        <!-- Password -->
        <flux:input name="password" :label="__('Password')" type="password" autocomplete="new-password"
            :placeholder="__('Password')" viewable wire:model="password"/>

        <!-- Confirm Password -->
        <flux:input name="password_confirmation" :label="__('Confirm password')" type="password" 
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable wire:model="password_confirmation"/>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>
</div>
