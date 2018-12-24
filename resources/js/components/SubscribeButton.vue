<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
	export default {
		props: ['active'],

        computed: {
			classes() {
				return ['btn', this.active ? 'btn-primary' : 'btn-outline-secondary']
            }
        },

		methods: {
			subscribe() {
				axios.post(location.pathname + '/subscriptions');

				this.active = true;

				flash('Subscribed');
			}
		}
	}
</script>