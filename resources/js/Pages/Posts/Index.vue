<template>
    <Head title="Posts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
                        <label class="block text-gray-700">Trashed:</label>
                        <select v-model="form.trashed" class="form-select mt-1 w-full">
                            <option :value="null" />
                            <option value="with">With Trashed</option>
                            <option value="only">Only Trashed</option>
                        </select>
                    </search-filter>
                    <Link class="btn-indigo" href="/posts/create">
                        <span>Create</span>
                        <span class="hidden md:inline">&nbsp;Post</span>
                    </Link>
                </div>

                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="text-left font-bold">
                                <th class="pb-4 pt-6 px-6">Title</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/posts/${post.id}/edit`">
                                    {{ post.title }}
                                    <icon v-if="post.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                </Link>
                            </td>
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4" :href="`/posts/${post.id}/edit`" tabindex="-1">
                                    {{ post.updated_at }}
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="posts.data.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No posts found.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <pagination class="mt-6" :links="posts.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import SearchFilter from '@/Shared/SearchFilter.vue'
import Pagination from '@/Shared/Pagination.vue'

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        SearchFilter,
        Pagination
    },
    props: {
        filters: Object,
        posts: Object
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/posts', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    },
}
</script>
