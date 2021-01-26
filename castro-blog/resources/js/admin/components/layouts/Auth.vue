<template>
    <div id="app">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <router-view/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AuthLayout',

    beforeMount () {
        document.body.id = '';
        document.body.className = 'bg-gradient-primary';
    },

    mounted () {
        this.loadScript('/castro-blog/theme/admin/js/sb-admin-2.min.js');
    }
};
</script>

<style scoped>
    .bg-login-image {
        background-image: url('/castro-blog/cover.jpg') !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        background-position: center center !important;
    }
</style>
