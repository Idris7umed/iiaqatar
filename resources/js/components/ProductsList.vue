<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div v-if="loading" class="col-span-3 text-center py-8">
      <p class="text-gray-500">Loading products...</p>
    </div>
    
    <div v-else-if="products.length === 0" class="col-span-3">
      <p class="text-gray-500 text-center">No products available at the moment.</p>
    </div>
    
    <div
      v-for="product in products"
      :key="product.id"
      class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
    >
      <div class="h-48 bg-gray-200 relative">
        <img
          v-if="product.featured_image"
          :src="product.featured_image"
          :alt="product.title"
          class="w-full h-full object-cover"
        />
        <span
          class="absolute top-2 right-2 px-3 py-1 rounded text-xs font-semibold"
          :class="product.product_type === 'virtual' 
            ? 'bg-purple-100 text-purple-800' 
            : 'bg-green-100 text-green-800'"
        >
          {{ product.product_type }}
        </span>
      </div>
      <div class="p-6">
        <div class="flex items-center gap-2 mb-2">
          <span
            v-if="product.category"
            class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs"
          >
            {{ product.category.name }}
          </span>
          <span
            v-if="product.is_featured"
            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold"
          >
            Featured
          </span>
          <span
            v-if="product.stock_quantity !== null && product.stock_quantity === 0"
            class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold"
          >
            Out of Stock
          </span>
        </div>
        <h2 class="text-xl font-bold mb-2">{{ product.title }}</h2>
        <p class="text-gray-600 text-sm mb-4">{{ truncate(product.description, 100) }}</p>
        <div class="flex items-center justify-between">
          <div>
            <span
              v-if="product.discount_price"
              class="text-lg line-through text-gray-400"
            >
              ${{ product.price }}
            </span>
            <span class="text-2xl font-bold text-blue-600" :class="{ 'ml-2': product.discount_price }">
              ${{ product.discount_price || product.price }}
            </span>
          </div>
          <a
            :href="`/products/${product.slug}`"
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
  name: 'ProductsList',
  props: {
    initialProducts: {
      type: Array,
      default: () => [],
    },
    filterType: {
      type: String,
      default: 'all', // 'all', 'virtual', or 'physical'
    },
  },
  data() {
    return {
      products: this.initialProducts,
      loading: this.initialProducts.length === 0,
    };
  },
  mounted() {
    if (this.products.length === 0) {
      this.fetchProducts();
    }
  },
  methods: {
    async fetchProducts() {
      try {
        let url = '/api/v1/products';
        if (this.filterType !== 'all') {
          url += `?type=${this.filterType}`;
        }
        const response = await fetch(url);
        const data = await response.json();
        this.products = data.data || data;
      } catch (error) {
        console.error('Error fetching products:', error);
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
