<template>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            Посты
        </h1>
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="text-right mb-4">
                            <router-link
                                to="/posts/create"
                                class="btn btn-primary"
                            >
                                Создать
                            </router-link>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-nowrap">ID</th>
                                        <th scope="col" class="text-nowrap">Категория</th>
                                        <th scope="col" class="text-nowrap">Заголовок</th>
                                        <th scope="col" class="text-nowrap">Псевдоним</th>
                                        <th scope="col" class="text-nowrap">Опубликован</th>
                                        <th scope="col" class="text-nowrap">Создан</th>
                                        <th scope="col" class="text-nowrap">Изменен</th>
                                        <th scope="col" class="text-nowrap"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-if="!posts.length"
                                        class="text-nowrap"
                                    >
                                        <td
                                            colspan="4"
                                            class="text-center"
                                        >
                                            Постов не найдено
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="post in posts"
                                        :key="post.id"
                                    >
                                        <td class="text-nowrap">{{ post.id }}</td>
                                        <td class="text-nowrap">{{ post.category ? post.category.name : '-' }}</td>
                                        <td class="text-nowrap">{{ post.title }}</td>
                                        <td class="text-nowrap">{{ post.slug }}</td>
                                        <td class="text-nowrap">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    :id="`publishedCheck${post.id}`"
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    :checked="post.published"
                                                    disabled
                                                />

                                                <label
                                                    :for="`publishedCheck${post.id}`"
                                                    class="custom-control-label"
                                                >
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">{{ new Date(post.created_at).toLocaleString() }}</td>
                                        <td class="text-nowrap">{{ new Date(post.updated_at).toLocaleString() }}</td>
                                        <td class="text-nowrap">
                                            <router-link :to="`/posts/${post.id}/edit`" class="mx-1" title="Редактировать"><i class="far fa-edit"></i></router-link>
                                            <a href="javascript:void(0);" class="mx-1" title="Удалить" @click.prevent="deltePostById(post.id)"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PostsPage',

    data () {
        return {
            posts: []
        }
    },

    async created () {
        await this.fetchPosts();
    },

    methods: {
        async deltePostById (id) {
            if (confirm('Удалить пост?')) {
                try {
                    await axios.delete(`/api/posts/${id}`);
                    await this.fetchPosts();
                } catch (error) {
                    //

                    console.log(error);
                }
            }
        },

        async fetchPosts () {
            try {
                const { data } = await axios.get('/api/posts');
                this.posts = data.data;
            } catch (error) {
                //

                console.log(error);
            }
        }
    }
};
</script>
