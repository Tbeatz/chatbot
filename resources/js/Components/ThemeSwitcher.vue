<script setup>
    import { ref, onMounted } from 'vue';

    const dark_mode = ref(false);

    function toggleTheme() {
        dark_mode.value = !dark_mode.value;
        localStorage.setItem('dark_mode', dark_mode.value.toString());
        document.body.classList.toggle('dark', dark_mode.value);
    }

    onMounted(() => {
        const storedDarkMode = localStorage.getItem('dark_mode');
        dark_mode.value = (storedDarkMode == 'true' ? true : false);
        document.body.classList.toggle('dark', dark_mode.value);
    });

    const props = defineProps({
        extraStyle: String,
    })
</script>

<template>
    <div>
        <button type="button" @click='toggleTheme' :class="extraStyle + 'text-white bg-blue-500 dark:bg-blue-600 border border-transparent hover:border-blue-300 dark:hover:border-blue-700 hover:text-white dark:hover:text-white font-medium rounded-full text-sm px-3 py-3 inline-flex items-center transition duration-300 ease-in-out'">
            <svg v-if="dark_mode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
            <span class="sr-only">Light/Dark Mode</span>
        </button>
    </div>
</template>
