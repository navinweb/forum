<template>
    <div class="alert alert-flash alert-warning fade show" role="alert">
      <strong>Success!</strong> {{ message }}
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: 'Temporary'
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
