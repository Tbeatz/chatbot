<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, useForm } from '@inertiajs/vue3';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import SlideTransition from '@/Transitions/SlideTransition.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import ModalTransition from '@/Transitions/ModalTransition.vue';

const drawer = ref(false);
const modal_open = ref(false);
const edit_topic_trigger = ref(false);
const emit = defineEmits('clearChat');

const props = defineProps({
    history_date: {
        type: Object,
    },
    topic_id: {
        type: Number,
    },
    chatMessages: {
        type: Array,
    }
})

const form = useForm({
    topic_id : null,
    topic_name: null,
})

function modal_trigger(id){
    form.topic_id = id;
    modal_open.value = true;
}

function edit_topic(topic){
    form.topic_name = topic.topics;
    form.topic_id = topic.id;
    edit_topic_trigger.value = true;
}

function update_topic(){
    edit_topic_trigger.value = false;
    form.patch(route('update_topic'), {
        preserveScroll: true,
        onSuccess: (res) => {
            form.reset();
        }
    })
}

function delete_topic(){
    modal_open.value = false;
    if (props.chatMessages[0]['topicID'] == form.topic_id) {
        emit('clearChat');
    }
    form.delete(route('del_topic'), {
        preserveScroll: true,
        onSuccess: (res) => {
            form.reset('topic_id');
        }
    })
}

</script>

<template>
    <div>
        <div class="h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800">
                <div class="max-w-full flex justify-between items-center py-2 shadow-md shadow-green-300 dark:shadow-green-500">
                    <Link :href="route('dashboard')">
                        <ApplicationLogo class="w-10 h-10 dark:fill-green-600 fill-green-500 text-gray-500 hover:text-green-600 animate-disco-light ml-10" />
                    </Link>
                    <div class="flex flex-row items-center justify-center gap-3 mr-10">
                        <button @click="drawer = !drawer" v-if="route().current('dashboard')" class="text-white bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 border border-transparent hover:border-green-300 dark:hover:border-green-700 hover:text-white dark:hover:text-white font-medium rounded-full text-sm px-3 py-3 inline-flex items-center transition duration-300 ease-in-out" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </button>
                        <ThemeSwitcher />
                        <div class="relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-sans rounded-md text-black font-semibold dark:text-white bg-white dark:bg-gray-800 hover:text-black/70 dark:hover:text-white/80 focus:outline-none"
                                        >
                                            {{ $page.props.auth.user.name }}
                                            <div class="ms-2">
                                                <img class="w-10 h-10 rounded-full ring-2 ring-blue-600" :src="$page.props.auth.user.avatar ? 'file/' + $page.props.auth.user.avatar : '/style_images/user.png'" alt="">
                                            </div>
                                        </button>
                                    </span>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('profile.edit')" :active="route().current('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('profile.password')" :active="route().current('profile.password')"> Change Password </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
                <!-- drawer component -->
                <SlideTransition>
                    <div id="history_nav" class="shadow-md shadow-green-300 dark:shadow-green-500 fixed w-64 h-screen p-4 overflow-y-auto bg-gray-300 dark:bg-gray-700" v-if="drawer">
                        <h5 class="text-sm font-semibold text-gray-900 uppercase dark:text-gray-200">Chat History</h5>
                        <button @click="drawer = !drawer" type="button" class="text-green-500 bg-transparent hover:bg-gray-200 hover:text-green-600 rounded-lg text-sm p-1 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-green-400" >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Close menu</span>
                        </button>
                        <div class="py-3 overflow-y-auto">
                            <ul class="space-y-2 font-medium text-sm">
                                <li class="px-3 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 mb-1">
                                    <button @click="$emit('clearChat')" class="flex flex-row gap-2 items-center justify-between w-full text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400">
                                        <span class="text-green-600 rounded-lg dark:text-green-400">New Chat</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </li>
                                <template v-for="(date, index) in history_date" :key="index">
                                    <p class="text-sm font-semibold text-gray-500 dark:text-blue-400 mb-2 mt-2">
                                        {{
                                            (new Date(date.date)).getDate() + ' ' +
                                            (new Date(date.date)).toLocaleDateString("en-US", { month: 'long' }) + ', ' +
                                            (new Date(date.date)).getFullYear()
                                        }}
                                    </p>
                                    <li v-for="(topic, index) in date.topics" :key="index"
                                        :class="{
                                            'bg-gray-100 dark:bg-gray-800': topic.id == topic_id,
                                            'px-3 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800': true
                                        }">
                                        <button @click="$emit('change_chat', topic.id)" class="text-gray-900 rounded-lg dark:text-white flex flex-row items-center justify-between gap-2 w-full">
                                            <input @click.stop v-if="edit_topic_trigger && form.topic_id == topic.id" type="text" v-model="form.topic_name" class="text-sm dark:bg-gray-600 border-none w-full bg-gray-200 rounded-lg">
                                            <span v-else class="truncate">{{ topic.topics }}</span>

                                            <div class="flex flex-row gap-2 items-center">
                                                <button type="button" v-if="edit_topic_trigger && form.topic_id == topic.id" @click.stop="update_topic()" class="text-green-600 dark:text-green-500 hover:text-green-700 dark:hover:text-green-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <template v-else>
                                                    <button type="button" @click.stop="edit_topic(topic)" class="text-yellow-600 dark:text-yellow-500 hover:text-yellow-700 dark:hover:text-yellow-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </button>
                                                    <button type="button" @click.stop="modal_trigger(topic.id)" class="text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </SlideTransition>
            </header>

            <!-- Page Content -->
            <slot />
            <ModalTransition>
                <DeleteModal v-if="modal_open" @modal_close="modal_open = !modal_open" @delete="delete_topic()" />
            </ModalTransition>
        </div>
    </div>
</template>
