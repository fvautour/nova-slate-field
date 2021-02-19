<template>
    <span class="icon material-icons">{{icon}}</span>
</template>

<script>
  export default {
    name: 'icon',
    props: {
      icon: String
    }
  };
</script>

<style scoped>
.icon {
  font-size: 18px;
  vertical-align: text-bottom;
}
</style>
