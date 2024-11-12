<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6">
                <h2 class="text-xl font-semibold">{{ $t('Posts') }}</h2>
                <Link 
                    :href="route('posts.create', { locale: $page.props.locale })"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    {{ $t('Create Post') }}
                </Link>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">{{ $t('Title') }}</th>
                                <th class="px-4 py-2 text-left">{{ $t('Content') }}</th>
                                <th class="px-4 py-2 text-left">{{ $t('Published at') }}</th>
                                <th class="px-4 py-2 text-left">{{ $t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-t">{{ post.id }}</td>
                                <td class="px-4 py-2 border-t">{{ post.title }}</td>
                                <td class="px-4 py-2 border-t">{{ truncate(post.post, 100) }}</td>
                                <td class="px-4 py-2 border-t">
                                    {{ post.publish_date || $t('Unpublished') }}
                                </td>
                                <td class="px-4 py-2 border-t">
                                    <div class="flex space-x-2">
                                        <Link 
                                            :href="route('posts.edit', { locale: $page.props.locale, post: post.id })"
                                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                                        >
                                            {{ $t('Edit') }}
                                        </Link>
                                        <button
                                            @click="deletePost(post)"
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                        >
                                            {{ $t('Delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

defineProps({
    posts: {
        type: Array,
        required: true
    }
});

const truncate = (text, length) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const deletePost = (post) => {
    if (confirm($t('Are you sure you want to delete this post?'))) {
        router.delete(route('posts.destroy', { 
            locale: usePage().props.locale, 
            post: post.id 
        }));
    }
};
</script>