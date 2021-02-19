<template>
  <span class="button"
    :style="{color: color}"
    @mousedown="$emit('mouseDown', $event)"
    @mouseover="hovered = true"
    @mouseout="hovered = false"
  >
    <slot></slot>
  </span>
</template>

<script>
export default {
  props: {
    active: Boolean,
    reversed: Boolean
  },
  
  data: () => ({
    hovered: false,
  }),
  
  computed: {
    color() {
      return this.reversed
        ? (this.active || this.hovered
          ? 'white'
          : '#aaa')
        : (this.active || this.hovered
          ? 'var(--primary)'
          : 'var(--primary-50)')
    }
  },
}
</script>

<style scoped>
.button {
  cursor: pointer;
}
.button:hover {
  
}
</style>
