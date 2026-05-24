<script setup>
import { ref, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoginLayout from '@/Layouts/LoginLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import Swal from 'sweetalert2';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);

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

watch(
    () => form.errors.email,
    (newError) => {
        // Jika ada error baru masuk (login gagal)
        if (newError) {
            Swal.fire({
                title: 'Login Gagal!',
                text: 'Username or password incorrect.',
                icon: 'error',
                confirmButtonColor: '#404e67', // Sesuaikan dengan warna utama tombol template Anda
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                // Opsional: Bersihkan error di form setelah user klik 'Coba Lagi'
                form.clearErrors('email');
            });
        }
    }
);
</script>

<template>
    <LoginLayout>
        <form @submit.prevent="submit">
             <div class="d-flex justify-content-center align-items-center">
                <img src="/layouts/assets/images/logo.png" alt="logo.png">
            </div>
            <div class="auth-box card">
                <div class="card-block">
                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <h3 class="text-center">Sign In</h3>
                        </div>
                    </div>
                    <div class="form-group form-primary">
                        <InputLabel for="email" value="Email"/>
                        <TextInput v-model="form.email" type="email" name="email" placeholder="Your Email Address" required />
                        <span class="form-bar"></span>
                        <!-- <span class="messages"></span> -->
                    </div>
                    <div class="form-group form-primary">
                        <InputLabel for="password" value="Password" />
                        <div class="input-group">
                            <TextInput v-model="form.password" :type="showPassword ? 'text' : 'password'" name="password" placeholder="Password" required class="form-control" />
                            <span @click="showPassword = !showPassword" class="input-group-addon" id="basic-addon5" style="cursor: pointer;">
                                <i class="text-white" :class="showPassword ? 'icofont icofont-eye-blocked' : 'icofont icofont-eye'"></i>
                            </span>
                        </div>
                        <span class="form-bar"></span>
                    </div>
                    <div class="row m-t-25 text-left">
                        <div class="col-12">
                            <div class="input-group">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">Remember me</span>
                            </div>
                            <!-- <div class="input-group">
                                <label>
                                    <input type="checkbox" value="">
                                    <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                    <span class="text-inverse">Remember me</span>
                                </label>
                            </div> -->
                            <div class="forgot-phone text-right f-right">
                                <a href="auth-reset-password.htm" class="text-right f-w-600"> Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-30">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Sign in</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-10">
                            <p class="text-inverse text-left m-b-0">Don't have an account?</p>
                            <p class="text-inverse text-left"><a href="index-1.htm"><b class="f-w-600">Register here</b></a></p>
                        </div>
                        <div class="col-md-2">
                            <img src="/layouts/assets/images/auth/Logo-small-bottom.png" alt="small-logo.png">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </LoginLayout>
</template>
