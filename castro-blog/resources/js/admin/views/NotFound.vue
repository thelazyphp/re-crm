<template>
    <div class="container-fluid">
        <div class="text-center">
            <div
                class="error mx-auto"
                data-text="404"
            >
                404
            </div>

            <p class="lead text-gray-800 mb-5">
                Страница не найдена
            </p>
            <p class="text-gray-500 mb-0">
                Запрашиваемая страница не найдена...
            </p>
            <router-link to="/">
                &larr; Вернуться на главную
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'NotFoundPage'
};
</script>
