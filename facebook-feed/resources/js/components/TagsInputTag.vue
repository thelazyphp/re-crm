<script>
export default {
  props: {
    text: {
      type: String,
      required: true
    }
  }
}
</script>

<template>
  <span class="badge tag badge-pill badge-primary">
    <slot>{{ text }}</slot>
    <a href="" class="text-white" role="button" title="Удалить" @click.prevent="$emit('remove')">
      <i class="fas fa-times fa-sm"></i>
    </a>
  </span>
</template>

<style scoped>
.tag {
  font-size: 100%;
  font-weight: 400;
}
</style>
