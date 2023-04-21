<template>
    <Head :title="form.title" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">

                <div class="w-3/5 pr-6">
                    <h1 class="mb-8 text-3xl font-bold">
                        <Link class="text-indigo-400 hover:text-indigo-600" href="/posts">Post</Link>
                        <span class="text-indigo-400 font-medium">/</span> Create
                    </h1>

                    <div class="max-w-2xl bg-white rounded-md shadow overflow-hidden mr-6">
                        <form @submit.prevent="store">
                            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                                <text-input
                                    v-model="form.title"
                                    :error="form.errors.title"
                                    placeholder="Add a title …"
                                    class="pb-8 pr-6 w-full  mb-6"
                                />

                                <text-area-input
                                    v-model="form.body"
                                    :error="form.errors.body"
                                    class="pb-8 pr-6 w-full h-44 max-h-40 mb-6"
                                    label="Body"
                                    placeholder="Add a content  …"
                                />
                            </div>
                            <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
                                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Create Post</loading-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>

<script>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";
import TextAreaInput from "@/Shared/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

export default {
    components: {
        InputLabel,
        TextAreaInput, LoadingButton, TextInput,
        AuthenticatedLayout,
        Head,
        Link
    },

    remember: 'form',

    data() {
        return {
            form: this.$inertia.form({
                title: null,
                body: null,
            })
        }
    },
    methods: {
        store() {
            this.form.post('/posts', {
                onSuccess: () => {
                    this.form.reset()
                    router.get('/posts')
                }
            })
        }
    }
}
</script>

<style >
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    font-style: italic;
    padding: 2px;
}
::-moz-placeholder { /* Firefox 19+ */
    font-style: italic;
    padding: 2px;
}
:-ms-input-placeholder { /* IE 10+ */
    font-style: italic;
    padding: 2px;
}
:-moz-placeholder { /* Firefox 18- */
    font-style: italic;
    padding: 2px;
}
</style>
