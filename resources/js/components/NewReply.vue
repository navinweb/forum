<template>
    <div>
        <!--@if(auth()->check())-->
        <!--<form action="{{ $thread->path() . '/replies' }}" method="post">-->
        <div class="form-group">
                <textarea name="body"
                          class="form-control"
                          placeholder="Body"
                          rows="5"
                          required
                          v-model="body"></textarea>
        </div>
        <button type="submit"
                class="btn btn-default"
                @click="addReply">Post
        </button>
        <!--</form>-->
        <!--@else-->
        <!--<p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this-->
        <!--discussion.</p>-->
        <!--@endif-->
    </div>
</template>

<script>
	export default{
		data() {
			return {
				body: '',
                endpoint: ''
			}
		},

		methods: {
			addReply() {
				axios.post(this.endpoint, {
					body: this.body,
				}).then(response => {
					this.body = '';

					flash('Your Reply has been posted.');

					this.$emit('created', response.data);
				})
			}
		}
	}
</script>