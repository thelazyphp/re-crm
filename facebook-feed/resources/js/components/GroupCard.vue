<script>
export default {
  props: {
    group: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      edit: false,
      getPosts: false,
      postsLimit: null
    }
  },

  created () {
    this.getPosts = this.group.get_posts
    this.postsLimit = this.group.posts_limit
  },

  methods: {
    update () {
      this.$emit('update', {
        get_posts: this.getPosts,
        posts_limit: this.postsLimit
      })

      this.edit = false
    }
  }
}
</script>

<template>
  <div class="card">
    <div class="card-body">
      <div class="d-flex mb-3">
        <a :href="group.url" class="mr-3" target="_blank">
          <span :style="{
            display: 'inline-block',
            width: '50px',
            height: '50px',
            borderRadius: '50%',
            backgroundImage: `url(${group.image || '/facebook-feed/images/image_not_found.jpg'})`,
            backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
            backgroundPosition: 'center center' }"></span>
        </a>
        <a :href="group.url" target="_blank">{{ group.name }}</a>
      </div>
      <p v-if="group.latest_post && group.latest_post.message" class="text-truncate">
        <a :href="group.latest_post.user.url" target="_blank">{{ group.latest_post.user.name }}</a>
        <span>разместил(а)</span>
        <a :href="group.latest_post.url" target="_blank">{{ group.latest_post.message }}</a>
      </p>
      <div class="d-flex justify-content-end">
        <a href="" role="button" title="Удалить" @click.prevent="$emit('remove')">
          <i class="far fa-trash-alt"></i>
        </a>
        <a v-if="!edit" href="" class="ml-2" role="button" title="Редактировать" @click.prevent="edit = true">
          <i class="far fa-edit"></i>
        </a>
      </div>
      <form v-if="edit" class="mt-3" @submit.prevent="update">
        <div class="form-group">
          <div class="custom-control custom-switch">
            <input :id="`get-posts-${group.id}`" v-model="getPosts" type="checkbox" class="custom-control-input">
            <label :for="`get-posts-${group.id}`" class="custom-control-label">Получать посты</label>
          </div>
        </div>
        <div class="form-group">
          <label :for="`posts-limit-${group.id}`">Лимит постов</label>
          <select :id="`posts-limit-${group.id}`" v-model="postsLimit" class="custom-select custom-select-sm">
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="300">300</option>
            <option value="400">400</option>
            <option value="500">500</option>
          </select>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-sm btn-primary" @click="edit = false">Отменить</button>
          <button type="submit" class="btn ml-2 btn-sm btn-primary">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</template>
