<script setup>
import {usePage} from "@inertiajs/vue3";
const user = usePage().props.auth.user;
</script>
<template>
    <div>
        <div v-html="comment.body" v-if="!editAction"></div>
        <!-- Editor box-->
        <comment-form
            v-else
            :dummy-comment="comment"
            :reply-action.sync="replyAction"
            @hideCommentForm="handleHideCommentForm"
        />

        <!-- author-->
        <p class="mt-2 mb-2.5 text-right italic text-sm">by <span>@{{comment.user.name}}</span> - <timeago :datetime="new Date(comment.created_at)" /></p>

        <!-- Editor box/ Actions by comment/reply -->
        <div class="flex flex-wrap bg-slate-100 pt-2 pb-2" v-show="!(editAction || replyAction)">
            <button
                class="basis-1/4 text-blue-600 hover:underline"
                @click="handleEvent('edit', comment)"
                v-if="user.id === comment.user_id"
            >
                Edit</button>

            <button
                class="basis-1/4 text-blue-600 hover:underline"
                @click="handleEvent('reply', comment)"
                v-if="!comment.parent_id"
            >
                Reply</button>

            <button
                class="basis-1/4 text-red-600 hover:underline"
                @click="handleEvent('remove', comment)"
                v-if="user.id === comment.user_id"
            >
                Delete</button>
        </div>
        <!-- Editor box-->
        <comment-form
            v-if="replyAction"
            :dummy-comment="comment"
            :reply-action.sync="replyAction"
            @hideCommentForm="handleHideCommentForm"
        />
    </div>

    <!-- replies (with recursive loads) -->
    <div class="pl-4 mt-4" v-if="comment.threads.length">
        <div v-for="thread in comment.threads" class="border-l-2 pl-2">
            <!-- recursive load -->
            <comment
                class="pl-2"
                :comment="thread"
                :id="'thread_' + thread.id"
                :key="thread.id"
            />
        </div>
    </div>

</template>

<script>
import {clone} from "lodash";
import LoadingButton from "@/Shared/LoadingButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CommentForm from "@/Pages/Comments/CommentForm.vue";
export default {
    name: "Comment",
    components: {
        CommentForm,
        PrimaryButton,
        LoadingButton
    },
    inheritAttrs:false,
    props: {
        comment: Object|null,
    },

    data: () => ({
        currentComment: {},
        editAction: false,
        replyAction: false
    }),

    methods: {
        handleHideCommentForm() {
            this.editAction = this.replyAction = false
        },
        removeCommentDialogEvent() {
            this.$emit('removeCommentEvent', this.currentComment);
        },
        handleEvent(action, obj) {
            this.currentComment = clone(obj)
            switch (action) {
                case 'edit':
                    this.editAction = true
                    break;
                case 'reply':
                    this.replyAction = true
                    break;
                case 'remove':
                    this.removeCommentDialogEvent()
                    break;
            }
        },
    }
}
</script>

<style scoped>

</style>
