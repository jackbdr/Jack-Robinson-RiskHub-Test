<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Order Manager</h1>

    <form
      @submit.prevent="addProduct"
      class="mb-8 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
    >
      <div class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="productType"
        >
          Product Type
        </label>
        <select
          v-model="productType"
          id="productType"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          <option value="digital">Digital</option>
          <option value="physical">Physical</option>
          <option value="subscription">Subscription</option>
        </select>
      </div>

      <div class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="productName"
        >
          Product Name
        </label>
        <input
          v-model="productName"
          id="productName"
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
      </div>

      <div class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="productPrice"
        >
          Price
        </label>
        <input
          v-model.number="productPrice"
          id="productPrice"
          type="number"
          step="0.01"
          min="0.01"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
      </div>

      <div class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="productQuantity"
        >
          Quantity
        </label>
        <input
          v-model.number="productQuantity"
          id="productQuantity"
          type="number"
          min="1"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
      </div>

      <div v-if="productType === 'digital'" class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="downloadLink"
        >
          Download Link
        </label>
        <input
          v-model="downloadLink"
          id="downloadLink"
          type="url"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>

      <div v-if="productType === 'physical'" class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="weight">
          Weight
        </label>
        <input
          v-model.number="weight"
          id="weight"
          type="number"
          step="0.01"
          min="0.01"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>

      <div v-if="productType === 'subscription'" class="mb-4">
        <label
          class="block text-gray-700 text-sm font-bold mb-2"
          for="subscriptionDuration"
        >
          Subscription Duration (months)
        </label>
        <input
          v-model.number="subscriptionDuration"
          id="subscriptionDuration"
          type="number"
          min="1"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>

      <div class="flex items-center justify-between">
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        >
          Add Product
        </button>
      </div>
    </form>

    <div
      v-if="errorMessage"
      class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
      role="alert"
    >
      <span class="block sm:inline">{{ errorMessage }}</span>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <h2 class="text-xl font-bold mb-4">Current Order</h2>
      
      <div v-if="order.products.length === 0" class="text-gray-700 italic">
        Add products to create your order.
      </div>
      
      <div v-else>
        <div
          v-for="(product, index) in order.products"
          :key="index"
          class="border-b pb-4 mb-4"
        >
          <div class="flex justify-between">
            <div class="flex-1">
              <h3 class="font-bold">{{ product.name }}</h3>
              <p class="text-sm">
                Type: {{ getProductTypeLabel(product) }}
              </p>
              <p class="text-sm">
                Quantity: {{ product.quantity }} × ${{ product.price.toFixed(2) }}
              </p>
              <div class="text-sm">
                {{ getProductSpecificInfo(product) }}
              </div>
            </div>
            <div class="text-right">
              <p class="font-bold">${{ product.calculatePrice().toFixed(2) }}</p>
              <button
                @click="removeProduct(index)"
                class="text-red-500 text-sm"
              >
                Remove
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="text-xl font-bold mt-6 pt-4 border-t">
        Total: ${{ orderTotal.toFixed(2) }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

import {
  Order,
  DigitalProduct,
  PhysicalProduct,
  SubscriptionProduct,
} from './orderClasses';

const order = ref(new Order());
const productType = ref('digital');
const productName = ref('');
const productPrice = ref('');
const productQuantity = ref('');
const downloadLink = ref('');
const weight = ref('');
const subscriptionDuration = ref('');
const errorMessage = ref('');

const addProduct = () => {
  let product;
  
  if (productType.value === 'digital') {
    product = new DigitalProduct(productName.value, productPrice.value, productQuantity.value, downloadLink.value);
  } else if (productType.value === 'physical') {
    product = new PhysicalProduct(productName.value, productPrice.value, productQuantity.value, weight.value);
  } else if (productType.value === 'subscription') {
    product = new SubscriptionProduct(productName.value, productPrice.value, productQuantity.value, subscriptionDuration.value);
  }
  
  order.value.addProduct(product);
  resetForm();
};

const resetForm = () => {
  productName.value = '';
  productPrice.value = '';
  productQuantity.value = '';
  downloadLink.value = '';
  weight.value = '';
  subscriptionDuration.value = '';
  errorMessage.value = '';
};

const removeProduct = (index) => {
  order.value.products.splice(index, 1);
};

const orderTotal = computed(() => {
  return order.value.calculateTotal();
});

const getProductTypeLabel = (product) => {
  if (product instanceof DigitalProduct) return 'Digital';
  if (product instanceof PhysicalProduct) return 'Physical';
  if (product instanceof SubscriptionProduct) return 'Subscription';

  return 'Unknown Product Type';
};

const getProductSpecificInfo = (product) => {
  if (product instanceof DigitalProduct) {
    return `Download Link: ${product.downloadLink}`;
  }

  if (product instanceof PhysicalProduct) {
    return `Weight: ${product.weight}kg`;
  }

  if (product instanceof SubscriptionProduct) {
    return `Duration: ${product.subscriptionDuration} months`;
  }

  return null;
};
</script>