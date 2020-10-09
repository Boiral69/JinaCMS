import {JinaManager} from './manager.js'
import {setPopup} from "./popup.js";
import {setLibrary} from "./library.js";

export const JinaCMS = {
  init: function (params) {
    this.managers = []
    this.path = params.path
    this.params = params
    if (!this.params.lng) this.params.lng = params.defaultLng
    this.collection = params.collection
    this.containers = params.containers
    this.components = params.components
    this.defaultLng = params.defaultLng
    this.maxHistory = params.maxHistory
    if (!params.admin) return
    this.popup = setPopup(params)
    this.library = setLibrary(this.params)
  },
  removeTinymce: function () {
    var editors = tinymce.get();
    for (var i = editors.length - 1; i > -1; i--) {
      editors[i].destroy()
    }
  },
  getDocURL: function (doc) {
    return this.path + 'upload/' + doc.folder + '/' + doc.name
  },
  display: function (params) {
    var manager = new JinaManager(params, this.params)
    manager.vue = new Vue({
      el: '#' + params.componentId,
      data: {
        component: params.component,
        values: params.values,
        manager: manager,
        cindex: 0,
      },
      mounted: function () {
        if (JinaCMS.params.admin) this.manager.doSortable()
      }
    })
    this.managers.push(manager)
  },
  getRoot: function (id) {
    var root
    var _id = id.replace(/Jina-/, '')
    var parts = _id.split('_')
    var root_id = parts[0]
    $.each(this.managers, function(k, manager) {
      if (manager.root.id == root_id) root = manager.root
    })
    return root
  }
}