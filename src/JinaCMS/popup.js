Vue.component('jinapopup', {
    template: '#jinapopup-template',
    props: {component: Object, instance: Object, values: Object, lng: String, defaultlng: String, lnglist: Object, currentonglet: Number},
    data: function () {
        return {
            uploading: false
        }
    },
    methods: {
        fieldAction: function(code) {
            $('#'+code+'_actions').addClass('active');
            $('#'+code+'_actions_popup').dialog({modal: true, title: 'Préférences du champ', show: {effect: "blind", duration: 400},
                close: function(){$('.JinaPopupFieldAction').removeClass('active')}});
        },
        ongletHandle: function(id) {
            this.$parent.$data.currentonglet = id
        },
        sectionToggle: function(e) {
            $(e.currentTarget).next().toggleClass('closed');
        },
        close: function() {
            $('#JinaPopup').dialog('close')
        },
        getTinymceValues: function() {
            var that = this;
            $('#JinaPopup .richtext').each(function() {
                var parts = $(this).attr('id').split('-');
                that.values[parts[0]][parts[1]] = tinyMCE.get($(this).attr('id')).getContent();
            });
        },
        save: function() {
            this.getTinymceValues();
            this.instance.values = $.extend(true, {}, this.values);
            this.close();
        },
        switchLng: function(e) {
            e.preventDefault();
            this.getTinymceValues();
            JinaCMS.removeTinymce();
            var lng = $(e.target).data('lng');
            if (!this.values[lng]) this.values[lng] = {};
            this.$parent.$data.lng = lng
        },
        dropFile: function(e) {
            var t = e.currentTarget;
            $(t.children[0]).click()
        },
        getDropped: function(e, lng, code) {
            e.preventDefault();
            var t = e.currentTarget;
            $(t).removeClass('over');
            t.children[0].files = e.dataTransfer.files;
            this.afterChange(t.children[0], lng, code)
        },
        changeFile: function(e, lng, code) {
            this.afterChange(e.target, lng, code)
        },
        deleteFile: function(e, lng, code, sub, doc) {
            e.stopImmediatePropagation();
            var that = this;
            $.each(this.values[lng][code][sub], function(k, v) {
                if (v == doc) {
                    that.values[lng][code][sub].splice(k, 1)
                }
            });
            this.$forceUpdate()
        },
        afterChange: function(node, lng, code) {
            if (!node.files.length) return;
            var that = this;
            $.each(node.files, function(k, v) {
                that.values[lng][code]['docs'].push(v)
            });
            this.$forceUpdate()
        },
        isImage: function(doc) {
            var type = doc.type.replace(/(.*?)\/(.*)/, "$1");
            return (type == 'image')
        },
        getDocURL: function(doc) {
            return JinaCMS.getDocURL(doc)
        },
        dragOver: function(e) {
            e.preventDefault()
        },
        dragEnter: function(e) {
            e.preventDefault();
            $(e.target).addClass('over')
        },
        dragExit: function(e) {
            e.preventDefault();
            $(e.target).removeClass('over')
        },
        getFieldValue: function(lng, code, value) {
            if (!this.values[lng][code]) {
                this.values[lng][code] = {}
            }
            if (!this.values[lng][code][value]) {
                this.values[lng][code][value] = []
            }
            return this.values[lng][code][value]
        },
        submitFiles: function (event, lng, code) {
            if (this.$data.uploading) return false;
            this.$data.uploading = true;
            var docs = this.getFieldValue(lng, code, 'docs');
            if (docs.length == 0) return;
            var fdata = new FormData();
            $.each(docs, function(k, v) {
                fdata.append(k, v)
            });
            var that = this;
            $.ajax({
                type: 'post',
                url: JinaCMS.path + 'app/action/upload/',
                data: fdata,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        $(event.target).find('.jauge').css('width', 'calc('+(e.loaded / e.total)*100+'% - 20px)').show();
                    }, false);
                    return xhr;
                },
                success: function(flow) {
                    var obj = JSON.parse(flow);
                    that.addDocuments(lng, code, obj.uploaded, true);
                    console.log(flow)
                },
                complete: function() {
                    that.$data.uploading = false;
                    $(event.target).find('.jauge').hide();
                }
            })
        },
        addDocuments: function (lng, code, documents, raz) {
            if (!this.values[lng][code]['documents']) {
                this.values[lng][code].documents = []
            }
            var that = this;
            $.each(documents, function(k, v) {
                var exists = false;
                $.each(that.values[lng][code].documents, function(kk, vv) {
                    if (vv['folder'] == v['folder'] && vv['name'] == v['name']) exists = true
                });
                if (!exists) that.values[lng][code].documents.push(v)
            });
            if (raz) this.values[lng][code]['docs'] = [];
            this.$forceUpdate()
        }
    }
})
export function setPopup(params) {
    return new Vue({
        el: '#JinaPopup',
        data: {
            component: {},
            instance: {},
            values: {},
            lng: params.lng,
            defaultlng: params.defaultLng,
            currentonglet: 0,
            lnglist: {fr: 'fr.png', en: 'en.png', es: 'es.png', de: 'de.png', it: 'it.png'}
        },
        beforeUpdate: function () {
            if (!this.$data.values[this.$data.lng]) this.$data.values[this.$data.lng] = {}
        },
        updated: function () {
            tinymce.init({
                selector: '#JinaPopup .richtext',
                theme: "modern",
                schema: "html5",
                inline: false,
                plugins: 'textcolor paste link code',
                fontsize_formats: '14pt 16pt',
                /*content_css: 'templates/css/h-front.css',*/
                block_formats: 'Paragraph=div',
                style_formats: [
                    {
                        title: 'Mon format', items: [
                            {title: 'Gras', format: 'bold'},
                            {title: 'Grand texte', inline: 'span', classes: 'big'},
                        ]
                    }
                ],
                style_formats_merge: true,
                language: 'fr_FR',
                /*toolbar: "undo redo | styleselect | fontsizeselect | bold italic underline | bullist numlist | forecolor backcolor | alignleft aligncenter alignright alignjustify",*/
                toolbar: "undo redo | styleselect | fontsizeselect | bold italic underline | bullist numlist | forecolor backcolor | sub sup alignleft aligncenter alignright alignjustify | link | code",
                statusbar: false,
                menubar: false
            })
        }
    })
}

