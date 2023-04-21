<template>
    <Head :title="form.title" />

    <AuthenticatedLayout>

        <div class="py-12">
            <trashed-message v-if="post.deleted_at" class="mb-6" @restore="restore"> This post has been deleted. </trashed-message>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">

                    <div class="w-3/5 pr-6">
                        <h1 class="mb-8 text-3xl font-bold">
                            <Link class="text-indigo-400 hover:text-indigo-600" href="/posts">Posts</Link>
                            <span class="text-indigo-400 font-medium">/</span>
                            <span class="text-ellipsis overflow-hidden">{{ form.title }}</span>
                        </h1>

                          <div class="max-w-2xl bg-white rounded-md shadow overflow-hidden mr-6">
                            <form @submit.prevent="update">
                                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                                    <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full  mb-6" label="Title" />

                                    <text-area-input
                                        v-model="form.body"
                                        :error="form.errors.body"
                                         class="pb-8 pr-6 w-full h-44 max-h-40 mb-6"
                                         label="Body"
                                        />
                                </div>
                                <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
                                    <button v-if="!post.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Post</button>
                                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Post</loading-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- List of comments/replies -->
                    <div class="w-2/5">
                        <comment-index
                            :post="post"
                        />
                    </div>

                </div>

        </div>

    </AuthenticatedLayout>

</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Shared/TextAreaInput.vue';
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import CommentIndex from '@/Pages/Comments/Index.vue'

export default {
    components: {
        AuthenticatedLayout,
        Head,
        // Icon,
        Link,
        LoadingButton,
        TextInput,
        TextAreaInput,
        TrashedMessage,
        CommentIndex,
    },
    // layout: Layout,
    props: {
        post: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                title: this.post.title,
                body: this.post.body,
            }),
        }
    },
    methods: {
        update() {
            this.form.put(`/posts/${this.post.id}`)
        },
        destroy() {
            if (confirm('Are you sure you want to delete this post?')) {
                this.$inertia.delete(`/posts/${this.post.id}`)
            }
        },
        restore() {
            if (confirm('Are you sure you want to restore this post?')) {
                this.$inertia.put(`/posts/${this.post.id}/restore`)
            }
        },
    },
}
</script>
