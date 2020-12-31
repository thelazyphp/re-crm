<script>
import GroupCard from '../components/GroupCard'

export default {
  components: {
    GroupCard
  },

  data () {
    return {
      addGroup: false,
      searching: false,
      url: null,
      group: null,
      notFound: false,
      loading: false,
      loadingNext: false
    }
  },

  watch: {
    search (value) {
      if (!value) {
        this.fetchGroups(1)
      }
    }
  },

  computed: {
    title () {
      return this.$route.meta.title
    },

    page () {
      return this.$store.state.groups.page
    },

    groups () {
      return this.$store.state.groups.items
    },

    lastPage () {
      return this.$store.state.groups.lastPage
    },

    sort: {
      get () {
        return this.$store.state.groups.sort
      },

      set (value) {
        this.$store.commit('groups/setSort', value)
      }
    },

    search: {
      get () {
        return this.$store.state.groups.search
      },

      set (value) {
        this.$store.commit('groups/setSearch', value)
      }
    }
	},

	async created () {
    await this.fetchGroups(1)
  },

  methods: {
    async searchGroup () {
      this.group = null
      this.notFound = false
      this.searching = true

      try {
        const res = await this.$http.get(`/search-group?url=${this.url}`)
        this.group = res.data
      } catch (error) {
        this.notFound = true
      } finally {
        this.searching = false
      }
    },

    async createGroup () {
      await this.$store.dispatch('groups/create', this.group)
      this.addGroup = false
      this.url = null
      this.groups = false
    },

    async fetchGroups (page = null) {
      if (!!page) {
        this.$store.commit('groups/setPage', page)
      }

      this.loading = true
      await this.$store.dispatch('groups/fetch')
      this.loading = false
    },

    async fetchNextGroups () {
      this.loadingNext = true
      await this.$store.dispatch('groups/fetchNext')
      this.loadingNext = false
    },

    async removeGroup (id) {
      if (confirm('Удалить группу?')) {
        await this.$store.dispatch('groups/remove', id)
      }
    },

    async updateGroup (id, data) {
      await this.$store.dispatch('groups/update', { id, data })
    }
  }
}
</script>

<template>
  <div class="container">
    <div class="mx-auto" style="max-width: 500px">
      <h1 class="my-5">{{ title }}</h1>
      <div class="my-5">
        <button v-if="!addGroup" type="button" class="btn btn-primary" @click="addGroup = true">Добавить группу</button>
        <div v-if="addGroup" class="card">
          <div class="card-header">Добавить группу</div>
          <div class="card-body">
            <div v-if="searching" class="text-center">
              <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Поиск на Facebook...</span>
              </div>
            </div>
            <form v-else @submit.prevent="searchGroup">
              <div class="form-group">
                <input v-model="url" type="text" class="form-control form-control-sm" placeholder="Ссылка на группу или ID" required>
              </div>
              <button type="submit" class="btn btn-sm btn-block btn-primary">Найти на Facebook</button>
            </form>
            <div v-if="group" class="d-flex mt-3">
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
            <p v-else-if="notFound" class="mt-3">Группа не найдена на Facebook.</p>
            <div v-if="group || notFound" class="d-flex mt-3 justify-content-end">
              <button type="button" class="btn btn-sm btn-primary" @click="addGroup = false">Закрыть</button>
              <button v-if="group" type="button" class="btn ml-2 btn-sm btn-primary" @click="createGroup">Сохранить</button>
            </div>
          </div>
        </div>
      </div>
      <form class="mb-3" @submit.prevent="fetchGroups(1)">
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
          <form @submit.prevent="fetchGroups(1)">
            <div class="form-group">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">Сортировать</span>
                </div>
                <select v-model="sort" class="form-control">
                  <option value="-updated_at">сначала новые</option>
                  <option value="updated_at">сначала старые</option>
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
      <template v-else-if="groups.length">
        <GroupCard v-for="group in groups" :key="group.id" :group="group" class="mb-3" @remove="removeGroup(group.id)" @update="updateGroup(group.id, $event)" />
        <template v-if="page < lastPage">
          <div v-if="loadingNext" class="mb-3 text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Загрузка...</span>
            </div>
          </div>
          <button v-else type="button" class="btn mb-3 btn-block btn-primary" @click="fetchNextGroups">Следующие группы</button>
        </template>
      </template>
      <p v-else class="text-center">Группы не найдены</p>
    </div>
  </div>
</template>
