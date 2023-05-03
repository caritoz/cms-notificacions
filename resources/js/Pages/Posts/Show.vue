<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
const props = defineProps({
    post: {
        type: Object
    }
});

const currentPost = props.post;
</script>

<template>
    <Head :title="currentPost.title" />
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <h1 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">
                <Link class="text-indigo-400 hover:text-indigo-600" href="/">Posts</Link>
                <span class="text-indigo-400 font-medium">/</span>
                <span class="text-ellipsis overflow-hidden">{{ currentPost.title }}</span>
            </h1>

            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{currentPost.body}}</p>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
        </div>
    </div>

</template>

<script>
export default {
    name: "Show"
}
</script>

<style scoped>

</style>
