<template>
  <div class="mb-2">
    <span>
      Total: <strong>${{ totalPrice }}</strong>
    </span>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "CartTotalPrice",
  computed: {
    ...mapGetters('cart', ['totalPrice'])
  }
}
</script>

<style scoped>

</style>