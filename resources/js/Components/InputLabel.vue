<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="j-label" v-if="value">{{ value }}</label>
</template>
