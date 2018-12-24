import Vue from 'vue'
import router from "../../router"
import config from '../../config'
import { routeByName, routeFormatTag } from "../../libs/util"


const state = {
  tagList: [],
}

const getters = {
  tags: state => state.tagList,
  cacheTags: state => {
    let tags = state.tagList.length ? state.tagList.filter(item => item.cache).map(item => item.name) : []
    tags.push('ParentView')
    return tags
  }
}

const mutations = {
  OPEN_TAG_VIEW (state, tag) {
    let isset = state.tagList.some(function (item) {
      return item.fullPath === tag.fullPath
    })

    if (tag.provider && tag.provider === Vue.prototype.$provider) {

      let dashboardName = config[tag.provider].dashboardName
      if (tag.name !== dashboardName && (state.tagList.length === 0 || state.tagList[0].name !== dashboardName)) {
        let dashboardTag = routeFormatTag(routeByName(dashboardName))
        dashboardTag.fullPath = config[tag.provider].dashboardFullPath

        state.tagList.splice(0, 0, dashboardTag)
      }

      if (!isset) {
        state.tagList.push(tag)
      }
    }
  },
  CLOSE_TAG_VIEW (state, key) {
    for (let item of state.tagList) {
      if (key === item.fullPath) {
        let index = state.tagList.indexOf(item)
        state.tagList.splice(index, 1)

        if (router.currentRoute.fullPath === item.fullPath) {
          router.push({path: state.tagList[index -1] . fullPath })
        }
      }
    }
  },
  CLOSE_TAG_HANDLE (state, tagList) {
    tagList.reverse().forEach( key => {
      state.tagList.splice(key, 1)
    })
  }
}

const actions = {
  openTagView ({ commit }, tag) {
    commit('OPEN_TAG_VIEW', tag)
  },
  closeTagView ({ commit }, key) {
    commit('CLOSE_TAG_VIEW', key)
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}