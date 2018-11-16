<template>
    <div class="alert alert-flash alert-warning fade show" role="alert" v-show="show">
        <strong>Success!</strong> {{ message }}
    </div>
</template>

<script>
	export default {
		props: ['message'],

		data() {
			return {
				body: '',
				show: false
			}
		},

		created() {
			if (this.message) {
				this.body = this.message;
				this.show = true;
			}
		}
	}
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
