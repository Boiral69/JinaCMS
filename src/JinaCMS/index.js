import {JinaCMS} from './jina.js'
export {JinaCMS}
// Bug tools TinyMCE
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
})
