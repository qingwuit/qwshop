  // initial state
  const state = {
    isLogin:false,
    userInfo:{},
  }
  
  // getters
  const getters = {}
  
  // actions
  const actions = {
      check_login(context,e){
          if(e.code == 200){
            state.isLogin = true;
            state.userInfo = e.data;
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