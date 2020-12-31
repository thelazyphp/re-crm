<script>
import PostCard from '../components/PostCard'

export default {
  components: {
    PostCard
  },

  data () {
    return {
      loading: false,
      loadingNext: false
    }
  },

  watch: {
    search (value) {
      if (!value) {
        this.fetchPosts(1)
      }
    }
  },

  computed: {
    title () {
      return this.$route.meta.title
    },

    page () {
      return this.$store.state.favorites.page
    },

    posts () {
      return this.$store
        .state
        .favorites
        .items
        .filter(item => item.message)
    },

    lastPage () {
      return this.$store.state.favorites.lastPage
    },

    sort: {
      get () {
        return this.$store.state.favorites.sort
      },

      set (value) {
        this.$store.commit('favorites/setSort', value)
      }
    },

    search: {
      get () {
        return this.$store.state.favorites.search
      },

      set (value) {
        this.$store.commit('favorites/setSearch', value)
      }
    }
	},

	async created () {
    await this.fetchPosts(1)
  },

  methods: {
    async fetchPosts (page = null) {
      if (!!page) {
        this.$store.commit('favorites/setPage', page)
      }

      this.loading = true
      await this.$store.dispatch('favorites/fetch')
      this.loading = false
    },

    async fetchNextPosts () {
      this.loadingNext = true
      await this.$store.dispatch('favorites/fetchNext')
      this.loadingNext = false
    },

    async togglePostFavorite (id) {
      await this.$store.dispatch('favorites/toggleFavorite', id)
    }
  }
}
</script>

<template>
  <div class="container">
    <div class="mx-auto" style="max-width: 500px">
      <h1 class="my-5">{{ title }}</h1>
      <form class="mb-3" @submit.prevent="fetchPosts(1)">
        <div class="row no-gutters">
          <div class="col">
            <input v-model="search" type="search" class="form-control" placeholder="Поиск">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn ml-2 btn-primary">Найти</button>
          </div>
        </div>
      </form>
      <div class="dropdown mb-3 text-right">
        <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">параметры</a>
        <div class="dropdown-menu p-3 dropdown-menu-right">
          <form @submit.prevent="fetchPosts(1)">
            <div class="form-group">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">Сортировать</span>
                </div>
                <select v-model="sort" class="form-control">
                  <option value="-published_at">сначала новые</option>
                  <option value="published_at">сначала старые</option>
                </select>
              </div>
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
      <div v-if="loading" class="mb-3 text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Загрузка...</span>
        </div>
      </div>
      <template v-else-if="posts.length">
        <PostCard v-for="post in posts" :key="post.id" :post="post" class="mb-3" @toggle-favorite="togglePostFavorite(post.id)" />
        <template v-if="page < lastPage">
          <div v-if="loadingNext" class="mb-3 text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Загрузка...</span>
            </div>
          </div>
          <button v-else type="button" class="btn mb-3 btn-block btn-primary" @click="fetchNextPosts">Следующие посты</button>
        </template>
      </template>
      <p v-else class="text-center">Посты не найдены</p>
    </div>
  </div>
</template>
