<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xl-10 col-lg-12 col-md-9">
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<router-view/>
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
		document.body.className = '';
		document.body.classList.add('bg-gradient-primary');
    },

    mounted () {
        this.$nextTick(() => {
            this.loadScript('/nca/theme/js/sb-admin-2.min.js');
        });
    }
};
</script>
