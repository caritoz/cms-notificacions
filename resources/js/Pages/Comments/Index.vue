<script setup>
import {usePage} from "@inertiajs/vue3";
const user = usePage().props.auth.user;
</script>
<template>
    <h6 class="mb-8 text-3xl font-bold">Comments</h6>
    <div class="max-w-2xl bg-white rounded-md shadow overflow-hidden">
        <div
            v-for="(comment, index) in post.comments"
            class="mx-6 my-4"
        >
            <comment
                :comment="comment"
                :key="index"
                :id="'comment_' + comment.id"
                @removeCommentEvent="handleRemoveComment"
            />
        </div>
        <p v-if="!post.comments.length" class="p-4">
            <i data-cy="empty-comments-section">No comments yet.</i>
        </p>

        <!-- Editor box-->
        <div class="mx-6 mb-4">
            <comment-form
                :post="post"
                />
        </div>

    </div>
</template>

<script>
import {clone} from "lodash";
import Comment from "@/Pages/Comments/Comment.vue";
import CommentForm from "@/Pages/Comments/CommentForm.vue";

export default {
    name: "Index",
    components: {
        Comment,
        CommentForm
    },

    props: {
        post: Object|null,
    },

    data: () => ({
        selectedComment: {}
    }),

    methods: {
        handleRemoveComment(item) {
            if( confirm('Are you sure you want to delete this comment?') ){
                this.selectedComment = clone(item); // assign comment
                this.submitRemoveComment();
            }
        },
        submitRemoveComment() {
            this.$inertia.delete(`/comments/${this.selectedComment.id}`)
        },
    }
}
</script>

<style scoped>

</style>
