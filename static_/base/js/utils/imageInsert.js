define([
    'jquery',
    'underscore',
    'bootstrap-dialog',
    'jquery.cookie'
], function($, _, BootstrapDialog) {

    ImageInsert = function(element) {
        $(element).on('click.mpws.imageinsert', this.show);
    }

    // texts
    var defaults = {
        FLD_URL: 'Image Url:',
        FLD_TITLE: 'Title:',
        FLD_WIDTH: 'Width:',
        FLD_HEIGHT: 'Height:',
        FLD_PH_URL: 'put your image url here',
        FLD_PH_TITLE: 'no title',
        FLD_PH_WIDTH: 'auto',
        FLD_PH_HEIGHT: 'auto',
        DLG_TITLE: 'Image Insert',
        OK: 'OK',
        CANCEL: 'Cancel'
    };

    ImageInsert.setDefaults = function(k, v) {
        if (k && v) {
            defaults[k] = v;
        }
        if (typeof v === 'undefined' && typeof k === 'object') {
            _.extend(defaults, k);
        }
    };

    ImageInsert.prototype.show = function(e) {
        var $this = $(this),
            $targetToPutImg = $('#' + $this.data('target'));
            replaceTargetValue = !!$this.data('replace');

        // TODO: make all texts configurable by user
        var $dialog = new BootstrapDialog({
            title: $this.data('title') || defaults.DLG_TITLE,
            message: '<form class="form-horizontal" role="form">' +
                '<div class="form-group">' +
                    '<label class="control-label col-md-2">' + defaults.FLD_URL + '</label>' +
                    '<div class="col-md-10">' +
                        '<input type="text" class="form-control" id="imginsUrlID" placeholder="' + defaults.FLD_PH_URL + '">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-md-4">' +
                        '<label class="control-label">' + defaults.FLD_TITLE + '</label>' +
                        '<input type="text" class="form-control" id="imginsTitleID" placeholder="' + defaults.FLD_PH_TITLE + '">' +
                    '</div>' +
                    '<div class="col-md-4">' +
                        '<label class="control-label">' + defaults.FLD_WIDTH + '</label>' +
                        '<input type="text" class="form-control" id="imginsWidthID" placeholder="' + defaults.FLD_PH_WIDTH + '">' +
                    '</div>' +
                    '<div class="col-md-4">' +
                        '<label class="control-label">' + defaults.FLD_HEIGHT + '</label>' +
                        '<input type="text" class="form-control" id="imginsHeightID" placeholder="' + defaults.FLD_PH_HEIGHT + '">' +
                    '</div>' +
                '</div></form>',
            buttons: [{
                label: defaults.CANCEL,
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: defaults.OK,
                cssClass: 'btn-success',
                action: function(dialog) {
                    var $img;
                    if ($targetToPutImg.length) {
                        $img = $('<img>').attr({
                                src: $(dialog.getModalContent()).find('input#imginsUrlID').val(),
                                title: $(dialog.getModalContent()).find('input#imginsTitleID').val() || null,
                                width: $(dialog.getModalContent()).find('input#imginsWidthID').val() || 'auto',
                                height: $(dialog.getModalContent()).find('input#imginsHeightID').val() || 'auto'
                            });
                        if (replaceTargetValue) {
                            $targetToPutImg.val($img.get(0).outerHTML);
                        } else {
                            $targetToPutImg.val(($targetToPutImg.val() + ' ' + $img.get(0).outerHTML).trim());
                        }
                    }
                    dialog.close();
                }
            }]
        });
        $dialog.open();

        return $dialog;
    }

    // DROPDOWN PLUGIN DEFINITION
    // ==========================

    function Plugin(option) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data('mpws.imageinsert')

            if (!data) $this.data('mpws.imageinsert', (data = new ImageInsert(this)))
            if (typeof option == 'string') data[option].call($this)
        })
    }

    var old = $.fn.imageinsert

    $.fn.imageinsert = Plugin
    $.fn.imageinsert.Constructor = ImageInsert


    // DROPDOWN NO CONFLICT
    // ====================

    $.fn.imageinsert.noConflict = function() {
        $.fn.imageinsert = old
        return this
    }

    // APPLY TO STANDARD IMAGE POPUP LINK
    // ===================================
    $(document)
        .on('click.mpws.imageinsert', '[data-toggle="imageinsert"]', ImageInsert.prototype.show);

    return ImageInsert;
});
