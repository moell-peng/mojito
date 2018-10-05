const state = {
  local: '',
  breadcrumb: []
}

const getters = {
  breadcrumb: state => state.breadcrumb
}

const mutations = {
  SET_BREADCRUMB (state, breadcrumb) {
    let title = []
    state.breadcrumb = breadcrumb.filter( item => {
      if (title.indexOf(item.meta.title) >= 0) {
        return false;
      }
      title.push(item.meta.title)
      return item.meta.title
    })
  }
}

const actions = {

}

export default {
  state,
  getters,
  mutations,
  actions
}