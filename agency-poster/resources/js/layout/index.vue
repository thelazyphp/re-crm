<template>
    <div id="app">
        <TheHeader/>

        <main class="container py-5">
            <RouterView/>
        </main>

        <TheFooter/>
    </div>
</template>

<script>
    import TheHeader from '../components/TheHeader'
    import TheFooter from '../components/TheFooter'

    export default {
        components: {
            TheHeader,
            TheFooter
        }
    }
</script>
