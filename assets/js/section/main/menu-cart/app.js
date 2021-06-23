import Vue from "vue";
import App from './App';
import store from "./store";

const vueMenuCartInstance = new Vue({
    el: "#appMainMenuCart",
    store,
    render: h => h(App)
});

window.vueMenuCartInstance = {};
window.vueMenuCartInstance.addCartProduct =
    (productData) => vueMenuCartInstance.$store.dispatch('cart/addCartProduct', productData)