  // initial state
  const state = {
    isStore:false,
    storeVerify:0,
  }
  
  // getters
  const getters = {}
  
  // actions
  const actions = {
      store_verify(context,e){
          if(e.code == 200){
            state.isStore = true;
            state.store_verify = e.data.store_verify;
          }
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