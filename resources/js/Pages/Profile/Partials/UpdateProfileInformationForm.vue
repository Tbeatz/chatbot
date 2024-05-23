<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const preview_url = ref('');

function upload(e){
    form.clearErrors('avatar');
    form.avatar = e.target.files[0];
    preview_url.value = URL.createObjectURL(form.avatar);
}

const form = useForm({
    _method: 'patch',
    avatar: user.avatar,
    name: user.name,
    email: user.email,
});

function form_submit(){
    form.post(route('profile.update'), {
        preserveScroll: true,
    });
    URL.revokeObjectURL(preview_url.value);
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

            <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <img class="w-20 h-20 rounded-full shadow-md dark:shadow-blue-600 shadow-blue-500" :src="preview_url ? preview_url : (form.avatar ? 'file/' + form.avatar : 'style_images/user.png')" alt="">
            <div class="w-full">
                <InputLabel for="avatar" value="Avatar" />
                <input accept=".jpg, .png" @input="upload" class="block w-full mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-200 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400" type="file">
                <InputError :message="form.errors.avatar" />
            </div>
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" @click="form_submit" class="text-center items-center py-1.5 px-3 text-sm bg-green-500 dark:bg-green-600 border border-transparent rounded-md font-semibold text-white dark:text-white hover:bg-green-600 dark:hover:bg-green-700 focus:bg-green-700 dark:focus:bg-green-700 active:bg-green-900 dark:active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-green-800 transition ease-in-out duration-150 font-arial" :disabled="form.processing">
                    Save
                </button>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
