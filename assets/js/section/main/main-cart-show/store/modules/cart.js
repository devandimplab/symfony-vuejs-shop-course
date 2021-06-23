import axios from "axios";
import {StatusCodes} from "http-status-codes";
import {apiConfig, apiConfigPatch} from "../../../../../utils/settings";
import {concatUrlByParams} from "../../../../../utils/url-generator";

function getAlertStructure() {
    return {
        type: null,
        message: null
    };
}

const state = () => ({
    cart: {},
    alert: getAlertStructure(),
    isSentForm: false,

    staticStore: {
        url: {
            apiCart: window.staticStore.urlCart,
            apiCartProduct: window.staticStore.urlCartProduct,
            apiOrder: window.staticStore.urlOrder,
            viewProduct: window.staticStore.urlViewProduct,
            assetImageProducts: window.staticStore.urlAssetImageProducts,
        }
    }
});

const getters = {
    totalPrice(state) {
        let result = 0;
        if (!state.cart.cartProducts) {
            return 0;
        }

        state.cart.cartProducts.forEach(
            cartProduct => {
                result += cartProduct.product.price * cartProduct.quantity
            }
        )

        return result;
    }
};

const actions = {
    async getCart({ state, commit }) {
        const url = state.staticStore.url.apiCart;

        const result = await axios.get(url, apiConfig);

        if (
            result.data
            && result.data["hydra:member"].length
            && result.status === StatusCodes.OK
        ) {
            commit('setCart', result.data["hydra:member"][0]);
        } else {
            commit('setAlert', {
                type: 'info',
                message: 'Your cart is empty ...'
            });
        }
    },
    async cleanCart({ state, commit }) {
        const url =
            concatUrlByParams(
                state.staticStore.url.apiCart,
                state.cart.id
            );

        const result = await axios.delete(url, apiConfig);

        if (result.status === StatusCodes.NO_CONTENT) {
            commit('setCart', {});
        }
    },
    async removeCartProduct({ state, commit, dispatch }, cartProductId) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            cartProductId
        );

        const result = await axios.delete(url, apiConfig);

        if (result.status === StatusCodes.NO_CONTENT) {
            dispatch('getCart');
        }
    },
    async updateCartProductQuantity({ state, dispatch }, payload) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            payload.cartProductId
        );

        const data = {
            quantity: parseInt(payload.quantity)
        };

        const result = await axios.patch(url, data, apiConfigPatch);

        if (result.status === StatusCodes.OK) {
            dispatch('getCart');
        }
    },
    async makeOrder({ state, commit, dispatch }) {
        const url = state.staticStore.url.apiOrder;
        const data = {
          cartId: state.cart.id
        };

        const result = await axios.post(url, data, apiConfig);

        if (result.data && result.status === StatusCodes.CREATED) {
          commit('setAlert', {
              type: 'success',
              message: 'Thank you for your purchase! Our manager will contact with you in 24 hours.'
          });
          commit('setIsSentForm', true);
          dispatch('cleanCart');
        }
    }
};

const mutations = {
    setCart(state, cart) {
        state.cart = cart;
    },
    cleanAlert(state) {
        state.alert = getAlertStructure();
    },
    setAlert(state, model) {
        state.alert = {
            type: model.type,
            message: model.message
        };
    },
    setIsSentForm(state, value) {
        state.isSentForm = value;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}