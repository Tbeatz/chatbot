<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import TypeWriter from '@/Components/TypeWriter.vue';
import { nextTick, ref, watch } from 'vue';

const chatBody = ref(null);
const trigger_effect = ref(true);

defineProps({
    history_date : {
        type: Object,
    },
})

const form = useForm({
    message: null,
    chatMessages: [],
})

function submit(){
    trigger_effect.value = true;
    form.post(route('send_msg'), {
        preserveScroll: true,
        onSuccess: (res) => {
            form.reset('message');
            handleMessage(res.props.answer, res.props.message, res.props.topic_id);
        },
    })
}

function handleMessage(reply, chat, topic_id) {
    form.chatMessages.push({ userMessage: chat, serverMessage: reply, topicID: topic_id });
}

// watch(form.chatMessages, async () => {
//     await nextTick();
//     chatBody.value.scrollTop = chatBody.value.scrollHeight;
// }, { deep: true });

function switch_chat(id){
    trigger_effect.value = false;
    router.get(route('dashboard'), {
        topic_id : id
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            form.chatMessages = [];
            (res.props.history_chat).forEach(val => {
                form.chatMessages.push({ userMessage: val.question, serverMessage: val.answer, topicID: val.history_topic_id });
            });
        }
    });
}

</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout
        :history_date="history_date"
        @change_chat="switch_chat"
        @clear-chat="form.chatMessages = []"
        :topic_id="form.chatMessages.length > 0 ? parseInt(form.chatMessages[0]['topicID']) : null"
        :chatMessages = "form.chatMessages"
    >
    <div class="bg-transparent overflow-y-scroll flex-grow max-w-4xl w-full mx-auto">
        <div ref="chatBody" class="bg-transparent p-7 overflow-y-auto" id="chat_body">
            <div class="flex flex-row items-center mb-4 gap-3 justify-start">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-8 h-8 dark:fill-blue-600 fill-blue-500 flex-shrink-0">
                    <path d="M320 0c17.7 0 32 14.3 32 32V96H472c39.8 0 72 32.2 72 72V440c0 39.8-32.2 72-72 72H168c-39.8 0-72-32.2-72-72V168c0-39.8 32.2-72 72-72H288V32c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H208zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H304zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H400zM264 256a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm152 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80zM48 224H64V416H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H576V224h16z"/>
                </svg>
                <div class="dark:bg-green-600 bg-green-500 text-white p-3 rounded-lg shadow-md shadow-gray-700">
                    <TypeWriter :text="'မင်္ဂလာပါ ' + $page.props.auth.user.name + ' ။ ငါ မင်းကို ဘယ်လိုကူညီရမလဲ...'"/>
                </div>
            </div>
            <div v-for="(chat, index) in form.chatMessages" :key="index">
                <div v-if="chat.userMessage" class="flex items-center justify-end mb-4 gap-3">
                    <div class="bg-blue-500 dark:bg-blue-600 p-3 rounded-lg shadow-md shadow-gray-700 text-white">
                        <p class="text-sm">{{ chat.userMessage }}</p>
                    </div>
                    <img :src="$page.props.auth.user.avatar ? 'file/' + $page.props.auth.user.avatar : '/style_images/user.png'" alt="User" class="h-8 w-8 rounded-full"/>
                </div>
                <div v-if="chat.serverMessage" class="flex flex-row items-center mb-4 gap-3 justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-8 h-8 dark:fill-blue-600 fill-blue-500 flex-shrink-0">
                        <path d="M320 0c17.7 0 32 14.3 32 32V96H472c39.8 0 72 32.2 72 72V440c0 39.8-32.2 72-72 72H168c-39.8 0-72-32.2-72-72V168c0-39.8 32.2-72 72-72H288V32c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H208zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H304zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H400zM264 256a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm152 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80zM48 224H64V416H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H576V224h16z"/>
                    </svg>
                    <div class="dark:bg-green-600 bg-green-500 text-white p-3 rounded-lg shadow-md shadow-gray-700">
                        <TypeWriter :text="chat.serverMessage" :trigger="trigger_effect" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form @submit.prevent="submit()" class="flex items-center justify-center gap-2 mb-4">
        <input type="text" v-model="form.message" placeholder="Type your message here..." class="w-1/2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white rounded-full px-4 py-2 focus:outline-none focus:ring-blue-600 dark:focus:ring-blue-500" />
        <button type="submit" :disabled="form.processing" class="bg-green-500 text-white px-3 py-3 rounded-full hover:bg-green-600 focus:outline-none focus-visible:ring focus-visible:ring-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus-visible:ring dark:focus-visible:ring-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>
        </button>
    </form>
    </AuthenticatedLayout>
</template>
