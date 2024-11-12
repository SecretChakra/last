
<script setup>
import { onMounted, onUnmounted } from 'vue';
import NavLink from '@/Components/NavLink.vue';
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close']);

// Close sidebar on escape key
const handleEscape = (e) => {
  if (e.key === 'Escape') {
    emit('close');
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscape);
});
</script>

<template>
  <!-- Overlay -->
  <div
    v-if="isOpen"
    @click="emit('close')"
    class="fixed inset-0 z-50 bg-black bg-opacity-50 transition-opacity"
  />

  <!-- Sidebar -->
  <div
    :class="[
      'fixed z-50 top-0 left-0 z-100 h-full w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-150 ease-in-out border-r border-bor-default rounded-r-xl',
      isOpen ? 'translate-x-0' : '-translate-x-full'
    ]"
  >
    <!-- Sidebar Header -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Menu</h2>
        <button
          @click="emit('close')"
          class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors transform transition-transform duration-150 ease-in-out"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Sidebar Content -->
    <div class="p-4">
      <nav class="space-y-2">
        <!-- Add your sidebar navigation items here -->
        <a href="#" class="block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
          Home
        </a>
        <a href="#" class="block px-4 py-2 text-base rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
          Issues
        </a>
        <a href="#" class="block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
          Settings
        </a>
     
        <NavLink :href="route('systems.index')" :active="route().current('systems.index')">
    Sistemi di refrigerazione
</NavLink>

          <NavLink :href="route('applications.index')" :active="route().current('applications.index')">
              Applicazioni
          </NavLink>



                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>




      </nav>
    </div>
  </div>
</template>