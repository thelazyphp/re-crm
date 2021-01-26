<template>
    <div id="app">
        <div id="wrapper">
            <TheSidebar/>

            <div
                id="content-wrapper"
                class="d-flex flex-column"
            >
                <div id="content">
                    <TheTopbar/>
                    <router-view/>
                </div>
                <TheFooter/>
            </div>
        </div>

        <a
            href="#page-top"
            class="scroll-to-top rounded"
        >
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
</template>

<script>
import TheSidebar from '../TheSidebar.vue';
import TheTopbar from '../TheTopbar.vue';
import TheFooter from '../TheFooter.vue';

export default {
    name: 'DefaultLayout',

    components: {
        TheSidebar,
        TheTopbar,
        TheFooter,
    },

    beforeMount () {
        document.body.id = 'page-top';
        document.body.className = '';
    },

    mounted () {
        this.loadScript('/castro-blog/theme/admin/js/sb-admin-2.min.js');
    }
};
</script>
