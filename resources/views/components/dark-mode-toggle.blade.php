<div x-data="{ dark: localStorage.getItem('dark') === 'true' }" 
     x-init="dark = localStorage.getItem('dark') === 'true';
             if (dark) document.documentElement.classList.add('dark');
             $watch('dark', val => {
                 if (val) {
                     document.documentElement.classList.add('dark');
                     localStorage.setItem('dark', 'true');
                 } else {
                     document.documentElement.classList.remove('dark');
                     localStorage.setItem('dark', 'false');
                 }
             })"
     class="flex items-center">
    
    <button @click="dark = !dark" 
            class="relative w-14 h-7 rounded-full transition-all duration-500 focus:outline-none"
            :class="dark ? 'bg-primary-600' : 'bg-gray-300'">
        
        <!-- Track -->
        <span class="absolute inset-0 rounded-full overflow-hidden">
            <span class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 opacity-0 transition-opacity duration-500"
                  :class="dark ? 'opacity-0' : 'opacity-100'"></span>
            <span class="absolute inset-0 bg-gradient-to-r from-primary-600 to-purple-600 opacity-0 transition-opacity duration-500"
                  :class="dark ? 'opacity-100' : 'opacity-0'"></span>
        </span>
        
        <!-- Thumb -->
        <span class="absolute top-0.5 left-0.5 w-6 h-6 rounded-full bg-white shadow-md transform transition-all duration-500 flex items-center justify-center text-xs"
              :class="dark ? 'translate-x-7' : 'translate-x-0'">
            <span x-show="!dark" class="transition-all">☀️</span>
            <span x-show="dark" class="transition-all" x-cloak>🌙</span>
        </span>
        
        <!-- Stars (visible in dark mode) -->
        <span class="absolute inset-0 rounded-full overflow-hidden pointer-events-none">
            <span x-show="dark" x-transition.opacity class="absolute top-1 left-3 w-1 h-1 bg-white rounded-full animate-pulse"></span>
            <span x-show="dark" x-transition.opacity class="absolute top-3 left-5 w-0.5 h-0.5 bg-white rounded-full animate-pulse" style="animation-delay: 0.3s"></span>
            <span x-show="dark" x-transition.opacity class="absolute top-2 left-7 w-1 h-1 bg-white rounded-full animate-pulse" style="animation-delay: 0.6s"></span>
        </span>
    </button>
</div>