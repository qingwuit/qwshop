  // initial state
  const state = {
    cart_num:0
  }
  
  // getters
  const getters = {}
  
  // actions
  const actions = {
      set_cart_num(context,data){
           state.cart_num = data;
      }
  }
  
  // mutations
  const mutations = {}
  
  export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
  }