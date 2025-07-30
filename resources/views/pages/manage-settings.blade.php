<x-filament-panels::page>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <form wire:submit.prevent="saveSettings" @keydown.enter.prevent="if($event.target.tagName !== 'TEXTAREA') $wire.saveSettings()">
        @if($errors->any())
            <div class="mb-6">
                <x-filament::section class="bg-danger-50 dark:bg-danger-950/10">
                    <div class="flex gap-3">
                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 text-danger-600 flex-shrink-0" />
                        <div>
                            <h3 class="text-sm font-medium text-danger-800 dark:text-danger-400">
                                {{ __('filament-settings::settings.error_summary', ['count' => $errors->count()]) }}
                            </h3>
                            <p class="text-sm text-danger-600 dark:text-danger-400 mt-1">
                                {{ __('filament-settings::settings.validation_error') }}
                            </p>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        @endif

        <div class="space-y-6">
        @foreach($groups as $groupKey => $group)
            @php
                $sectionHasErrors = false;
                foreach ($group['settings'] ?? [] as $mappedKey => $setting) {
                    if($errors->has('data.' . $mappedKey)) {
                        $sectionHasErrors = true;
                        break;
                    }
                }
            @endphp
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        @if(isset($group['icon']))
                            <x-filament::icon 
                                :icon="$group['icon']" 
                                class="w-5 h-5"
                            />
                        @endif
                        <span>{{ __($group['label']) ?? Str::title($groupKey) }}</span>
                        @if($sectionHasErrors)
                            <span class="inline-flex items-center justify-center w-2 h-2 bg-danger-600 rounded-full"></span>
                        @endif
                    </div>
                </x-slot>

                @php
                    // Group settings by tab while preserving keys
                    $settingsByTab = [];
                    foreach ($group['settings'] ?? [] as $mappedKey => $setting) {
                        $tabName = $setting['tab'] ?? 'general';
                        if (!isset($settingsByTab[$tabName])) {
                            $settingsByTab[$tabName] = [];
                        }
                        $settingsByTab[$tabName][$mappedKey] = $setting;
                    }
                    ksort($settingsByTab);
                @endphp

                @if(count($settingsByTab) > 1)
                    {{-- Multiple tabs - show tab navigation --}}
                    <div x-data="{ 
                        activeTab: '{{ array_key_first($settingsByTab) }}',
                        tabErrors: {
                            @foreach($settingsByTab as $tabName => $tabSettings)
                                '{{ $tabName }}': [
                                    @foreach($tabSettings as $mappedKey => $setting)
                                        @error('data.' . $mappedKey) true, @else false, @enderror
                                    @endforeach
                                ].includes(true),
                            @endforeach
                        }
                    }">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex space-x-8">
                                @foreach($settingsByTab as $tabName => $tabSettings)
                                    @php
                                        $tabHasErrors = false;
                                        foreach($tabSettings as $mappedKey => $setting) {
                                            if($errors->has('data.' . $mappedKey)) {
                                                $tabHasErrors = true;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <button
                                        type="button"
                                        @click="activeTab = '{{ $tabName }}'"
                                        :class="{
                                            'border-primary-500 text-primary-600 dark:border-primary-400 dark:text-primary-400': activeTab === '{{ $tabName }}',
                                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== '{{ $tabName }}'
                                        }"
                                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2"
                                    >
                                        {{ __('filament-settings::settings.tabs.' . $tabName) ?? Str::title(str_replace('_', ' ', $tabName)) }}
                                        @if($tabHasErrors)
                                            <span class="inline-flex items-center justify-center w-2 h-2 bg-danger-600 rounded-full"></span>
                                        @endif
                                    </button>
                                @endforeach
                            </nav>
                        </div>

                        <div class="mt-6">
                            @foreach($settingsByTab as $tabName => $tabSettings)
                                <div 
                                    x-show="activeTab === '{{ $tabName }}'"
                                    class="grid gap-6"
                                    @if(!$loop->first) style="display: none;" @endif
                                >
                                    @foreach($tabSettings as $mappedKey => $setting)
                                        @if(is_string($mappedKey))
                                            @include('filament-settings::partials.setting-field', [
                                                'key' => $mappedKey,
                                                'setting' => $setting,
                                                'data' => $this->data
                                            ])
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Single tab or no tabs - show all settings --}}
                    <div class="grid gap-6">
                        @foreach($group['settings'] ?? [] as $mappedKey => $setting)
                            @if(is_string($mappedKey))
                                @include('filament-settings::partials.setting-field', [
                                    'key' => $mappedKey,
                                    'setting' => $setting,
                                    'data' => $this->data
                                ])
                            @endif
                        @endforeach
                    </div>
                @endif
            </x-filament::section>
        @endforeach
        </div>
    </form>
</x-filament-panels::page>