Vue.component('library',  {
    template: '#jinalibrary-template',
    props: {
        node: Object,
        component: Object,
        manager: Object,
        after: 0
    },
    methods: {
        insert: function(event, node, manager, component, after) {
            event.stopImmediatePropagation();
            if (node.type == 'folder') return;
            manager.insertComponent(null, component, {
                id: '8',
                name: 'Article 8',
                className: 'Jina_Article',
                /*tag: 'jinaarticle',*/
                children: [],
                values: {fr: {title: 'Mon titre 8', corps: 'Corps de mon <b class="test">joli</b> article'}, en: {corps: 'My beautiful article'}}
            }, after, true);
            $('#JinaLibrary').dialog('close')
        }
    }
})
export function setLibrary(params) {
    return new Vue({
        el: '#JinaLibrary',
        data: {
            collection: params.collection,
            manager: {},
            component: {},
            after: 0,
            classes: [],
        },
        methods: {
            setFilter: function (classes) {
                if (classes) this.classes = classes;
                this.setAvailable(this.collection);
                //this.$forceUpdate()
                this.collection = JSON.parse(JSON.stringify(this.collection))
            },
            setAvailable: function (node) {
                if (node.type == 'folder') {
                    node.available = false;
                    if (node.children) {
                        for (var child in node.children) {
                            if (this.setAvailable(node.children[child])) {
                                node.available = true
                            }
                        }
                    }
                } else {
                    if (this.classes.length == 0) {
                        node.available = true
                    } else {
                        node.available = this.classes.indexOf(node.className) >= 0
                    }
                }
                return node.available
            },
            getComponent: function (className, node) {
                if (node === undefined) node = this.collection;
                if (!node.children) return;
                var ret = '';
                for (var child in node.children) {
                    if (node.children[child].className == className) {
                        return node.children[child];
                    }
                    ret = this.getComponent(className, node.children[child]);
                    if (ret) return ret;
                }
            }
        }
    })
}
