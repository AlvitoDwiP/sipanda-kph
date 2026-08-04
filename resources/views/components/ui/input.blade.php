@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => null,
    'helpText' => null,
    'hint' => null,
    'error' => null,
    'success' => false,
    'options' => [],
    'value' => null,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'checked' => false,
    'prefixIcon' => null,
    'suffixIcon' => null,
    'characterCount' => false,
    'maxlength' => null,
])

@php
    $inputId = $attributes->get('id') ?? $name;
    
    // Base styles
    $inputBase = 'w-full rounded-ui-md border transition-all focus:outline-none focus:ring-2 text-xs sm:text-sm';
    
    // State classes
    $normalState = 'border-ui-border text-ui-text-primary focus:border-ui-primary focus:ring-ui-primary/20';
    $successState = 'border-ui-success text-ui-text-primary focus:border-ui-success focus:ring-ui-success/20 bg-ui-success-soft/10';
    $errorState = 'border-ui-danger text-ui-danger focus:border-ui-danger focus:ring-ui-danger/20 bg-ui-danger-soft/10';
    
    $currentState = $normalState;
    if ($error) {
        $currentState = $errorState;
    } elseif ($success) {
        $currentState = $successState;
    }
    
    $disabledState = 'disabled:pointer-events-none disabled:bg-ui-primary-soft disabled:text-ui-muted';
    $readonlyState = 'read-only:bg-ui-primary-soft/50 read-only:cursor-default';
    
    $heightClass = 'min-h-[36px] px-3 py-1.5';
    
    // Add extra padding if prefix/suffix icons are present
    $paddingLeft = $prefixIcon ? 'pl-9' : '';
    $paddingRight = ($suffixIcon || $type === 'password') ? 'pr-9' : '';
    
    $inputClasses = "{$inputBase} {$currentState} {$disabledState} {$readonlyState} {$heightClass} {$paddingLeft} {$paddingRight}";
@endphp

<div 
    class="w-full flex flex-col gap-1.5 text-left"
    @if ($characterCount && $maxlength)
        x-data="{ count: {{ strlen($value ?? '') }}, max: {{ $maxlength }} }"
    @elseif ($type === 'password')
        x-data="{ show: false }"
    @endif
>
    <!-- Label & Hint -->
    @if ($label && !in_array($type, ['checkbox', 'radio', 'switch']))
        <div class="flex items-center justify-between gap-4">
            <label for="{{ $inputId }}" class="text-xs font-semibold text-ui-text-primary">
                {{ $label }}
                @if ($required)
                    <span class="text-ui-danger font-bold">*</span>
                @endif
            </label>
            @if ($hint)
                <span class="text-[10px] sm:text-xs text-ui-text-secondary italic">{{ $hint }}</span>
            @endif
        </div>
    @endif

    <!-- Input Containers -->
    <div class="relative w-full flex items-center">
        <!-- Prefix Icon -->
        @if ($prefixIcon && !in_array($type, ['checkbox', 'radio', 'switch', 'file']))
            <div class="absolute left-3 text-ui-text-secondary pointer-events-none shrink-0 flex items-center justify-center">
                <i data-lucide="{{ $prefixIcon }}" class="w-4 h-4"></i>
            </div>
        @endif

        @if ($type === 'textarea')
            <textarea 
                id="{{ $inputId }}"
                name="{{ $name }}"
                placeholder="{{ $placeholder }}"
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $required ? 'required' : '' }}
                @if ($maxlength) maxlength="{{ $maxlength }}" @endif
                @if ($characterCount && $maxlength) @input="count = $el.value.length" @endif
                {{ $attributes->class([$inputBase, $currentState, $disabledState, $readonlyState, 'px-3 py-2 min-h-[100px] resize-y']) }}
            >{{ $slot->isNotEmpty() ? $slot : $value }}</textarea>

        @elseif ($type === 'select')
            <select 
                id="{{ $inputId }}"
                name="{{ $name }}"
                {{ $disabled ? 'disabled' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $attributes->class([$inputClasses]) }}
            >
                @if ($placeholder)
                    <option value="" disabled {{ $value === null ? 'selected' : '' }}>{{ $placeholder }}</option>
                @endif
                {{ $slot }}
                @foreach ($options as $val => $lbl)
                    <option value="{{ $val }}" {{ $value == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>

        @elseif ($type === 'checkbox')
            <div class="flex items-start gap-2.5">
                <input 
                    type="checkbox"
                    id="{{ $inputId }}"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    {{ $checked ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $required ? 'required' : '' }}
                    {{ $attributes->class([
                        'h-4 w-4 rounded border-ui-border text-ui-primary focus:ring-ui-primary/20 focus:ring-offset-0 transition-colors',
                        $error ? 'border-ui-danger' : '',
                        $disabled ? 'opacity-50 pointer-events-none' : ''
                    ]) }}
                >
                @if ($label)
                    <label for="{{ $inputId }}" class="text-xs sm:text-sm font-semibold text-ui-text-primary cursor-pointer select-none">
                        {{ $label }}
                        @if ($required)
                            <span class="text-ui-danger">*</span>
                        @endif
                    </label>
                @endif
            </div>

        @elseif ($type === 'radio')
            <div class="flex items-start gap-2.5">
                <input 
                    type="radio"
                    id="{{ $inputId }}"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    {{ $checked ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $required ? 'required' : '' }}
                    {{ $attributes->class([
                        'h-4 w-4 border-ui-border text-ui-primary focus:ring-ui-primary/20 focus:ring-offset-0 transition-colors',
                        $error ? 'border-ui-danger' : '',
                        $disabled ? 'opacity-50 pointer-events-none' : ''
                    ]) }}
                >
                @if ($label)
                    <label for="{{ $inputId }}" class="text-xs sm:text-sm font-semibold text-ui-text-primary cursor-pointer select-none">
                        {{ $label }}
                        @if ($required)
                            <span class="text-ui-danger">*</span>
                        @endif
                    </label>
                @endif
            </div>

        @elseif ($type === 'switch')
            <div class="flex items-center gap-2.5" x-data="{ checked: {{ $checked ? 'true' : 'false' }} }">
                <button 
                    type="button"
                    role="switch"
                    :aria-checked="checked"
                    @click="if(!{{ $disabled ? 'true' : 'false' }}) { checked = !checked; $refs.checkbox.click() }"
                    :class="checked ? 'bg-ui-primary' : 'bg-ui-border'"
                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-ui-primary/20 focus:ring-offset-0 disabled:opacity-50 disabled:pointer-events-none shadow-sm"
                    {{ $disabled ? 'disabled' : '' }}
                >
                    <span 
                        :class="checked ? 'translate-x-4' : 'translate-x-0'"
                        class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                    ></span>
                </button>
                <input 
                    type="checkbox"
                    x-ref="checkbox"
                    id="{{ $inputId }}"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    class="sr-only"
                    {{ $checked ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                >
                @if ($label)
                    <label for="{{ $inputId }}" class="text-xs sm:text-sm font-semibold text-ui-text-primary cursor-pointer select-none">
                        {{ $label }}
                        @if ($required)
                            <span class="text-ui-danger">*</span>
                        @endif
                    </label>
                @endif
            </div>

        @elseif ($type === 'file')
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-ui-border rounded-ui-lg p-6 bg-white hover:border-ui-primary transition-all cursor-pointer relative w-full"
                 x-data="{ hasFile: false, fileName: '', fileList: [] }">
                <input 
                    type="file"
                    id="{{ $inputId }}"
                    name="{{ $name }}"
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $required ? 'required' : '' }}
                    @change="
                        fileList = $event.target.files;
                        hasFile = fileList.length > 0;
                        fileName = hasFile ? fileList[0].name : '';
                    "
                    class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10 disabled:pointer-events-none"
                    {{ $attributes->except(['class', 'type']) }}
                >
                <div class="flex flex-col items-center justify-center gap-2 pointer-events-none text-center">
                    <i data-lucide="upload-cloud" class="w-8 h-8 text-ui-primary shrink-0 transition-colors"></i>
                    <div class="flex flex-col gap-1">
                        <template x-if="!hasFile">
                            <div>
                                <span class="text-xs sm:text-sm font-semibold text-ui-primary">Pilih berkas</span>
                                <span class="text-xs text-ui-text-secondary"> atau seret dan lepas</span>
                            </div>
                        </template>
                        <template x-if="hasFile">
                            <div class="text-xs sm:text-sm font-semibold text-ui-text-primary truncate max-w-[240px]" x-text="fileName"></div>
                        </template>
                        <span class="text-[10px] text-ui-muted">Format yang diperbolehkan: PDF, Excel, JPG, PNG (Maks 5MB)</span>
                    </div>
                </div>
            </div>

        @elseif ($type === 'password')
            <input 
                :type="show ? 'text' : 'password'"
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $attributes->class([$inputClasses]) }}
            >
            <!-- Toggle password visibility button -->
            <button 
                type="button" 
                @click="show = !show"
                class="absolute right-3 text-ui-text-secondary hover:text-ui-text-primary transition-colors focus:outline-none shrink-0 flex items-center justify-center"
                aria-label="Tampilkan sandi"
            >
                <!-- Toggle Icons based on show variable -->
                <i x-show="!show" data-lucide="eye" class="w-4 h-4"></i>
                <i x-show="show" data-lucide="eye-off" class="w-4 h-4" style="display: none;"></i>
            </button>

        @else
            <!-- Default Input (text, number, email, date, search, etc.) -->
            <input 
                type="{{ $type }}"
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $required ? 'required' : '' }}
                @if ($maxlength) maxlength="{{ $maxlength }}" @endif
                @if ($characterCount && $maxlength) @input="count = $el.value.length" @endif
                {{ $attributes->class([$inputClasses]) }}
            >
            <!-- Suffix Icon -->
            @if ($suffixIcon && $type !== 'password')
                <div class="absolute right-3 text-ui-text-secondary pointer-events-none shrink-0 flex items-center justify-center">
                    <i data-lucide="{{ $suffixIcon }}" class="w-4 h-4"></i>
                </div>
            @endif
        @endif
    </div>

    <!-- Help text, Character counter, and Error details footer -->
    <div class="flex items-start justify-between gap-4">
        @if ($error)
            <p class="text-[11px] font-semibold text-ui-danger flex items-center gap-1.5">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>{{ $error }}</span>
            </p>
        @elseif ($helpText)
            <p class="text-[11px] text-ui-text-secondary flex items-start gap-1">
                <i data-lucide="info" class="w-3.5 h-3.5 shrink-0 mt-0.5 text-ui-muted"></i>
                <span>{{ $helpText }}</span>
            </p>
        @else
            <div></div> <!-- placeholder -->
        @endif

        @if ($characterCount && $maxlength)
            <span class="text-[10px] text-ui-text-secondary font-medium shrink-0" aria-live="polite">
                <span x-text="count"></span>/<span x-text="max"></span>
            </span>
        @endif
    </div>
</div>
