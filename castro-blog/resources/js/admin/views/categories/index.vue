<template>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            Категории
        </h1>
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="text-right mb-4">
                            <router-link
                                to="/categories/create"
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
                                        <th scope="col" class="text-nowrap">Название</th>
                                        <th scope="col" class="text-nowrap">Псевдоним</th>
                                        <th scope="col" class="text-nowrap">Опубликована</th>
                                        <th scope="col" class="text-nowrap">Создана</th>
                                        <th scope="col" class="text-nowrap">Изменена</th>
                                        <th scope="col" class="text-nowrap"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-if="!categories.length"
                                        class="text-nowrap"
                                    >
                                        <td
                                            colspan="4"
                                            class="text-center"
                                        >
                                            Категорий не найдено
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="category in categories"
                                        :key="category.id"
                                    >
                                        <td class="text-nowrap">{{ category.id }}</td>
                                        <td class="text-nowrap">{{ category.name }}</td>
                                        <td class="text-nowrap">{{ category.slug }}</td>
                                        <td class="text-nowrap">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    :id="`publishedCheck${category.id}`"
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    :checked="category.published"
                                                    disabled
                                                />

                                                <label
                                                    :for="`publishedCheck${category.id}`"
                                                    class="custom-control-label"
                                                >
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">{{ new Date(category.created_at).toLocaleString() }}</td>
                                        <td class="text-nowrap">{{ new Date(category.updated_at).toLocaleString() }}</td>
                                        <td class="text-nowrap">
                                            <router-link :to="`/categories/${category.id}/edit`" class="mx-1" title="Редактировать"><i class="far fa-edit"></i></router-link>
                                            <a href="javascript:void(0);" class="mx-1" title="Удалить" @click.prevent="delteCategoryById(category.id)"><i class="far fa-trash-alt"></i></a>
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
    name: 'CategoriesPage',

    data () {
        return {
            categories: []
        }
    },

    async created () {
        await this.fetchCategories();
    },

    methods: {
        async delteCategoryById (id) {
            if (confirm('Удалить категорию?')) {
                try {
                    await axios.delete(`/api/categories/${id}`);
                    await this.fetchCategories();
                } catch (error) {
                    //

                    console.log(error);
                }
            }
        },

        async fetchCategories () {
            try {
                const { data } = await axios.get('/api/categories');
                this.categories = data.data;
            } catch (error) {
                //

                console.log(error);
            }
        }
    }
};
</script>
