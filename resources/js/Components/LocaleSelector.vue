<template>
    <div class="flex items-center">
        <select 
            v-model="currentLocale" 
            @change="changeLocale" 
            class="rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option v-for="locale in availableLocales" :key="locale" :value="locale">
                {{ locale.toUpperCase() }}
            </option>
        </select>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { loadLanguageAsync, getCurrentLocale } from 'laravel-vue-i18n';

const page = usePage();
const currentLocale = ref(getCurrentLocale());
const availableLocales = computed(() => page.props.locales);

async function changeLocale() {
    try {
        // Carica le nuove traduzioni
        await loadLanguageAsync(currentLocale.value);
        
        // Aggiorna l'URL con il nuovo locale
        const currentPath = window.location.pathname;
        const newPath = currentPath.replace(/^\/[a-z]{2}/, '/' + currentLocale.value);
        
        router.visit(newPath, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Aggiorna il tag html lang
                document.documentElement.lang = currentLocale.value;
            }
        });
    } catch (error) {
        console.error('Errore durante il caricamento delle traduzioni:', error);
        // Ripristina il locale precedente in caso di errore
        currentLocale.value = getCurrentLocale();
    }
}
</script>