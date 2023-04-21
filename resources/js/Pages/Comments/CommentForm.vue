<template>
    <form @submit.prevent="">
        <text-area-input
            v-model="formComment.body"
            class="w-full h-44 max-h-40"
            label="Body"
        />

        <div class="flex flex-wrap">
            <button
                class="basis-1/4 text-gray-600 hover:underline"
                @click="cancelSendComment"
            >Cancel</button>
            <button
                class="basis-1/4 text-blue-600 hover:underline"
                @click="sendComment"
            >Send</button>
        </div>
    </form>
</template>

<script>
import TextAreaInput from "@/Shared/TextAreaInput.vue";

export default {
    inheritAttrs:false,
    components: {
        TextAreaInput
    },
    props: {
        post: Object|null,
        dummyComment: Object|null,
        replyAction: {
            type: Boolean,
            required: false,
            default: false
        },
        cancelEvent: {
            type: Boolean,
            required: false,
            default: true
        }
    },
    // remember: 'form',
    data() {
        return {
            commentForm: {},
            formComment: this.$inertia.form({
                body: this.replyAction? null : this.dummyComment?.body,
                commentable_type: this.dummyComment ? this.dummyComment?.commentable_type: 'App\\Models\\Post',
                commentable_id: this.dummyComment ? this.dummyComment?.commentable_id : this.post.id,
                parent_id: this.replyAction? this.dummyComment?.id : null,
            }),
        }
    },

    methods: {
        resetCommentForm() {
            this.commentForm = {}
        },
        cancelSendComment() {
            this.$emit('hideCommentForm', false)
            this.resetCommentForm()
        },
        updateComment(){
            this.formComment.put(`/comments/${this.dummyComment.id}`, {
                preserveScroll: true,
                onSuccess: () => this.cancelSendComment()
            })
        },
        saveComment(){
            this.formComment.post(`/comments/`, {
                preserveScroll: true,
                onSuccess: () => {
                    this.formComment.reset()
                    this.cancelSendComment()
                }
            })
        },
        sendComment() {
            if (this.dummyComment?.id && !this.replyAction)
                this.updateComment()
            else
                this.saveComment()
        },
    }
}
</script>
