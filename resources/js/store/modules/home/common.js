  // initial state
  const state = {
    common:{
        classes:[],
        common:{
            icp:'',
        }
    },
  }
  
  // getters
  const getters = {}
  
  // actions
  const actions = {
      set_common(context,data){
           state.common = data;
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