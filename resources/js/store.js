let store = {
    state: {
        cart: [],
        cartCount: 0,
    },

    mutations: {
        addToCart(state, item) {
            let found = state.cart.find(product => product.id == item.id);
            if (found) {
                found.quantity ++;
                found.totalPrice = found.quantity * found.price;
            } else {
                state.cart.push(item);

                Vue.set(item, 'quantity', 1);
                Vue.set(item, 'totalPrice', item.price);
            }

            state.cartCount++;
        },
        updateCart(state, item) {
            let found = state.cart.find(product => product.id == item.id);
            if (found) {
                found.quantity = item.quantity;
                found.totalPrice = found.quantity * found.price;
            }
        },
        removeFromCart(state, item) {
            let index = state.cart.indexOf(item);

            if (index > -1) {
                let product = state.cart[index];
                state.cartCount -= product.quantity;

                state.cart.splice(index, 1);
            }
        },
        clearCart(state) {
            state.cart = [];
            state.cartCount = 0
        }
    }
};

export default store;
