<template>
	<footer class="sticky-footer bg-white">
		<div class="container my-auto">
			<div class="copyright text-center my-auto">
				<span>
					&copy; {{ year }}
				</span>
			</div>
		</div>
	</footer>
</template>

<script>
export default {
	name: 'TheFooter',

	computed: {
		year () {
			return new Date().getFullYear();
		}
	}
};
</script>
