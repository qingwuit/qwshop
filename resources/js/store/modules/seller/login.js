  // initial state
  const state = {
    isLogin:false,
    storeInfo:{},
  }
  
  // getters
  const getters = {}
  
  // actions
  const actions = {
      check_login(context,e){
          if(e.code == 200){
            state.isLogin = true;
            state.storeInfo = e.data;
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