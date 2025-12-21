<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div v-if="loading" class="col-span-3 text-center py-8">
      <p class="text-gray-500">Loading courses...</p>
    </div>
    
    <div v-else-if="courses.length === 0" class="col-span-3">
      <p class="text-gray-500 text-center">No courses available at the moment.</p>
    </div>
    
    <div
      v-for="course in courses"
      :key="course.id"
      class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
    >
      <div class="h-48 bg-gray-200">
        <img
          v-if="course.featured_image"
          :src="course.featured_image"
          :alt="course.title"
          class="w-full h-full object-cover"
        />
      </div>
      <div class="p-6">
        <div class="flex items-center gap-2 mb-2">
          <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold uppercase">
            {{ course.level }}
          </span>
          <span
            v-if="course.is_featured"
            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold"
          >
            Featured
          </span>
        </div>
        <h2 class="text-xl font-bold mb-2">{{ course.title }}</h2>
        <p class="text-gray-600 text-sm mb-4">{{ truncate(course.description, 100) }}</p>
        <div class="flex items-center text-sm text-gray-500 mb-4">
          <span>👤 {{ course.instructor?.name || 'Instructor' }}</span>
          <span class="mx-2">•</span>
          <span>⏱️ {{ course.duration }} min</span>
        </div>
        <div class="flex items-center justify-between">
          <div>
            <span
              v-if="course.discount_price"
              class="text-lg line-through text-gray-400"
            >
              ${{ course.price }}
            </span>
            <span class="text-2xl font-bold text-blue-600" :class="{ 'ml-2': course.discount_price }">
              ${{ course.discount_price || course.price }}
            </span>
          </div>
          <a
            :href="`/courses/${course.slug}`"
            class="text-blue-600 hover:text-blue-800 font-semibold"
          >
            Learn More →
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoursesList',
  props: {
    initialCourses: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      courses: this.initialCourses,
      loading: this.initialCourses.length === 0,
    };
  },
  mounted() {
    if (this.courses.length === 0) {
      this.fetchCourses();
    }
  },
  methods: {
    async fetchCourses() {
      try {
        const response = await fetch('/api/v1/courses');
        const data = await response.json();
        this.courses = data.data || data;
      } catch (error) {
        console.error('Error fetching courses:', error);
      } finally {
        this.loading = false;
      }
    },
    truncate(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
  },
};
</script>
