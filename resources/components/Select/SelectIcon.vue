<template>
  <div>
    <el-popover
      v-if="inputType || disabledSelected"
      placement="bottom-start"
      popper-class="pupop-select-icon"
      transition="el-zoom-in-top"
      trigger="click"
      v-model="popoverVisible"
      :disabled="disabledSelected">
      <!-- 弹出框内容区 -->
      <el-scrollbar
        v-if="popoverVisible"
        class="hide-x"
        :native="false"
        :noresize="false">
        <!-- 图标项 -->
        <div
          class="icon-item"
          v-for="item in options"
          :key="item"
          :class="{ 'is-active': isActive(item) }"
          @click="onClickSelected(item)">
          <i :class="item"></i>
        </div>
      </el-scrollbar>
      <!-- 页面显示内容区 -->
      <template slot="reference">
        <div :class="{
          'mod-select-icon': 1,
          'is-opened': popoverVisible,
          'is-active': value,
          'is-disabled': disabledSelected
          }">
          <!-- 显示图标 -->
          <div class="icon-item">
            <i :class="value || 'el-icon-plus'"></i>
          </div>
          <!-- 清空按钮 -->
          <div v-show="value" class="btn-clear">
            <i class="el-icon-close" @click.stop="onClickClear()"></i>
          </div>
        </div>
      </template>
    </el-popover>
    <el-input v-else class="mod-input" v-model.trim="value"></el-input>
    <el-button :disabled="disabledSelected" @click="inputType = !inputType" :type="inputType?'primary':'ghost'" icon="el-icon-edit" circle></el-button>
  </div>
</template>

<script>
export default {
  name: 'SelectIcon',
  // 设置绑定参数
  model: {
    prop: 'value',
    event: 'selected',
  },
  props: {
    disabled: Boolean,
    // 接收绑定参数 - 图标类名
    value: {
      type: String,
      required: false,
    },
    // 选项数据，图标类名数组
    options: {
      type: Array,
      default: () => (['mofont mo-icon-dashboard mo-menu','mofont mo-icon-admin mo-menu','el-icon-ice-cream-round','el-icon-ice-cream-square','el-icon-lollipop','el-icon-potato-strips','el-icon-milk-tea','el-icon-ice-drink','el-icon-ice-tea','el-icon-coffee','el-icon-orange','el-icon-pear','el-icon-apple','el-icon-cherry','el-icon-watermelon','el-icon-grape','el-icon-refrigerator','el-icon-goblet-square-full','el-icon-goblet-square','el-icon-goblet-full','el-icon-goblet','el-icon-cold-drink','el-icon-coffee-cup','el-icon-water-cup','el-icon-hot-water','el-icon-ice-cream','el-icon-dessert','el-icon-sugar','el-icon-tableware','el-icon-burger','el-icon-knife-fork','el-icon-fork-spoon','el-icon-chicken','el-icon-food','el-icon-dish-1','el-icon-dish','el-icon-moon-night','el-icon-moon','el-icon-cloudy-and-sunny','el-icon-partly-cloudy','el-icon-cloudy','el-icon-sunny','el-icon-sunset','el-icon-sunrise-1','el-icon-sunrise','el-icon-heavy-rain','el-icon-lightning','el-icon-light-rain','el-icon-wind-power','el-icon-baseball','el-icon-soccer','el-icon-football','el-icon-basketball','el-icon-ship','el-icon-truck','el-icon-bicycle','el-icon-mobile-phone','el-icon-service','el-icon-key','el-icon-unlock','el-icon-lock','el-icon-watch','el-icon-watch-1','el-icon-timer','el-icon-alarm-clock','el-icon-map-location','el-icon-delete-location','el-icon-add-location','el-icon-location-information','el-icon-location-outline','el-icon-location','el-icon-place','el-icon-discover','el-icon-first-aid-kit','el-icon-trophy-1','el-icon-trophy','el-icon-medal','el-icon-medal-1','el-icon-stopwatch','el-icon-mic','el-icon-copy-document','el-icon-full-screen','el-icon-switch-button','el-icon-aim','el-icon-crop','el-icon-odometer','el-icon-time','el-icon-bangzhu','el-icon-close-notification','el-icon-microphone','el-icon-turn-off-microphone','el-icon-position','el-icon-postcard','el-icon-message','el-icon-chat-line-square','el-icon-chat-dot-square','el-icon-chat-dot-round','el-icon-chat-square','el-icon-chat-line-round','el-icon-chat-round','el-icon-set-up','el-icon-turn-off','el-icon-open','el-icon-connection','el-icon-link','el-icon-cpu','el-icon-thumb','el-icon-female','el-icon-male','el-icon-guide','el-icon-news','el-icon-price-tag','el-icon-discount','el-icon-wallet','el-icon-coin','el-icon-money','el-icon-bank-card','el-icon-box','el-icon-present','el-icon-sell','el-icon-sold-out','el-icon-shopping-bag-2','el-icon-shopping-bag-1','el-icon-shopping-cart-2','el-icon-shopping-cart-1','el-icon-shopping-cart-full','el-icon-smoking','el-icon-no-smoking','el-icon-house','el-icon-table-lamp','el-icon-school','el-icon-office-building','el-icon-toilet-paper','el-icon-notebook-2','el-icon-notebook-1','el-icon-files','el-icon-collection','el-icon-receiving','el-icon-suitcase-1','el-icon-suitcase','el-icon-film','el-icon-collection-tag','el-icon-data-analysis','el-icon-pie-chart','el-icon-data-board','el-icon-data-line','el-icon-reading','el-icon-magic-stick','el-icon-coordinate','el-icon-mouse','el-icon-brush','el-icon-headset','el-icon-umbrella','el-icon-scissors','el-icon-mobile','el-icon-attract','el-icon-monitor','el-icon-search','el-icon-takeaway-box','el-icon-paperclip','el-icon-printer','el-icon-document-add','el-icon-document','el-icon-document-checked','el-icon-document-copy','el-icon-document-delete','el-icon-document-remove','el-icon-tickets','el-icon-folder-checked','el-icon-folder-delete','el-icon-folder-remove','el-icon-folder-add','el-icon-folder-opened','el-icon-folder','el-icon-edit-outline','el-icon-edit','el-icon-date','el-icon-c-scale-to-original','el-icon-view','el-icon-loading','el-icon-rank','el-icon-sort-down','el-icon-sort-up','el-icon-sort','el-icon-finished','el-icon-refresh-left','el-icon-refresh-right','el-icon-refresh','el-icon-video-play','el-icon-video-pause','el-icon-d-arrow-right','el-icon-d-arrow-left','el-icon-arrow-up','el-icon-arrow-down','el-icon-arrow-right','el-icon-arrow-left','el-icon-top-right','el-icon-top-left','el-icon-top','el-icon-bottom','el-icon-right','el-icon-back','el-icon-bottom-right','el-icon-bottom-left','el-icon-caret-top','el-icon-caret-bottom','el-icon-caret-right','el-icon-caret-left','el-icon-d-caret','el-icon-share','el-icon-menu','el-icon-s-grid','el-icon-s-check','el-icon-s-data','el-icon-s-opportunity','el-icon-s-custom','el-icon-s-claim','el-icon-s-finance','el-icon-s-comment','el-icon-s-flag','el-icon-s-marketing','el-icon-s-shop','el-icon-s-open','el-icon-s-management','el-icon-s-ticket','el-icon-s-release','el-icon-s-home','el-icon-s-promotion','el-icon-s-operation','el-icon-s-unfold','el-icon-s-fold','el-icon-s-platform','el-icon-s-order','el-icon-s-cooperation','el-icon-bell','el-icon-message-solid','el-icon-video-camera','el-icon-video-camera-solid','el-icon-camera','el-icon-camera-solid','el-icon-download','el-icon-upload2','el-icon-upload','el-icon-picture-outline-round','el-icon-picture-outline','el-icon-picture','el-icon-close','el-icon-check','el-icon-plus','el-icon-minus','el-icon-help','el-icon-s-help','el-icon-circle-close','el-icon-circle-check','el-icon-circle-plus-outline','el-icon-remove-outline','el-icon-zoom-out','el-icon-zoom-in','el-icon-error','el-icon-success','el-icon-circle-plus','el-icon-remove','el-icon-info','el-icon-question','el-icon-warning-outline','el-icon-warning','el-icon-goods','el-icon-s-goods','el-icon-star-off','el-icon-star-on','el-icon-more-outline','el-icon-more','el-icon-phone-outline','el-icon-phone','el-icon-user','el-icon-user-solid','el-icon-setting','el-icon-s-tools','el-icon-delete','el-icon-delete-solid','el-icon-eleme']),
    }
  },
  computed: {
    disabledSelected() {
      if (this.disabled) return true;
      return this.$parent.form ? this.$parent.form.disabled : false;
    },
  },
  data() {
    return {
      // 弹出框显示状态
      popoverVisible: false,
      inputType: true
    };
  },
  methods: {
    // 是否为当前已选项
    isActive(item) {
      return this.value === item;
    },
    // 选中图标
    onClickSelected(item) {
      this.$emit('selected', item);
      this.popoverVisible = false;
    },
    // 清空选项
    onClickClear() {
      this.$emit('selected', '');
    },
  },
};
</script>

<style lang="scss">
@import "~element-ui/packages/theme-chalk/src/common/var.scss";
.mod-input {
  width: 80%;
}
.mod-select-icon
{
  $size: 40px;
  $col-count: 8;
  $row-count: 4;
  $scope: 5px;

  position: relative;
  display: inline-block;
  width: $size;
  height: $size;
  border: 1px dashed $--border-color-base;
  border-radius: 5px;
  text-align: center;
  cursor: pointer;
  outline: none;

  // 菜单打开状态
  &.is-opened, &:hover { border-color: $--color-primary; }
  // 禁用状态
  &.is-disabled:hover { border-color: $--border-color-base !important; } 
  &.is-disabled,
  &.is-disabled > .icon-item,
  &.is-disabled > .btn-clear {
    background-color: $--background-color-base;
  }
  // 已选状态
  &.is-active {
    border-style: solid;
    border-radius: 0;
    > .icon-item {
      padding: $scope;
      text-align: center;
      cursor: pointer;
      > i {
        display: block;
        width: 100%;
        height: 100%;
        line-height: $size - ($scope * 2);
        color: $--color-white;
        background-color: $--color-primary;
      }
    }
  }
  > .icon-item > i { font-size: 16px; }
  > .icon-item > iel-icon-plus {
    width: 100%;
    line-height: $size;
    font-size: ($size / 2);
    font-weight: bold;
    color: $--color-info;
    cursor: inherit;
  }

  // 清空按钮
  .btn-clear {
    width: 0;
    height: 0;
    border-width: ($size / 2) 0 0 ($size / 2);
    border-style: solid;
    border-color: $--color-danger transparent transparent transparent;
    position: absolute;
    top: 0;
    right: 0;
    cursor: pointer;
    > iel-icon-close {
      position: absolute;
      top: -($size / 2);
      right: 0;
      color: $--color-white;
      font-size: .7em;
      &:hover { color: darken($--color-white, 5%); }
    }
  }

  // 弹出内容
  @at-root .el-popover.el-popper.pupop-select-icon {
    display: block;
    padding: 0;
    width: ($size + $scope * 2) * $col-count + 2px;
    height: ($size + $scope * 2) * $row-count;

    > .el-scrollbar { height: 100%; }
    .el-scrollbar__view {
    }

    .icon-item {
      float: left;
      width: $size;
      height: $size;
      line-height: $size;
      margin: $scope;
      padding: $scope;
      text-align: center;
      cursor: pointer;
      &:hover { background-color: $--color-info-light; }
      &.is-active {
        background-color: $--color-success-light;
        border: 1px solid $--color-success;
      }
      > i {
        display: block;
        width: 100%;
        height: 100%;
        font-size: 16px;
        line-height: $size - ($scope * 2);
        color: $--color-white;
        background-color: $--color-primary;
      }
    }
  }
}
</style>