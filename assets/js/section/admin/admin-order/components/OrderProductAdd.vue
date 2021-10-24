<template>
  <div class="row mb-2">
    <div class="col-md-2">
      <select
        v-model="form.categoryId"
        name="add_product_category_select"
        class="form-control"
        @change="getProducts()"
      >
        <option value="" disabled>- choose option -</option>
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
        >
          {{ category.title }}
        </option>
      </select>
    </div>
    <div v-if="form.categoryId" class="col-md-3">
      <select
          v-model="form.productId"
          name="add_product_product_select"
          class="form-control"
          @change="changeProduct()"
      >
        <option value="" disabled>- choose option -</option>
        <option
            v-for="categoryProduct in freeCategoryProducts"
            :key="categoryProduct.id"
            :value="categoryProduct.uuid"
        >
          {{ productTitle(categoryProduct) }}
        </option>
      </select>
    </div>
    <div v-if="showProductOptions" class="col-md-2">
      <input
        v-model="form.quantity"
        type="number"
        class="form-control"
        placeholder="quantity"
        min="1"
        :max="productQuantityMax"
        @change="updateMaxValue($event, 'quantity', productQuantityMax)"
      />
    </div>
    <div v-if="showProductOptions" class="col-md-2">
      <input
        v-model="form.pricePerOne"
        type="number"
        class="form-control"
        placeholder="price per one"
        step="0.01"
        min="1"
        :max="productPriceMax"
        @change="updateMaxValue($event, 'pricePerOne', productPriceMax)"
      />
    </div>
    <div v-if="showProductOptions" class="col-md-3">
      <button
        class="btn btn-outline-info"
        @click="viewDetails"
      >
        Details
      </button>
      <button
        class="btn btn-outline-success"
        @click="submit"
      >
        Add
      </button>
    </div>
  </div>
</template>

<script>
import {mapActions, mapState, mapGetters, mapMutations} from "vuex";
import {getProductInformativeTitle} from "../../../../utils/title-formatter";
import {getUrlViewProduct} from "../../../../utils/url-generator";

  export default {
    name: "OrderProductAdd",
    data() {
      return {
        form: {
          categoryId: "",
          productId: "",
          quantity: "",
          pricePerOne: ""
        }
      };
    },
    computed: {
      ...mapState("products", ["categories", "staticStore", "categoryProducts"]),
      ...mapGetters("products", ["freeCategoryProducts"]),
      productQuantityMax() {
        return parseInt(this.selectedProduct.quantity);
      },
      productPriceMax() {
        return parseFloat(this.selectedProduct.price);
      },
      selectedProduct() {
        return this.freeCategoryProducts.find(
            product => product.uuid === this.form.productId
        );
      },
      showProductOptions() {
        return this.selectedProduct;
      }
    },
    methods: {
      ...mapMutations("products", ["setNewProductInfo"]),
      ...mapActions("products", ["addNewOrderProduct", "getProductsByCategory"]),
      updateMaxValue(event, field, maxValue) {
        const value = Number.parseFloat(event.target.value);
        let updatedValue = 1;

        if (value > 0 && value <= maxValue) {
          updatedValue = value;
        } else if (value > maxValue) {
          updatedValue = maxValue;
        }

        this.form[field] = updatedValue;
      },
      productTitle(product) {
        return getProductInformativeTitle(product);
      },
      getProducts() {
        const categoryId = this.form.categoryId;
        this.resetFormData();

        this.form.categoryId = categoryId;
        this.setNewProductInfo(this.form);
        this.getProductsByCategory();
      },
      changeProduct() {
        this.form.quantity = "";
        this.form.pricePerOne = "";
      },
      viewDetails(event) {
        event.preventDefault();

        const url = getUrlViewProduct(
            this.staticStore.url.viewProduct,
            this.selectedProduct.id
        );
        window.open(url, "_blank").focus();
      },
      submit(event) {
        event.preventDefault();
        this.setNewProductInfo(this.form);
        this.addNewOrderProduct();
        this.resetFormData();
      },
      resetFormData() {
        Object.assign(this.$data, this.$options.data.apply(this));
      }
    }
  }
</script>
