<template>
  <div class="tags-box">
    <div class="tags-left-btn" @click="tagsScroll(300)">
      <el-button :plain="true">
        <i class="el-icon-arrow-left"></i>
      </el-button>
    </div>
    <div class="tags-right-btn" @click="tagsScroll(-300)">
      <el-button :plain="true">
        <i class="el-icon-arrow-right"></i>
      </el-button>
    </div>
    <div class="tags-close">
      <el-dropdown>
        <el-button :plain="true">
          <i class="el-icon-close"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item @click.native="closeAll">{{ $t('closeButton.closeAll') }}</el-dropdown-item>
          <el-dropdown-item @click.native="closeOther">{{ $t('closeButton.closeOther') }}</el-dropdown-item>
          <el-dropdown-item @click.native="closeRight">{{ $t('closeButton.closeRight') }}</el-dropdown-item>
          <el-dropdown-item @click.native="closeLeft">{{ $t('closeButton.closeLeft') }}</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>

    </div>
    <div class="tags-list" ref="tagsList">
      <div class="tags-view" ref="tagsView" :style="{left: tagsViewLeft + 'px'}">
        <el-tag
          :key="tag.fullPath"
          v-for="tag in tagList"
          :closable = "tag.closable"
          :color="isActive(tag)"
          :disable-transitions="false"
          @click.native="openTagPage(tag)"
          @close="closeTagPage(tag)">
          <i class="el-icon-star-on" v-if="isStar(tag)"></i> {{ $t(`meta.title.${tag.title}`) }}
        </el-tag>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'TagsView',
    data() {
      return {
        tagsViewLeft:0,
      };
    },
    methods: {
      isActive (tag) {
        return tag.fullPath === this.$route.fullPath ? '' : '#fff'
      },
      isStar (tag) {
        return tag.fullPath === this.$route.fullPath
      },
      closeTagPage (tag) {
        this.$store.dispatch('closeTagView', tag.fullPath)
      },
      closeAll () {
        let tagList = []
        this.$store.getters.tags.forEach((item, index) => {
          if (item.closable) {
            tagList.push(index)
          }
        })

        console.log(this.$route.matched)

        this.$store.commit('CLOSE_TAG_HANDLE', tagList)
        this.$router.push({path: this.$config[this.$provider].dashboardFullPath})
      },
      closeOther () {
        let tagList = []
        this.$store.getters.tags.forEach((item, index) => {
          if (item.closable && item.fullPath !== this.$route.fullPath) {
            tagList.push(index)
          }
        })

        this.$store.commit('CLOSE_TAG_HANDLE', tagList)
      },
      closeRight () {
        let tagList = []
        let flag = false
        this.$store.getters.tags.forEach((item, index) => {
          if (item.fullPath === this.$route.fullPath) {
            flag = true
          } else if (item.closable && flag) {
            tagList.push(index)
          }
        })

        this.$store.commit('CLOSE_TAG_HANDLE', tagList)
      },
      closeLeft () {
        let tagList = []
        let flag = true
        this.$store.getters.tags.forEach((item, index) => {
          if (item.fullPath === this.$route.fullPath) {
            flag = false
          }
          if (item.closable && flag) {
            tagList.push(index)
          }
        })

        this.$store.commit('CLOSE_TAG_HANDLE', tagList)
      },
      openTagPage (tag) {
        this.$router.push({path: tag.fullPath})
      },
      tagsScroll (offset) {
        const tagsListWidth = this.$refs.tagsList.offsetWidth - 150;
        const tagsViewWidth = this.$refs.tagsView.offsetWidth;

        if (tagsListWidth > tagsViewWidth) {
          return this.tagsViewLeft = 0;
        }

        if (offset > 0) {
          return this.tagsViewLeft = Math.min(0, this.tagsViewLeft + offset)
        }

        if (this.tagsViewLeft > - (tagsViewWidth - tagsListWidth)) {
          this.tagsViewLeft = Math.max(this.tagsViewLeft + offset,  tagsListWidth - tagsViewWidth)
        }
      }
    },
    computed: {
      tagList () {
        return [...this.$store.getters.tags]
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .tags-box {
    position:relative;
    height:40px;
    background-color:#F2F6FC;
    flex-shrink:0;
    -webkit-flex-shrink:0;
    overflow:hidden;
    width:100%;
    .tags-left-btn {
      position:absolute;
      z-index:10;
      left:0;
    }
    .tags-right-btn {
      position:absolute;
      z-index:10;
      right:44px;
      border-right:1px solid #e6e6e6;
    }
    .tags-close {
      position:absolute;
      z-index:10;
      right:0;
    }
    .tags-list {
      position: absolute;
      left:45px;
      width:100%;
      .tags-view {
        margin-top:3px;
        position:absolute;
        white-space:nowrap;
        overflow:hidden;
      }
    }
  }
  .el-button {
    padding:10px 15px;
    border:none;
    height:39px;
  }
  .el-tag {
    margin-left: 5px;
    cursor:pointer;
  }
</style>