<script>
export default {
  props: {
    post: {
      type: Object,
      required: true
    }
  },

  computed: {
    date () {
      return new Date(this.post.published_at).toLocaleDateString()
    },

    time () {
      return new Date(this.post.published_at).toLocaleTimeString()
    }
  }
}
</script>

<template>
  <div class="card">
    <div class="card-body">
      <div class="media mb-3">
        <a :href="post.user.url" class="mr-3" target="_blank">
          <img :src="post.user.image || '/facebook-feed/images/image_not_found.jpg'" class="user-avatar" alt="">
        </a>
        <div class="media-body">
          <div class="d-flex flex-column">
            <a :href="post.user.url" target="_blank">{{ post.user.name }}</a>
            <small>{{ date }} в {{ time }}</small>
          </div>
        </div>
      </div>
      <p class="text-truncate">
        <span>Разместил(а) в группе</span>
        <a :href="post.group.url" target="_blank">{{ post.group.name }}</a>
      </p>
      <p v-if="post.message">{{ post.message }}</p>
      <div class="d-flex justify-content-end">
        <a :href="post.url" class="btn btn-sm btn-primary" target="_blank">Открыть</a>
        <button class="btn ml-2 btn-sm" :class="post.is_favorite ? 'btn-primary' : 'btn-outline-primary'" :title="post.is_favorite ? 'Из избранных' : 'В избранные'" type="button" @click="$emit('toggle-favorite')">
          <i :class="post.is_favorite ? 'fas fa-star' : 'far fa-star'"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>
