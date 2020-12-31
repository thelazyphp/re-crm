<script>
export default {
  props: {
    sidebarToggled: {
      type: Boolean,
      required: true
    }
  }
}
</script>

<template>
  <nav class="navbar navbar-light bg-white">
    <router-link to="/" class="navbar-brand">Facebook Feed <span class="badge badge-pill badge-success">beta</span></router-link>
    <a href="" class="d-inline d-md-none" role="button" @click.prevent="$emit('toggle-sidebar')">
      <i :class="sidebarToggled ? 'fas fa-times' : 'fas fa-bars'"></i>
    </a>
  </nav>
</template>
