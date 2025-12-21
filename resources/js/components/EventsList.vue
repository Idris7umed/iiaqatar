<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div v-if="loading" class="col-span-3 text-center py-8">
      <p class="text-gray-500">Loading events...</p>
    </div>
    
    <div v-else-if="events.length === 0" class="col-span-3">
      <p class="text-gray-500 text-center">No upcoming events at the moment.</p>
    </div>
    
    <div
      v-for="event in events"
      :key="event.id"
      class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
    >
      <div class="h-48 bg-gray-200">
        <img
          v-if="event.featured_image"
          :src="event.featured_image"
          :alt="event.title"
          class="w-full h-full object-cover"
        />
      </div>
      <div class="p-6">
        <div class="flex items-center text-sm text-gray-500 mb-2">
          <span>{{ formatDate(event.start_date) }}</span>
          <span class="mx-2">•</span>
          <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs uppercase">
            {{ event.event_type }}
          </span>
          <span v-if="event.location" class="mx-2">•</span>
          <span v-if="event.location" class="text-xs">{{ event.location }}</span>
        </div>
        <h2 class="text-xl font-bold mb-2">{{ event.title }}</h2>
        <p class="text-gray-600 text-sm mb-4">{{ truncate(event.description, 100) }}</p>
        <div class="flex items-center justify-between">
          <span class="text-lg font-bold text-blue-600">
            {{ event.price > 0 ? `$${event.price}` : 'Free' }}
          </span>
          <a
            :href="`/events/${event.slug}`"
            class="text-blue-600 hover:text-blue-800 font-semibold"
          >
            View Details →
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EventsList',
  props: {
    initialEvents: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      events: this.initialEvents,
      loading: this.initialEvents.length === 0,
    };
  },
  mounted() {
    if (this.events.length === 0) {
      this.fetchEvents();
    }
  },
  methods: {
    async fetchEvents() {
      try {
        const response = await fetch('/api/v1/events');
        const data = await response.json();
        this.events = data.data || data;
      } catch (error) {
        console.error('Error fetching events:', error);
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
      });
    },
    truncate(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
  },
};
</script>
