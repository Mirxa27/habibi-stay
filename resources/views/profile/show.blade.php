<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your account settings and preferences
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <nav class="space-y-1">
                        <a href="#personal-info" 
                           class="bg-white text-primary hover:bg-gray-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <i class="bi bi-person text-primary mr-3 flex-shrink-0 h-6 w-6"></i>
                            <span class="truncate">Personal Information</span>
                        </a>

                        <a href="#security" 
                           class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <i class="bi bi-shield-lock text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6"></i>
                            <span class="truncate">Security</span>
                        </a>

                        <a href="#notifications" 
                           class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <i class="bi bi-bell text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6"></i>
                            <span class="truncate">Notifications</span>
                        </a>

                        <a href="#payment-methods" 
                           class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <i class="bi bi-credit-card text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6"></i>
                            <span class="truncate">Payment Methods</span>
                        </a>

                        <a href="#preferences" 
                           class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <i class="bi bi-gear text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6"></i>
                            <span class="truncate">Preferences</span>
                        </a>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Personal Information -->
                    <section id="personal-info" class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Update your personal information and profile photo
                            </p>

                            <form action="{{ route('user-profile-information.update') }}" method="POST" class="mt-6 space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="flex items-center space-x-6">
                                    <div class="flex-shrink-0">
                                        <div class="relative">
                                            <img class="h-16 w-16 rounded-full" 
                                                 src="{{ Auth::user()->profile_photo_url }}" 
                                                 alt="{{ Auth::user()->name }}">
                                            <span class="absolute inset-0 shadow-inner rounded-full" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn-secondary">
                                            Change Photo
                                        </button>
                                        <p class="mt-1 text-xs text-gray-500">
                                            JPG, GIF or PNG. Max size of 2MB
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="name" id="name" 
                                               value="{{ old('name', Auth::user()->name) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" name="email" id="email" 
                                               value="{{ old('email', Auth::user()->email) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" 
                                               value="{{ old('phone', Auth::user()->phone) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    </div>

                                    <div class="col-span-6">
                                        <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                        <textarea name="bio" id="bio" rows="3" 
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">{{ old('bio', Auth::user()->bio) }}</textarea>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Brief description for your profile.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="btn-primary">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Security -->
                    <section id="security" class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Security</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Update your password and security settings
                            </p>

                            <form action="{{ route('user-password.update') }}" method="POST" class="mt-6">
                                @csrf
                                @method('PUT')

                                <div class="space-y-6">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">
                                            Current Password
                                        </label>
                                        <input type="password" name="current_password" id="current_password" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">
                                            New Password
                                        </label>
                                        <input type="password" name="password" id="password" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                            Confirm New Password
                                        </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="btn-primary">
                                        Update Password
                                    </button>
                                </div>
                            </form>

                            <div class="mt-10 border-t border-gray-200 pt-6">
                                <h3 class="text-md font-medium text-gray-900">Two-Factor Authentication</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Add additional security to your account using two-factor authentication.
                                </p>

                                <div class="mt-4">
                                    @if(! Auth::user()->two_factor_secret)
                                        <form action="{{ route('two-factor.enable') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-secondary">
                                                Enable Two-Factor Authentication
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('two-factor.disable') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-secondary">
                                                Disable Two-Factor Authentication
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Notifications -->
                    <section id="notifications" class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Notification Settings</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage how you receive notifications
                            </p>

                            <form action="{{ route('notification-settings.update') }}" method="POST" class="mt-6">
                                @csrf
                                @method('PUT')

                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Email Notifications</h3>
                                        <div class="mt-4 space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="email_booking_updates" id="email_booking_updates" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->notification_preferences->email_booking_updates ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="email_booking_updates" class="font-medium text-gray-700">Booking Updates</label>
                                                    <p class="text-gray-500">Get notified when a booking is confirmed, modified, or cancelled</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="email_messages" id="email_messages" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->notification_preferences->email_messages ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="email_messages" class="font-medium text-gray-700">Messages</label>
                                                    <p class="text-gray-500">Receive email notifications for new messages</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="email_promotions" id="email_promotions" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->notification_preferences->email_promotions ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="email_promotions" class="font-medium text-gray-700">Promotions</label>
                                                    <p class="text-gray-500">Receive updates about promotions and special offers</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6">
                                        <h3 class="text-sm font-medium text-gray-900">Push Notifications</h3>
                                        <div class="mt-4 space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="push_booking_updates" id="push_booking_updates" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->notification_preferences->push_booking_updates ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="push_booking_updates" class="font-medium text-gray-700">Booking Updates</label>
                                                    <p class="text-gray-500">Get push notifications for booking updates</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="push_messages" id="push_messages" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->notification_preferences->push_messages ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="push_messages" class="font-medium text-gray-700">Messages</label>
                                                    <p class="text-gray-500">Receive push notifications for new messages</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="btn-primary">
                                        Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Payment Methods -->
                    <section id="payment-methods" class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Payment Methods</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage your payment methods and billing information
                            </p>

                            <div class="mt-6">
                                <div class="space-y-4">
                                    @forelse(Auth::user()->paymentMethods as $method)
                                        <div class="flex items-center justify-between py-4 border-b border-gray-200">
                                            <div class="flex items-center">
                                                @if($method->type === 'credit_card')
                                                    <i class="bi bi-credit-card text-gray-400 text-2xl mr-3"></i>
                                                @else
                                                    <i class="bi bi-wallet text-gray-400 text-2xl mr-3"></i>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $method->brand }} •••• {{ $method->last4 }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        Expires {{ $method->exp_month }}/{{ $method->exp_year }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                @if($method->is_default)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Default
                                                    </span>
                                                @else
                                                    <form action="{{ route('payment-methods.make-default', $method) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-sm text-primary hover:text-primary-dark">
                                                            Make Default
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('payment-methods.destroy', $method) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6">
                                            <i class="bi bi-credit-card text-gray-400 text-4xl mb-4"></i>
                                            <p class="text-sm text-gray-500">No payment methods added yet</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="mt-6">
                                    <button type="button" class="btn-primary" onclick="Livewire.emit('openModal', 'add-payment-method')">
                                        <i class="bi bi-plus-lg mr-2"></i>
                                        Add Payment Method
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Preferences -->
                    <section id="preferences" class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Preferences</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Customize your experience
                            </p>

                            <form action="{{ route('preferences.update') }}" method="POST" class="mt-6">
                                @csrf
                                @method('PUT')

                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Language & Region</h3>
                                        <div class="mt-4 grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                                                <select id="language" name="language" 
                                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                                    <option value="en" {{ Auth::user()->preferences->language === 'en' ? 'selected' : '' }}>English</option>
                                                    <option value="ar" {{ Auth::user()->preferences->language === 'ar' ? 'selected' : '' }}>Arabic</option>
                                                </select>
                                            </div>

                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                                <select id="currency" name="currency" 
                                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                                    <option value="SAR" {{ Auth::user()->preferences->currency === 'SAR' ? 'selected' : '' }}>Saudi Riyal (SAR)</option>
                                                    <option value="USD" {{ Auth::user()->preferences->currency === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6">
                                        <h3 class="text-sm font-medium text-gray-900">Privacy</h3>
                                        <div class="mt-4 space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="show_profile" id="show_profile" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->preferences->show_profile ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="show_profile" class="font-medium text-gray-700">Public Profile</label>
                                                    <p class="text-gray-500">Allow other users to view your profile</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="share_activity" id="share_activity" 
                                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                           {{ Auth::user()->preferences->share_activity ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="share_activity" class="font-medium text-gray-700">Activity Sharing</label>
                                                    <p class="text-gray-500">Share your booking activity with other users</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="btn-primary">
                                        Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 