<template>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="submit">
            <!-- Errors Summary if needed -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-4 bg-red-50 p-4 rounded-lg">
                <div class="text-sm text-red-600" v-for="(error, index) in form.errors" :key="index">
                    {{ error }}
                </div>
            </div>

            <!-- Author Selection -->
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">
                    {{ $t('Author') }}
                </label>
                <select
                    v-model="form.user_id"
                    id="user_id"
                    :class="[
                        'bg-gray-100 border-2 w-full p-4 rounded-lg',
                        { 'border-red-500': form.errors.user_id }
                    ]"
                >
                    <option value="">{{ $t('Select author') }}</option>
                    <option 
                        v-for="(name, id) in authors" 
                        :key="id" 
                        :value="id"
                    >
                        {{ name }}
                    </option>
                </select>
                <div v-if="form.errors.user_id" class="text-red-500 mt-2 text-sm">
                    {{ form.errors.user_id }}
                </div>
            </div>

            <!-- Translatable Fields -->
            <div v-for="locale in locales" :key="locale" class="mb-4">
                <fieldset class="border-2 w-full p-4 rounded-lg">
                    <legend class="px-2">{{ $t('Text for') }} {{ locale.toUpperCase() }}</legend>
                    
                    <!-- Title -->
                    <div class="mb-4">
                        <label :for="`${locale}.title`" class="block text-sm font-medium text-gray-700">
                            {{ $t('Title') }}
                        </label>
                        <input
                            type="text"
                            v-model="form[locale].title"
                            :id="`${locale}.title`"
                            :placeholder="$t('Title')"
                            :class="[
                                'bg-gray-100 border-2 w-full p-4 rounded-lg',
                                { 'border-red-500': form.errors[`${locale}.title`] }
                            ]"
                        >
                        <div v-if="form.errors[`${locale}.title`]" class="text-red-500 mt-2 text-sm">
                            {{ form.errors[`${locale}.title`] }}
                        </div>
                    </div>

                    <!-- Post Body -->
                    <div class="mb-4">
                        <label :for="`${locale}.post`" class="block text-sm font-medium text-gray-700">
                            {{ $t('Body') }}
                        </label>
                        <textarea
                            v-model="form[locale].post"
                            :id="`${locale}.post`"
                            rows="4"
                            :placeholder="$t('Post')"
                            :class="[
                                'bg-gray-100 border-2 w-full p-4 rounded-lg',
                                { 'border-red-500': form.errors[`${locale}.post`] }
                            ]"
                        ></textarea>
                        <div v-if="form.errors[`${locale}.post`]" class="text-red-500 mt-2 text-sm">
                            {{ form.errors[`${locale}.post`] }}
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- Publish Date -->
            <div class="mb-4">
                <label for="publish_date" class="block text-sm font-medium text-gray-700">
                    {{ $t('Published at') }}
                </label>
                <input
                    type="datetime-local"
                    v-model="form.publish_date"
                    id="publish_date"
                    :placeholder="$t('Published at')"
                    :class="[
                        'bg-gray-100 border-2 w-full p-4 rounded-lg',
                        { 'border-red-500': form.errors.publish_date }
                    ]"
                >
                <div v-if="form.errors.publish_date" class="text-red-500 mt-2 text-sm">
                    {{ form.errors.publish_date }}
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex space-x-4">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full hover:bg-blue-600 
                           disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ form.processing ? $t('Updating...') : $t('Update') }}
                </button>
                
                <Link
                    :href="route('posts.index')"
                    class="bg-gray-500 text-white px-4 py-3 rounded font-medium w-full text-center hover:bg-gray-600"
                >
                    {{ $t('Cancel') }}
                </Link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    authors: {
        type: Object,
        required: true
    },
    locales: {
        type: Array,
        required: true
    }
})

// Inizializza il form con i dati esistenti
const initializeForm = () => {
    const formData = {
        user_id: props.post.user_id,
        publish_date: props.post.publish_date,
    }
    
    // Aggiungi i campi tradotti per ogni lingua
    props.locales.forEach(locale => {
        formData[locale] = {
            title: props.post[`title:${locale}`],
            post: props.post[`post:${locale}`]
        }
    })
    
    return formData
}

const form = useForm(initializeForm())

const submit = () => {
    form.put(route('posts.update', props.post.id), {
        onSuccess: () => {
            // Redirect o feedback
        },
        preserveScroll: true
    })
}
</script>