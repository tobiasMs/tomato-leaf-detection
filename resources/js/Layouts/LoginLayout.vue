<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Head>
        <link rel="icon" href="/layouts/assets/images/favicon.ico" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/layouts/bower_components/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/layouts/assets/icon/themify-icons/themify-icons.css">
        <link rel="stylesheet" type="text/css" href="/layouts/assets/icon/icofont/css/icofont.css">
        <link rel="stylesheet" type="text/css" href="/layouts/assets/css/style.css">
    </Head>
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <slot />
                </div>
            </div>
        </div>
    </section>

</template>
