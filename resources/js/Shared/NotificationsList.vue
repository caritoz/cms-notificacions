<script setup>
import { Link } from '@inertiajs/vue3';
import Dropdown from "@/Components/Dropdown.vue";
import {BellAlertIcon, BellIcon} from "@heroicons/vue/24/outline";

import {usePage} from "@inertiajs/vue3";
const user = usePage().props.auth.user;

import {useNotificationStore} from "@/stores/NotificationStore";
const notificationStore = useNotificationStore()

</script>
<template>
    <Dropdown align="right" width-content="w-80">
        <template #trigger>
                <span class="inline-flex rounded-md">
                    <button type="button" class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-800 focus:outline-none focus:ring-1 focus:ring-gray focus:ring-offset-2 focus:ring-offset-gray-500">
                        <span class="sr-only">View notifications</span>
                        <BellIcon class="h-6 w-6" aria-hidden="true" v-if="notificationStore.isEmpty" />
                        <BellAlertIcon class="h-6 w-6" aria-hidden="true" v-else />
                    </button>
                </span>
        </template>
        <template #content>
            <div v-if="!notificationStore.isEmpty" class="max-w-xs bg-white overflow-y-auto h-40">
                <ul >
                    <li class="text-gray-600 text-xs py-2 px-4 hover:bg-gray-100 focus-within:bg-gray-100 cursor-pointer border-b border-b-gray-100"
                        v-for="notification in notificationStore.notifications" :key="notification.id"
                        :class="{'bg-gray-200': notification.read_at}"
                        @click.stop="notificationStore.markAsRead(notification)"
                    >
                        <p>{{ notification.data.message.content}} // <Link :href="notification.data.message.slug" class="hover:underline text-blue-500">read more</Link></p>
                        <p class="mb-0 italic"><timeago :datetime="new Date(notification.created_at)" /></p>
                    </li>
                </ul>
            </div>
            <div class="max-w-xs bg-white" v-else>
                <p class="p-4 italic">No notifications yet.</p>
            </div>
        </template>
    </Dropdown>
</template>
