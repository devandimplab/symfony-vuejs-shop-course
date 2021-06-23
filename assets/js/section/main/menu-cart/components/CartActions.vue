<template>
  <div class="actions">
    <a
        :href="staticStore.url.viewCart"
        class="btn btn-success"
    >
      View cart
    </a>
    <a
        :href="'#'"
        class="btn btn-cancel mt-2"
        @click="cleanCart"
    >
      Checkout
    </a>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
  name: "CartActions",
  computed: {
    ...mapState("cart", ["staticStore"])
  },
  methods: {
    ...mapActions("cart", ["cleanCart"])
  }
}
</script>

<style scoped>

</style>