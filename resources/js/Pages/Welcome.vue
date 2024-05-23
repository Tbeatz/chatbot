<script setup>
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 dark:bg-gray-900 text-black dark:text-white">
        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="w-full max-w-3xl px-6">
                <header class="flex justify-between items-center py-10">
                    <h1 class="text-3xl font-bold">Hello User!</h1>
                    <nav class="flex flex-row gap-2">
                        <ThemeSwitcher />
                        <template v-if="canLogin">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="rounded-md px-4 py-2 text-black dark:text-white bg-green-500 dark:bg-green-600 border border-transparent hover:border-gray-300 dark:hover:border-gray-700 transition duration-200 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus-visible:ring-[#FF2D20] dark:focus-visible:ring-[#FF2D20]"
                            >
                                Dashboard
                            </Link>

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="rounded-md px-4 py-2 text-white font-arial dark:text-white bg-green-500 dark:bg-green-600 border border-transparent hover:border-green-300 dark:hover:border-green-700 transition duration-200 hover:text-white dark:hover:text-white focus:outline-none"
                                >
                                    Log in
                                </Link>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="rounded-md px-4 py-2 text-white font-arial dark:text-white bg-green-500 dark:bg-green-600 border border-transparent hover:border-green-300 dark:hover:border-green-700 transition duration-200 hover:text-white dark:hover:text-white focus:outline-none"
                                >
                                    Register
                                </Link>
                            </template>
                        </template>
                    </nav>
                </header>

                <main class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <div class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                        Welcome! How can I assist you today?
                    </div>
                    <!-- Chatbot interface -->
                    <div class="flex flex-col gap-4">
                        <!-- Chat messages -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
                                <div class="bg-gray-200 dark:bg-gray-600 text-black dark:text-white rounded-lg p-3">
                                    Hi there! How can I help you?
                                </div>
                            </div>
                            <!-- Example chat message -->
                            <div class="flex items-center justify-end gap-2">
                                <div class="bg-gray-200 dark:bg-gray-600 text-black dark:text-white rounded-lg p-3">
                                    OK, Please Help me with ...
                                </div>
                                <div class="w-8 h-8 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
                            </div>
                        </div>
                        <!-- Chat input -->
                        <div class="flex items-center gap-2">
                            <input type="text" placeholder="Type your message here..." class="flex-1 bg-gray-100 dark:bg-gray-700 text-black dark:text-white rounded-full px-4 py-2 focus:outline-none focus:ring-blue-600 dark:focus:ring-blue-500" />
                            <button class="bg-green-500 text-white px-3 py-3 rounded-full hover:bg-green-600 focus:outline-none focus-visible:ring focus-visible:ring-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus-visible:ring dark:focus-visible:ring-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </main>

                <footer class="py-10 text-center text-sm text-gray-600 dark:text-gray-400">
                    © 2024 Thu Ta. All rights reserved.
                </footer>
            </div>
        </div>
    </div>
</template>
