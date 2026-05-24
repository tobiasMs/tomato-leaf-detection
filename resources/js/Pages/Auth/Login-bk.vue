<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

         <div class="min-h-screen flex bg-gray-100">
            <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500 items-center justify-center p-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 text-white max-w-md">
                        <h1 class="text-5xl font-extrabold leading-tight mb-6">
                            Welcome Back
                        </h1>
                        <div class="mt-10 flex gap-4">
                            <div class="w-3 h-3 rounded-full bg-white"></div>
                            <div class="w-3 h-3 rounded-full bg-white/60"></div>
                        <div class="w-3 h-3 rounded-full bg-white/30"></div>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center p-6">
                <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8" >
                    <div class="text-center mb-8">
                        <div class="mx-auto w-16 h-16 rounded-2xl bg-indigo-300 flex items-center justify-center" >
                            <img src="/assets/images/logo-white.png" alt="Logo">
                        </div>

                        <h2 class="mt-4 text-3xl font-bold text-gray-800">
                            Sign In
                        </h2>

                        <p class="text-gray-500 mt-2">
                            Please login to your account
                        </p>
                        <div
                            v-if="status"
                            class="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm" >
                            {{ status }}
                        </div>
                    </div>
                </div>
            </div>

         </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
