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
                              <!-- If is not the Owner of the Post, just show it -->
                              <div class="flex flex-wrap mx-4 p-8" v-if="!isOwner">
                                  <p class="pb-8 pr-6 w-full  mb-6">{{ post.title }}</p>
                                  <div class="pb-8 pr-6 w-full mb-6">{{ post.body }}</div>
                              </div>

                              <!-- If is the Owner of the Post, can edit it -->
                              <form @submit.prevent="update" v-else>
                                <div class="flex flex-wrap mx-4 p-8">
                                    <text-input v-model="form.title" :error="form.errors.title" class="p-4 w-full mb-6" label="Title" />
                                    <text-area-input
                                        v-model="form.body"
                                        :error="form.errors.body"
                                         class="p-4 w-full h-44 max-h-40"
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
import {clone} from "lodash";
import {usePage} from "@inertiajs/vue3";
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

    inheritAttrs:false,

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

    computed: {
        isOwner(){
            return this.post.user.id === usePage().props.auth.user.id
        }
    },

    mounted() {
        this.createCommentsChannel('Post', this.post);
    },

    methods: {
        update() {
            this.form.put(`/posts/${this.post.id}`, {
                preserveScroll: true,
                onSuccess: () => console.log('Post updated'),
                onError: (e) => console.error(e)
            })
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

        createCommentsChannel(entityClass, entity) {

            Echo.private(`comments.${entityClass}.${entity?.id}`).listen(
                `CommentUpdatedEvent`,
                (data) => {
                    // comparison current user with others (because axios bug... for some reason doesnt send the properly headers by sockets -or pusher-php-server laravel package-)
                    if (data?.user.id !== usePage().props.auth.user.id && data?.comment) {
                        switch (data.action) {
                            case 'add':
                                this.addFetchingComment(data.comment)
                                console.log('Comment received!')
                                break
                            case 'update':
                                this.updateFetchingComment(data.comment)
                                console.log('Comment updated!')
                                break
                            case 'remove':
                                this.removeFetchingComment(data.comment)
                                console.log('Comment removed!')
                                break
                        }
                    }
                }
            )

            console.log('Created comments channel!')
        },

        // NEEDS TO MOVE TO PINIA
        addFetchingComment(payload) {
            if (payload.parent_id) // needs to improve this recursive call for VUEX
                this.post.comments = window._recursiveAddItem( clone(this.post.comments), payload)
            else
                this.post.comments.push(payload);
        },
        updateFetchingComment(payload){
            if (payload.parent_id) // needs to improve this recursive call for VUEX
                this.post.comments = window._recursiveUpdateItem( clone(this.post.comments), payload)
            else {
                Vue.set(
                    this.post.comments,
                    this.post.comments.findIndex(comment => comment.id === payload.id),
                    payload
                );
            }
        },
        removeFetchingComment(payload){
            if (payload.parent_id) // needs to improve this recursive call for VUEX
                this.post.comments = window._recursiveRemoveItem( clone(this.post.comments), payload)
            else
                this.post.comments.splice(this.post.comments.findIndex(comment => comment.id === payload.id), 1);
        },

    },
}
</script>
