<template>
  <div class="w-full bg-20 border border-60 border-b-2 rounded-t-lg px-3 py-4 flex toolbar">
    <slot></slot>
  </div>
</template>

<script>
export default {
}
</script>

<style scoped>
.toolbar>:not([hidden])~:not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(0.56rem * var(--tw-space-x-reverse));
  margin-left: calc(0.65rem * calc(1 - var(--tw-space-x-reverse)));
}
</style>
