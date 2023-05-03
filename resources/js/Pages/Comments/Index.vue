<template>
    <h6 class="mb-8 text-3xl font-bold">Comments</h6>
    <div class="max-w-2xl bg-white rounded-md shadow overflow-hidden">

        <div class="overflow-y-auto h-80" id="comments">
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
            <p v-if="isEmpty" class="p-4">
                <i data-cy="empty-comments-section">No comments yet.</i>
            </p>
        </div>

        <!-- Editor box-->
        <div class="mx-6 mb-4">
            <hr class="p-4" />
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

    computed: {
      isEmpty() {
          return !this.post.comments.length;
      }
    },

    methods: {
        scrollToBottom() {
            const container = document.querySelector('#comments');
            container.scrollTop = container.scrollHeight;
        },
        handleRemoveComment(item) {
            if( confirm('Are you sure you want to delete this comment?') ){
                this.selectedComment = clone(item); // assign comment
                this.submitRemoveComment();
            }
        },
        submitRemoveComment() {
            this.$inertia.delete(`/comments/${this.selectedComment.id}`)
        },
    },

    mounted() {
        this.scrollToBottom()
    },
}
</script>
