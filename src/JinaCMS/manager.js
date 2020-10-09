export function JinaManager(params, adminParams) {
    this.componentId = params.componentId
    this.root = params.component
    this.history = [$.extend(true, {}, params.component)]
    this.vue = {}
    this.currentIndex = 0
    this.maxHistory = adminParams.maxHistory
    this.isDragging = false
    this.changes = 0
    this.currentVersion = 0
    this.library = {}
    this.containers = adminParams.containers
    this.currentId = 0
    this.lng = adminParams.lng
    this.defaultLng = adminParams.defaultLng
    this.deleteComponent = function (component, e) {
        e.stopImmediatePropagation();
        this.doDelete(this.root, component);
    }
    this.doDelete = function (node, component) {
        if (node.children) {
            for (var i = 0; i < node.children.length; i++) {
                if (node.children[i] == component) {
                    node.children.splice(i, 1);
                    return true;
                }
                if (this.doDelete(node.children[i], component)) return true;
            }
        }
    }
    this.insertComponent = function (node, component, newComponent, after, generateId) {
        if (generateId) this.generateId(newComponent)
        if (!node) node = this.root
        if (node.children) {
            for (var i = 0; i < node.children.length; i++) {
                if (node.children[i] == component) {
                    if (after == 2) {
                        node.children[i].children.push(newComponent)
                    } else {
                        node.children.splice(i + after, 0, newComponent)
                    }
                    return true;
                }
                if (this.insertComponent(node.children[i], component, newComponent, after)) {
                    return true
                }
            }
        }
    }
    this.generateId = function(node) {
        var id = this.getMaxId(this.root, 0)
        node.id = ++id
        this.generateChildrenIds(node, node.id)
    }
    this.getMaxId = function(node, max) {
        if (!node) node = this.root;
        if (Number(node.id) > Number(max)) max = node.id
        if (node.children) {
            for (var i = 0; i < node.children.length; i++) {
                max = this.getMaxId(node.children[i], max)
            }
        }
        return max
    }
    this.generateChildrenIds = function(node, id) {
        if (node.children) {
            for (var i = 0; i < node.children.length; i++) {
                node.children[i].id = ++id
                id = this.generateChildrenIds(node.children[i], id)
            }
        }
        return id
    }
    this.addComponent = function (component, e) {
        component.children.push({
            id: '7',
            code: 'type3',
            name: 'Composant 7',
            className: 'Jina_Field_Select',
            tag: 'jinadbfieldselect',
            values: {
                '3': 'Valeur 3',
                '4': 'Valeur 4',
            }
        })
    }
    this.isTouched = function () {
        if (this.maxHistory > 0 && this.history.length > this.maxHistory + 1) {
            this.history.splice(0, 1);
            this.currentIndex--;
        }
        if (JSON.stringify(this.history[this.currentIndex]) != JSON.stringify(this.root)) {
            this.history[++this.currentIndex] = $.extend(true, {}, this.root);
            this.history.splice(this.currentIndex + 1, this.history.length - (this.currentIndex + 1))
        }
        //console.log(JSON.stringify(this.root))
        return this.currentIndex > 0;
    }
    this.backward = function () {
        if (this.currentIndex == 0) return;
        this.root = $.extend(true, {}, this.history[--this.currentIndex]);
        this.vue.$data.component = this.root
    }
    this.forward = function () {
        if (this.currentIndex >= this.history.length - 1) return;
        this.root = $.extend(true, {}, this.history[++this.currentIndex]);
        this.vue.$data.component = this.root
    }
    this.save = function () {
        var that = this
        $.ajax({
            type: 'post',
            url: JinaCMS.path + 'app/action/save/',
            data: {
                id: that.root.id,
                data: JSON.stringify(that.root)
            },
            complete: function (flow) {
                that.history.splice(0, that.history.length);
                that.history.push($.extend(true, {}, that.root));
                that.currentIndex = 0;
            }
        })
    }
    this.moveComponent = function (component, nextComponent, previousComponent, parentComponent) {
        var targetNode = (nextComponent ? nextComponent : (previousComponent ? previousComponent : parentComponent))
        if (!targetNode) return false
        var srcRoot = JinaCMS.getRoot(component.attr('id'))
        var targetRoot = JinaCMS.getRoot(targetNode)
        if (!srcRoot || !targetRoot) return
        var mode = (nextComponent ? 0 : (previousComponent ? 1 : 2))
        var target = this.findComponentById(targetRoot, targetNode)
        var item = this.findComponentById(srcRoot, component.attr('id'))
        var item_ = $.extend(true, {}, item)
        if (srcRoot == targetRoot) this.doDelete(srcRoot, item)
        this.insertComponent(targetRoot, target, item_, mode, srcRoot != targetRoot)
    }
    this.openPopup = function (event, c_instance) {
        event.preventDefault()
        JinaCMS.removeTinymce()
        $('.JinaComponentManager,.JinaColumnHandle').addClass('unvisible')
        var component = JinaCMS.library.getComponent(c_instance.className)
        JinaCMS.popup.component = component
        JinaCMS.popup.instance = c_instance
        JinaCMS.popup.values = $.extend(true, {}, c_instance.values)
        JinaCMS.popup.currentonglet = 0
        $('#JinaPopup').dialog({
            position: {my: "center", at: "center", of: window},
            modal: false,
            draggable: true,
            resizable: true,
            title: component.label,
            minWidth: 900,
            minHeight: 700,
            show: {effect: "size", duration: 400},
            hide: {effect: "size", duration: 400},
            close: function () {
                $('.JinaComponentManager,.JinaColumnHandle').removeClass('unvisible');
            }
        })
    }
    this.findComponentById = function (root, id) {
        var _id = id.replace(/Jina-/, '')
        var parts = _id.split('_')
        if (parts.length == 1) {
            _id = parts[0];
            return (root.id == _id ? root : false)
        } else {
            _id = parts[1]
        }
        if (root.children) {
            for (var i = 0; i < root.children.length; i++) {
                if (root.children[i].id == _id) return root.children[i]
                var target = this.findComponentById(root.children[i], id)
                if (target) return target
            }
        }
        return false
    }
    this.findParent = function (root, component) {
        if (root == component) return root
        if (root.children) {
            for (var i = 0; i < root.children.length; i++) {
                if (root.children[i] == component) return root
                var target = this.findParent(root.children[i], component)
                if (target) return target
            }
        }
        return false
    }
    this.getChildCandidates = function (obj) {
        if (!obj.attr('id')) return false
        var component = this.findComponentById(this.root, obj.attr('id'))
        return Object.keys(this.containers[component.className])
    }
    this.getSiblingCandidates = function (component) {
        var parent = this.findParent(this.root, component)
        return Object.keys(this.containers[parent.className])
    }
    this.getValue = function (component, code) {
        if (!this.lng) this.lng = this.defaultLng
        if (!component.values[this.lng]) component.values[this.lng] = {}
        if (!component.values[this.lng][code]) {
            return (component.values[this.defaultLng][code] || '')
        }
        return component.values[this.lng][code]
    }
    this.getStyles = function (obj) {
        var ret = ''
        if (obj && obj.attributes && obj.attributes.style) {
            for (var p in obj.attributes.style) {
                ret += p + ': ' + obj.attributes.style[p] + ';'
            }
        }
        return ret
    }
    this.getDocuments = function (component, code, type) {
        var field = this.getValue(component, code)
        if (!field.documents) return []
        var result = []
        var that = this
        $.each(field.documents, function (k, document) {
            document['url'] = JinaCMS.getDocURL(document)
            result.push(document)
        });
        return result
    }
    this.doSortable = function () {
        var that = this
        var source = null
        var dragged = null
        var next = null
        var parent = null
        $('#'+that.componentId+' .JinaContainer').sortable().sortable('destroy')
        $('#'+that.componentId+' .JinaContainer').each(function () {
            var children = that.getChildCandidates($(this).parents('.JinaComponent:first'))
            if (!children) return
            var items = children.map(s => '.' + s).join(',')
            $(this).sortable({
                items: items,
                handle: '>.JinaComponentManager >.JinaComponentManagerMenu >div >.JinaComponentHandle',
                placeholder: 'JinaPlaceholder',
                tolerance: 'pointer',
                cursor: 'move',
                revert: true,
                revertDuration: 0,
                dropOnEmpty: true,
                helper: 'clone',
                start: function (event, ui) {
                    $('.JinaComponentManager').addClass('unvisible')
                    dragged = $('#' + ui.item.data('id'))
                    that.isDragging = true
                    if (dragged) dragged.addClass('unvisible')
                },
                stop: function (event, ui) {
                    that.isDragging = false
                    var n = ui.item.next('.JinaComponent').attr('id')
                    var p = ui.item.prev('.JinaComponent').attr('id')
                    var pp = ui.item.parents('.JinaComponent:first').attr('id')
                    if (dragged) dragged.removeClass('unvisible')
                    $(this).sortable('cancel')
                    if (dragged.attr('id') != n && dragged.attr('id') != p) {
                        that.moveComponent(dragged, n, p, pp)
                    }
                    ui.item.remove()
                },
                deactivate: function (event, ui) {
                    $('.JinaComponentManager').removeClass('unvisible')
                    if (dragged) dragged.removeClass('unvisible')
                }
            })
        })
        $('#'+that.componentId+' .ui-draggable').draggable('destroy')
        $('#'+that.componentId+' .JinaComponent:not(.Jina_Root)').each(function () {
            var component = that.findComponentById(that.root, $(this).attr('id'));
            var classes = '';
            for (var container in that.containers) {
                var c = Object.keys(that.containers[container]);
                if (c.length > 0) {
                    for (var i = 0; i < c.length; i++) {
                        if (c[i] == component.className) classes += '.' + container + '_Container,'
                    }
                }
            }
            classes = classes.replace(/,$/, '');//$(this).parents('.JinaContainer:first').attr('class').replace('JinaContainer ', '').split(' ').map(s => (s.substr(0, 5) == 'Jina_' ? '.'+s : '')).join(',').replace(/,$/, '');
            $(this).draggable({
                handle: '>.JinaComponentManager >.JinaComponentManagerMenu >div >.JinaComponentHandle',
                cursor: 'move',
                tolerance: 'pointer',
                connectToSortable: classes,
                revert: true,
                revertDuration: 100,
                helper: 'clone',
                start: function (event, ui) {
                    $('.JinaComponentManager').addClass('unvisible');
                    $(this).addClass('unvisible');
                    dragged = $(this)
                    /*next = dragged.next('.JinaComponent')
                    parent = dragged.parents('.JinaComponent:first')
                    source = $(this).parents('.JinaContainer:first')*/
                }
            })
        });
        this.doColumnsDraggable();
        $(window).on('resize', function () {
            that.doColumnsDraggable()
        })
    }
    this.doColumnsDraggable = function () {
        var that = this;
        $('#'+that.componentId+' .JinaColumnHandle.ui-draggable').draggable('destroy');
        $('#'+that.componentId+' .JinaColumnHandle').each(function () {
            function getType(obj) {
                var bloc = that.findComponentById(that.root, obj.parents('.Jina_Bloc:first').attr('id'));
                return bloc.values[that.defaultLng].type
            }

            function getBootstrapClass(w) {
                var diff = width;
                var bclass = '';
                for (var k in bootstrap_steps) {
                    if (Math.abs(w - bootstrap_steps[k]) < diff) {
                        diff = Math.abs(w - bootstrap_steps[k]);
                        bclass = k
                    }
                }
                return bclass
            }

            function removeBootstrapClass(obj) {
                var classes = obj.attr("class").split(' ');
                var r = /col-md-[0-9]+$/;
                for (var i = 0; i < classes.length; i++) {
                    if (classes[i].match(r)) {
                        obj.removeClass(classes[i]);
                        return classes[i]
                    }
                }
            }

            var previous = $(this).parent().prev('.Jina_Column');
            var x1 = 20;
            var x2 = $(this).parent().offset().left + $(this).parent().width() - 20;
            if (previous.length > 0) {
                x1 += previous.offset().left
            }
            var width = $(this).parent().parents('.JinaComponentContent:first').width();
            var bootstrap_steps = {};
            for (var i = 1; i <= 12; i++) {
                bootstrap_steps[i] = ((width / 12) * i) - 30
            }
            $(this).draggable({
                axis: 'x',
                containment: [x1, 0, x2, 5000],
                stop: function (event, ui) {
                    var type = getType(ui.helper);
                    var x1 = $(this).parent().offset().left;
                    var x2 = ui.helper.offset().left;
                    var other = $(this).parent().prev('.Jina_Column');
                    var w1 = other.width() - (x1 - x2);
                    var w2 = $(this).parent().width() + (x1 - x2);
                    var cother = that.findComponentById(that.root, other.attr('id'));
                    var cthis = that.findComponentById(that.root, $(this).parent().attr('id'));
                    switch (type) {
                        case 'px':
                            other.width(w1);
                            $(this).parent().width(w2);
                            if (!cother.attributes.style) cother.attributes.style = {};
                            cother.attributes.style.width = w1;
                            if (!cthis.attributes.style) cthis.attributes.style = {};
                            cthis.attributes.style.width = w2;
                            break;
                        case 'pc':
                            removeBootstrapClass($(this).parent());
                            removeBootstrapClass(other);
                            w1 = (Math.round(w1 * 1000000 / width) / 10000) + '%';
                            other.width(w1);
                            w2 = (w2 * 100 / width) + '%';
                            $(this).parent().width(w2);
                            if (!cother.attributes.style) cother.attributes.style = {};
                            cother.attributes.style.width = w1;
                            if (!cthis.attributes.style) cthis.attributes.style = {};
                            cthis.attributes.style.width = w2;
                            break;
                        case 'bootstrap':
                            var ex_class = removeBootstrapClass($(this).parent()).replace(/col-md-/, '');
                            var ex_class_other = removeBootstrapClass(other).replace(/col-md-/, '');
                            var new_class = getBootstrapClass(w2);
                            while (new_class < 1) {
                                new_class++
                            }
                            var new_class_other = Number(ex_class_other) + Number(ex_class) - Number(new_class);
                            while (new_class_other < 1) {
                                new_class--;
                                new_class_other++
                            }
                            $(this).parent().addClass('col-md-' + new_class);
                            other.addClass('col-md-' + new_class_other);
                            cthis.classes = cthis.classes.replace(/col-md-[0-9]+/, '') + ' col-md-' + new_class;
                            cother.classes = cother.classes.replace(/col-md-[0-9]+/, '') + ' col-md-' + new_class_other;
                            break;
                    }
                    ui.helper.css('left', '0px');
                }
            })
        })
    }
    this.showLibrary = function (component, after) {
        $('.JinaComponentManager').removeClass('visible')
        JinaCMS.library.$data.manager = this
        JinaCMS.library.$data.component = component
        JinaCMS.library.$data.after = after
        var classes = this.getSiblingCandidates(component)
        JinaCMS.library.$data.classes = classes
        JinaCMS.library.setFilter()
        $('#JinaLibrary').dialog({
            modal: true,
            title: 'Ajouter un composant',
            width: '800',
            height: '600',
            show: {effect: "blind", duration: 800}
        })
    }
};
